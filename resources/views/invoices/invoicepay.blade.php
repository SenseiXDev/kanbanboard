@extends('layouts.invoicepayheader')
@section('title')
    {{ __('Invoice') }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0 ">{{ __('Invoice') }} {{ '(' . $invoice->name . ')' }}</h5>
    </div>
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
@php
    if(isset($invoice))
    {
        $payment_detail = Utility::getCompanyPaymentSetting($invoice->created_by);
    }
    elseif(\Auth::check())
    {
        $payment_detail = Utility::getCompanyPaymentSetting(\Auth::user()->creatorId());
    }
@endphp

@section('action-button')
@if ($invoice->getDue($invoice->id) > 0)
        <a href="#" data-toggle="modal" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Pay Now') }}" data-size="lg"
            data-target="#paymentModal" class="btn btn-sm btn-white btn-icon rounded-pill mt-3">
            <span class="btn-inner--text">{{ __('Pay Now') }}</span>
        </a>
@endif

    <a href="{{ route('invoice.download.pdf', \Crypt::encrypt($invoice->id)) }}" target="_blank"
        class="btn btn-sm btn-white btn-icon rounded-pill mt-3">
        <span class="btn-inner--text">{{ __('Print') }}</span>
    </a>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <dl class="row">
                        <div class="col-12">
                            <div class="row align-items-center mb-5">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-8">
                                            <h6 class="d-inline-block m-0 d-print-none">{{ __('Invoice ID') }}</h6>
                                            <span class="col-sm-8">
                                                <span
                                                    class="text-sm">{{ App\Models\User::invoiceNumberFormat($invoice->id) }}</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row mb-5">
                                        <div class="col-lg-6 col-md-8">
                                            <h6 class="d-inline-block m-0 d-print-none">{{ __('Invoice Date') }}</h6>
                                            <span class="col-sm-8"><span
                                                    class="text-sm">{{ App\Models\User::dateFormat($invoice->created_at) }}</span></span>
                                        </div>
                                    </div>

                                </div>
                                <div>
                                    <div class="col-lg-6 col-md-6" style="right: -391px; padding-top: 10px;">
                                        <span
                                            class="badge badge-pill badge-{{ \App\Models\Invoice::$status_color[$invoice->status] }} ml-3">
                                            {{ __(\App\Models\Invoice::$status[$invoice->status]) }}</span>
                                    </div>

                                    <div class="col-lg-6 col-md-6 text-align-right "
                                        style="right: -356px; padding-top: 10px;" style="position: relative">
                                        {!! DNS2D::getBarcodeHTML(
                                            route('pay.invoice', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id)),'QRCODE',2,2,) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-md-4 text-sm">
                                    <strong>{{__('Project')}}</strong><br>
                                    {{$invoice->project->title}}<br>
                                </div>
                                <div class="col-md-4 text-sm text-center">
                                    <strong>{{__('Due Date')}}</strong><br>
                                    {{\App\Models\Utility::getDateFormated($invoice->due_date)}}<br>
                                </div>
                                <div class="col-md-4 text-sm text-right">
                                    <strong>{{__('Due Amount')}}</strong><br>
                                    {{\App\Models\Utility::projectCurrencyFormat($invoice->project_id,$invoice->getDue(),true)}}<br>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <h5>{{ __('Item List') }}</h5>
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="px-0 col-lg-4 bg-transparent border-top-0">
                                                        {{ __('Item') }}</th>
                                                    <th class="px-0 col-lg-4 bg-transparent border-top-0">
                                                        {{ __('Price') }}</th>
                                                    <th class="px-0 col-lg-4 bg-transparent border-top-0">
                                                        {{ __('Tax') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $totalQuantity = 0;
                                                    $totalRate = 0;
                                                    $totalAmount = 0;
                                                    $totalTaxPrice = 0;
                                                    $totalDiscount = 0;
                                                    $taxesData = [];
                                                @endphp
                                                {{-- @foreach ($invoice->items as $invoiceitem)
                                                    @php
                                                        // $taxes=App\Models\Utility::tax($invoiceitem->tax);
                                                        $totalQuantity += $invoiceitem->quantity;
                                                        $totalRate += $invoiceitem->price;
                                                        $totalDiscount += $invoiceitem->discount;
                                                        if (!empty($taxes[0])) {
                                                            foreach ($taxes as $taxe) {
                                                                $taxDataPrice = App\Models\Utility::taxRate($taxe->rate, $invoiceitem->price, $invoiceitem->quantity);
                                                                if (array_key_exists($taxe->tax_name, $taxesData)) {
                                                                    $taxesData[$taxe->tax_name] = $taxesData[$taxe->tax_name] + $taxDataPrice;
                                                                } else {
                                                                    $taxesData[$taxe->tax_name] = $taxDataPrice;
                                                                }
                                                            }
                                                        }
                                                    @endphp

                                                    <tr>
                                                        <td class="px-0">{{ $invoiceitem->item }} </td>

                                                        <td class="px-0">
                                                            {{ App\Models\User::priceFormat($invoiceitem->price) }} </td>

                                                        <td class="px-0">
                                                            {{ \App\Models\Utility::projectCurrencyFormat($invoice->project_id, $taxes, true) }}
                                                        </td>
                                                    </tr>
                                                @endforeach --}}

                                                @foreach($invoice->items as $item)
                                                    <tr>
                                                        <td class="px-0">
                                                            <span class="h6 text-sm">{{ $item->item }}</span>
                                                        </td>

                                                        <td class="px-0">
                                                            {{ App\Models\User::priceFormat($item->price) }}
                                                        </td>

                                                        <td class="px-0">{{ number_format($item->tax()) }}</td>
                                                    </tr>
                                                @endforeach
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4">&nbsp;</td>
                                                    <td class="px-0"></td>
                                                    <td class="text-right"><strong>{{ __('Sub Total') }}</strong></td>

                                                    <td class="text-right subTotal">
                                                        {{ App\Models\User::priceFormat($invoice->getSubTotal()) }}</td>

                                                </tr>

                                                <tr>
                                                    <td colspan="4">&nbsp;</td>
                                                    <td class="px-0"></td>
                                                    <td class="text-right"><strong>{{ __('Discount') }}</strong></td>
                                                    <td class="text-right subTotal">
                                                        {{ App\Models\User::priceFormat($invoice->getTotalDiscount()) }}
                                                    </td>

                                                </tr>
                                                @if (!empty($taxesData))
                                                    @foreach ($taxesData as $taxName => $taxPrice)
                                                        @if ($taxName != 'No Tax')
                                                            <tr>
                                                                <td colspan="4"></td>
                                                                <td class="px-0"></td>
                                                                <td class="text-right"><b>{{ $taxName }}</b></td>
                                                                <td class="text-right">
                                                                    {{ App\Models\User::priceFormat($taxPrice) }}</td>

                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                <tr>
                                                    <td colspan="4">&nbsp;</td>
                                                    <td class="px-0"></td>
                                                    <td class="text-right"><strong>{{ __('Total') }}</strong></td>
                                                    <td class="text-right subTotal">
                                                        {{ App\Models\User::priceFormat($invoice->getTotal()) }}</td>

                                                </tr>
                                            </tfoot>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card my-5 bg-secondary">
                                        <div class="card-body">
                                            <div class="row justify-content-between align-items-center">
                                                <div class="col-md-6 order-md-2 mb-4 mb-md-0">
                                                    <div class="d-flex align-items-center justify-content-md-end">
                                                        <span
                                                            class="h6 text-muted d-inline-block mr-3 mb-0">{{ __('Total value') }}:</span>
                                                        <span
                                                            class="h4 mb-0">{{ App\Models\User::priceFormat($invoice->getTotal()) }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 order-md-1">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <h5>{{ __('From') }}</h5>
                                    <dl class="row mt-4 align-items-center">
                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Company Address') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $left_address['address'] }}</span></dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{ __('Company City') }}</span>
                                        </dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $left_address['city'] }}</span></dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Company Country') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $left_address['country'] }}</span></dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{ __('Zip Code') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $left_address['zipcode'] }}</span></dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Company Contact') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $left_address['telephone'] }}</span></dd>
                                    </dl>
                                </div>
                                <div class="col-12 col-md-4">
                                    <h5>{{ __('Billing Address') }}</h5>
                                    <dl class="row mt-4 align-items-center">
                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Billing Address') }}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $left_address['address'] }}</span>
                                        </dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Billing City') }}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $left_address['city'] }}</span>
                                        </dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Billing Country') }}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $left_address['country'] }}</span>
                                        </dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{ __('Zip Code') }}</span>
                                        </dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $left_address['zipcode'] }}</span></dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Billing Contact') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ !empty($left_address->contacts->name) ? $left_address->contacts->name : '--' }}</span>
                                        </dd>
                                    </dl>
                                </div>

                                <div class="col-12 col-md-4">
                                    <h5>{{ __('Shipping Address') }}</h5>
                                    <dl class="row mt-4 align-items-center">
                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Shipping Address') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $right_address['address'] }}</span></dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Shipping City') }}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $right_address['city'] }}</span>
                                        </dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Shipping Country') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $right_address['country'] }}</span></dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{ __('Zip Code') }}</span>
                                        </dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $right_address['zipcode'] }}</span></dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Shipping Contact') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ !empty($invoice->contacts->name) ? $invoice->contacts->name : '--' }}</span>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="card">
                <div class="card-footer py-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                {{-- <dt class="col-sm-12"><span class="h6 text-sm mb-0">{{ __('Assigned User') }}</span></dt>
                                <dd class="col-sm-12"><span
                                        class="text-sm">{{ !empty($invoice->assign_user) ? $invoice->assign_user->name : '' }}</span>
                                </dd> --}}

                                <dt class="col-sm-12"><span class="h6 text-sm mb-0">{{ __('Created') }}</span></dt>
                                <dd class="col-sm-12"><span
                                        class="text-sm">{{ App\Models\User::dateFormat($invoice->created_at) }}</span>
                                </dd>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-10">
            <div class="row">
                <div class="col-12">
                    <h4 class="h4 font-weight-400 float-left">{{ __('Payment History') }}</h4>
                </div>
                @if($plan->storage_limit <= $total_storage && $plan->storage_limit != -1)
                    <h6><span class="text-danger col-lg-10">{{__('Note : Your plan storage limit is over , so you can not see customer uploaded payment receipt.')}}</span></h6>
                @endif
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('Transaction ID') }}</th>
                                        <th>{{ __('Payment Date') }}</th>
                                        <th>{{ __('Payment Type') }}</th>
                                        <th>{{ __('Receipt')}}</th>
                                        <th>{{ __('Note') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    @php $i=0; @endphp
                                    @foreach ($invoice->payments as $payment)
                                        <tr>
                                            <td>{{ sprintf('%05d', $payment->transaction_id) }}</td>
                                            <td>{{ App\Models\User::dateFormat($payment->date) }}</td>
                                            <td>{{ $payment->payment_type }}</td>
                                            <td>{{'-'}}</td>
                                            <td>{{ !empty($payment->notes) ? $payment->notes : '-' }}</td>
                                            <td>{{ App\Models\User::priceFormat($payment->amount) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    @foreach($bankpayments as $bankpayment)
                                        <tr>
                                            <td>{{sprintf('%05d', $bankpayment->order_id)}}</td>
                                            <td>{{ App\Models\User::dateFormat($bankpayment->date) }}</td>
                                            <td>{{'Bank Transfer'}}</td>
                                            <td>
                                                @if($plan->storage_limit <= $total_storage != -1)
                                                <a href="{{ \App\Models\Utility::get_file($bankpayment->receipt) }}"  title="Invoice" target="_blank"
                                                    class="text-center">
                                                    <i class="fas fa-file-invoice"></i>
                                                </a>
                                                @else
                                                    {{'-'}}
                                                @endif
                                            </td>
                                            <td>{{(!empty($bankpayment->notes)) ? $bankpayment->notes : '-'}}</td>
                                            <td>{{\App\Models\Utility::projectCurrencyFormat($invoice->project_id,$bankpayment->amount,true)}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">{{ __('Add Payment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="card">
                    <ul class="nav nav-pills mt-4" role="tablist">
                        @if (isset($payment_detail['is_bank_tranfer_enabled']) && $payment_detail['is_bank_tranfer_enabled'] == 'on')
                            <li class="nav-item ml-4">
                                <a href="#bank-payment" class="nav-link active" data-toggle="tab" role="tab" aria-controls="bank" aria-selected="false">
                                    {{ __('Bank Transfer') }}
                                </a>
                            </li>
                        @endif

                        @if (isset($payment_detail['enable_stripe']) && $payment_detail['enable_stripe'] == 'on')
                            @if (isset($payment_detail['stripe_key']) &&
                                    !empty($payment_detail['stripe_key']) &&
                                    (isset($payment_detail['stripe_secret']) && !empty($payment_detail['stripe_secret'])))
                                <li class="nav-item ml-4">
                                    <a href="#stripe-payment" class="nav-link" data-toggle="tab" role="tab"
                                        aria-selected="false">
                                        {{ __('Stripe') }}
                                    </a>
                                </li>
                            @endif
                        @endif

                        @if (isset($payment_detail['enable_paypal']) && $payment_detail['enable_paypal'] == 'on')
                            @if (isset($payment_detail['paypal_client_id']) &&
                                    !empty($payment_detail['paypal_client_id']) &&
                                    (isset($payment_detail['paypal_secret_key']) && !empty($payment_detail['paypal_secret_key'])))
                                <li class="nav-item ml-4">
                                    <a data-toggle="tab" href="#paypal-payment" class="nav-link" role="tab"
                                        aria-controls="paypal" aria-selected="false">{{ __('Paypal') }}</a>
                                </li>
                            @endif
                        @endif

                        @if (isset($payment_detail['is_paystack_enabled']) && $payment_detail['is_paystack_enabled'] == 'on')
                            @if (isset($payment_detail['paystack_public_key']) &&
                                    !empty($payment_detail['paystack_public_key']) &&
                                    (isset($payment_detail['paystack_secret_key']) && !empty($payment_detail['paystack_secret_key'])))
                                <li class="nav-item ml-4">
                                    <a data-toggle="tab" href="#paystack-payment" class="nav-link" role="tab"
                                        aria-controls="paystack" aria-selected="false">{{ __('Paystack') }}</a>
                                </li>
                            @endif
                        @endif

                        @if (isset($payment_detail['is_flutterwave_enabled']) && $payment_detail['is_flutterwave_enabled'] == 'on')
                            @if (isset($payment_detail['flutterwave_secret_key']) &&
                                    !empty($payment_detail['flutterwave_secret_key']) &&
                                    (isset($payment_detail['flutterwave_public_key']) && !empty($payment_detail['flutterwave_public_key'])))
                                <li class="nav-item ml-4">
                                    <a data-toggle="tab" href="#flutterwave-payment" class="nav-link" role="tab"
                                        aria-controls="flutterwave" aria-selected="false">{{ __('Flutterwave') }}</a>
                                </li>
                            @endif
                        @endif

                        @if (isset($payment_detail['is_razorpay_enabled']) && $payment_detail['is_razorpay_enabled'] == 'on')
                            @if (isset($payment_detail['razorpay_secret_key']) &&
                                    !empty($payment_detail['razorpay_secret_key']) &&
                                    (isset($payment_detail['razorpay_public_key']) && !empty($payment_detail['razorpay_public_key'])))
                                <li class="nav-item ml-4">
                                    <a data-toggle="tab" href="#razorpay-payment" class="nav-link" role="tab"
                                        aria-controls="razorpay" aria-selected="false">{{ __('Razorpay') }}</a>
                                </li>
                            @endif
                        @endif

                        @if (isset($payment_detail['is_mercado_enabled']) && $payment_detail['is_mercado_enabled'] == 'on')
                            <li class="nav-item ml-4">
                                <a data-toggle="tab" href="#mercado-payment" class="nav-link" role="tab"
                                    aria-controls="mercado" aria-selected="false">{{ __('Mercado Pago') }}</a>
                            </li>
                        @endif

                        @if (isset($payment_detail['is_paytm_enabled']) && $payment_detail['is_paytm_enabled'] == 'on')
                            @if (isset($payment_detail['paytm_merchant_id']) &&
                                    !empty($payment_detail['paytm_merchant_id']) &&
                                    (isset($payment_detail['paytm_merchant_key']) && !empty($payment_detail['paytm_merchant_key'])))
                                <li class="nav-item ml-4">
                                    <a data-toggle="tab" href="#paytm-payment" class="nav-link" role="tab"
                                        aria-controls="paytm" aria-selected="false">{{ __('Paytm') }}</a>
                                </li>
                            @endif
                        @endif

                        @if (isset($payment_detail['is_mollie_enabled']) && $payment_detail['is_mollie_enabled'] == 'on')
                            @if (isset($payment_detail['mollie_api_key']) &&
                                    !empty($payment_detail['mollie_api_key']) &&
                                    (isset($payment_detail['mollie_profile_id']) && !empty($payment_detail['mollie_profile_id'])))
                                <li class="nav-item ml-4">
                                    <a data-toggle="tab" href="#mollie-payment" class="nav-link" role="tab"
                                        aria-controls="mollie" aria-selected="false">{{ __('Mollie') }}</a>
                                </li>
                            @endif
                        @endif

                        @if (isset($payment_detail['is_skrill_enabled']) && $payment_detail['is_skrill_enabled'] == 'on')
                            @if (isset($payment_detail['skrill_email']) && !empty($payment_detail['skrill_email']))
                                <li class="nav-item ml-4">
                                    <a data-toggle="tab" href="#skrill-payment" class="nav-link" role="tab"
                                        aria-controls="skrill" aria-selected="false">{{ __('Skrill') }}</a>
                                </li>
                            @endif
                        @endif

                        @if (isset($payment_detail['is_coingate_enabled']) && $payment_detail['is_coingate_enabled'] == 'on')

                            <li class="nav-item ml-4">
                                <a data-toggle="tab" href="#coingate-payment" class="nav-link" role="tab"
                                    aria-controls="coingate" aria-selected="false">{{ __('CoinGate') }}</a>
                            </li>
                        @endif

                        @if (isset($payment_detail['is_payfast_enabled']) && $payment_detail['is_payfast_enabled'] == 'on')
                            <li class="nav-item ml-4">
                                <a data-toggle="tab" href="#payfast-payment" class="nav-link" role="tab"
                                    aria-controls="payfast" aria-selected="false">{{ __('Payfast') }}</a>
                            </li>
                        @endif

                        @if (isset($payment_detail['is_paymentwall_enabled']) && $payment_detail['is_paymentwall_enabled'] == 'on')
                            <li class="nav-item ml-4">
                                <a class="nav-link" data-toggle="tab" href="#paymentwall-payment" role="tab"
                                    aria-controls="paymentwall" aria-selected="false">{{ __('PaymentWall') }}</a>
                            </li>
                        @endif

                        @if (isset($payment_detail['is_toyyibpay_enabled']) && $payment_detail['is_toyyibpay_enabled'] == 'on')
                            <li class="nav-item ml-4">
                                <a class="nav-link" data-toggle="tab" href="#toyyibpay-payment" role="tab"
                                    aria-controls="toyyibpay" aria-selected="false">{{ __('Toyyibpay') }}</a>
                            </li>
                        @endif

                        @if (isset($payment_detail['is_iyzipay_enabled']) && $payment_detail['is_iyzipay_enabled'] == 'on')
                            <li class="nav-item ml-4">
                                <a class="nav-link" data-toggle="tab" href="#iyzipay-payment" role="tab"
                                    aria-controls="iyzipay" aria-selected="false">{{ __('Iyzipay') }}</a>
                            </li>
                        @endif

                        @if (isset($payment_detail['is_sspay_enabled']) && $payment_detail['is_sspay_enabled'] == 'on')
                            @if (isset($payment_detail['sspay_category_code']) &&
                                        !empty($payment_detail['sspay_category_code']) &&
                                        (isset($payment_detail['sspay_secret_key']) && !empty($payment_detail['sspay_secret_key'])))
                                <li class="nav-item ml-4">
                                    <a class="nav-link" data-toggle="tab" href="#sspay-payment" role="tab"
                                        aria-controls="sspay" aria-selected="false">{{ __('Sspay') }}</a>
                                </li>
                            @endif
                        @endif

                        @if (isset($payment_detail['is_paytab_enabled']) && $payment_detail['is_paytab_enabled'] == 'on')
                            @if (isset($payment_detail['paytab_profile_id']) &&
                                        !empty($payment_detail['paytab_profile_id']) &&
                                        (isset($payment_detail['paytab_server_key']) && !empty($payment_detail['paytab_server_key'])) && (isset($payment_detail['paytab_region']) && !empty($payment_detail['paytab_region'])))
                                <li class="nav-item ml-4">
                                    <a class="nav-link" data-toggle="tab" href="#paytab-payment" role="tab"
                                        aria-controls="paytab" aria-selected="false">{{ __('Paytab') }}</a>
                                </li>
                            @endif
                        @endif

                        @if (isset($payment_detail['is_benefit_enabled']) && $payment_detail['is_benefit_enabled'] == 'on')
                            @if (isset($payment_detail['benefit_api_key']) && !empty($payment_detail['benefit_api_key']) &&
                                    (isset($payment_detail['benefit_secret_key']) && !empty($payment_detail['benefit_secret_key'])))
                                <li class="nav-item ml-4">
                                    <a class="nav-link" data-toggle="tab" href="#benefit-payment" role="tab"
                                        aria-controls="benefit" aria-selected="false">{{ __('Benefit') }}</a>
                                </li>
                            @endif
                        @endif

                        @if (isset($payment_detail['is_cashfree_enabled']) && $payment_detail['is_cashfree_enabled'] == 'on')
                            @if (isset($payment_detail['cashfree_api_key']) && !empty($payment_detail['cashfree_api_key']) &&
                                    (isset($payment_detail['cashfree_secret_key']) && !empty($payment_detail['cashfree_secret_key'])))
                                <li class="nav-item ml-4">
                                    <a class="nav-link" data-toggle="tab" href="#cashfree-payment" role="tab"
                                        aria-controls="cashfree" aria-selected="false">{{ __('Cashfree') }}</a>
                                </li>
                            @endif
                        @endif

                        @if (isset($payment_detail['is_aamarpay_enabled']) && $payment_detail['is_aamarpay_enabled'] == 'on')
                            <li class="nav-item ml-4">
                                <a class="nav-link" data-toggle="tab" href="#aamarpay-payment" role="tab"
                                    aria-controls="aamarpay" aria-selected="false">{{ __('Aamarpay') }}</a>
                            </li>
                        @endif

                        @if (isset($payment_detail['is_paytr_enabled']) && $payment_detail['is_paytr_enabled'] == 'on')
                            <li class="nav-item ml-4">
                                <a class="nav-link" data-toggle="tab" href="#paytr-payment" role="tab"
                                    aria-controls="paytr" aria-selected="false">{{ __('PayTR') }}</a>
                            </li>
                        @endif
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="bank-payment" role="tabpanel" aria-labelledby="bank-payment-tab">
                            <div class="card-body">

                                <form method="post" action="{{ route('invoice.pay.with.bank') }}"
                                     id="bank-payment-form" enctype = "multipart/form-data">
                                    @csrf
                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label"><b>{{ __('Bank Details:') }}</b></label>
                                                <div class="form-group">
                                                    @if(isset($payment_detail) && !empty($payment_detail['bank_details']))
                                                        {!! $payment_detail['bank_details'] !!}
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
                                        </div>
                                    </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="amount"
                                                    class="form-control-label">{{ __('Amount') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-prepend"><span
                                                            class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span></span>
                                                    <input class="form-control" required="required" min="0"
                                                        name="amount" type="number"
                                                        value="{{ $invoice->getDue() }}" min="0"
                                                        step="0.01" max="" id="amount">
                                                    <input type="hidden" value="invoice" name="from">
                                                    <input type="hidden" value="{{ $invoice->created_by }}"
                                                        name="invoice_creator">
                                                    <input type="hidden" value="{{ $invoice->id }}"
                                                        name="invoice_id">
                                                </div>
                                            </div>
                                        </div>

                                    <div class="form-group mt-3 text-sm-right">
                                        <button class="btn btn-primary btn-sm rounded-pill"
                                            type="submit">{{ __('Make Payment') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="stripe-payment" role="tabpanel"
                            aria-labelledby="stripe-payment-tab">
                            <div class="card-body">
                                @if (isset($payment_detail['enable_stripe']) && $payment_detail['enable_stripe'] == 'on')
                                    @if (isset($payment_detail['stripe_key']) &&
                                            !empty($payment_detail['stripe_key']) &&
                                            (isset($payment_detail['stripe_secret']) && !empty($payment_detail['stripe_secret'])))
                                        <form method="post"
                                            action="{{ route('invoice.pay.with.stripe', \Crypt::encrypt($invoice->id)) }}"
                                            class="require-validation" id="payment-form">

                                            @csrf
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <div class="custom-radio">
                                                        <label
                                                            class="font-16 font-weight-bold">{{ __('Credit / Debit Card') }}</label>
                                                    </div>
                                                    <p class="mb-0 pt-1">
                                                        {{ __('Safe money transfer using your bank account. We support Mastercard, Visa, Discover and American express.') }}
                                                    </p>
                                                </div>
                                                <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                                                    <img src="{{ asset('assets/img/payments/master.png') }}"
                                                        height="24" alt="master-card-img">
                                                    <img src="{{ asset('assets/img/payments/paypal.png') }}"
                                                        height="24" alt="paypal-card-img">
                                                    <img src="{{ asset('assets/img/payments/visa.png') }}" height="24"
                                                        alt="visa-card-img">
                                                    <img src="{{ asset('assets/img/payments/american-express.png') }}"
                                                        height="24" alt="american-express-card-img">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <hr>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="card-name-on"
                                                            class="form-control-label">{{ __('Name on card') }}</label>
                                                        <input type="text" name="name" id="card-name-on"
                                                            class="form-control required">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div id="card-element">
                                                        <!-- A Stripe Element will be inserted here. -->
                                                    </div>
                                                    <div id="card-errors" role="alert"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <br>
                                                    <label for="amount"
                                                        class="form-control-label">{{ __('Amount') }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-prepend"><span
                                                                class="input-group-text">{{ $invoice->project->currency }}</span></span>
                                                        <input class="form-control" required="required" min="1"
                                                            name="amount" type="number"
                                                            value="{{ $invoice->getDue() }}" step="0.01"
                                                            id="amount">
                                                    </div>
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
                                            <div class="form-group mt-3 text-sm-right">
                                                <button class="btn btn-primary btn-sm rounded-pill"
                                                    type="submit">{{ __('Make Payment') }}</button>
                                            </div>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="paypal-payment" role="tabpanel"
                            aria-labelledby="paypal-payment-tab">
                            <div class="card-body">
                                @if (isset($payment_detail['enable_paypal']) && $payment_detail['enable_paypal'] == 'on')
                                    @if (isset($payment_detail['paypal_client_id']) &&
                                            !empty($payment_detail['paypal_client_id']) &&
                                            (isset($payment_detail['paypal_secret_key']) && !empty($payment_detail['paypal_secret_key'])))
                                        <form class="w3-container w3-display-middle w3-card-4 " method="POST"
                                            id="payment-form"
                                            action="{{ route('client.pay.with.paypal', $invoice->id) }}">
                                            @csrf
                                            <div class="border p-3 mb-3 rounded">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="amount"
                                                            class="form-control-label">{{ __('Amount') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-prepend"><span
                                                                    class="input-group-text">{{ $invoice->project->currency }}</span></span>
                                                            <input class="form-control" required="required"
                                                                min="0" name="amount" type="number"
                                                                value="{{ $invoice->getDue() }}" min="0"
                                                                step="0.01" max="{{ $invoice->getDue() }}"
                                                                id="amount">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mt-3 text-sm-right">
                                                <input type="hidden" value="invoice" name="from">
                                                <input type="hidden" value="{{ $invoice->created_by }}"
                                                    name="invoice_creator">
                                                <button class="btn btn-primary btn-sm rounded-pill" name="submit"
                                                    type="submit">{{ __('Make Payment') }}</button>
                                            </div>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="paystack-payment" role="tabpanel"
                            aria-labelledby="paystack-payment-tab">
                            <div class="card-body">
                                    <form method="post" action="{{ route('invoice.pay.with.paystack') }}"
                                        class="require-validation" id="paystack-payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded">
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount"
                                                        class="form-control-label">{{ __('Amount') }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-prepend"><span
                                                                class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span></span>
                                                        <input class="form-control" required="required" min="0"
                                                            name="amount" type="number"
                                                            value="{{ $invoice->getDue() }}" min="0"
                                                            step="0.01" max="" id="amount">
                                                        <input type="hidden" value="invoice" name="from">
                                                        <input type="hidden" value="{{ $invoice->created_by }}"
                                                            name="invoice_creator">
                                                        <input type="hidden" value="{{ $invoice->id }}"
                                                            name="invoice_id">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 form-group mt-3 text-right">
                                            <input type="button" value="{{ __('Make Payment') }}"
                                                class="btn btn-sm btn-primary rounded-pill" id="pay_with_paystack">
                                        </div>
                                    </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="flutterwave-payment" role="tabpanel"
                            aria-labelledby="flutterwave-payment-tab">
                            <div class="card-body">
                                {{-- @if (isset($payment_detail['is_flutterwave_enabled']) && $payment_detail['is_flutterwave_enabled'] == 'on') --}}

                                        <form method="post" action="{{ route('invoice.pay.with.flaterwave') }}"
                                            class="require-validation" id="flaterwave-payment-form">
                                            @csrf
                                            <div class="border p-3 mb-3 rounded">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="form-control-label"
                                                            for="amount">{{ __('Amount') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-prepend"><span
                                                                    class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span></span>
                                                            <input class="form-control" required="required"
                                                                min="0" name="amount" type="number"
                                                                value="{{ $invoice->getDue() }}" min="0"
                                                                step="0.01" max="" id="amount">
                                                            <input type="hidden" value="invoice" name="from">
                                                            <input type="hidden" value="{{ $invoice->created_by }}"
                                                                name="invoice_creator">
                                                            <input type="hidden" value="{{ $invoice->id }}"
                                                                name="invoice_id">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mt-3 text-sm-right">
                                                <button class="btn btn-primary btn-sm rounded-pill" type="button"
                                                    id="pay_with_flaterwave">{{ __('Make Payment') }}</button>
                                            </div>
                                        </form>
                                {{-- @endif --}}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="razorpay-payment" role="tabpanel"
                            aria-labelledby="razorpay-payment-tab">
                            <div class="card-body">
                                @if (isset($payment_detail['is_razorpay_enabled']) && $payment_detail['is_razorpay_enabled'] == 'on')
                                    <form method="post" action="{{ route('invoice.pay.with.razorpay') }}"
                                        class="require-validation" id="razorpay-payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded">
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label class="form-control-label"
                                                        for="amount">{{ __('Amount') }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-prepend"><span
                                                                class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span></span>
                                                        <input class="form-control" required="required" min="0"
                                                            name="amount" type="number"
                                                            value="{{ $invoice->getDue() }}" min="0"
                                                            step="0.01" max="" id="amount">
                                                        <input type="hidden" value="invoice" name="from">
                                                        <input type="hidden" value="{{ $invoice->created_by }}"
                                                            name="invoice_creator">
                                                        <input type="hidden" value="{{ $invoice->id }}"
                                                            name="invoice_id">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 form-group mt-3 text-right">
                                            <input type="button" value="{{ __('Make Payment') }}"
                                                class="btn btn-sm btn-primary rounded-pill" id="pay_with_razerpay">
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="mollie-payment" role="tabpanel"
                            aria-labelledby="mollie-payment-tab">
                            <div class="card-body">

                                @if (isset($payment_detail['is_mollie_enabled']) && $payment_detail['is_mollie_enabled'] == 'on')
                                    @if (isset($payment_detail['mollie_api_key']) &&
                                            !empty($payment_detail['mollie_api_key']) &&
                                            (isset($payment_detail['mollie_profile_id']) && !empty($payment_detail['mollie_profile_id'])))
                                        <form method="post" action="{{ route('invoice.pay.with.mollie') }}"
                                            class="require-validation" id="mollie-payment-form">
                                            @csrf
                                            <div class="border p-3 mb-3 rounded">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="form-control-label"
                                                            for="amount">{{ __('Amount') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-prepend"><span
                                                                    class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span></span>
                                                            <input class="form-control" required="required"
                                                                min="0" name="amount" type="number"
                                                                value="{{ $invoice->getDue() }}" min="0"
                                                                step="0.01" max="" id="amount">
                                                            <input type="hidden" value="invoice" name="from">
                                                            <input type="hidden" value="{{ $invoice->created_by }}"
                                                                name="invoice_creator">
                                                            <input type="hidden" value="{{ $invoice->id }}"
                                                                name="invoice_id">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mt-3 text-sm-right">
                                                <button class="btn btn-primary btn-sm rounded-pill"
                                                    type="submit">{{ __('Make Payment') }}</button>
                                            </div>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade " id="mercado-payment" role="tabpanel"
                            aria-labelledby="mercado-payment">
                            <div class="card-body">
                            <form method="post" action="{{ route('invoice.pay.with.mercado') }}"
                                class="require-validation" id="mercado-form">
                                @csrf
                                <div class="border p-3 mb-3 rounded">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="form-control-label" for="amount">{{ __('Amount') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-prepend"><span
                                                        class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span></span>
                                                <input class="form-control" required="required" min="0"
                                                    name="amount" type="number" value="{{ $invoice->getDue() }}"
                                                    min="0" step="0.01" max="" id="amount">
                                                <input type="hidden" value="invoice" name="from">
                                                <input type="hidden" value="{{ $invoice->created_by }}"
                                                    name="invoice_creator">
                                                <input type="hidden" value="{{ $invoice->id }}" name="invoice_id">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3 text-sm-right">
                                    <button class="btn btn-primary btn-sm rounded-pill"
                                        type="submit">{{ __('Make Payment') }}</button>
                                </div>
                            </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="paytm-payment" role="tabpanel"
                            aria-labelledby="paytm-payment-tab">
                            <div class="card-body">
                                @if (isset($payment_detail['is_paytm_enabled']) && $payment_detail['is_paytm_enabled'] == 'on')
                                    @if (isset($payment_detail['paytm_merchant_id']) &&
                                            !empty($payment_detail['paytm_merchant_id']) &&
                                            (isset($payment_detail['paytm_merchant_key']) && !empty($payment_detail['paytm_merchant_key'])))
                                        <form method="post" action="{{ route('invoice.pay.with.paytm') }}"
                                            class="require-validation" id="paytm-payment-form">
                                            @csrf
                                            <div class="border p-3 mb-3 rounded">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="form-control-label"
                                                            for="amount">{{ __('Amount') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-prepend"><span
                                                                    class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span></span>
                                                            <input class="form-control" required="required"
                                                                min="0" name="amount" type="number"
                                                                value="{{ $invoice->getDue() }}" min="0"
                                                                step="0.01" max="" id="amount">
                                                            <input type="hidden" value="invoice" name="from">
                                                            <input type="hidden" value="{{ $invoice->created_by }}"
                                                                name="invoice_creator">
                                                            <input type="hidden" value="{{ $invoice->id }}"
                                                                name="invoice_id">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="mobile"
                                                                class="form-control-label text-dark">{{ __('Mobile Number') }}</label>
                                                            <input type="text" id="mobile" name="mobile"
                                                                class="form-control mobile" data-from="mobile"
                                                                placeholder="Enter Mobile Number" required="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mt-3 text-sm-right">
                                                <button class="btn btn-primary btn-sm rounded-pill"
                                                    type="submit">{{ __('Make Payment') }}</button>
                                            </div>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="skrill-payment" role="tabpanel"
                            aria-labelledby="skrill-payment-tab">
                            <div class="card-body">
                                @if (isset($payment_detail['is_skrill_enabled']) && $payment_detail['is_skrill_enabled'] == 'on')
                                    @if (isset($payment_detail['skrill_email']) && !empty($payment_detail['skrill_email']))
                                        <form method="post" action="{{ route('invoice.pay.with.skrill') }}"
                                            class="require-validation" id="skrill-payment-form">
                                            @csrf
                                            <div class="border p-3 mb-3 rounded">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="form-control-label"
                                                            for="amount">{{ __('Amount') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-prepend"><span
                                                                    class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span></span>
                                                            <input class="form-control" required="required"
                                                                min="0" name="amount" type="number"
                                                                value="{{ $invoice->getDue() }}" min="0"
                                                                step="0.01" max="" id="amount">
                                                            <input type="hidden" value="invoice" name="from">
                                                            <input type="hidden" value="{{ $invoice->created_by }}"
                                                                name="invoice_creator">
                                                            <input type="hidden" value="{{ $invoice->id }}"
                                                                name="invoice_id">
                                                        </div>
                                                    </div>
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
                                            <div class="form-group mt-3 text-sm-right">
                                                <button class="btn btn-primary btn-sm rounded-pill"
                                                    type="submit">{{ __('Make Payment') }}</button>
                                            </div>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="coingate-payment" role="tabpanel"
                            aria-labelledby="coingate-payment-tab">
                            <div class="card-body">
                                @if (isset($payment_detail['is_coingate_enabled']) && $payment_detail['is_coingate_enabled'] == 'on')
                                    @if (isset($payment_detail['coingate_auth_token']) && !empty($payment_detail['coingate_auth_token']))
                                        <form method="post" action="{{ route('invoice.pay.with.coingate') }}"
                                            class="require-validation" id="coingate-payment-form">
                                            @csrf

                                            <div class="border p-3 mb-3 rounded">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label class="form-control-label"
                                                            for="amount">{{ __('Amount') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-prepend"><span
                                                                    class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span></span>
                                                            <input class="form-control" required="required"
                                                                min="0" name="amount" type="number"
                                                                value="{{ $invoice->getDue() }}" min="0"
                                                                step="0.01" max="" id="amount">
                                                            <input type="hidden" value="invoice" name="from">
                                                            <input type="hidden" value="{{ $invoice->created_by }}"
                                                                name="invoice_creator">
                                                            <input type="hidden" value="{{ $invoice->id }}"
                                                                name="invoice_id">
                                                        </div>
                                                    </div>
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
                                            <div class="form-group mt-3 text-sm-right">
                                                <button class="btn btn-primary btn-sm rounded-pill"
                                                    type="submit">{{ __('Make Payment') }}</button>
                                            </div>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade " id="toyyibpay-payment" role="tabpanel"
                            aria-labelledby="toyyibpay-payment">
                            <div class="card-body">
                                <form method="post" action="{{ route('invoice.pay.with.toyyibpay', $invoice->id) }}"
                                    class="require-validation" id="toyyibpay-payment-form">
                                    @csrf
                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="amount" class="form-control-label">{{ __('Amount') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-prepend"><span
                                                            class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span></span>
                                                    <input class="form-control" required="required" min="0"
                                                        name="amount" type="number" value="{{ $invoice->getDue() }}"
                                                        min="0" step="0.01" max="" id="amount">
                                                    <input type="hidden" value="invoice" name="from">
                                                    <input type="hidden" value="{{ $invoice->created_by }}"
                                                        name="invoice_creator">
                                                    <input type="hidden" value="{{ $invoice->id }}" name="invoice_id">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 text-sm-right">
                                        <button class="btn btn-primary btn-sm rounded-pill"
                                            type="submit">{{ __('Make Payment') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade " id="paymentwall-payment" role="tabpanel"
                            aria-labelledby="paymentwall-payment">
                            <div class="card-body">
                            <form method="post" action="{{ route('invoice.paymentwallpayment') }}"
                                class="require-validation" id="paymentwall-payment-form">
                                @csrf
                                <div class="border p-3 mb-3 rounded">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="amount" class="form-control-label">{{ __('Amount') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-prepend"><span
                                                        class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span></span>
                                                <input class="form-control" required="required" min="0"
                                                    name="amount" type="number" value="{{ $invoice->getDue() }}"
                                                    min="0" step="0.01" max="" id="amount">
                                                <input type="hidden" value="invoice" name="from">
                                                <input type="hidden" value="{{ $invoice->created_by }}"
                                                    name="invoice_creator">
                                                <input type="hidden" value="{{ $invoice->id }}" name="invoice_id">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 text-sm-right">
                                        <button class="btn btn-primary btn-sm rounded-pill" type="submit"
                                            id="pay_with_paymentwall">{{ __('Make Payment') }}</button>
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="payfast-payment" role="tabpanel"
                            aria-labelledby="payfast-payment-tab">
                            <div class="card-body">
                                @if (isset($payment_detail['is_payfast_enabled']) && $payment_detail['is_payfast_enabled'] == 'on')
                                    @php
                                        $pfHost = $payment_detail['payfast_mode'] == 'sandbox' ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
                                    @endphp

                                    <form method="post" action={{ 'https://' . $pfHost . '/eng/process' }}
                                        class="require-validation payfast_form" id="payfast-payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded">
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount">{{ __('Amount') }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-prepend"><span
                                                                class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span>
                                                        </span>
                                                        <input class="form-control" required="required" min="0"
                                                            name="amount" type="number"
                                                            value="{{ $invoice->getDue() }}" min="0"
                                                            step="0.01" max="" id="payfast_amount">
                                                        <input type="hidden" value="invoice" name="from">
                                                        <input type="hidden" value="{{ $invoice->created_by }}"
                                                            name="invoice_creator">
                                                        <input type="hidden" value="{{ $invoice->id }}"
                                                            id="invoice_id">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-end">

                                            <div id="get-payfast-inputs"></div>
                                            <div class="form-group mt-3 text-sm-right">
                                                <button type="button" id="payfast_submit"
                                                    class="btn btn-primary btn-sm rounded-pill">{{ __('Make Payment') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade " id="iyzipay-payment" role="tabpanel"
                            aria-labelledby="iyzipay-payment">
                            <div class="card-body">
                            <form method="post" action="{{ route('invoice.pay.with.iyzipay') }}"
                                class="require-validation" id="paymentwall-payment-form">
                                @csrf
                                <div class="border p-3 mb-3 rounded">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="amount" class="form-control-label">{{ __('Amount') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-prepend"><span
                                                        class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span></span>
                                                <input class="form-control" required="required" min="0"
                                                    name="amount" type="number" value="{{ $invoice->getDue() }}"
                                                    min="0" step="0.01" max="" id="amount">
                                                <input type="hidden" value="invoice" name="from">
                                                <input type="hidden" value="{{ $invoice->created_by }}"
                                                    name="invoice_creator">
                                                <input type="hidden" value="{{ $invoice->id }}" name="invoice_id">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 text-sm-right">
                                        <button class="btn btn-primary btn-sm rounded-pill" type="submit"
                                            id="pay_with_iyzipay">{{ __('Make Payment') }}</button>
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
                        <div class="tab-pane fade " id="sspay-payment" role="tabpanel"
                            aria-labelledby="sspay-payment">
                            <div class="card-body">
                                <form method="post" action="{{ route('invoice.pay.with.sspay', $invoice->id) }}"
                                    class="require-validation" id="sspay-payment-form">
                                    @csrf
                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="amount" class="form-control-label">{{ __('Amount') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-prepend"><span
                                                            class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span></span>
                                                    <input class="form-control" required="required" min="0"
                                                        name="amount" type="number" value="{{ $invoice->getDue() }}"
                                                        min="0" step="0.01" max="" id="amount">
                                                    <input type="hidden" value="invoice" name="from">
                                                    <input type="hidden" value="{{ $invoice->created_by }}"
                                                        name="invoice_creator">
                                                    <input type="hidden" value="{{ $invoice->id }}" name="invoice_id">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 text-sm-right">
                                        <button class="btn btn-primary btn-sm rounded-pill"
                                            type="submit">{{ __('Make Payment') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade " id="paytab-payment" role="tabpanel"
                            aria-labelledby="paytab-payment">
                            <div class="card-body">
                                <form method="post" action="{{ route('invoice.pay.with.paytab', $invoice->id) }}"
                                    class="require-validation" id="paytab-payment-form">
                                    @csrf
                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="amount" class="form-control-label">{{ __('Amount') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-prepend"><span
                                                            class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span></span>
                                                    <input class="form-control" required="required" min="0"
                                                        name="amount" type="number" value="{{ $invoice->getDue() }}"
                                                        min="0" step="0.01" max="" id="amount">
                                                    <input type="hidden" value="invoice" name="from">
                                                    <input type="hidden" value="{{ $invoice->created_by }}"
                                                        name="invoice_creator">
                                                    <input type="hidden" value="{{ $invoice->id }}" name="invoice_id">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 text-sm-right">
                                        <button class="btn btn-primary btn-sm rounded-pill"
                                            type="submit">{{ __('Make Payment') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade " id="benefit-payment" role="tabpanel"
                            aria-labelledby="benefit-payment">
                            <div class="card-body">
                                <form method="post" action="{{ route('invoice.pay.with.benefit', $invoice->id) }}"
                                    class="require-validation" id="benefit-payment-form">
                                    @csrf
                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="amount" class="form-control-label">{{ __('Amount') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-prepend"><span
                                                            class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span></span>
                                                    <input class="form-control" required="required" min="0"
                                                        name="amount" type="number" value="{{ $invoice->getDue() }}"
                                                        min="0" step="0.01" max="" id="amount">
                                                    <input type="hidden" value="invoice" name="from">
                                                    <input type="hidden" value="{{ $invoice->created_by }}"
                                                        name="invoice_creator">
                                                    <input type="hidden" value="{{ $invoice->id }}" name="invoice_id">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 text-sm-right">
                                        <button class="btn btn-primary btn-sm rounded-pill"
                                            type="submit">{{ __('Make Payment') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade " id="cashfree-payment" role="tabpanel"
                            aria-labelledby="cashfree-payment">
                            <div class="card-body">
                                <form method="post" action="{{ route('invoice.pay.with.cashfree', $invoice->id) }}"
                                    class="require-validation" id="cashfree-payment-form">
                                    @csrf
                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="amount" class="form-control-label">{{ __('Amount') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-prepend"><span
                                                            class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span></span>
                                                    <input class="form-control" required="required" min="0"
                                                        name="amount" type="number" value="{{ $invoice->getDue() }}"
                                                        min="0" step="0.01" max="" id="amount">
                                                    <input type="hidden" value="invoice" name="from">
                                                    <input type="hidden" value="{{ $invoice->created_by }}"
                                                        name="invoice_creator">
                                                    <input type="hidden" value="{{ $invoice->id }}" name="invoice_id">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 text-sm-right">
                                        <button class="btn btn-primary btn-sm rounded-pill"
                                            type="submit">{{ __('Make Payment') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade " id="aamarpay-payment" role="tabpanel"
                            aria-labelledby="aamarpay-payment">
                            <div class="card-body">
                                <form method="post" action="{{ route('invoice.pay.with.aamarpay', $invoice->id) }}"
                                    class="require-validation" id="aamarpay-payment-form">
                                    @csrf
                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="amount" class="form-control-label">{{ __('Amount') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-prepend"><span
                                                            class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span></span>
                                                    <input class="form-control" required="required" min="0"
                                                        name="amount" type="number" value="{{ $invoice->getDue() }}"
                                                        min="0" step="0.01" max="" id="amount">
                                                    <input type="hidden" value="invoice" name="from">
                                                    <input type="hidden" value="{{ $invoice->created_by }}"
                                                        name="invoice_creator">
                                                    <input type="hidden" value="{{ $invoice->id }}" name="invoice_id">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 text-sm-right">
                                        <button class="btn btn-primary btn-sm rounded-pill"
                                            type="submit">{{ __('Make Payment') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade " id="paytr-payment" role="tabpanel"
                            aria-labelledby="paytr-payment">
                                <div class="card-body">
                                    <form method="post" action="{{ route('invoice.pay.with.paytr', $invoice->id) }}"
                                        class="require-validation" id="paytr-payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded">
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount" class="form-control-label">{{ __('Amount') }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-prepend"><span
                                                                class="input-group-text">{{ isset($invoice->project->currency) ? $invoice->project->currency : '$' }}</span></span>
                                                        <input class="form-control" required="required" min="0"
                                                            name="amount" type="number" value="{{ $invoice->getDue() }}"
                                                            min="0" step="0.01" max="" id="amount">
                                                        <input type="hidden" value="invoice" name="from">
                                                        <input type="hidden" value="{{ $invoice->created_by }}"
                                                            name="invoice_creator">
                                                        <input type="hidden" value="{{ $invoice->id }}" name="invoice_id">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-3 text-sm-right">
                                            <button class="btn btn-primary btn-sm rounded-pill"
                                                type="submit">{{ __('Make Payment') }}</button>
                                        </div>
                                    </form>
                                </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="{{ url('assets/js/jquery.form.js') }}"></script>
     @if ($invoice->getDue() > 0 && isset($payment_detail['enable_stripe']) == 'on')
        <script src="https://js.stripe.com/v3/"></script>
        <script type="text/javascript">
            var stripe = Stripe('{{ isset($payment_detail['stripe_key'])}}');
            var elements = stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            var style = {
                base: {
                    // Add your base input styles here. For example:
                    fontSize: '14px',
                    color: '#32325d',
                },
            };

            // Create an instance of the card Element.
            var card = elements.create('card', {
                style: style
            });

            // Add an instance of the card Element into the `card-element` <div>.
            card.mount('#card-element');

            // Create a token or display an error when the form is submitted.
            var form = document.getElementById('stripe-payment-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        toastr('Error', result.error.message, 'error');
                    } else {
                        // Send the token to your server.
                        stripeTokenHandler(result.token);
                    }
                });
            });

            function stripeTokenHandler(token) {
                // Insert the token ID into the form so it gets submitted to the server
                var form = document.getElementById('stripe-payment-form');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);

                // Submit the form
                form.submit();
            }
        </script>
    @endif

    @if ($invoice->getDue() > 0 && isset($payment_detail['is_paystack_enabled']) && $payment_detail['is_paystack_enabled'] == 'on')

        <script src="https://js.paystack.co/v1/inline.js"></script>
        <script type="text/javascript">
            $(document).on("click", "#pay_with_paystack", function() {

                $('#paystack-payment-form').ajaxForm(function(res) {
                    if (res.flag == 1) {

                        var coupon_id = res.coupon;
                        var paystack_callback = "{{ url('/invoice-pay-with-paystack') }}";
                        var order_id = '{{ time() }}';
                        var handler = PaystackPop.setup({
                            key: '{{ isset($payment_detail['paystack_public_key']) }}',
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
                                window.location.href = "{{ url('/invoice/paystack') }}/" +
                                    response.reference + "/{{ encrypt($invoice->id) }}";
                            },
                            onClose: function() {
                                alert('window closed');
                            }
                        });
                        handler.openIframe();
                    } else if (res.flag == 2) {} else {
                        show_toastr('Error', data.message, 'msg');
                    }
                }).submit();
            });
        </script>
    @endif

    @if ($invoice->getDue() > 0 && isset($payment_detail['is_flutterwave_enabled']) && $payment_detail['is_flutterwave_enabled'] == 'on')

    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>

    <script type="text/javascript">
        //    Flaterwave Payment
        $(document).on("click", "#pay_with_flaterwave", function() {

            $('#flaterwave-payment-form').ajaxForm(function(res) {

                if (res.flag == 1) {

                    var coupon_id = res.coupon;
                    var API_publicKey = '';
                    if ("{{ isset($payment_detail['flutterwave_public_key']) }}") {
                        API_publicKey = "{{ isset($payment_detail['flutterwave_public_key']) }}";
                    }
                    var nowTim = "{{ date('d-m-Y-h-i-a') }}";
                    var flutter_callback = "{{ url('/invoice-pay-with-flaterwave') }}";
                    var x = getpaidSetup({
                        PBFPubKey: API_publicKey,
                        customer_email: res.email,
                        amount: res.total_price,
                        currency: '{{ env('currency_symbol') }}',
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
                            if (response.tx.chargeResponseCode == "00" || response.tx
                                .chargeResponseCode == "0") {
                                window.location.href = "{{ url('/invoice/flaterwave') }}/" +
                                    txref + "/{{ encrypt($invoice->id) }}";
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
    </script>
    @endif

    @if ($invoice->getDue() > 0 && isset($payment_detail['is_razorpay_enabled']) && $payment_detail['is_razorpay_enabled'] == 'on')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script type="text/javascript">
        // Razorpay Payment
        $(document).on("click", "#pay_with_razerpay", function() {
            $('#razorpay-payment-form').ajaxForm(function(res) {
                console.log('hry');
                if (res.flag == 1) {
                    var razorPay_callback = "{{ url('/invoice-pay-with-razorpay') }}";
                    var totalAmount = res.total_price * 100;
                    var coupon_id = res.coupon;
                    var API_publicKey = '';
                    if ("{{ isset($payment_detail['razorpay_public_key']) }}") {
                        API_publicKey = "{{ isset($payment_detail['razorpay_public_key']) }}";
                    }
                    var options = {
                        "key": API_publicKey, // your Razorpay Key Id
                        "amount": totalAmount,
                        "name": 'Invoice Payment',
                        "currency": res.currency,
                        "description": "",
                        "handler": function(response) {
                            window.location.href = "{{ url('/invoice/razorpay') }}/" + response
                                .razorpay_payment_id + "/{{ encrypt($invoice->id) }}";
                        },
                        "theme": {
                            "color": "#528FF0"
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                } else if (res.flag == 2) {

                } else {
                    toastrs('Error', data.message, 'msg');
                }
            }).submit();
        });
    </script>
    <script>
        @if (isset(
            $payment_detail['is_payfast_enabled']) == 'on' &&
                !empty($payment_detail['payfast_merchant_id']) &&
                !empty($payment_detail['payfast_merchant_key']))

            $("#payfast_submit").click(function() {
                var amount = $('#payfast_amount').val();
                get_payfast_status(amount, form = true);
            });

            function get_payfast_status(amount, form) {
                var invoice_id = '{{ $invoice->id }}';
                $.ajax({
                    url: '{{ route('invoice.with.payfast') }}',
                    method: 'POST',
                    data: {
                        'invoice_id': invoice_id,
                        'amount': amount,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.success == true) {
                            $('#get-payfast-inputs').append(data.inputs);
                            $('.payfast_form').submit();
                        } else {
                            show_toastr('Error', data.inputs, 'error')
                        }
                    }
                });
            }
        @endif
    </script>
    @endif
@endpush
