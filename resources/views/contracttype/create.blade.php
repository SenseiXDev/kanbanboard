{{ Form::open(array('url' => 'contract')) }}
<div class="row">
    @if(Utility::plancheck()['enable_chatgpt'] == 'on')
        <div class="col-12 col-md-12">
            <div class="text-right">
                <a href="#" class="btn btn-sm btn-primary rounded-pill " data-url="{{ route('generate',['contract_type']) }}" data-ajax-popup-over="true" data-bs-placement="top" data-size="md" title="{{ __('Generate') }}" data-title="{{__('Generate content with AI')}}">
                    <span class="btn-inner--icon"> <i class="fas fa-robot"></i></span>
                    <span class="btn-inner--text text-white">{{__('Generate with AI')}}</span>
                </a>
            </div>
        </div>
    @endif
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('name', __('Name'),['class' => 'form-control-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control','required'=>'required']) }}
        </div>
    </div>
</div>

<div class="text-right pt-3">
    {{ Form::button(__('Create'), ['type' => 'submit','class' => 'btn btn-sm btn-primary rounded-pill']) }}
    <button type="button" class="btn btn-sm btn-secondary rounded-pill" data-dismiss="modal">{{__('Cancel')}}</button>
</div>
{{ Form::close() }}
