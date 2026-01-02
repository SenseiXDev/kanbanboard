@extends('layouts.admin')

@section('title')
    {{__('Orders')}}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" width="100%">
                            <thead class="thead-light">
                            <tr>
                                <th>{{__('Order Id')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Plan Name')}}</th>
                                <th>{{__('Price')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Coupon')}}</th>
                                <th class="text-right">{{__('Invoice')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($orders->count() > 0)
                                @foreach($orders as $order)
                                    <tr>
                                       
                                        <td>{{$order->order_id}}</td>
                                        <td>{{$order->user_name}}</td>
                                        <td>{{$order->plan_name}}</td>
                                        <td>{{(env('CURRENCY') ? env('CURRENCY') : '$')}} {{number_format($order->price)}}</td>
                                        <td>
                                            @if($order->payment_status == 'succeeded' || $order->payment_status == 'Approved')
                                                <span class="badge badge-success">{{ucfirst($order->payment_status)}}</span>
                                            @else
                                                <span class="badge badge-danger">{{ucfirst($order->payment_status)}}</span>
                                            @endif
                                        </td>

                                        <td>{{$order->payment_type}}</td>
                                        <td>{{$order->created_at->format('d M Y')}}</td>
                                        <td>{{!empty($order->use_coupon)?$order->use_coupon->coupon_detail->code:'-'}}</td>
                                        <td class="text-right">
                                            @if($order->receipt =='free coupon')
                                                <p>{{__('Used 100 % discount coupon code.')}}</p>
                                            @elseif ($order->payment_type == 'Bank Transfer')
                                            <a href="{{ \App\Models\Utility::get_file($order->receipt) }}"  title="Invoice" target="_blank"
                                                class="text-center">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>
                                            @else
                                                {{'-'}}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="actions">
                                                @if(($order->payment_type == 'Bank Transfer' && $order->payment_status == 'pending'))
                                                <a href="#" class="action-item px-2"
                                                    data-url="{{ route('bank_transfer.show', $order->id) }}"
                                                    data-ajax-popup="true" data-size="lg"
                                                    data-title="{{ __('Payment Status') }}" data-toggle="tooltip"
                                                    data-original-title="{{ __('Payment Status') }}">
                                                    <i class="fas fa-caret-square-right"></i>
                                                </a>
                                                @endif
                                                <a href="#" class="action-item text-danger px-2"
                                                    data-toggle="tooltip"
                                                    data-original-title="{{ __('Delete') }}"
                                                    data-confirm="{{ __('Are You Sure?') }}|{{ __('This action can not be undone. Do you want to continue?') }}"
                                                    data-confirm-yes="document.getElementById('delete-order-{{ $order->id }}').submit();">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                            {!! Form::open(['method' => 'GET', 'route' => ['order.destory', $order->id], 'id' => 'delete-order-' . $order->id]) !!}
                                            {!! Form::close() !!}
                                        </td>
                                        {{-- <td class="Id">
                                            @if($order->receipt != 'free coupon' && $order->payment_type == 'Stripe')
                                            <a href="{{$order->receipt}}" class="btn  btn-outline-primary" target="_blank"><i class="fas fa-file-invoice"></i> {{__('Invoice')}}</a>
                                            @endif
                                        </td> --}}

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <th scope="col" colspan="8"><h6 class="text-center">{{__('No Orders Found.')}}</h6></th>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
