{{ Form::open(array('url' => 'webhook','enctype' => 'multipart/form-data')) }}
<div class="row">
    <div class="col-12 col-md-12">
        <div class="form-group">
            {{ Form::label('module', __('Module'),['class' => 'form-control-label']) }}
            <select name="module" id="module" class="form-control">
                @foreach ($module as $key=>$value)
                    <option value="{{ $key }}" >{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-12 col-md-12">
        <div class="form-group">
            {{ Form::label('url', __('URL'),['class' => 'form-control-label']) }}
            {{ Form::text('url', null, ['class' => 'form-control','required'=>'required', 'placeholder' => __('Enter Webhook URL'),]) }}
        </div>
    </div>
    <div class="col-12 col-md-12">
        <div class="form-group">

            {{Form::label('method',__('Method'),['class'=>'form-control-label'])}}
                <select name="method" id="method" class="form-control">
                   @foreach ($method as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                   @endforeach
                </select>
        </div>
    </div>
</div>

<div class="text-right pt-3">
    {{ Form::button(__('Create'), ['type' => 'submit','class' => 'btn btn-sm btn-primary rounded-pill']) }}
    <button type="button" class="btn btn-sm btn-secondary rounded-pill" data-dismiss="modal">{{__('Cancel')}}</button>
</div>
{{ Form::close() }}
