{{Form::model($coupon, array('route' => array('coupons.update', $coupon->id), 'method' => 'PUT')) }}
<div class="row">
        <div class="col-12 col-md-12">
            
            <div class="text-right">
                <a href="#" class="btn btn-sm btn-primary rounded-pill ml-2" data-url="{{ route('generate',['coupon']) }}" data-ajax-popup-over="true" data-bs-placement="top" data-size="md" title="{{ __('Generate') }}" data-title="{{__('Generate content with AI')}}">
                    <span class="btn-inner--text text-white"> <span><i class="fas fa-robot"></i></span> {{__('Generate with AI')}}</span>
                </a>
            </div>
        </div>
    <div class="form-group col-md-12">
        {{Form::label('name',__('Name'),['class'=>'form-control-label'])}}
        {{Form::text('name',null,array('class'=>'form-control font-style','required'=>'required'))}}
    </div>
    <div class="form-group col-md-6">
        {{Form::label('discount',__('Discount'),['class'=>'form-control-label'])}}
        {{Form::number('discount',null,array('class'=>'form-control','required'=>'required','min'=>'1','max'=>'100','step'=>'0.01'))}}
        <span class="small">{{__('Note: Discount in Percentage')}}</span>
    </div>
    <div class="form-group col-md-6">
        {{Form::label('limit',__('Limit'),['class'=>'form-control-label'])}}
        {{Form::number('limit',null,array('class'=>'form-control','min'=>'1','required'=>'required'))}}
    </div>
    <div class="form-group col-md-12">
        {{Form::label('code',__('Code'),['class'=>'form-control-label'])}}
        {{Form::text('code',null,array('class'=>'form-control','required'=>'required'))}}
    </div>
    <div class="form-group col-md-12 text-right">
        {{Form::submit(__('Update'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
    </div>
</div>
{{ Form::close() }}

