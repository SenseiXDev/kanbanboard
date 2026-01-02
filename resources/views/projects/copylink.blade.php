@extends('layouts.invoicepayheader')
@section('title')
{{ __('Copy Link') }}
@endsection
@php
    $result = json_decode($project->copylinksetting);
    $logo_path = \App\Models\Utility::get_file('/');
    $logo = \App\Models\Utility::get_file('tasks/');
@endphp

<style>
    .application .container-application {
        display: flow-root !important;
    }
</style>

@section('action-button')
    <a href="" class="pt-3">
        <select name="language" id="language" class="btn btn-md btn-white btn-icon p-0 p-3"
            onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
            @foreach (\App\Models\Utility::languages() as $code => $language)
                <option @if ($lang == $code) selected @endif
                    value="{{ route('projects.link',[\Illuminate\Support\Facades\Crypt::encrypt($project->id), $code]) }}">{{ ucfirst($language) }}</option>
            @endforeach
        </select>
    </a>
@endsection


<section class="application-section">
@section('content')
    <div class="row">
        <div class="col-lg-9">
            <div id="tabs-1" class="tabs-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h6 mb-0">{{ __('Basic details') }}</h5>
                    </div>
                    <div class="row application-row">
                        <div class="col-md-6 col-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card ">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <h6 class="text-muted mb-1">{{ __('Task Done') }}</h6>
                                                    <span class="h4 font-weight-bold mb-0 ">{{ $project_data['task']['done'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="progress-circle progress-sm"
                                                        data-progress="{{ $project_data['task']['percentage'] }}"
                                                        data-text="{{ $project_data['task']['percentage'] }}%" data-color="primary">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <span
                                                        class="text-sm text-muted">{{ __('Total Task') . ' : ' . $project_data['task']['total'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h6 class="mb-0">{{ $project_data['task_chart']['total'] }}</h6>
                                                        <span class="text-sm text-muted">{{ __('Last 7 days task done') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="w-100 pt-4 pb-5">
                                                <div class="spark-chart" data-toggle="spark-chart" data-color="info"
                                                    data-dataset="{{ json_encode($project_data['task_chart']['chart']) }}"></div>
                                            </div>
                                            <div class="progress-wrapper mb-3">
                                                <small class="progress-label">{{ __('Day Left') }} <span
                                                        class="text-muted">{{ $project_data['day_left']['day'] }}</span></small>
                                                <div class="progress mt-0 height-3">
                                                    <div class="progress-bar bg-info" role="progressbar"
                                                        aria-valuenow="{{ $project_data['day_left']['percentage'] }}" aria-valuemin="0"
                                                        aria-valuemax="100" style="width: {{ $project_data['day_left']['percentage'] }}%;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="progress-wrapper">
                                                <small class="progress-label">{{ __('Open Task') }} <span
                                                        class="text-muted">{{ $project_data['open_task']['tasks'] }}</span></small>
                                                <div class="progress mt-0 height-3">
                                                    <div class="progress-bar bg-info" role="progressbar"
                                                        aria-valuenow="{{ $project_data['open_task']['percentage'] }}" aria-valuemin="0"
                                                        aria-valuemax="100" style="width: {{ $project_data['open_task']['percentage'] }}%;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="progress-wrapper">
                                                <small class="progress-label">{{ __('Completed Milestone') }} <span
                                                        class="text-muted">{{ $project_data['milestone']['total'] }}</span></small>
                                                <div class="progress mt-0" style="height: 3px;">
                                                    <div class="progress-bar bg-info" role="progressbar"
                                                        aria-valuenow="{{ $project_data['milestone']['percentage'] }}" aria-valuemin="0"
                                                        aria-valuemax="100" style="width: {{ $project_data['milestone']['percentage'] }}%;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="mb-0">{{ $project_data['timesheet_chart']['total'] }}</h6>
                                                    <span class="text-sm text-muted">{{ __('Last 7 days hours spent') }}</span>
                                                </div>
                                            </div>
                                            <div class="w-100 pt-4 pb-5">
                                                <div class="spark-chart" data-toggle="spark-chart" data-color="warning"
                                                    data-dataset="{{ json_encode($project_data['timesheet_chart']['chart']) }}"></div>
                                            </div>
                                            <div class="progress-wrapper mb-3">
                                                <small class="progress-label">{{ __('Total project time spent') }} <span
                                                        class="text-muted">{{ $project_data['time_spent']['total'] }}</span></small>
                                                <div class="progress mt-0 height-3">
                                                    <div class="progress-bar bg-warning" role="progressbar"
                                                        aria-valuenow="{{ $project_data['time_spent']['percentage'] }}" aria-valuemin="0"
                                                        aria-valuemax="100" style="width: {{ $project_data['time_spent']['percentage'] }}%;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="progress-wrapper">
                                                <small class="progress-label">{{ __('Allocated hours on task') }} <span
                                                        class="text-muted">{{ $project_data['task_allocated_hrs']['hrs'] }}</span></small>
                                                <div class="progress mt-0 height-3">
                                                    <div class="progress-bar bg-warning" role="progressbar"
                                                        aria-valuenow="{{ $project_data['task_allocated_hrs']['percentage'] }}"
                                                        aria-valuemin="0" aria-valuemax="100"
                                                        style="width: {{ $project_data['task_allocated_hrs']['percentage'] }}%;"></div>
                                                </div>
                                            </div>
                                            <div class="progress-wrapper">
                                                <small class="progress-label">{{ __('User Assigned') }} <span
                                                        class="text-muted">{{ $project_data['user_assigned']['total'] }}</span></small>
                                                <div class="progress mt-0 height-3">
                                                    <div class="progress-bar bg-warning" role="progressbar"
                                                        aria-valuenow="{{ $project_data['user_assigned']['percentage'] }}" aria-valuemin="0"
                                                        aria-valuemax="100"
                                                        style="width: {{ $project_data['user_assigned']['percentage'] }}%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="card card-fluid">
                                <div class="card-header">
                                    <h6 class="mb-0">{{ __('Project overview') }}</h6>
                                </div>
                                <div class="card-body flex-grow-1">
                                    <div class="pb-3 mb-3 border-bottom">
                                        <div class="row align-items-center">
                                            <div class="">
                                                @if ($project->image)
                                                    <img src="{{ $logo_path . $project->image }}"
                                                        alt="{{ $project->title }}" class="avatar rounded-circle"
                                                        id="blah" title="{{ $project->title }}" />
                                                @else
                                                    <img {{ $project->img_image }} class="avatar rounded-circle"
                                                        title="{{ $project->title }}">
                                                @endif
                                            </div>
                                            <div class="col ml-n2">
                                                <div class="progress-wrapper">
                                                    <span class="progress-percentage"><small
                                                            class="font-weight-bold">{{ __('Completed:') }}
                                                        </small>{{ $project->project_progress()['percentage'] }}</span>
                                                    <div class="progress progress-xs mt-2">
                                                        <div class="progress-bar bg-{{ $project->project_progress()['color'] }}"
                                                            role="progressbar"
                                                            aria-valuenow="{{ $project->project_progress()['percentage'] }}"
                                                            aria-valuemin="0" aria-valuemax="100"
                                                            style="width: {{ $project->project_progress()['percentage'] }};">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-sm mb-0">
                                        {{ $project->descriptions }}
                                    </p>
                                </div>
                                <div class="card-footer py-0 px-0">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-6">
                                                    <small>{{ __('Start date') }}:</small>
                                                    <div class="h6 mb-0">
                                                        {{ \App\Models\Utility::getDateFormated($project->start_date) }}</div>
                                                </div>
                                                <div class="col-6">
                                                    <small>{{ __('End date') }}:</small>
                                                    <div
                                                        class="h6 mb-0 {{ strtotime($project->end_date) < time() ? 'text-danger' : '' }}">
                                                        {{ \App\Models\Utility::getDateFormated($project->end_date) }}</div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tabs-2" class="tabs-card d-none">
                <div class="card">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card mb-0">
                                <div class="card-header border-0">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="mb-0">{{ __('Member') }}</h6>
                                        </div>
                                    </div>
                                </div>
                                 <div class="table-responsive">
                                    <table class="table table-striped data-table">
                                    <thead>
                                        <tr>
                                            <th> {{__('Avatar')}}</th>
                                            <th> {{__('Name')}}</th>
                                            <th> {{__('Type')}}</th>
                                            <th> {{__('Email')}}</th>
                                            <th></th>
                                        </tr>
                                        </thead>

                                        @if ($project->users || $project->users != '' || $project->users != null)
                                        <tr>
                                            @foreach ($project->users as $user)
                                            <td>
                                                @if ($user->avatar)
                                                    <img src="{{ $logo_path . $user->avatar }}" class="avatar rounded-circle avatar-sm"
                                                        title="{{ $user->name }}">
                                                @else
                                                    <img {{ $user->img_avatar }} class="avatar rounded-circle avatar-sm">
                                                @endif
                                            </td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ __(ucfirst($user->pivot->permission)) }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tabs-3" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ __('Milestone') }} ({{ count($project->milestones) }})</h6>
                            </div>
                        </div>
                        <div class="card-wrapper  p-3   table_scroll" style="overflow: auto; ">
                            <div class="list-group list-group-flush">
                                <table class="table table-striped data-table">
                                    <thead>
                                        <tr>
                                            <th> {{__('Title')}}</th>
                                            <th> {{__('Status')}}</th>
                                            <th> {{__('Tasks')}}</th>
                                            <th> {{__('Progress')}}</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                    @if ($project->milestones->count() > 0)
                                    @foreach ($project->milestones as $milestone)

                                    <td>{{ $milestone->title }}</td>
                                    <td> {{ __(\App\Models\Project::$status[$milestone->status]) }}</td>
                                    <td>{{ $milestone->tasks->count() . ' ' . __('Tasks') }}</td>
                                    <td>
                                        <div class="progress_wrapper">
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width:{{ $milestone->progress }}px;"
                                                    aria-valuenow="55" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                            <div class="progress_labels">
                                                <div class="total_progress">
                                                    <strong> {{ $milestone->progress }}%</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach

                                @endif
                            </table>
                            </thead>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tabs-4" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ __('Activity Log') }}</h6>
                                  <small>{{ __('Activity Log of this project') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- <div class="scrollbar-inner"> --}}
                        <div class="mh-500 min-h-500 card-wrapper  p-3   table_scroll" style="overflow: auto; ">
                            <div class="timeline timeline-one-side" data-timeline-content="axis"
                                data-timeline-axis-style="dashed">
                                @foreach ($project->activities as $activity)
                                    <div class="timeline-block">
                                        <span class="timeline-step timeline-step-sm bg-dark border-dark text-white">
                                            <i class="fas {{ $activity->logIcon($activity->log_type) }}"></i>
                                        </span>
                                        <div class="timeline-content">
                                            <span class="text-dark text-sm">{{ __($activity->log_type) }}</span>
                                            <a class="d-block h6 text-sm mb-0">{!! $activity->getRemark() !!}</a>
                                            <small><i
                                                    class="fas fa-clock mr-1"></i>{{ $activity->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        {{-- </div> --}}
                    </div>
                </div>
            </div>

            <div id="tabs-5" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ __('Attachments') }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mh-500 min-h-500 card-wrapper  p-3   table_scroll" style="overflow: auto; ">
                            @if ($project->projectAttachments()->count() > 0)
                                @foreach ($project->projectAttachments() as $attachment)
                                    <div class="card mb-3 border shadow-none">
                                        <div class="px-3 py-3">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <img src="{{ asset('assets/img/icons/files/' . $attachment->extension . '.png') }}"
                                                        class="img-fluid" style="width: 40px;">
                                                </div>
                                                <div class="col ml-n2">
                                                    <h6 class="text-sm mb-0">
                                                        <a href="#">{{ $attachment->name }}</a>

                                                    </h6>
                                                    <p class="card-text small text-muted">{{ $attachment->file_size }}</p>
                                                </div>
                                                <div class="col-auto actions">

                                                    <a href="{{ $logo . $attachment->file }}" target="_blank"
                                                        class="action-item" role="button">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <a href="{{ $logo . $attachment->file }}" download
                                                        class="action-item" role="button">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="py-5">
                                    <h6 class="h6 text-center">{{ __('No Attachments Found.') }}</h6>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div id="tabs-6" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h6 mb-0">{{ __('Task') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mh-500 min-h-500 card-wrapper  p-3   table_scroll row" style="overflow: auto; ">
                            @foreach($stages as $stage)
                                <div class="card-list card-list-flush col-3">
                                    <div class="card-list-title row align-items-center mb-3">
                                        <div class="col">
                                            <h6 class="mb-0">{{ $stage->name }}</h6>

                                        </div>
                                    </div>
                                    <div class="card-list-body task-list-items" id="task-list-{{ $stage->id }}" data-status="{{$stage->id}}">
                                        @foreach($stage->tasks as $taskDetail)

                                            <div class="card card-progress  border shadow-none" id="{{$taskDetail->id}}">
                                                <div class="card-body">
                                                    <div class="row align-items-center mb-2">
                                                        <div class="col-6">
                                                            <span class="badge badge-pill badge-xs badge-{{\App\Models\ProjectTask::$priority_color[$taskDetail->priority]}}">{{ __(\App\Models\ProjectTask::$priority[$taskDetail->priority]) }}</span>
                                                            {{-- @dd($taskDetail->priority) --}}
                                                        </div>

                                                        <div class="col-6 text-right">
                                                            @if(str_replace('%','',$taskDetail->taskProgress()['percentage']) > 0)<span class="text-sm">{{ $taskDetail->taskProgress()['percentage'] }}</span>@endif

                                                            <a href="#" class="action-item " role="button"  data-url="{{ route('projects.task.show',[$project->id,$taskDetail->id]) }}" data-ajax-popup-right="true"><i class="fa fa-eye"></i></a>
                                                        </div>
                                                    </div>

                                                        <a class="h6 task-name-break" href="#" data-url="{{ route('projects.task.show',[$project->id,$taskDetail->id]) }}" data-ajax-popup-right="true">{{ $taskDetail->title }}</a>

                                                    <div class="row align-items-center">
                                                        <div class="col-12">
                                                            <div class="actions d-inline-block">
                                                                @if(count($taskDetail->taskFiles) > 0)
                                                                    <div class="action-item mr-2"><i class="fas fa-paperclip mr-2"></i>{{ count($taskDetail->taskFiles) }}</div>@endif
                                                                @if(count($taskDetail->comments) > 0)
                                                                    <div class="action-item mr-2"><i class="fas fa-comment-alt mr-2"></i>{{ count($taskDetail->comments) }}</div>@endif
                                                                @if($taskDetail->checklist->count() > 0)
                                                                    <div class="action-item mr-2"><i class="fas fa-tasks mr-2"></i>{{ $taskDetail->countTaskChecklist() }}</div>@endif
                                                            </div>
                                                        </div>
                                                        <div class="col-5">@if(!empty($taskDetail->end_date) && $taskDetail->end_date != '0000-00-00')<small @if(strtotime($taskDetail->end_date) < time())class="text-danger"@endif>{{ \App\Models\Utility::getDateFormated($taskDetail->end_date) }}</small>@endif</div>
                                                        <div class="col-7 text-right">
                                                            @if($users = $taskDetail->users())
                                                                <div class="avatar-group">
                                                                    @foreach($users as $key => $user)
                                                                        @if($key<3)
                                                                            <a href="" class="avatar rounded-circle avatar-sm">
                                                                                    @if($user->avatar)
                                                                                    <img  src="{{$logo_path.$user->avatar}}" title="{{ $user->name }}"  >
                                                                                    @else
                                                                                    <img {{ $user->img_avatar }} title="{{ $user->name }}">
                                                                                    @endif
                                                                            </a>
                                                                        @else
                                                                            @break
                                                                        @endif
                                                                    @endforeach
                                                                    @if(count($users) > 3)
                                                                        <a href="#" class="avatar rounded-circle avatar-sm">
                                                                            <img avatar="+ {{ count($users)-3 }}">
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <span class="empty-container" data-placeholder="{{__('Empty')}}"></span>
                                    </div>
                                </div>

                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

            <div id="tabs-7" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h6 mb-0">{{ __('Tracker details') }}</h5>
                    </div>
                    <div class="card-body">
                        {{-- <div class="row"> --}}
                            {{-- <div class="col-12"> --}}
                                {{-- <div class="card"> --}}
                                    {{-- <div class=""> --}}
                                        <div class="table-responsive">
                                            <table class="table table-striped data-table">
                                                <thead>
                                                <tr>
                                                    <th> {{__('Description')}}</th>
                                                    <th> {{__('Task')}}</th>
                                                    <th> {{__('Start Time')}}</th>
                                                    <th> {{__('End Time')}}</th>
                                                    <th>{{__('Total Time')}}</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse ($treckers as $trecker)
                                                    @php
                                                        $total_name = Utility::second_to_time($trecker->total_time);

                                                    @endphp
                                                    <tr>
                                                        <td>{{isset($trecker->name)?$trecker->name:' - '}}</td>
                                                        <td>{{isset($trecker->tasks->name)?$trecker->tasks->name:' - '}}</td>
                                                        <td>{{date("H:i:s",strtotime($trecker->start_time))}}</td>
                                                        <td>{{date("H:i:s",strtotime($trecker->end_time))}}</td>
                                                        <td>{{$total_name}}</td>
                                                        <td>
                                                            <img alt="Image placeholder" src="{{ asset('assets/img/gallery.png')}}" class="avatar view-images rounded-circle avatar-sm" data-toggle="tooltip" data-original-title="{{__('View Screenshot images')}}" style="height: 25px;width:24px;margin-right:10px;cursor: pointer;" data-id="{{$trecker->id}}" id="track-images-{{$trecker->id}}" >

                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center"> {{__("No Record")}}</td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    {{-- </div> --}}
                                {{-- </div> --}}
                            {{-- </div> --}}
                        {{-- </div> --}}
                    </div>
                </div>
            </div>

            <div id="tabs-8" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h6 mb-0">{{ __('Timesheet') }}</h5>
                    </div>
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6 weekly-dates-div pt-1">
                                <a href="#" class="action-item previous"><i class="fas fa-arrow-left"></i></a>
                                <span class="weekly-dates"></span>
                                <input type="hidden" id="weeknumber" value="0">
                                <input type="hidden" id="selected_dates">
                                <a href="#" class="action-item next"><i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-wrapper project-timesheet overflow-auto "></div>
                    <div class="text-center notfound-timesheet">
                        <div class="empty-project-text text-center p-3 min-h-300">
                            <h5 class="pt-5">{{ __("We couldn't find any data") }}</h5>
                            <p class="m-0">{{ __("Sorry we can't find any timesheet records on this week.") }}</p>
                            <p class="m-0">{{ __("To add timesheet record go to Add Task on Timesheet") }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="card">
                <div class="list-group list-group-flush" id="tabs">

                    @if (isset($result->basic_details) && $result->basic_details == 'on')
                        <div data-href="#tabs-1" class="list-group-item text-primary">
                            <div class="media">
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Basic details') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (isset($result->member) && $result->member == 'on')
                        <div data-href="#tabs-2" class="list-group-item">
                            <div class="media">
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Member') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (isset($result->milestone) && $result->milestone == 'on')
                        <div data-href="#tabs-3" class="list-group-item">
                            <div class="media">
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Milestone') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (isset($result->activity) && $result->activity == 'on')
                        <div data-href="#tabs-4" class="list-group-item">
                            <div class="media">
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Activity') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (isset($result->attachment) && $result->attachment == 'on')
                        <div data-href="#tabs-5" class="list-group-item">
                            <div class="media">
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Attachment') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (isset($result->task) && $result->task == 'on')
                        <div data-href="#tabs-6" class="list-group-item">
                            <div class="media">
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Task') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (isset($result->tracker_details) && $result->tracker_details == 'on')
                        <div data-href="#tabs-7" class="list-group-item">
                            <div class="media">
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Tracker details') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (isset($result->timesheet) && $result->timesheet == 'on')
                        <div data-href="#tabs-8" class="list-group-item">
                            <div class="media">
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Timesheet') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
@endsection
</section>
@push('script')
    <script src="{{ asset('assets/js/letter.avatar.js') }}"></script>

    <script>
        LetterAvatar.transform();
    </script>
    <script>
        // For Sidebar Tabs
        $(document).ready(function() {
            $('.list-group-item').on('click', function() {
                var href = $(this).attr('data-href');
                $('.tabs-card').addClass('d-none');
                $(href).removeClass('d-none');
                $('#tabs .list-group-item').removeClass('text-primary');
                $(this).addClass('text-primary');
            });
        });



        $(document).on('click', '.custom-control-input', function() {
            var pp = $(this).parents('.tabs-card').removeClass('d-none');
        });


    </script>

    {{-- Project Timesheet  --}}
    <script>

            function ajaxFilterTimesheetTableView() {
            var mainEle = $('.project-timesheet');
            var notfound = $('.notfound-timesheet');
            var week = parseInt($('#weeknumber').val());
            var project_id = '{{ $project->id }}'   ;
            var isowner = $('.owner-timesheet-status').prop('checked');
            var data = {
                week: week,
                project_id: project_id,
            }
            data.isowner = isowner;

            $.ajax({
                url: '{{ route('filter.timesheet.table.view') }}',
                data: data,
                success: function (data) {
                    $('.weekly-dates-div .weekly-dates').text(data.onewWeekDate);
                    $('.weekly-dates-div #selected_dates').val(data.selectedDate);

                    $.each(data.sectiontasks, function (i, item) {

                        var optionhtml = '';

                        if (item.section_id != 0 && item.section_name != '' && item.tasks.length > 0) {
                            optionhtml += `<a href="#" class="dropdown-item select-sub-heading" data-tasks-count="` + item.tasks.length + `">` + item.section_name + `</a>`;
                        }
                        $.each(item.tasks, function (ji, jitem) {
                            optionhtml += `<a href="#" class="dropdown-item select-task" data-task-id="` + jitem.task_id + `">` + jitem.task_name + `</a>`;
                        });
                    });

                    if (data.totalrecords == 0) {
                        mainEle.hide();
                        notfound.css('display', 'block');
                    } else {
                        notfound.hide();
                        mainEle.show();
                    }

                    mainEle.html(data.html);
                }
            });
        }

        $(function () {
            ajaxFilterTimesheetTableView();
        });

        $(document).on('click', '.weekly-dates-div .action-item', function () {
            var weeknumber = parseInt($('#weeknumber').val());
            if ($(this).hasClass('previous')) {
                weeknumber--;
                $('#weeknumber').val(weeknumber);
            } else if ($(this).hasClass('next')) {
                weeknumber++;
                $('#weeknumber').val(weeknumber);
            }
            ajaxFilterTimesheetTableView();
        });
    </script>
@endpush
