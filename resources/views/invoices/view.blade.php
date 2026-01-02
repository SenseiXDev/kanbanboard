@php
    $payment_detail = Utility::getPaymentSetting(Auth::user()->id);
@endphp
<div class="row">
    <div class="col-12">
        <table class="table">
            <tr>
                <th>{{__('Order Id')}}</th>
                <td>
                    {{$payment->order_id}}
                </td>
            </tr>
            <tr>
                <th>{{__('Price')}}</th>
                <td>
                    {{$payment->amount}}
                </td>
            </tr>
            <tr>
                <th>{{__('Payment Type')}}</th>
                <td>
                    {{'bank Transfer'}}
                </td>
            </tr>
            <tr>
                <th>{{__('Payment Stutus')}}</th>
                <td>
                    {{$payment->status}}
                </td>
            </tr>
            @if(isset($payment_detail['bank_details']))
            <tr>
                <th>{{__('Bank Details')}}</th>
                <td class="col-md-8"><span class="text-md">{!! $payment_detail['bank_details'] !!}</span></td>
            </tr>
            @endif
            <tr>
                <th>{{__('Payment Receipt')}}</th>
                <td>
                    <div class="action-btn btn px-2 bg-primary p-0 w-auto ">
                        @if($plan->storage_limit <= $total_storage != -1)
                        <a href="{{ \App\Models\Utility::get_file($payment->receipt) }}"  title="Invoice" download=""
                            class="text-center">
                            <i class="fas fa-download"></i>
                        </a>
                        @else
                            {{'-'}}
                        @endif
                    </div>
                </td>

            </tr>

        </table>

            {{ Form::model($payment, ['route' => ['invoice.status', $payment->id], 'method' => 'POST']) }}
                <div class="text-right">
                    <input type="submit" value="{{ __('Approved') }}" class="btn btn-sm btn-success rounded-pill" name="status">
                    <input type="submit" value="{{ __('Reject') }}" class="btn btn-sm btn-danger rounded-pill" name="status">
                </div>
            {{ Form::close() }}

    </div>
</div>
