<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\EmailTemplate;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\ProjectEmailTemplate;
use App\Models\ProjectTask;
use App\Models\ProjectTaskTimer;
use App\Models\ProjectUser;
use App\Models\TaskStage;
use App\Models\Timesheet;
use App\Models\User;
use App\Models\UserContact;
use App\Models\Utility;
use App\Models\TaskChecklist;
use App\Models\TaskComment;
use App\Models\TaskFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProjectExport;
use App\Imports\ProjectImport;
use App\Models\TimeTracker;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($view = 'grid')
    {
        $authuser = Auth::user();
        if($authuser->type != 'admin')
        {
            $allow = false;
            $plan  = $authuser->getPlan();
            if($plan)
            {
                $countProjects = $authuser->projects->count();
                if($countProjects < $plan->max_projects || $plan->max_projects == -1)
                {
                    $allow = true;
                }
            }

            return view('projects.index', compact('view', 'allow'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $plan = $user->getPlan();
        if(isset($request->currency))
        {
            $data = explode(' - ',$request->currency);
            $currancy       = $data[0];
            $currency_code  = $data[1];
        }
        else
        {
            $currancy    = '$';
            $currency_code  = 'USD';
        }
        if($plan)
        {
            $countProjects = $user->projects->count();

            if($countProjects < $plan->max_projects || $plan->max_projects == -1)
            {
                $validation = [
                    'title' => 'required|max:120',
                    'currency' => 'required',
                ];

                if($request->hasFile('image'))
                {
                    $validation['image'] = 'mimes:jpeg,jpg,png';
                }

                $validator = Validator::make(
                    $request->all(), $validation
                );

                if($validator->fails())
                {
                    return redirect()->back()->with('error', $validator->errors()->first());
                }

                $project                        = new Project();
                $project['title']               = $request->title;
                $project['budget']              = (!empty($request->budget)) ? $request->budget : 0;
                $project['status']              = 'on_hold';
                $project['currency']            = $currancy;
                $project['currency_code']       = $currency_code;
                $project['currency_position']   = 'pre';
                $project['start_date']          = (!empty($request->start_date)) ? $request->start_date : null;
                $project['end_date']            = (!empty($request->end_date)) ? $request->end_date : null;
                $project['descriptions']        = $request->descriptions;
                $project['tags']                = $request->tags;
                $project['estimated_hrs']       = (!empty($request->estimated_hrs)) ? $request->estimated_hrs : 0;
                $project['created_by']          = Auth::user()->creatorId();
                $project['is_active']           = 1;
                $project['copylinksetting']     = '{"member":"on","milestone":"off","basic_details":"on","activity":"off","attachment":"on","bug_report":"on","task":"off","tracker_details":"off","timesheet":"off" ,"password_protected":"off"}';



                if($request->hasFile('image'))
                {
                    //storage limit
                    $image_size = $request->file('image')->getSize();
                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);

                    if($result == 1)
                    {
                        $dir = 'projects/';
                        $fileNameToStore  = time() . '.' . $request->image->getClientOriginalExtension();
                        $path_1            = $request->file('image')->storeAs('projects', $fileNameToStore);

                            $path = Utility::upload_file($request,'image',$fileNameToStore,$dir,[]);
                            if($path['flag'] == 1)
                            {
                                $image = $path['url'];
                            }
                            else{
                                return redirect()->back()->with('error', __($path['msg']));
                            }

                        $project['image'] = $path['url'];
                    }
                }

                $project->save();

                // Make Entry in project_users table
                ProjectUser::create(
                    [
                        'project_id' => $project->id,
                        'user_id' => $user->id,
                        'permission' => 'owner',
                        'user_permission' => json_encode(Auth::user()->getAllPermission()),
                    ]
                );

                // Make Entry in task_stages table
                foreach(TaskStage::$stages as $key => $value)
                {
                    TaskStage::create(
                        [
                            'name' => $value,
                            'complete' => (count(TaskStage::$stages) - 1 == $key) ? 1 : 0,
                            'project_id' => $project->id,
                            'order' => $key,
                            'created_by' => Auth::user()->id,
                        ]
                    );
                }

                // Make Entry In Project_Email_Template tbl
                $allEmail = EmailTemplate::all();
                foreach($allEmail as $email)
                {
                    ProjectEmailTemplate::create(
                        [
                            'template_id' => $email->id,
                            'project_id' => $project->id,
                            'is_active' => 1,
                        ]
                    );
                }

                //webhook
                $module ='New Project';

                $webhook=  Utility::webhookSetting($module);

                if($webhook)
                {
                    $parameter = json_encode($project);
                    // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                    $status = Utility::WebhookCall($webhook['url'],$parameter,$webhook['method']);
                    if($status != true)
                    {
                        $msg= "Webhook call failed.";
                    }
                }

                $settings  = Utility::settingsById(Auth::user()->id);
                $user = Auth::user();

                $uArr = [
                    'project_name' => $request->title,
                    'project_budget'  => $request->budget,
                    'project_hours' => $request->estimated_hrs,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                ];
                if(isset($settings['is_project_enabled']) && $settings['is_project_enabled'] == 1){

                    Utility::send_slack_msg('new_project',$uArr, $user->id);
                }

                if(isset($settings['telegram_is_project_enabled']) && $settings['telegram_is_project_enabled'] == 1){

                    Utility::send_telegram_msg('new_project',$uArr, $user->id);
                }
                return redirect()->route('projects.index')->with('success', __('Project added successfully.').((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')).((isset($result) && $result != 1) ? '</br> <span class="text-danger">' . $result . '</span>' : ''));
            }
            else
            {
                return redirect()->back()->with('error', __('Your project limit is over, Please upgrade plan.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Default plan is deleted.'));
        }
    }

    public function show(Project $project)
    {
        if(Auth::user()->type != 'admin' && !empty($project))
        {
            $EmailTemplates = EmailTemplate::where('id','!=','5')->get();
            $usr           = Auth::user();
            $user_projects = $usr->projects->pluck('id')->toArray();


            if(in_array($project->id, $user_projects) && $project->is_active == 1)
            {
                $project_data = [];
                // Task Count
                $project_task         = $project->tasks->count();
                $project_done_task    = $project->tasks->where('is_complete', '=', 1)->count();
                $project_data['task'] = [
                    'total' => number_format($project_task),
                    'done' => number_format($project_done_task),
                    'percentage' => Utility::getPercentage($project_done_task, $project_task),
                ];
                // end Task Count

                // Expense
                $expAmt = 0;
                foreach($project->expense as $expense)
                {
                    $expAmt += $expense->amount;
                }
                $project_data['expense'] = [
                    'allocated' => $project->budget,
                    'total' => $expAmt,
                    'percentage' => Utility::getPercentage($expAmt, $project->budget),
                ];
                // end expense

                // Users Assigned
                // $total_users                   = User::where('created_by', '=', $usr->id)->count();
                $total_users                   = $usr->contacts->count();
                $project_user                  = $project->users()->where('user_id', '!=', $usr->id)->count();
                $project_data['user_assigned'] = [
                    'total' => number_format($project_user) . '/' . number_format($total_users),
                    'percentage' => Utility::getPercentage($project_user, $total_users),
                ];
                // end users assigned

                // Day left
                $total_day                = Carbon::parse($project->start_date)->diffInDays(Carbon::parse($project->end_date));
                $remaining_day            = Carbon::parse($project->start_date)->diffInDays(now());
                $project_data['day_left'] = [
                    'day' => number_format($remaining_day) . '/' . number_format($total_day),
                    'percentage' => Utility::getPercentage($remaining_day, $total_day),
                ];
                // end Day left

                // Open Task
                if($usr->checkProject($project->id) == 'Owner')
                {
                    $remaining_task = ProjectTask::where('project_id', '=', $project->id)->where('is_complete', '=', 0)->count();
                    $total_task     = ProjectTask::where('project_id', '=', $project->id)->count();
                }
                else
                {
                    $remaining_task = ProjectTask::where('project_id', '=', $project->id)->where('is_complete', '=', 0)->whereRaw("find_in_set('" . $usr->id . "',assign_to)")->count();
                    $total_task     = ProjectTask::where('project_id', '=', $project->id)->whereRaw("find_in_set('" . $usr->id . "',assign_to)")->count();
                }
                $project_data['open_task'] = [
                    'tasks' => number_format($remaining_task) . '/' . number_format($total_task),
                    'percentage' => Utility::getPercentage($remaining_task, $total_task),
                ];
                // end open task

                // Milestone
                $total_milestone           = $project->milestones()->count();
                $complete_milestone        = $project->milestones()->where('status', 'LIKE', 'complete')->count();
                $project_data['milestone'] = [
                    'total' => number_format($complete_milestone) . '/' . number_format($total_milestone),
                    'percentage' => Utility::getPercentage($complete_milestone, $total_milestone),
                ];
                // End Milestone

                // Time spent
                if($usr->checkProject($project->id) == 'Owner')
                {
                    $times = $project->timesheets->pluck('time')->toArray();
                }
                else
                {
                    $times = $project->timesheets()->where('created_by', '=', $usr->id)->pluck('time')->toArray();
                }
                $totaltime                  = str_replace(':', '.', Utility::timeToHr($times));
                $estimatedtime              = $project->estimated_hrs != '' ? $project->estimated_hrs : '0';
                $project_data['time_spent'] = [
                    'total' => number_format($totaltime) . '/' . number_format($estimatedtime),
                    'percentage' => Utility::getPercentage(number_format($totaltime), $estimatedtime),
                ];
                // end time spent

                // Allocated Hours
                $hrs                                = Project::projectHrs($project->id);
                $project_data['task_allocated_hrs'] = [
                    'hrs' => number_format($hrs['allocated']) . '/' . number_format($hrs['total']),
                    'percentage' => Utility::getPercentage($hrs['allocated'], $hrs['total']),
                ];
                // end allocated hours

                // Chart
                $seven_days      = Utility::getLastSevenDays();
                $chart_task      = [];
                $chart_timesheet = [];
                $cnt             = 0;
                $cnt1            = 0;

                foreach(array_keys($seven_days) as $k => $date)
                {
                    if($usr->checkProject($project->id) == 'Owner')
                    {
                        $task_cnt     = $project->tasks()->where('is_complete', '=', 1)->where('marked_at', 'LIKE', $date)->count();
                        $arrTimesheet = $project->timesheets()->where('date', 'LIKE', $date)->pluck('time')->toArray();
                    }
                    else
                    {
                        $task_cnt     = $project->tasks()->where('is_complete', '=', 1)->whereRaw("find_in_set('" . $usr->id . "',assign_to)")->where('marked_at', 'LIKE', $date)->count();
                        $arrTimesheet = $project->timesheets()->where('created_by', '=', $usr->id)->where('date', 'LIKE', $date)->pluck('time')->toArray();
                    }

                    // Task Chart Count
                    $cnt += $task_cnt;

                    // Timesheet Chart Count
                    $timesheet_cnt = str_replace(':', '.', Utility::timeToHr($arrTimesheet));
                    $cn[]          = $timesheet_cnt;
                    $cnt1          += number_format($timesheet_cnt, 2);

                    $chart_task[]      = $task_cnt;
                    $chart_timesheet[] = number_format($timesheet_cnt, 2);
                }

                $project_data['task_chart']      = [
                    'chart' => $chart_task,
                    'total' => $cnt,
                ];
                $project_data['timesheet_chart'] = [
                    'chart' => $chart_timesheet,
                    'total' => $cnt1,
                ];

                // end chart

                return view('projects.show', compact('project', 'project_data','EmailTemplates'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function edit(Project $project)
    {
        if(!empty($project))
        {
            if(Auth::user()->type != 'admin' && $project->is_active == 1)
            {
                $permissions = Auth::user()->getPermission($project->id);

                if(isset($permissions) && in_array('project setting', $permissions))
                {
                    $project->taskstages = $project->taskstages()->orderBy('order')->get()->toArray();
                    $EmailTemplates      = EmailTemplate::all();
                    return view('projects.edit', compact('project', 'EmailTemplates'));
                }
                else
                {
                    return redirect()->back()->with('error', __('Permission Denied.'));
                }
            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function update(Request $request, Project $project)
    {
        $permissions = Auth::user()->getPermission($project->id);

        if(isset($permissions) && in_array('project setting', $permissions))
        {
            // validation
            $validation = [];
            if($request->from == 'basic')
            {
                $validation = [
                    'title' => 'required|max:120|unique:projects,title,' . $project->id,
                    'start_date' => 'required|date',
                    'end_date' => 'required|date',
                ];
            }
            elseif($request->from == 'financial')
            {
                $validation = [
                    'budget' => 'required|numeric',
                    'currency' => 'required',
                    'currency_code' => 'required',
                    'currency_position' => 'required',
                    'estimated_hrs' => 'required|numeric|min:0',
                ];
            }


            $validator = Validator::make($request->all(), $validation);

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $user = Auth::user();
            if($request->from == 'basic')
            {
                $project['title']         = $request->title;
                $project['status']       = $request->status;
                $project['start_date']   = $request->start_date;
                $project['end_date']     = $request->end_date;
                $project['descriptions'] = $request->descriptions;

                if($request->hasFile('image'))
                {
                    //storage limit
                    $file_path = $project->image;
                    $image_size = $request->file('image')->getSize();
                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                    if($result == 1)
                    {
                        Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);
                        $fileNameToStore  = time() . '.' . $request->image->getClientOriginalExtension();
                        $path             = $request->file('image')->storeAs('projects', $fileNameToStore);
                        $dir = 'projects/';
                        $path = Utility::upload_file($request,'image',$fileNameToStore,$dir,[]);

                            if($path['flag'] == 1)
                            {
                                $image = $path['url'];
                            }
                            else{
                                return redirect()->back()->with('error', __($path['msg']));
                            }

                            $project['image'] = $path['url'];
                        }

                }
            }
            elseif($request->from == 'financial')
            {
                $project['budget']            = $request->budget;
                $project['currency']          = $request->currency;
                $project['currency_code']     = $request->currency_code;
                $project['currency_position'] = $request->currency_position;
                $project['estimated_hrs']     = $request->estimated_hrs;
                $project['tags']              = $request->tags;
            }
            elseif($request->from == 'project_progress')
            {
                $project['project_progress'] = $request->project_progress;
                if(isset($request->progress))
                {
                    $project['progress'] = $request->progress;
                }
            }
            elseif($request->from = 'task_progress_bar')
            {
                $project['task_progress'] = $request->task_progress;
            }

            $project->save();

            return redirect()->back()->with('success', __('Project updated successfully.').((isset($result) && $result!= 1) ? '</br> <span class="text-danger">' . $result . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function destroy(Project $project)
    {


        if($project->is_active == 1)
        {
            //storage limit
            Project::deleteProject($project->id);

            $file_path = $project->image;
            $result = Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);

            return redirect()->route('projects.index')->with('success', __('Project deleted successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    // For Filter
    public function filterProjectView(Request $request)
    {
        $usr           = Auth::user();
        $user_projects = $usr->projects()->pluck('permission', 'project_id')->toArray();
        if($request->ajax() && $request->has('view') && $request->has('sort'))
        {
            $sort     = explode('-', $request->sort);
            $projects = Project::whereIn('id', array_keys($user_projects))->orderBy($sort[0], $sort[1]);
            if(!empty($request->keyword))
            {
                $projects->where('title', 'LIKE', $request->keyword . '%')->orWhereRaw('FIND_IN_SET("' . $request->keyword . '",tags)');
            }
            if(!empty($request->status))
            {
                $projects->whereIn('status', $request->status);
            }
            $projects   = $projects->get();

            $returnHTML = view('projects.' . $request->view, compact('projects', 'user_projects'))->render();

            return response()->json(
                [
                    'success' => true,
                    'html' => $returnHTML,
                ]
            );
        }
    }

    // For Load User on Project Detail Page
    public function loadUser(Request $request)
    {
        if($request->ajax())
        {
            $project    = Project::find($request->project_id);
            $returnHTML = view('projects.users', compact('project'))->render();

            return response()->json(
                [
                    'success' => true,
                    'html' => $returnHTML,
                ]
            );
        }
    }

    // For Invite Member
    public function inviteMemberView(Request $request, $project_id)
    {

        $usr          = Auth::user();
        $project      = Project::find($project_id);
        $user_project = $project->users->pluck('id')->toArray();

        $user_contact = UserContact::where('parent_id', '=', $usr->id)->where('role', 'user')->whereNOTIn('user_id', $user_project)->pluck('user_id')->toArray();
        $arrUser      = array_unique($user_contact);
        $users        = User::whereIn('id', $arrUser)->get();

        $client_contact = UserContact::where('parent_id', '=', $usr->id)->where('role', 'client')->whereNOTIn('user_id', $user_project)->pluck('user_id')->toArray();
        $arrClient      = array_unique($client_contact);
        $clients        = User::whereIn('id', $arrClient)->get();

        return view('projects.invite', compact('project_id', 'users', 'clients'));
    }

    public function inviteProjectUserMember(Request $request)
    {

        $authuser = Auth::user();
        $role     = $request->role;

        // check validation
        $validator = Validator::make(
            $request->all(), [
                               'username' => 'required',
                               'useremail' => 'required|email|unique:users,email',
                               'userpassword' => 'required',
                           ]
        );
        if($validator->fails())
        {
            return json_encode(
                [
                    'code' => 404,
                    'status' => 'Error',
                    'error' => __('The Email has already been taken.'),
                ]
            );
        }

        // end validation
        $plan = $authuser->getPlan();
        if($plan)
        {
            $name               = $request->username;
            $email              = $request->useremail;
            $password           = $request->userpassword;
            $project_id         = $request->project_id;
            $countUsers         = $authuser->contacts->count();
            if($countUsers < $plan->max_users || $plan->max_users == -1)
            {
                // make new user
                $user = User::create(
                    [
                        'name' => $name,
                        'email' => $email,
                        'email_verified_at' => date("H:i:s"),
                        'password' => Hash::make($password),
                        'type' => $role,
                        'created_by' => $authuser->id,
                        'lang' => Utility::getValByName('default_language'),
                        'is_active' => 1,
                        'is_invited' => 1,
                        ]
                    );


                $user->assignPlan(1);

                // make entry in user_contact tbl
                UserContact::create(
                    [
                        'parent_id' => $authuser->id,
                        'user_id' => $user->id,
                        'role' => $role,
                    ]
                );

                // Make entry in project_user tbl
                ProjectUser::create(
                    [
                        'project_id' => $project_id,
                        'user_id' => $user->id,
                        'permission' => $role,
                        'user_permission' => json_encode($authuser->getAllPermission()),
                        'invited_by' => $authuser->id,
                    ]
                );

                // Make entry in activity_log tbl
                ActivityLog::create(
                    [
                        'user_id' => $authuser->id,
                        'project_id' => $project_id,
                        'log_type' => 'Invite User',
                        'remark' => json_encode(['title' => $user->name]),
                    ]
                );

                // send email
                $uArr = [
                    'email' => $email,
                    'password' => $password,
                ];
                $resp = Utility::sendEmailTemplate('User Invited', [$user->id => $user->email], $uArr);

                $project = Project::find($project_id);
                $pArr    = [
                    'project_name' => $project->title,
                    'project_status' => Project::$status[$project->status],
                    'project_budget' => Utility::projectCurrencyFormat($project->id, $project->budget),
                    'project_hours' => number_format($project->estimated_hrs),
                ];

                Utility::sendEmailTemplate('Project Assigned', [$user->id => $user->email], $pArr, $project_id);

                return json_encode(
                    [
                        'code' => 200,
                        'status' => 'Success',
                        'success' => __('User invited successfully.') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''),
                    ]
                );
            }
            else
            {
                return json_encode(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('Your user limit is over, Please upgrade plan.'),
                    ]
                );
            }
        }
        else
        {
            return json_encode(
                [
                    'code' => 404,
                    'status' => 'Error',
                    'error' => __('Default plan is deleted.'),
                ]
            );
        }
    }

    //For duplicate
    public function copyprojects($id){

        $project = Project::find($id);
        return view('projects.duplicate',compact('project'));

    }

    public function copyprojectsstore(Request $request,$id)
    {
        $project = Project::find($id);

        $duplicate                      = new Project();
        $duplicate['title']             = $project->title;
        $duplicate['image']             = $project->image;
        $duplicate['status']            = $project->status;
        $duplicate['budget']            = $project->budget;
        $duplicate['start_date']        = $project->start_date;
        $duplicate['end_date']          = $project->end_date;
        $duplicate['currency']          = $project->currency;
        $duplicate['currency_code']     = $project->currency_code;
        $duplicate['currency_position'] = $project->currency_position;
        $duplicate['created_by']        = Auth::user()->id;
        $duplicate['descriptions']      = $project->descriptions;
        $duplicate['project_progress']  = $project->project_progress;
        $duplicate['progress']          = $project->progress;
        $duplicate['task_progress']     = $project->task_progress;
        $duplicate['tags']              = $project->tags;
        $duplicate['estimated_hrs']     = $project->estimated_hrs;
        $duplicate['is_active']         = 1;
        $duplicate['copylinksetting']   = $project->copylinksetting;
        $duplicate->save();

        $allEmail = EmailTemplate::all();
        foreach($allEmail as $email)

        {
            ProjectEmailTemplate::create(
                [
                    'template_id' => $email->id,
                    'project_id' => $duplicate->id
                    ,
                    'is_active' => 1,
                ]
            );
        }

        if(isset($request->members) && in_array('members', $request->members)){
            $users = ProjectUser::where('project_id',$project->id)->get();
            foreach($users as $user){
                $users = new ProjectUser();
                $users['user_id'] = $user->user_id;
                $users['project_id'] = $duplicate->id;
                $users['permission'] = $user->permission;
                $users['user_permission'] =  $user->user_permission;
                $users['invited_by'] = $user->invited_by;
                $users->save();
            }
        }
        else{
            $objUser = Auth::user();
            $users              = new ProjectUser();
            $users['user_id']   = $objUser->id;
            $users['project_id']= $duplicate->id;
            $users['permission'] = 'owner';
            $users['user_permission'] = json_encode(Auth::user()->getAllPermission());
            $users['invited_by'] = 0;
            $users->save();
        }
        $stages = TaskStage::where('project_id',$project->id)->get();
        foreach($stages as  $stage)
        {
            $taskstage  = new TaskStage();
            $taskstage['name'] = $stage->name;
            $taskstage['complete'] = $stage->complete;
            $taskstage['project_id'] = $duplicate->id;
            $taskstage['order'] = $stage->order;

            $taskstage['created_by'] = Auth::user()->id;
            $taskstage->save();

            if(isset($request->task) && in_array("task", $request->task)){
                $tasks = ProjectTask::where('project_id',$project->id)->where('stage_id',$stage->id)->get();

                foreach($tasks as $task){
                    $project_task                       = new ProjectTask();
                    $project_task['title']              = $task->title;
                    $project_task['description']        = $task->description;
                    $project_task['estimated_hrs']      = $task->estimated_hrs;
                    $project_task['start_date']         = $task->start_date;
                    $project_task['end_date']           = $task->end_date;
                    $project_task['priority']           = $task->priority;
                    $project_task['priority_color']     = $task->priority_color;
                    $project_task['assign_to']          = $task->assign_to;
                    $project_task['project_id']         = $duplicate->id;
                    $project_task['milestone_id']       = $task->id;
                    $project_task['stage_id']           = $taskstage->id;
                    $project_task['order']              = $task->order;
                    $project_task['time_tracking']      = $task->time_tracking;
                    $project_task['created_by']         = $task->created_by;
                    $project_task['is_favourite']       = $task->is_favourite;
                    $project_task['is_complete']        = $task->is_complete;
                    $project_task['marked_at']          = $task->marked_at;
                    $project_task['progress']           = $task->progress;

                    $project_task->save();


                    if(in_array("checklist",$request->task)){
                        $checklists = TaskChecklist::where('task_id',$task->id)->get();
                        foreach($checklists as $checklist){
                                $task_checklist                = new TaskChecklist();
                                $task_checklist['name']        = $checklist->name;
                                $task_checklist['task_id']     = $project_task->id;
                                $task_checklist['user_type']   = $checklist->user_type;
                                $task_checklist['created_by']  = $checklist->created_by;
                                $task_checklist['status']      = $checklist->status;
                                $task_checklist->save();
                        }
                    }

                    if(in_array("task_comment",$request->task)){
                        $task_comments = TaskComment::where('task_id',$task->id)->get();
                        foreach($task_comments as $task_comment){
                            $comment                = new TaskComment();
                            $comment['comment']     = $task_comment->comment;
                            $comment['created_by']  = $task_comment->created_by;
                            $comment['task_id']     = $project_task->id;
                            $comment['user_type']   = $task_comment->user_type;
                            $comment->save();
                        }

                    }

                    if(in_array("task_files",$request->task)){
                        $task_files = TaskFile::where('task_id',$task->id)->get();
                        foreach($task_files as $task_file){
                            $file               = new TaskFile();
                            $file['file']       = $task_file->file;
                            $file['name']       = $task_file->name;
                            $file['extension']  = $task_file->extension;
                            $file['file_size']  = $task_file->file_size;
                            $file['created_by'] = $task_file->created_by;
                            $file['task_id']    = $project_task->id;
                            $file['user_type']  = $task_file->user_type;
                            $file->save();
                        }
                    }

                    $activities = ActivityLog::where('project_id',$project->id)->where('task_id',$task->id)->get();

                    foreach($activities as $activity){

                            $activitylog                = new ActivityLog();
                            $activitylog['user_id']     = $activity->user_id;
                            $activitylog['project_id']  = $duplicate->id;
                            $activitylog['task_id']     = $project_task->id;
                            $activitylog['log_type']    = $activity->log_type;
                            $activitylog['remark']      = $activity->remark;
                            $activitylog->save();

                    }
                }

            }
        }
        if(isset($request->milestone) && in_array("milestone", $request->milestone)){
            $milestones = Milestone::where('project_id',$project->id)->get();
            foreach ($milestones as $milestone) {

                $post                   = new Milestone();
                $post['project_id']     = $duplicate->id;
                $post['title']          = $milestone->title;
                $post['status']         = $milestone->status;
                $post['description']    = $milestone->description;
                $post->save();
            }
        }

        if(isset($request->activity) && in_array('activity',$request->activity))
        {
            $where_in_array = [];
            if( isset($request->milestone) && in_array("milestone", $request->milestone))
            {
                array_push($where_in_array,"Create Milestone");
            }
            if(isset($request->task) && in_array("task", $request->task))
            {
                array_push($where_in_array,"Create Task");
            }
            if(isset($request->user) && in_array("user", $request->user))
            {
                array_push($where_in_array,"Invite User");
            }

            if(count($where_in_array) > 0)
            {
                $activities = ActivityLog::where('project_id',$project->id)->where('task_id',0)->get();

                foreach($activities as $activity){

                        $activitylog                = new ActivityLog();
                        $activitylog['user_id']     = $activity->user_id;
                        $activitylog['project_id']  = $duplicate->id;
                        $activitylog['task_id']     = 0;
                        $activitylog['log_type']    = $activity->log_type;
                        $activitylog['remark']      = $activity->remark;
                        $activitylog->save();


                }
            }
    }
    return redirect()->back()->with('success', 'Project Created Successfully');
    }

    // For MileStones
    public function milestone($project_id)
    {
        $permissions = Auth::user()->getPermission($project_id);

        if(isset($permissions) && in_array('create milestone', $permissions))
        {
            $project = Project::find($project_id);

            return view('projects.milestone', compact('project'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function milestoneStore(Request $request, $project_id)
    {
        $permissions = Auth::user()->getPermission($project_id);

        if(isset($permissions) && in_array('create milestone', $permissions))
        {
            $project   = Project::find($project_id);
            $validator = Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'status' => 'required',
                                   'description' =>'required',
                               ]
            );

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $milestone              = new Milestone();
            $milestone->project_id  = $project->id;
            $milestone->title       = $request->title;
            $milestone->status      = $request->status;
            $milestone->description = $request->description;
            $milestone->save();

            ActivityLog::create(
                [
                    'user_id' => \Auth::user()->id,
                    'project_id' => $project->id,
                    'log_type' => 'Create Milestone',
                    'remark' => json_encode(['title' => $milestone->title]),
                ]
            );

            $settings  = Utility::settingsById(Auth::user()->id);
            $user = Auth::user();

            $uArr = [
                'milestone_title' => $request->title,
                'milestone_status'  => $request->status,
                'owner_name' => Auth::user()->name,
                'project_name' => $project->title,
            ];

            if(isset($settings['mileston_notificaation']) && $settings['mileston_notificaation'] == 1){
                Utility::send_slack_msg('new_milestone',$uArr , $user->id);
            }

            if(isset($settings['telegram_mileston_notificaation']) && $settings['telegram_mileston_notificaation'] == 1){
                Utility::send_telegram_msg('new_milestone',$uArr, $user->id);
            }

            //webhook
            $module ='New Milestone';

            $webhook=  Utility::webhookSetting($module);
            if($webhook)
            {
                $parameter = json_encode($milestone);
                // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                $status = Utility::WebhookCall($webhook['url'],$parameter,$webhook['method']);
                if($status != true)
                {
                    $msg= "Webhook call failed.";
                }
            }

            return redirect()->back()->with('success', __('Milestone successfully created.'). ((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function milestoneEdit($id)
    {
        $milestone = Milestone::find($id);

        return view('projects.milestoneEdit', compact('milestone'));
    }

    public function milestoneUpdate($id, Request $request)
    {
        $mileston=MileStone::where('id',$id)->first();
        $validator = \Validator::make(
            $request->all(), [
                                'title' => 'required',
                                'status' => 'required',
                                'start_date' => 'required',
                                'end_date' => 'required',
                                'description' =>'required',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->route('projects.show',$mileston->project_id)->with('error', $messages->first());
        }

         if($mileston->status !=$request->status)
        {
            $status=1;
        }
        else
        {
            $status=0;
        }

        $milestone                  = Milestone::find($id);
        $milestone->title           = $request->title;
        $milestone->status          = $request->status;
        $milestone->progress        = $request->progress;
        $milestone->description     = $request->description;
        $milestone->start_date      = $request->start_date;
        $milestone->end_date        = $request->end_date;
        $milestone->save();

        $settings  = Utility::settingsById(Auth::user()->id);
        $user = Auth::user();

        if($status==1)
        {
            $uArr = [
                'milestone_title' => $request->title,
                'milestone_status'  => $request->status,
                'milestone_progress' => $request->progress,
                'owner_name' => Auth::user()->name,
            ];
        if(isset($settings['milestone_status_notificaation']) && $settings['milestone_status_notificaation'] == 1){
            Utility::send_slack_msg('milestone_status_updated',$uArr, $user->id);
        }

        if(isset($settings['telegram_milestone_status_notificaation']) && $settings['telegram_milestone_status_notificaation'] == 1){
            Utility::send_telegram_msg('milestone_status_updated',$uArr, $user->id);
        }

        }

        //webhook
        $module = 'Milestone Status Updated';
        $webhook=  Utility::webhookSetting($module);

        if($webhook)
        {
            $parameter = json_encode($milestone);
            // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
            $status = Utility::WebhookCall($webhook['url'],$parameter,$webhook['method']);

            if($status != true)
            {
                $msg= "Webhook call failed.";
            }
        }


        return redirect()->back()->with('success', __('Milestone updated successfully.'). ((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')));
    }

    public function milestoneDestroy($id)
    {
        $milestone = Milestone::find($id);
        $milestone->delete();

        return redirect()->back()->with('success', __('Milestone successfully deleted.'));
    }

    public function milestoneShow($id)
    {
        $milestone = Milestone::find($id);

        return view('projects.milestoneShow', compact('milestone'));
    }

    // Remove User from Project
    public function removeUserFromProject($project_id, $user_id)
    {
        $project = Project::find($project_id);
        $user    = User::find($user_id);

        if($project && $user)
        {
            // Remove from project_user tbl
            $project->users()->detach($user->id);

            // Delete From project_tasks Table
            ProjectTask::removeAssigned($user->id);

            return redirect()->back()->with('success', __('Member removed from this project.'));
        }

        return redirect()->back()->with('success', __('Member cannot be removed from this project.'));
    }

    // Move Task Stage
    public function storeProjectTaskStages(Request $request, $project_id)
    {
        $rules = [
            'stages' => 'required|present|array',
        ];

        $attributes = [];

        if($request->stages)
        {
            foreach($request->stages as $key => $val)
            {
                $rules['stages.' . $key . '.name']      = 'required|max:255';
                $attributes['stages.' . $key . '.name'] = __('Stage Name');
            }
        }

        $validator = Validator::make($request->all(), $rules, [], $attributes);

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $arrStages = TaskStage::where('project_id', '=', $project_id)->orderBy('order')->pluck('name', 'id')->all();
        $order     = 0;

        foreach($request->stages as $key => $stage)
        {
            $obj = new TaskStage();
            if(isset($stage['id']) && !empty($stage['id']))
            {
                $obj = TaskStage::find($stage['id']);
                unset($arrStages[$obj->id]);
            }
            $obj->project_id = $project_id;
            $obj->name       = $stage['name'];
            $obj->order      = $order++;
            $obj->complete   = 0;
            $obj->save();
        }

        if($arrStages)
        {
            foreach($arrStages as $id => $name)
            {
                TaskStage::find($id)->delete();
            }
        }
        $lastStage = TaskStage::where('project_id', '=', $project_id)->orderBy('order', 'desc')->first();

        if($lastStage)
        {
            $lastStage->complete = 1;
            $lastStage->save();
        }

        return redirect()->back()->with('success', __('Stages Saved Successfully.'));
    }

    // User Permission Module
    public function userPermission($project_id, $user_id)
    {
        $project     = Project::find($project_id);
        $user        = User::find($user_id);
        $permissions = $user->getPermission($project_id);

        if(!$permissions)
        {
            $permissions = [];
        }

        return view('projects.user_permission', compact('project', 'user', 'permissions'));
    }

    public function userPermissionStore($project_id, $user_id, Request $request)
    {
        $userProject                  = ProjectUser::where('project_id', '=', $project_id)->where('user_id', '=', $user_id)->first();
        $userProject->user_permission = json_encode($request->permissions);
        $userProject->save();

        return redirect()->back()->with('success', __('Permission Updated Successfully!'));
    }
    // end User permission module

    // Project Gantt Chart
    public function gantt($projectID, $duration = 'Week')
    {
        $project = Project::find($projectID);
        $tasks   = [];

        if($project)
        {
            $tasksobj = $project->tasks;

            foreach($tasksobj as $task)
            {
                $tmp                 = [];
                $tmp['id']           = 'task_' . $task->id;
                $tmp['name']         = $task->name;
                $tmp['start']        = $task->start_date;
                $tmp['end']          = $task->end_date;
                $tmp['custom_class'] = (empty($task->priority_color) ? '#ecf0f1' : $task->priority_color);
                $tmp['progress']     = str_replace('%', '', $task->taskProgress()['percentage']);
                $tmp['extra']        = [
                    'priority' => ucfirst(__($task->priority)),
                    'comments' => count($task->comments),
                    'duration' => Utility::getDateFormated($task->start_date) . ' - ' . Utility::getDateFormated($task->end_date),
                ];
                $tasks[]             = $tmp;
            }
        }

        return view('projects.gantt', compact('project', 'tasks', 'duration'));
    }

    public function ganttPost($projectID, Request $request)
    {
        $project = Project::find($projectID);

        if($project)
        {
            $permissions = Auth::user()->getPermission($projectID);

            if(isset($permissions) && in_array('show task', $permissions))
            {
                $id               = trim($request->task_id, 'task_');
                $task             = ProjectTask::find($id);
                $task->start_date = $request->start;
                $task->end_date   = $request->end;
                $task->save();

                return response()->json(
                    [
                        'is_success' => true,
                        'message' => __("Time Updated"),
                    ], 200
                );
            }
            else
            {
                return response()->json(
                    [
                        'is_success' => false,
                        'message' => __("You can't change Date!"),
                    ], 400
                );
            }
        }
        else
        {
            return response()->json(
                [
                    'is_success' => false,
                    'message' => __("Something is wrong."),
                ], 400
            );
        }
    }

    public function export()
    {
        $name = 'Project' . date('Y-m-d i:h:s');
        $data = Excel::download(new ProjectExport(), $name . '.xlsx'); ob_end_clean();

        return $data;
    }


    public function importFile()
    {
        return view('projects.import');
    }

    public function import(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'file' => 'required|mimes:csv,txt',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }


        $projects = (new ProjectImport())->toArray(request()->file('file'))[0];

        $totalitem = count($projects) - 1;
        $errorArray    = [];
        for($i = 1; $i <= count($projects) - 1; $i++)
        {
            $project = $projects[$i];

            $projectByEmail = Project::where('title', $project[1])->first();
            if(!empty($projectByEmail))
            {
                $projectData = $projectByEmail;
            }
            else
            {
                $projectData = new Project();
            }
            //$projectData->id                  = $project[0];
            $projectData->title               = $project[0];
            $projectData->status              = $project[1];
            $projectData->budget	          = 0;
            $projectData->start_date          = $project[3];
            $projectData->end_date            = $project[4];
            $projectData->currency            = $project[5];
            $projectData->currency_code       = $project[6];
            $projectData->currency_position   = 'pre';
            $projectData->descriptions        = $project[8];
            $projectData->project_progress    = 'false';
            $projectData->progress            = '0';
            $projectData->task_progress       = 'true';
            $projectData->is_active           = 1;
            $projectData->tags                = $project[13];
            $projectData->estimated_hrs       = $project[14];
            $projectData->created_by          = \Auth::user()->creatorId();

            if(empty($projectData))
            {
                $errorArray[]      = $projectData;
            }
            else
            {
                $projectData->save();
            }

              // Make Entry in project_users table
              ProjectUser::create(
                [
                    'project_id' => $projectData->id,
                    'user_id' => $user->id,
                    'permission' => 'owner',
                    'user_permission' => json_encode(Auth::user()->getAllPermission()),
                ]
            );

             // Make Entry in task_stages table
            foreach(TaskStage::$stages as $key => $value)
            {
                TaskStage::create(
                    [
                        'name' => $value,
                        'complete' => (count(TaskStage::$stages) - 1 == $key) ? 1 : 0,
                        'project_id' => $projectData->id,
                        'order' => $key,
                        'created_by' => Auth::user()->id,
                    ]
                );
            }

             // Make Entry In Project_Email_Template tbl
            $allEmail = EmailTemplate::all();
            foreach($allEmail as $email)
            {
                ProjectEmailTemplate::create(
                    [
                        'template_id' => $email->id,
                        'project_id' => $projectData->id,
                        'is_active' => 1,
                    ]
                );
            }
        }

        $errorRecord = [];
        if(empty($errorArray))
        {
            $data['status'] = 'success';
            $data['msg']    = __('Record successfully imported');
        }
        else
        {
            $data['status'] = 'error';
            $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalitem . ' ' . 'record');


            foreach($errorArray as $errorData)
            {
                $errorRecord[] = implode(',', $errorData);
            }

            \Session::put('errorArray', $errorRecord);
        }

        return redirect()->back()->with($data['status'], $data['msg']);
    }

    public function copylinksetting(Request $request, $id)
    {
        $data = [];
        $data['basic_details']          = isset($request->basic_details) ? 'on' : 'off';
        $data['member']                 = isset($request->member) ? 'on' : 'off';
        $data['milestone']              = isset($request->milestone) ? 'on' : 'off';
        $data['activity']               = isset($request->activity) ? 'on' : 'off';
        $data['attachment']             = isset($request->attachment) ? 'on' : 'off';
        $data['bug_report']             = isset($request->bug_report) ? 'on' : 'off';
        $data['task']                   = isset($request->task) ? 'on' : 'off';
        $data['tracker_details']        = isset($request->tracker_details) ? 'on' : 'off';
        $data['timesheet']              = isset($request->timesheet) ? 'on' : 'off';
        $data['password_protected']     = isset($request->password_protected) ? 'on' : 'off';

        $project = Project::find($id);

        if(isset($request->password_protected) && $request->password_protected == 'on' ){
            $project->password = base64_encode($request->password);
        }else{
            $project->password = null;
         }

        $project->copylinksetting = (count($data) > 0 ) ? json_encode($data) : null;
        $project->save();

        return redirect()->back()->with('success', __('Copy Link Setting Save Successfully!'));
    }

    public function projectlink(Request $request,$project_id,$lang='')
    {
        try
        {
            $id=\Illuminate\Support\Facades\Crypt::decrypt($project_id);

        } catch (\Throwable $th)

        {
            return redirect()->route('login');
        }

        $project = Project::find($id);

        $data = [];
        $data['basic_details']  = isset($request->basic_details) ? 'on' : 'off';
        $data['member']  = isset($request->member) ? 'on' : 'off';
        $data['milestone']  = isset($request->milestone) ? 'on' : 'off';
        $data['activity']  = isset($request->activity) ? 'on' : 'off';
        $data['attachment']  = isset($request->attachment) ? 'on' : 'off';
        $data['bug_report']  = isset($request->bug_report) ? 'on' : 'off';
        $data['task']  = isset($request->task) ? 'on' : 'off';
        $data['tracker_details']  = isset($request->tracker_details) ? 'on' : 'off';
        $data['timesheet']  = isset($request->timesheet) ? 'on' : 'off';
        $data['password_protected']  = isset($request->password_protected) ? 'on' : 'off';

        $project = Project::find($id);

        if(Auth::user() != null){
            $usr         = Auth::user();
        }else{
            $usr         = User::where('id',$project->created_by)->first();
        }

        $user_projects = $usr->projects->pluck('id')->toArray();

        $project_data = [];

        // Task Count
        $project_task         = $project->tasks->count();
        $project_done_task    = $project->tasks->where('is_complete', '=', 1)->count();
        $project_data['task'] = [
            'total' => number_format($project_task),
            'done' => number_format($project_done_task),
            'percentage' => Utility::getPercentage($project_done_task, $project_task),
        ];
        // end Task Count


        // Users Assigned
        $total_users                   = $usr->contacts->count();
        $project_user                  = $project->users()->where('user_id', '!=', $usr->id)->count();
        $project_data['user_assigned'] = [
            'total' => number_format($project_user) . '/' . number_format($total_users),
            'percentage' => Utility::getPercentage($project_user, $total_users),
        ];
        // End Users Assigned


        // Day left
        $total_day   = Carbon::parse($project->start_date)->diffInDays(Carbon::parse($project->end_date));
        $remaining_day = Carbon::parse($project->start_date)->diffInDays(now());
        $project_data['day_left'] = [
                    'day' => number_format($remaining_day) . '/' . number_format($total_day),
                    'percentage' => Utility::getPercentage($remaining_day, $total_day),
                ];
        // end day left

        if($usr->checkProject($project->id) == 'Owner')
        {
            $remaining_task     = ProjectTask::where('project_id', '=', $project->id)->where('is_complete', '=', 0)->count();
            $total_task         = ProjectTask::where('project_id', '=', $project->id)->count();
        }
        else
        {
            $remaining_task     = ProjectTask::where('project_id', '=', $project->id)->where('is_complete', '=', 0)->whereRaw("find_in_set('" . $usr->id . "',assign_to)")->count();
            $total_task         = ProjectTask::where('project_id', '=', $project->id)->whereRaw("find_in_set('" . $usr->id . "',assign_to)")->count();
        }
        $project_data['open_task'] = [
            'tasks' => number_format($remaining_task) . '/' . number_format($total_task),
            'percentage' => Utility::getPercentage($remaining_task, $total_task),
        ];

        // Milestone
        $total_milestone           = $project->milestones()->count();
        $complete_milestone        = $project->milestones()->where('status', 'LIKE', 'complete')->count();
        $project_data['milestone'] = [
            'total' => number_format($complete_milestone) . '/' . number_format($total_milestone),
            'percentage' => Utility::getPercentage($complete_milestone, $total_milestone),
        ];
        // End Milestone


          // Chart
          $seven_days      = Utility::getLastSevenDays();
          $chart_task      = [];
          $chart_timesheet = [];
          $cnt             = 0;
          $cnt1            = 0;

        foreach(array_keys($seven_days) as $k => $date)
        {
            if($usr->checkProject($project->id) == 'Owner')
            {
                $task_cnt     = $project->tasks()->where('is_complete', '=', 1)->where('marked_at', 'LIKE', $date)->count();
                $arrTimesheet = $project->timesheets()->where('date', 'LIKE', $date)->pluck('time')->toArray();
            }
            else
            {
                $task_cnt     = $project->tasks()->where('is_complete', '=', 1)->whereRaw("find_in_set('" . $usr->id . "',assign_to)")->where('marked_at', 'LIKE', $date)->count();
                $arrTimesheet = $project->timesheets()->where('created_by', '=', $usr->id)->where('date', 'LIKE', $date)->pluck('time')->toArray();
            }

            // Task Chart Count
            $cnt += $task_cnt;

            // Timesheet Chart Count
            $timesheet_cnt = str_replace(':', '.', Utility::timeToHr($arrTimesheet));
            $cn[]          = $timesheet_cnt;
            $cnt1          += number_format($timesheet_cnt, 2);

            $chart_task[]      = $task_cnt;
            $chart_timesheet[] = number_format($timesheet_cnt, 2);
        }

        // Allocated Hours
        $hrs                                = Project::projectHrs($project->id);
        $project_data['task_allocated_hrs'] = [
            'hrs' => number_format($hrs['allocated']) . '/' . number_format($hrs['total']),
            'percentage' => Utility::getPercentage($hrs['allocated'], $hrs['total']),
        ];
        // end allocated hours

         // Time spent
        if($usr->checkProject($project->id) == 'Owner')
        {
            $times = $project->timesheets->pluck('time')->toArray();
        }
        else
        {
            $times = $project->timesheets()->where('created_by', '=', $usr->id)->pluck('time')->toArray();
        }
        $totaltime                  = str_replace(':', '.', Utility::timeToHr($times));
        $estimatedtime              = $project->estimated_hrs != '' ? $project->estimated_hrs : '0';
        $project_data['time_spent'] = [
            'total' => number_format($totaltime) . '/' . number_format($estimatedtime),
            'percentage' => Utility::getPercentage(number_format($totaltime), $estimatedtime),
        ];
         // end time spent

        $project_data['task_chart']      = [
            'chart' => $chart_task,
            'total' => $cnt,
        ];

        $project_data['timesheet_chart'] = [
            'chart' => $chart_timesheet,
            'total' => $cnt1,
        ];
        if(isset($request->milestone) && in_array("milestone", $request->milestone)){
            $milestones = Milestone::where('project_id',$project->id)->get();

            foreach ($milestones as $milestone) {

                $post                   = new Milestone();
                $post['project_id']     = $milestone->id;
                $post['title']          = $milestone->title;
                $post['status']         = $milestone->status;
                $post['description']    = $milestone->description;
                $post->save();
            }
        }

        if(isset($request->task) && in_array("task", $request->task)){
            $tasks = ProjectTask::where('project_id',$project->id)->where('stage_id',$stage->id)->get();
            $activities = ActivityLog::where('project_id',$project->id)->where('task_id',$task->id)->get();

            foreach($activities as $activity){

                $activitylog                = new ActivityLog();
                $activitylog['user_id']     = $activity->user_id;
                $activitylog['project_id']  = $activity->id;
                $activitylog['task_id']     = $activity->id;
                $activitylog['log_type']    = $activity->log_type;
                $activitylog['remark']      = $activity->remark;
                $activitylog->save();
            }
        }

        $stages = TaskStage::where('project_id', '=', $id)->orderBy('order')->get();
            foreach ($stages as &$status)
            {
                $stageClass[] = 'task-list-' . $status->id;
                $task = ProjectTask::where('project_id', '=', $id);

                // check project is shared or owner
                if ($usr->checkProject($project_id) == 'Shared') {
                    $task->whereRaw(
                        "find_in_set('" . $usr->id . "',assign_to)"
                    );
                }
                //end

                $task->orderBy('order');
                $status['tasks'] = $task ->where('stage_id', '=', $status->id) ->get();
            }

        $treckers=TimeTracker::where('project_id',$id)->where('created_by',$usr->id)->get();
        $project = Project::find($id);
        $lang = !empty($lang) ? $lang : (!empty($usr->lang) ? $usr->lang : env('DEFAULT_ADMIN_LANG')) ;
        \App::setLocale($lang);

        if(\Session::get('copy_pass_true'. $id) == $project->password . '-' . $id){

            return view('projects.copylink', compact('data','project','project_data','stages','treckers','usr','lang'));
        }else{

            if(!isset(json_decode($project->copylinksetting)->password_protected) || json_decode($project->copylinksetting)->password_protected != 'on')
            {
                return view('projects.copylink', compact('data','project','project_data','stages','treckers','usr','lang'));

            }elseif(isset(json_decode($project->copylinksetting)->password_protected) && json_decode($project->copylinksetting)->password_protected == 'on' && $request->password == base64_decode($project->password)){

                \Session::put('copy_pass_true'.$id, $project->password . '-' . $id);

                return view('projects.copylink', compact('data','project','project_data','stages','treckers','usr','lang'));

            }else{

                return view('projects.copylink_password', compact('id'));
            }
        }
    }

    public function shows($project_id, $task_id)
    {
        $task = ProjectTask::find($task_id);
        $allow_progress = Project::find($project_id);


        if ($allow_progress && $task) {
            $allow_progress = $allow_progress->task_progress;

            return view('tasks.task_show', compact('task', 'allow_progress'));
        } else {
            return redirect() ->back() ->with('error', __('Task Not Found.'));
        }
    }

// public function changeLangcopylink($lang)
// {
//     \Cookie::queue('LANGUAGE', $lang, 120);
//     return redirect()->back()->with('success', __('Language Change Successfully!'));
// }

}
