@php
    $paymentSetting = Utility::getPaymentSetting();
@endphp
<div class="row">
    <div class="col-12">
        <table class="table">
            <tr>
                <th>{{__('Order Id')}}</th>
                <td>
                    {{$order->order_id}}
                </td>
            </tr>
            <tr>
                <th>{{__('Plan Name')}}</th>
                <td>
                    {{$order->plan_name}}
                </td>
            </tr>
            <tr>
                <th>{{__('Plan Price')}}</th>
                <td>
                    {{$order->price}}
                </td>
            </tr>
            <tr>
                <th>{{__('Payment Type')}}</th>
                <td>
                    {{$order->payment_type}}
                </td>
            </tr>
            <tr>
                <th>{{__('Payment Stutus')}}</th>
                <td>
                    {{$order->payment_status}}
                </td>
            </tr>
            @if(isset($paymentSetting['bank_details']))
            <tr>
                <th>{{__('Bank Details')}}</th>
                <td class="col-md-8"><span class="text-md">{!! $paymentSetting['bank_details'] !!}</span></td>
            </tr>
            @endif
            <tr>
                <th>{{__('Payment Receipt')}}</th>
                <td>
                    <div class="action-btn btn px-2 bg-primary p-0 w-auto ">
                        <a href="{{ \App\Models\Utility::get_file($order->receipt) }}"  title="Invoice" download=""
                            class="text-center">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </td>
            </tr>

        </table>
        @if (\Auth::user()->type == 'admin')
            {{ Form::model($order, ['route' => ['status.edit', $order->id], 'method' => 'POST']) }}
                <div class="text-right">
                    <input type="submit" value="{{ __('Approved') }}" class="btn btn-sm btn-success rounded-pill" name="status">
                    <input type="submit" value="{{ __('Reject') }}" class="btn btn-sm btn-danger rounded-pill" name="status">
                </div>
            {{ Form::close() }}
        @endif
    </div>
</div>
