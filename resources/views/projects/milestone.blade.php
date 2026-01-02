{{ Form::open(array('route' => array('project.milestone.store',$project->id))) }}

<div class="row">
    @if(Utility::plancheck()['enable_chatgpt'] == 'on')
        <div class="col-12 col-md-12">
            <div class="text-right">
                <a href="#" class="btn btn-sm btn-primary rounded-pill ml-2" data-url="{{ route('generate',['project_milestone']) }}" data-ajax-popup-over="true" data-bs-placement="top" data-size="md" title="{{ __('Generate') }}" data-title="{{__('Generate contact with AI')}}">
                    <span class="btn-inner--icon"> <i class="fas fa-robot"></i></span>
                    <span class="btn-inner--text text-white">{{__('Generate with AI')}}</span>
                </a>
            </div>
        </div>
    @endif
    <div class="form-group col-md-6">
        {{ Form::label('title', __('Title'),['class' => 'form-control-label']) }}
        {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
        @error('title')
        <span class="invalid-title" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('status', __('Status'),['class' => 'form-control-label']) }}
        {!! Form::select('status',\App\Models\Project::$status, null,array('class' => 'form-control','required'=>'required')) !!}
        @error('client')
        <span class="invalid-client" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="row">
    <div class="form-group  col-md-12">
        {{ Form::label('description', __('Description'),['class' => 'form-control-label']) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'3']) !!}
        @error('description')
        <span class="invalid-description" role="alert">
        <strong class="text-danger">{{ $message }}</strong>
    </span>
        @enderror
    </div>
    <div class="col-md-12 text-right">
        {{ Form::button(__('Save'), ['type' => 'submit','class' => 'btn btn-sm btn-primary rounded-pill']) }}
        <button type="button" class="btn btn-sm btn-secondary rounded-pill" data-dismiss="modal">{{__('Cancel')}}</button>
    </div>
</div>
{{ Form::close() }}
