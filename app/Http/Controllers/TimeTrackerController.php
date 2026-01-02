<?php

namespace App\Http\Controllers;

use App\Models\TimeTracker;
use App\Models\TrackPhoto;
use App\Models\Utility;
use App\Models\Project;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class TimeTrackerController extends Controller
{
   
    public function index()
    {
       $treckers=TimeTracker::with(['project','tasks'])->where('created_by',\Auth::user()->id)->get();
       
       return view('time_trackers.index',compact('treckers'));

    }

   
    public function create()
    {
        
    }


    public function store(Request $request)
    {
        
    }

   
    public function show(TimeTracker $timeTracker)
    {
        
    }

   
    public function edit(TimeTracker $timeTracker)
    {
        
    }


    public function update(Request $request, TimeTracker $timeTracker)
    {
        
    }

    public function destroy($timetracker_id)
    {
            $timetrecker = TimeTracker::find($timetracker_id);
            $timetrecker->delete();
        
                return redirect()->back()->with('success', __('TimeTracker successfully deleted.'));
      
    }

    public function getTrackerImages(Request $request){

        $tracker = TimeTracker::find($request->id);

        $images = TrackPhoto::where('track_id',$request->id)->get();

        return view('time_trackers.images',compact('images','tracker'));
    }

    public function removeTrackerImages(Request $request){
        $images = TrackPhoto::find($request->id);
         $logo=Utility::get_file('/');
        if($images){
            $url= $images->img_path;
            if($images->delete())
            {
                \File::delete($logo.$url);
                // \Storage::delete($url);
                return Utility::success_res(__('Tracker Photo remove successfully.'));
            }else{
                return Utility::error_res(__('opps something wren wrong.'));
            }
        }else{
            return Utility::error_res(__('opps something wren wrong.'));
        }

    }

    public function removeTracker(Request $request)
    {
        $track = TimeTracker::find($request->input('id'));
        if($track)
        {
            $track->delete();

            return Utility::success_res(__('Track remove successfully.'));
        }
        else
        {
            return Utility::error_res(__('Track not found.'));
        }
    }
    public function projectTracks($id)
    {
        $treckers=TimeTracker::where('project_id',$id)->where('created_by',\Auth::user()->id)->get();
        $project = Project::find($id);
        if($project )
        { 
            return view('projects.tracker',compact('treckers','project'));
        }
        else
        {
           return redirect()->back()->with('error', __('Project Not Found.'));  
        }
       
    }
}
