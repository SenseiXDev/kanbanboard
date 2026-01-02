
{{ Form::model($webhook, array('route' => array('webhook.update', $webhook->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="col-12 col-md-12">
        <div class="form-group">
            {{ Form::label('module', __('Module'),['class' => 'form-control-label']) }}
            {{ Form::text('module', null, ['class' => 'form-control','required'=>'required']) }}
        </div>
    </div>
    <div class="col-12 col-md-12">
        <div class="form-group">
            {{ Form::label('url', __('URL'),['class' => 'form-control-label']) }}
            {{ Form::text('url', null, ['class' => 'form-control','required'=>'required']) }}
        </div>
    </div>
    <div class="col-12 col-md-12">
        <div class="form-group">
            {{ Form::label('method', __('Method'),['class' => 'form-control-label']) }}
            {{ Form::text('method', null, ['class' => 'form-control','required'=>'required']) }}
        </div>
    </div>
</div>

<div class="text-right pt-3">
    {{ Form::button(__('Update'), ['type' => 'submit','class' => 'btn btn-sm btn-primary rounded-pill']) }}
    <button type="button" class="btn btn-sm btn-secondary rounded-pill" data-dismiss="modal">{{__('Cancel')}}</button>
</div>
{{ Form::close() }}
