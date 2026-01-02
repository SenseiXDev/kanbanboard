@extends('layouts.admin')

@section('title')
    {{ __('Buy Plan') }}
@endsection

@push('css')
    <style>
        #card-element {
            border: 1px solid #e4e6fc;
            border-radius: 5px;
            padding: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-4 order-lg-2">
            <div class="card">
                <div class="list-group list-group-flush" id="tabs">
                    @if(isset($paymentSetting['is_manually_enabled']) && $paymentSetting['is_manually_enabled'] == 'on')
                        <div data-href="#manually" class="list-group-item text-primary">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Manually') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($paymentSetting['is_bank_tranfer_enabled']) && $paymentSetting['is_bank_tranfer_enabled'] == 'on')
                        <div data-href="#bank_transfer" class="list-group-item text-primary">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Bank Transfer') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['enable_stripe']) && $paymentSetting['enable_stripe'] == 'on')
                        <div data-href="#tabs-2" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Stripe') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['enable_paypal']) == 'on' && !empty($paymentSetting['paypal_client_id']) && !empty($paymentSetting['paypal_secret_key']))
                        <div data-href="#tabs-1" class="list-group-item text-primary">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Paypal') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on')
                        <div data-href="#tabs-3" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Paystack') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on')
                        <div data-href="#tabs-4" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Flutterwave') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['is_razorpay_enabled']) && $paymentSetting['is_razorpay_enabled'] == 'on')
                        <div data-href="#tabs-5" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Razorpay') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['is_paytm_enabled']) && $paymentSetting['is_paytm_enabled'] == 'on')
                        <div data-href="#tabs-6" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Paytm') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['is_mercado_enabled']) && $paymentSetting['is_mercado_enabled'] == 'on')
                        <div data-href="#tabs-7" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Mercado Pago') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['is_mollie_enabled']) && $paymentSetting['is_mollie_enabled'] == 'on')
                        <div data-href="#tabs-8" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Mollie') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['is_skrill_enabled']) && $paymentSetting['is_skrill_enabled'] == 'on')
                        <div data-href="#tabs-9" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Skrill') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['is_coingate_enabled']) && $paymentSetting['is_coingate_enabled'] == 'on')
                        <div data-href="#tabs-10" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Coingate') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['is_paymentwall_enabled']) && $paymentSetting['is_paymentwall_enabled'] == 'on')
                        <div data-href="#tabs-11" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('PaymentWall') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['is_toyyibpay_enabled']) && $paymentSetting['is_toyyibpay_enabled'] == 'on')
                        <div data-href="#tabs-12" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Toyyibpay') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['is_payfast_enabled']) && $paymentSetting['is_payfast_enabled'] == 'on')
                        <div data-href="#tabs-13" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Payfast') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['is_iyzipay_enabled']) && $paymentSetting['is_iyzipay_enabled'] == 'on')
                        <div data-href="#tabs-14" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Iyzipay') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['is_sspay_enabled']) && $paymentSetting['is_sspay_enabled'] == 'on')
                        <div data-href="#tabs-15" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('SSpay') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['is_paytab_enabled']) && $paymentSetting['is_paytab_enabled'] == 'on')
                        <div data-href="#tabs-16" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Paytab') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['is_benefit_enabled']) && $paymentSetting['is_benefit_enabled'] == 'on')
                        <div data-href="#tabs-17" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Benefit') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['is_cashfree_enabled']) && $paymentSetting['is_cashfree_enabled'] == 'on')
                        <div data-href="#tabs-18" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Cashfree') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['is_aamarpay_enabled']) && $paymentSetting['is_aamarpay_enabled'] == 'on')
                        <div data-href="#tabs-19" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Aamarpay') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($paymentSetting['is_paytr_enabled']) && $paymentSetting['is_paytr_enabled'] == 'on')
                        <div data-href="#tabs-20" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-coins"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('PayTR') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8 order-lg-1">
            @if(isset($paymentSetting['is_manually_enabled']) && $paymentSetting['is_manually_enabled'] == 'on')
                <div id="manually" class="tabs-card">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Manually') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 py-2">
                                    <p>{{ __('Requesting Manual Payment For The Planned Amount For The Subscriptions Plan.') }}</p>
                                </div>
                            </div>
                            <div class="text-sm-right">
                                @if($plan->id != 1)
                                    <div class="col-auto mb-2">
                                        @if(\Auth::user()->requested_plan != $plan->id)
                                            <a href="#" class="btn btn-xs btn-primary btn-icon rounded-pill" data-url="{{route('request.view',\Illuminate\Support\Facades\Crypt::encrypt($plan->id),)}}" data-ajax-popup="true" data-title="{{__('Manually Request')}}">
                                                <span class="btn-inner--icon"><i class="fas fa-share"></i></span>
                                                <span class="btn-inner--text">{{ __('Manually Request') }}</span>
                                            </a>
                                        @else
                                            <a href="{{ route('request.cancel',\Auth::user()->id) }}" class="btn btn-xs btn-primary btn-icon rounded-pill">
                                                <span class="btn-inner--icon"><i class="fas fa-times"></i></span>
                                                <span class="btn-inner--text">{{ __('Cancel Request') }}</span>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            @endif

            @if (isset($paymentSetting['is_bank_tranfer_enabled']) && $paymentSetting['is_bank_tranfer_enabled'] == 'on')
                <div id="bank_transfer" class="tabs-card">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Bank Transfer') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('plan.pay.with.banktransfer') }}" method="post"
                                class="require-validation" id="banktransfer-payment-form" enctype="multipart/form-data">
                                @csrf
                                <div class="py-3 banktransfer-payment-div">
                                    <div class="row">
                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="banktransfer_payment_frequency"
                                                        class="payment_frequency" data-from="banktransfer" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="banktransfer_payment_frequency"
                                                        class="payment_frequency" data-from="banktransfer" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                        <label class="form-label"><b>{{ __('Bank Details:') }}</b></label>
                                            <div class="form-group">
                                                @if( !empty($paymentSetting['bank_details']))
                                                    {!! $paymentSetting['bank_details'] !!}
                                                @endif
                                            </div>
                                        </div>
                                            <div class="col-md-6 mt-4">
                                                <div class="form-group">
                                                    {{ Form::label('payment_receipt', __('Payment Receipt'), ['class' => 'form-control-label']) }}
                                                    <input type="file" name="payment_receipt" id="payment_receipt"
                                                        class="custom-input-file"
                                                        onchange="document.getElementById('payment_receipt_pre').src = window.URL.createObjectURL(this.files[0])" />
                                                    <label for="payment_receipt">
                                                        <i class="fa fa-upload"></i>
                                                        <span>{{ __('Choose a file…') }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-10">
                                                <div class="form-group">
                                                    <label for="banktransfer_coupon"
                                                        class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                    <input type="text" id="banktransfer_coupon" name="coupon"
                                                        class="form-control coupon" data-from="banktransfer"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group pt-3 mt-3">
                                                    <a href="javascript:void(0)"
                                                        class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                        data-from="banktransfer">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right banktransfer-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="banktransfer-coupon-price"></b>
                                            </div>
                                        </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id" id="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="banktransfer-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($paymentSetting['enable_paypal']) == 'on' && !empty($paymentSetting['paypal_client_id']) && !empty($paymentSetting['paypal_secret_key']))
                <div id="tabs-1" class="tabs-card">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Paypal') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('plan.pay.with.paypal') }}" method="post"
                                class="require-validation" id="paypal-payment-form">
                                @csrf
                                <div class="py-3 paypal-payment-div">
                                    <div class="row">
                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="paypal_payment_frequency"
                                                        class="payment_frequency" data-from="paypal" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="paypal_payment_frequency"
                                                        class="payment_frequency" data-from="paypal" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="paypal_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="paypal_coupon" name="coupon"
                                                    class="form-control coupon" data-from="paypal"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="javascript:void(0)"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="paypal">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right paypal-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="paypal-coupon-price"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id" id="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="paypal-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($paymentSetting['enable_stripe']) == 'on' && !empty($paymentSetting['stripe_key']) && !empty($paymentSetting['stripe_secret']))
                <div id="tabs-2" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Stripe') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('stripe.post') }}" method="post"
                                class="require-validation" id="stripe-payment-form">
                                @csrf
                                <div class="py-3 stripe-payment-div">
                                    <div class="row">
                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="stripe_payment_frequency"
                                                        class="payment_frequency" data-from="stripe" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="stripe_payment_frequency"
                                                        class="payment_frequency" data-from="stripe" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle mt-1 w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="payment_type" id="one_time_type"
                                                        value="one-time" autocomplete="off" checked="">
                                                    {{ __('One Time') }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="payment_type" id="recurring_type"
                                                        value="recurring" autocomplete="off">
                                                    {{ __('Reccuring') }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="stripe_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="stripe_coupon" name="coupon"
                                                    class="form-control coupon" data-from="stripe"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="#"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="stripe">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right stripe-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="stripe-coupon-price"></b>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">

                                            <input type="hidden" name="plan_id" id="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="stripe-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on')
                <div id="tabs-3" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Paystack') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('plan.pay.with.paystack') }}" method="post"
                                class="require-validation" id="paystack-payment-form">
                                @csrf
                                <div class="py-3 paystack-payment-div">
                                    <div class="row">
                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="paystack_payment_frequency"
                                                        class="payment_frequency" data-from="paystack" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="paystack_payment_frequency"
                                                        class="payment_frequency" data-from="paystack" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="paystack_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="paystack_coupon" name="coupon"
                                                    class="form-control coupon" data-from="paystack"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="#"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="paystack">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right paystack-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="paystack-coupon-price"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="button"
                                                id="pay_with_paystack">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="paystack-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($paymentSetting['is_flutterwave_enabled']) && $paymentSetting['is_flutterwave_enabled'] == 'on')
                <div id="tabs-4" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Flutterwave') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('plan.pay.with.flaterwave') }}" method="post"
                                class="require-validation" id="flaterwave-payment-form">
                                @csrf
                                <div class="py-3 flutterwave-payment-div">
                                    <div class="row">
                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="flaterwave_payment_frequency"
                                                        class="flaterwave_frequency" data-from="flaterwave"
                                                        value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="flaterwave_payment_frequency"
                                                        class="flaterwave_frequency" data-from="flaterwave"
                                                        value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="flaterwave_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="flaterwave_coupon" name="coupon"
                                                    class="form-control coupon" data-from="flaterwave"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="#"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="flaterwave">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right flaterwave-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="flaterwave-coupon-price"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="button"
                                                id="pay_with_flaterwave">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="flaterwave-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($paymentSetting['is_razorpay_enabled']) && $paymentSetting['is_razorpay_enabled'] == 'on')
                <div id="tabs-5" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Razorpay') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('plan.pay.with.razorpay') }}" method="post"
                                class="require-validation" id="razorpay-payment-form">
                                @csrf
                                <div class="py-3 razorpay-payment-div">
                                    <div class="row">
                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="razorpay_payment_frequency"
                                                        class="razorpay_frequency" data-from="razorpay" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="razorpay_payment_frequency"
                                                        class="razorpay_frequency" data-from="razorpay" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="razorpay_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="razorpay_coupon" name="coupon"
                                                    class="form-control coupon" data-from="razorpay"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="#"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="razorpay">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right razorpay-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="razorpay-coupon-price"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="button"
                                                id="pay_with_razorpay">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="razorpay-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($paymentSetting['is_paytm_enabled']) && $paymentSetting['is_paytm_enabled'] == 'on')
                <div id="tabs-6" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Paytm') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('plan.pay.with.paytm') }}" method="post"
                                class="require-validation" id="paytm-payment-form">
                                @csrf
                                <div class="py-3 paytm-payment-div">
                                    <div class="row">
                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="paytm_payment_frequency"
                                                        class="paytm_frequency" data-from="paytm" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="paytm_payment_frequency"
                                                        class="paytm_frequency" data-from="paytm" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-11">
                                            <div class="form-group">
                                                <label for="flaterwave_coupon"
                                                    class="form-control-label text-dark">{{ __('Mobile Number') }}</label>
                                                <input type="text" id="mobile" name="mobile"
                                                    class="form-control mobile" data-from="mobile"
                                                    placeholder="{{ __('Enter Mobile Number') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="flaterwave_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="paytm_coupon" name="coupon"
                                                    class="form-control coupon" data-from="paytm"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="#"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="paytm">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right paytm-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="paytm-coupon-price"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit"
                                                id="pay_with_paytm">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="paytm-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($paymentSetting['is_mercado_enabled']) && $paymentSetting['is_mercado_enabled'] == 'on')
                <div id="tabs-7" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Mercado Pago') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('plan.pay.with.mercado') }}" method="post"
                                class="require-validation" id="mercado-payment-form">
                                @csrf
                                <div class="py-3 mercado-payment-div">
                                    <div class="row">
                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="mercado_payment_frequency"
                                                        class="mercado_frequency" data-from="mercado" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="mercado_payment_frequency"
                                                        class="mercado_frequency" data-from="mercado" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="mercado_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="mercado_coupon" name="coupon"
                                                    class="form-control coupon" data-from="mercado"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="#"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="mercado">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right mercado-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="mercado-coupon-price"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit"
                                                id="pay_with_mercado">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="mercado-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($paymentSetting['is_mollie_enabled']) && $paymentSetting['is_mollie_enabled'] == 'on')
                <div id="tabs-8" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Mollie') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('plan.pay.with.mollie') }}" method="post"
                                class="require-validation" id="mollie-payment-form">
                                @csrf
                                <div class="py-3 mercado-payment-div">
                                    <div class="row">
                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="mollie_payment_frequency"
                                                        class="mollie_frequency" data-from="mollie" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="mollie_payment_frequency"
                                                        class="mollie_frequency" data-from="mollie" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="mollie_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="mollie_coupon" name="coupon"
                                                    class="form-control coupon" data-from="mollie"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="#"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="mollie">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right mollie-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="mollie-coupon-price"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit"
                                                id="pay_with_mollie">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="mollie-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($paymentSetting['is_skrill_enabled']) && $paymentSetting['is_skrill_enabled'] == 'on')
                <div id="tabs-9" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Skrill') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('plan.pay.with.skrill') }}" method="post"
                                class="require-validation" id="skrill-payment-form">
                                @csrf
                                <div class="py-3 skrill-payment-div">
                                    <div class="row">
                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="skrill_payment_frequency"
                                                        class="skrill_frequency" data-from="skrill" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="skrill_payment_frequency"
                                                        class="skrill_frequency" data-from="skrill" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="skrill_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="skrill_coupon" name="coupon"
                                                    class="form-control coupon" data-from="skrill"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="#"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="skrill">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right skrill-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="skrill-coupon-price"></b>
                                        </div>
                                    </div>
                                    @php
                                        $skrill_data = [
                                            'transaction_id' => md5(date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id'),
                                            'user_id' => 'user_id',
                                            'amount' => 'amount',
                                            'currency' => 'currency',
                                        ];
                                        session()->put('skrill_data', $skrill_data);

                                    @endphp
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit"
                                                id="pay_with_skrill">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="skrill-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($paymentSetting['is_coingate_enabled']) && $paymentSetting['is_coingate_enabled'] == 'on')
                <div id="tabs-10" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Coingate') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('plan.pay.with.coingate') }}" method="post"
                                class="require-validation" id="coingate-payment-form">
                                @csrf
                                <div class="py-3 coingate-payment-div">
                                    <div class="row">
                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="coingate_payment_frequency"
                                                        class="coingate_frequency" data-from="coingate" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="coingate_payment_frequency"
                                                        class="coingate_frequency" data-from="coingate" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="coingate_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="coingate_coupon" name="coupon"
                                                    class="form-control coupon" data-from="coingate"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="#"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="coingate">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right coingate-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="coingate-coupon-price"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit"
                                                id="pay_with_coingate">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="coingate-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($paymentSetting['is_paymentwall_enabled']) && $paymentSetting['is_paymentwall_enabled'] == 'on')
                <div id="tabs-11" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using PaymentWall') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('plan.paymentwallpayment') }}" method="post"
                                class="require-validation" id="paymentwall-payment-form">
                                @csrf
                                <div class="py-3 paymentwall-payment-div">
                                    <div class="row">
                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="paymentwall_payment_frequency"
                                                        class="payment_frequency" data-from="paymentwall" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="paymentwall_payment_frequency"
                                                        class="payment_frequency" data-from="paymentwall" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="paymentwall_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="paymentwall_coupon" name="coupon"
                                                    class="form-control coupon" data-from="paymentwall"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="#"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="paymentwall">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right paymentwall-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="paymentwall-coupon-price"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit"
                                                id="pay_with_paymentwall">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="paymentwall-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($paymentSetting['is_toyyibpay_enabled']) && $paymentSetting['is_toyyibpay_enabled'] == 'on')
                <div id="tabs-12" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Toyyibpay') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('plan.toyyibpaypayment') }}" method="post"
                                class="require-validation">
                                @csrf
                                <div class="py-3 toyyibpay-payment-div">
                                    <div class="row">

                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="toyyibpay_payment_frequency"
                                                        class="payment_frequency" data-from="toyyibpay" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="toyyibpay_payment_frequency"
                                                        class="payment_frequency" data-from="toyyibpay" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="toyyibpay_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="toyyibpay_coupon" name="coupon"
                                                    class="form-control coupon" data-from="toyyibpay"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="#"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="toyyibpay">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right toyyibpay-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="toyyibpay-coupon-price"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit"
                                                id="pay_with_toyyibpay">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="toyyibpay-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($paymentSetting['is_payfast_enabled']) && $paymentSetting['is_payfast_enabled'] == 'on' && !empty($paymentSetting['payfast_merchant_id']) && !empty($paymentSetting['payfast_merchant_key']) && !empty($paymentSetting['payfast_signature']) && !empty($paymentSetting['payfast_mode']))
                <div id="tabs-13" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Payfast') }}</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $pfHost = $paymentSetting['payfast_mode'] == 'sandbox' ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
                            @endphp
                            <form role="form" action="{{ 'https://' . $pfHost . '/eng/process' }} " method="post"
                                id="payfast-form" class="require-validation">

                                @csrf
                                <div class="py-3 payfast-payment-div">
                                    <div class="row">

                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="payfast_payment_frequency"
                                                        class="payfast_frequency" data-from="payfast" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="payfast_payment_frequency"
                                                        class="payfast_frequency" data-from="payfast" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="payfast_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="payfast_coupon" name="coupon" data-from="payfast"
                                                    class="form-control coupon"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="javascript:void(0)"  data-from="payfast"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right payfast-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="payfast-coupon-price"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id" id="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                <div id="get-payfast-inputs"></div>
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit"
                                                id="pay_with_payfast">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="payfast-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($paymentSetting['is_iyzipay_enabled']) && $paymentSetting['is_iyzipay_enabled'] == 'on')
                <div id="tabs-14" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Iyzipay') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('iyzipay.payment.init') }}" method="post"
                                class="require-validation">
                                @csrf
                                <div class="py-3 iyzipay-payment-div">
                                    <div class="row">

                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="iyzipay_payment_frequency"
                                                        class="payment_frequency" data-from="iyzipay" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="iyzipay_payment_frequency"
                                                        class="payment_frequency" data-from="iyzipay" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="iyzipay_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="iyzipay_coupon" name="coupon"
                                                    class="form-control coupon" data-from="iyzipay"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="#"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="iyzipay">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right iyzipay-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="iyzipay-coupon-price"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit"
                                                id="pay_with_iyzipay">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="iyzipay-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($paymentSetting['is_sspay_enabled']) && $paymentSetting['is_sspay_enabled'] == 'on')
                <div id="tabs-15" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Sspay') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('plan.sspaypayment') }}" method="post"
                                class="require-validation">
                                @csrf
                                <div class="py-3 sspay-payment-div">
                                    <div class="row">

                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="sspay_payment_frequency"
                                                        class="payment_frequency" data-from="sspay" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="sspay_payment_frequency"
                                                        class="payment_frequency" data-from="sspay" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="sspay_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="sspay_coupon" name="coupon"
                                                    class="form-control coupon" data-from="sspay"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="#"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="sspay">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right sspay-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="sspay-coupon-price"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit"
                                                id="pay_with_sspay">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="sspay-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($paymentSetting['is_paytab_enabled']) && $paymentSetting['is_paytab_enabled'] == 'on')
                <div id="tabs-16" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Paytab') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('plan.pay.with.paytab') }}" method="post"
                                class="require-validation">
                                @csrf
                                <div class="py-3 paytab-payment-div">
                                    <div class="row">

                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="paytab_payment_frequency"
                                                        class="payment_frequency" data-from="paytab" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="paytab_payment_frequency"
                                                        class="payment_frequency" data-from="paytab" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="paytab_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="paytab_coupon" name="coupon"
                                                    class="form-control coupon" data-from="paytab"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="#"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="paytab">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right paytab-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="paytab-coupon-price"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit"
                                                id="pay_with_paytab">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="paytab-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($paymentSetting['is_benefit_enabled']) && $paymentSetting['is_benefit_enabled'] == 'on')
                <div id="tabs-17" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Benefit') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('plan.pay.with.benefit') }}" method="post"
                                class="require-validation">
                                @csrf
                                <div class="py-3 benefit-payment-div">
                                    <div class="row">

                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="benefit_payment_frequency"
                                                        class="payment_frequency" data-from="benefit" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="benefit_payment_frequency"
                                                        class="payment_frequency" data-from="benefit" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="benefit_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="benefit_coupon" name="coupon"
                                                    class="form-control coupon" data-from="benefit"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="#"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="benefit">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right benefit-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="benefit-coupon-price"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit"
                                                id="pay_with_benefit">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="benefit-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($paymentSetting['is_cashfree_enabled']) && $paymentSetting['is_cashfree_enabled'] == 'on')
                <div id="tabs-18" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Cashfree') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('plan.pay.with.cashfree') }}" method="post"
                                class="require-validation">
                                @csrf
                                <div class="py-3 cashfree-payment-div">
                                    <div class="row">

                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="cashfree_payment_frequency"
                                                        class="payment_frequency" data-from="cashfree" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="cashfree_payment_frequency"
                                                        class="payment_frequency" data-from="cashfree" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="cashfree_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="cashfree_coupon" name="coupon"
                                                    class="form-control coupon" data-from="cashfree"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="#"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="cashfree">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right cashfree-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="cashfree-coupon-price"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit"
                                                id="pay_with_cashfree">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="cashfree-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($paymentSetting['is_aamarpay_enabled']) && $paymentSetting['is_aamarpay_enabled'] == 'on')
                <div id="tabs-19" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using Aamarpay') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('plan.pay.with.aamarpay') }}" method="post"
                                class="require-validation">
                                @csrf
                                <div class="py-3 aamarpay-payment-div">
                                    <div class="row">

                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="aamarpay_payment_frequency"
                                                        class="payment_frequency" data-from="aamarpay" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="aamarpay_payment_frequency"
                                                        class="payment_frequency" data-from="aamarpay" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="aamarpay_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="aamarpay_coupon" name="coupon"
                                                    class="form-control coupon" data-from="aamarpay"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="#"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="aamarpay">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right aamarpay-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="aamarpay-coupon-price"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit"
                                                id="pay_with_aamarpay">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="aamarpay-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif


            @if(isset($paymentSetting['is_paytr_enabled']) && $paymentSetting['is_paytr_enabled'] == 'on')
                <div id="tabs-20" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Pay Using PayTR') }}</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" action="{{ route('plan.pay.with.paytr') }}" method="post"
                                class="require-validation">
                                @csrf
                                <div class="py-3 paytr-payment-div">
                                    <div class="row">

                                        <div class="col-12 pb-3">
                                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                <label class="btn btn-sm btn-primary active">
                                                    <input type="radio" name="paytr_payment_frequency"
                                                        class="payment_frequency" data-from="paytr" value="monthly"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}"
                                                        autocomplete="off"
                                                        checked="">{{ __('Monthly Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}
                                                </label>
                                                <label class="btn btn-sm btn-primary">
                                                    <input type="radio" name="paytr_payment_frequency"
                                                        class="payment_frequency" data-from="paytr" value="annual"
                                                        data-price="{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}"
                                                        autocomplete="off">{{ __('Annual Payments') }}<br>
                                                    {{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->annual_price }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="paytr_coupon"
                                                    class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                <input type="text" id="paytr_coupon" name="coupon"
                                                    class="form-control coupon" data-from="paytr"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group pt-3 mt-3">
                                                <a href="#"
                                                    class="btn btn-primary btn-sm my-auto text-white apply-coupon"
                                                    data-from="paytr">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right paytr-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="paytr-coupon-price"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="text-sm-right">
                                            <input type="hidden" name="plan_id"
                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                            <button class="btn btn-primary btn-sm rounded-pill" type="submit"
                                                id="pay_with_paytr">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }} (<span
                                                    class="paytr-final-price">{{ (env('CURRENCY') ? env('CURRENCY') : '$') . $plan->monthly_price }}</span>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
@push('script')
    <script src="{{ url('assets/js/jquery.form.js') }}"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    @if (
        isset($paymentSetting['enable_stripe']) == 'on' &&
            !empty($paymentSetting['stripe_key']) &&
            !empty($paymentSetting['stripe_secret']))
        <?php $stripe_session = Session::get('stripe_session'); ?>
        <?php if(isset($stripe_session) && $stripe_session): ?>
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            var stripe = Stripe('{{ $paymentSetting['stripe_key'] }}');
            stripe.redirectToCheckout({
                sessionId: '{{ $stripe_session->id }}',
            }).then((result) => {});
        </script>
        <?php endif ?>
    @endif

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.list-group .list-group-item').first().trigger('click');
            }, 100);

            $('.list-group-item').on('click', function() {
                var href = $(this).attr('data-href');
                $('.tabs-card').addClass('d-none');
                $(href).removeClass('d-none');
                $('#tabs .list-group-item').removeClass('text-primary');
                $(this).addClass('text-primary');
            });

            $('.payment_frequency:first').trigger('change');
        });

        $(document).on('change', '.payment_frequency', function(e) {
            var price = $(this).attr('data-price');
            var where = $(this).attr('data-from');
            $('.' + where + '-final-price').text(price);

            if ($('#' + where + '_coupon').val() != null && $('#' + where + '_coupon').val() != '') {
                applyCoupon($('#' + where + '_coupon').val(), where);
            }
        });

        $(document).on('click', '.apply-coupon', function(e) {
            e.preventDefault();
            var where = $(this).attr('data-from');
            applyCoupon($('#' + where + '_coupon').val(), where);
        })


        // Paystack Payment
        if (!empty($paymentSetting['is_paystack_enabled']) && isset($paymentSetting['is_paystack_enabled']) && $paymentSetting['is_paystack_enabled'] == 'on')
            $(document).on("click", "#pay_with_paystack", function()
            {
                $('#paystack-payment-form').ajaxForm(function(res) {
                    if (res.flag == 1) {
                        var coupon_id = res.coupon;

                        var paystack_callback = "{{ url('/plan/paystack') }}";
                        var order_id = '{{ time() }}';
                        var handler = PaystackPop.setup({
                            key: '{{ $paymentSetting['paystack_public_key'] }}',
                            email: res.email,
                            amount: res.total_price * 100,
                            currency: res.currency,
                            ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                                1
                            ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                            metadata: {
                                custom_fields: [{
                                    display_name: "Email",
                                    variable_name: "email",
                                    value: res.email,
                                }]
                            },

                            callback: function(response) {
                                console.log(response.reference, order_id);
                                window.location.href = paystack_callback + '/' + response
                                    .reference + '/' + '{{ encrypt($plan->id) }}' +
                                    '?coupon_id=' + coupon_id + '&payment_frequency=' + res
                                    .payment_frequency
                            },
                            onClose: function() {
                                alert('window closed');
                            }
                        });
                        handler.openIframe();
                    } else if (res.flag == 2) {

                    } else {
                        show_toastr('Error', data.message, 'msg');
                    }

                }).submit();
            });
        endif

        // Flaterwave Payment
        $(document).on("click", "#pay_with_flaterwave", function() {
            $('#flaterwave-payment-form').ajaxForm(function(res) {
                if (res.flag == 1) {
                    var coupon_id = res.coupon;

                    var API_publicKey = '{{ $paymentSetting['flutterwave_public_key'] }}';
                    var nowTim = "{{ date('d-m-Y-h-i-a') }}";

                    var flutter_callback = "{{ url('/plan/flaterwave') }}";
                    var x = getpaidSetup({
                        PBFPubKey: API_publicKey,
                        customer_email: '{{ Auth::user()->email }}',
                        amount: res.total_price,
                        currency: res.currency,
                        txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) +
                            'fluttpay_online-' +
                            {{ date('Y-m-d') }},
                        meta: [{
                            metaname: "payment_id",
                            metavalue: "id"
                        }],


                        onclose: function() {},
                        callback: function(response) {
                            var txref = response.tx.txRef;
                            if (
                                response.tx.chargeResponseCode == "00" ||
                                response.tx.chargeResponseCode == "0"
                            ) {
                                window.location.href = flutter_callback + '/' + txref + '/' +
                                    '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}?coupon_id=' +
                                    coupon_id + '&payment_frequency=' + res.payment_frequency;
                            } else {
                                // redirect to a failure page.
                            }
                            x.close(); // use this to close the modal immediately after payment.
                        }
                    });
                } else if (res.flag == 2) {

                } else {
                    show_toastr('Error', data.message, 'msg');
                }

            }).submit();
        });

        @if(isset($paymentSetting['razorpay_public_key']))
            $(document).on("click", "#pay_with_razorpay", function () {
                $('#razorpay-payment-form').ajaxForm(function (res) {
                    if (res.flag == 1) {
                        var amount = res.total_price;
                        var razorPay_callback = '{{url('/invoice/razorpay')}}';
                        var totalAmount = res.total_price * 100;
                        var coupon_id = res.coupon;
                        var options = {
                            "key": "{{ $paymentSetting['razorpay_public_key']  }}", // your Razorpay Key Id
                            "amount": totalAmount,
                            "name": 'Plan',
                            "currency": '{{App\Models\Utility::getValByName('site_currency')}}',
                            "description": "",
                            "handler": function (response) {
                                window.location.href = razorPay_callback + '/' + response.razorpay_payment_id + '/' +
                                '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}?coupon_id=' +
                                    coupon_id + '&payment_frequency=' + res.payment_frequency;
                            },
                            "theme": {
                                "color": "#528FF0"
                            }
                        };
                        var rzp1 = new Razorpay(options);
                        rzp1.open();
                    } else if (res.flag == 2) {
                        toastrs('Error', res.msg, 'msg');
                    } else {ppaymenr
                        toastrs('Error', data.message, 'msg');
                    }

                }).submit();
            });
        @endif

        function applyCoupon(coupon_code, where) {
            if (coupon_code != null && coupon_code != '') {
                var frequency = $('input[name="' + where + '_payment_frequency"]:checked').val()
                $.ajax({
                    url: '{{route('apply.coupon')}}',
                    datType: 'json',
                    data: {
                        plan_id: '{{ $plan->id }}',
                        coupon: coupon_code,
                        frequency: frequency
                        // alert(frequency);
                    },
                    success: function (data) {
                        if (data.is_success) {

                            if(where == 'payfast')
                            {
                                get_payfast_status(data.price, coupon_code,frequency);
                            }
                            else
                            {
                                $('.' + where + '-coupon-tr').show().find('.' + where + '-coupon-price').text(data.discount_price);
                                $('.' + where + '-final-price').text(data.final_price);
                                // show_toastr('Success', data.message, 'success');
                            }

                        } else {
                            $('.' + where + '-coupon-tr').hide().find('.' + where + '-coupon-price').text('');
                            $('.' + where + '-final-price').text(data.final_price);
                            show_toastr('Error', data.message, 'error');
                        }
                    }
                })
            } else {
                show_toastr('Error', '{{__('Invalid Coupon Code.')}}', 'error');
                $('.' + where + '-coupon-tr').hide().find('.' + where + '-coupon-price').text('');
                var price = $('input[name="' + where + '_payment_frequency"]:checked').attr('data-price');
                $('.' + where + '-final-price').text(price);
            }
        }



            @if ( isset($paymentSetting['is_payfast_enabled']) &&
                $paymentSetting['is_payfast_enabled'] == 'on' &&
                !empty($paymentSetting['payfast_merchant_id']) &&
                !empty($paymentSetting['payfast_merchant_key']))
            $(document).ready(function() {
                var frequency = $('.payfast_frequency').val();
                // alert(frequency);
                get_payfast_status(amount = 0, coupon = null,frequency);

            })

            $('.payfast_frequency').on('change',function(){
                var frequency = $(this).val();
                get_payfast_status(amount = 0, coupon = null,frequency);

            })
            function get_payfast_status(amount, coupon,frequency) {

                var plan_id = $('#plan_id').val();
                // var frequency = $('input[name=payfast_payment_frequency]:checked', '.payfast-form').val();
                // alert(frequency);


                $.ajax({
                    url: '{{ route('payfast.payment') }}',
                    method: 'POST',
                    data: {
                        'plan_id': plan_id,
                        'coupon_amount': amount,
                        'coupon_code': coupon,
                        'frequency': frequency,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {

                        if (data.success == true) {
                            $('#get-payfast-inputs').append(data.inputs);

                        } else {
                            show_toastr('Error', data.inputs, 'error')
                        }
                    }
                });
            }
        @endif
</script>
@endpush



