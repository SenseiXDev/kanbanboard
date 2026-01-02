@php
    $logo_path = \App\Models\Utility::get_file('/');
    $setting = \App\Models\Utility::settings();
@endphp

{{ Form::open(['route' => ['projects.tasks.store', $project_id, $stage_id], 'id' => 'create_task']) }}
<div class="row">
    @if(Utility::plancheck()['enable_chatgpt'] == 'on')
        <div class="col-12 col-md-12">
            <div class="text-right">
                <a href="#" class="btn btn-sm btn-primary rounded-pill ml-2" data-url="{{ route('generate',['project_task']) }}" data-ajax-popup-over="true" data-bs-placement="top" data-size="md" title="{{ __('Generate') }}" data-title="{{__('Generate contact with AI')}}">
                    <span class="btn-inner--icon"> <i class="fas fa-robot"></i></span>
                    <span class="btn-inner--text text-white">{{__('Generate with AI')}}</span>
                </a>
            </div>
        </div>
    @endif

    <div class="col-8">
        <div class="form-group">
            {{ Form::label('name', __('Task name'), ['class' => 'form-control-label']) }}
            {{ Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
    </div>
    <div class="col-4">
        <div class="form-group">
            {{ Form::label('milestone_id', __('Milestone'), ['class' => 'form-control-label']) }}
            <select class="form-control" name="milestone_id" id="milestone_id">
                <option value="0"></option>
                @if ($project)
                    @foreach ($project->milestones as $m_val)
                        <option value="{{ $m_val->id }}">{{ $m_val->title }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('description', __('Description'), ['class' => 'form-control-label']) }}
            <small class="form-text text-muted mb-2 mt-0">{{ __('This textarea will autosize while you type') }}</small>
            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3', 'data-toggle' => 'autosize']) }}
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            {{ Form::label('estimated_hrs', __('Estimated Hours'), ['class' => 'form-control-label']) }}
            <small
                class="form-text text-muted mb-2 mt-0">{{ __('Total hrs of project ') . $hrs['total'] . __(' & allocated total ') . $hrs['allocated'] . __(' hrs in other tasks') }}</small>
            {{ Form::number('estimated_hrs', null, ['class' => 'form-control', 'required' => 'required', 'min' => '0', 'maxlength' => '8']) }}
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            {{ Form::label('priority', __('Priority'), ['class' => 'form-control-label']) }}
            <small class="form-text text-muted mb-2 mt-0">{{ __('Set Priority of your task') }}</small>
            <select class="form-control" name="priority" id="priority" required>
                @foreach (\App\Models\ProjectTask::$priority as $key => $val)
                    <option value="{{ $key }}">{{ __($val) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            {{ Form::label('start_date', __('Start Date'), ['class' => 'form-control-label']) }}
            {{ Form::date('start_date', date('Y-m-d'), ['class' => 'form-control']) }}
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            {{ Form::label('end_date', __('End Date'), ['class' => 'form-control-label']) }}
            {{ Form::date('end_date', date('Y-m-d'), ['class' => 'form-control']) }}
        </div>
    </div>
</div>

<div class="form-group">
    <label class="form-control-label">{{ __('Task members') }}</label>
    <small class="form-text text-muted mb-2 mt-0">{{ __('Below users are assigned in your project.') }}</small>
</div>
<div class="list-group list-group-flush mb-4">
    <div class="row">
        @if ($project)
            @foreach ($project->users as $user)
                <div class="col-6">
                    <div class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col-auto ml-3">
                                <a href="#" class="avatar avatar-sm rounded-circle">
                                     {{-- <img {{ $user->img_avatar }} />  --}}
                                    @if ($user->avatar)
                                        <img src="{{ $logo_path . $user->avatar }}" title="{{ $user->name }}">
                                    @else
                                        <img {{ $user->img_avatar }} title="{{ $user->name }}">
                                    @endif
                                </a>
                            </div>
                            <div class="col ml-n2">
                                <p class="d-block h6 text-sm mb-0">{{ $user->name }}</p>
                                <p class="card-text text-sm text-muted mb-0">{{ $user->email }}</p>
                            </div>
                            <div class="col-auto text-right add_usr" data-id="{{ $user->id }}">
                                <button type="button"
                                    class="btn btn-xs btn-animated btn-primary rounded-pill btn-animated-y mr-3">
                                    <span class="btn-inner--visible">
                                        <i class="fas fa-plus" id="usr_icon_{{ $user->id }}"></i>
                                    </span>
                                    <span class="btn-inner--hidden"
                                        id="usr_txt_{{ $user->id }}">{{ __('Add') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        @if (isset($settings['is_enabled']) && $settings['is_enabled'] == 'on')
            <div class="form-group col-md-6">
                <label>{{ __('Synchroniz in Google Calendar ?') }}</label>
                <div class="form-group">
                    <div class="switch__container custom-control custom-switch float-left">
                        <input id="is_enabled" class=" custom-control-input" value="google_calender" name="synchronize_type"
                            type="checkbox">
                        <label for="is_enabled" class="custom-control-label"></label>
                    </div>
                </div>
            </div>
        @endif
    </div>
    {{ Form::hidden('assign_to', null) }}
</div>

<div class="text-right">
    {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-sm btn-primary rounded-pill']) }}
</div>
{{ Form::close() }}
