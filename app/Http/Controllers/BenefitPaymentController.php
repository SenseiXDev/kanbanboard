<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BenefitPaymentController extends Controller
{
    public function planPayWithbenefit(Request $request)
    {

        $admin_payment_setting = Utility::getAdminPaymentSetting();
        $secret_key = $admin_payment_setting['benefit_secret_key'];
        $objUser = \Auth::user();
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        if($plan)
        {
            $plan->discounted_price = false;
            $get_amount             = $plan->{$request->benefit_payment_frequency . '_price'};
            // $get_amount = $plan->price;
            try {
                if (!empty($request->coupon))
                {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();

                    if (!empty($coupons))
                    {
                        $usedCoupun = $coupons->used_coupon();
                        $discount_value = ($get_amount / 100) * $coupons->discount;
                        $get_amount = $get_amount - $discount_value;

                        if ($coupons->limit == $usedCoupun) {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                        if ($get_amount <= 0)
                        {
                            $authuser = \Auth::user();
                            $authuser->plan = $plan->id;
                            $authuser->save();
                            $assignPlan = $authuser->assignPlan($plan->id);
                            if ($assignPlan['is_success'] == true && !empty($plan)) {
                                if (!empty($authuser->payment_subscription_id) && $authuser->payment_subscription_id != '') {
                                    try {
                                        $authuser->cancel_subscription($authuser->id);
                                    } catch (\Exception $exception) {
                                        \Log::debug($exception->getMessage());
                                    }
                                }
                                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                                $userCoupon = new UserCoupon();
                                $userCoupon->user = $authuser->id;
                                $userCoupon->coupon = $coupons->id;
                                $userCoupon->order = $orderID;
                                $userCoupon->save();
                                Order::create(
                                    [
                                        'order_id' => $orderID,
                                        'name' => null,
                                        'email' => null,
                                        'card_number' => null,
                                        'card_exp_month' => null,
                                        'card_exp_year' => null,
                                        'plan_name' => $plan->name,
                                        'plan_id' => $plan->id,
                                        'price' => $get_amount == null ? 0 : $get_amount,
                                        'price_currency' => !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD',
                                        // 'price_currency' => 'BHD',
                                        'txn_id' => '',
                                        'payment_frequency' => $request->paytab_payment_frequency,
                                        'payment_type' => 'Benefit',
                                        'payment_status' => 'success',
                                        'receipt' => null,
                                        'user_id' => $authuser->id,
                                    ]
                                );
                                $assignPlan = $authuser->assignPlan($plan->id , $request->paytab_payment_frequency);
                                return redirect()->route('plans.index')->with('success', __('Plan Successfully Activated'));
                            }
                        }
                    } else {
                        return redirect()->route('plans.index')->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }

                $coupon = (empty($request->coupon)) ? "0" : $request->coupon;
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                $frequency = $request->benefit_payment_frequency;

                $userData =
                    [
                        "amount" => $get_amount,
                        "currency" => !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD',
                        // "currency" =>'BHD',
                        "customer_initiated" => true,
                        "threeDSecure" => true,
                        "save_card" => false,
                        "description" => " Plan - " . $plan->name,
                        "metadata" => ["udf1" => "Metadata 1"],
                        "reference" => ["transaction" => "txn_01", "order" => "ord_01"],
                        "receipt" => ["email" => true, "sms" => true],
                        "customer" => ["first_name" => $objUser->name, "middle_name" => "", "last_name" => "", "email" => $objUser->email, "phone" => ["country_code" => 965, "number" => 51234567]],
                        "source" => ["id" => "src_bh.benefit"],
                        "post" => ["url" => "https://webhook.site/fd8b0712-d70a-4280-8d6f-9f14407b3bbd"],
                        "redirect" => ["url" => route('benefit.call_back', ['plan_id' => $plan->id, 'amount' => $get_amount, 'coupon' => $coupon, 'frequency' => $frequency])],
                    ];

                $responseData = json_encode($userData);

                $client = new Client();
                try {
                    $response = $client->request('POST', 'https://api.tap.company/v2/charges', [
                        'body' => $responseData,
                        'headers' => [
                            'Authorization' => 'Bearer ' . $secret_key,
                            'accept' => 'application/json',
                            'content-type' => 'application/json',
                        ],
                    ]);

                } catch (\Throwable $th)
                {
                    return redirect()->route('plans.index')->with('error','Currency Not Supported.Contact To Your Site Admin');
                }
                $data = $response->getBody();
                $res = json_decode($data);
                return redirect($res->transaction->url);
            } catch (\Exception $e)
            {
                return redirect()->route('plans.index')->with('error', $e);
            }
        }else
        {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function call_back(Request $request)
    {
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        $secret_key = $admin_payment_setting['benefit_secret_key'];
        $user = Auth::user();
        $plan = Plan::find($request->plan_id);
        $couponCode = $request->coupon;
        $getAmount = $request->amount;
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        if ($couponCode != 0){
            $coupons = Coupon::where('code', strtoupper($couponCode))->where('is_active', '1')->first();
            $request['coupon_id'] = $coupons->id;
        }else{
            $coupons = null;
        }
        try
        {
            $post = $request->all();
            $client = new Client();
            $response = $client->request('GET', 'https://api.tap.company/v2/charges/' . $post['tap_id'], [
                'headers' => [
                    'Authorization' => 'Bearer ' . $secret_key,
                    'accept' => 'application/json',
                ],
            ]);
            $json = $response->getBody();
            $data = json_decode($json);
            $status_code = $data->gateway->response->code;

            if ($status_code == '00')
            {
                $order = new Order();
                $order->order_id = $orderID;
                $order->name = $user->name;
                $order->card_number = '';
                $order->card_exp_month = '';
                $order->card_exp_year = '';
                $order->plan_name = $plan->name;
                $order->plan_id = $plan->id;
                $order->price = $getAmount;
                $order->price_currency = !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD';
                // $order->price_currency = 'BHD';
                $order->payment_type = __('Benefit');
                $order->payment_status = 'success';
                $order->txn_id = '';
                $order->receipt = '';
                $order->user_id = $user->id;
                $order->save();
                $assignPlan = $user->assignPlan($plan->id);
                $coupons = Coupon::find($request->coupon_id);
                if (!empty($request->coupon_id)) {
                    if (!empty($coupons)) {
                        $userCoupon = new UserCoupon();
                        $userCoupon->user = $user->id;
                        $userCoupon->coupon = $coupons->id;
                        $userCoupon->order = $orderID;
                        $userCoupon->save();
                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit <= $usedCoupun) {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }
                    }
                }

                if ($assignPlan['is_success']) {
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
                } else {
                    return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                }

            } else
            {
                return redirect()->route('plans.index')->with('error', __('Your Transaction is fail please try again'));
            }
        } catch(\Exception $e){
            return redirect()->route('plans.index')->with('error', __($e->getMessage()));
        }
    }

    public function invoicepayWithbenefit(Request $request)
    {
        try {
            $invoice_id = $request->invoice_id;
            $invoice = Invoice::find($invoice_id);
            $authuser      = User::find($invoice->created_by);
            $companyPaymentSettings = Utility::getCompanyPaymentSetting($authuser->id);
            $secret_key = $companyPaymentSettings['benefit_secret_key'];

            if (\Auth::check()) {
                $user = Auth::user();
            } else {
                $user = User::where('id', $invoice->created_by)->first();
            }
            if ($user->type != 'owner') {
                $user = User::where('id', $user->created_by)->first();
            }
            $get_amount = $request->amount;

            if ($invoice && $get_amount != 0)
            {
                if ($get_amount > $invoice->getDue())
                {
                    return redirect()->back()->with('error', __('Invalid amount.'));
                }
                else{
                    $userData =
                        [
                            "amount" => $get_amount,
                            // "currency" => !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD',
                            "currency" => 'INR',
                            "customer_initiated" => true,
                            "threeDSecure" => true,
                            "save_card" => false,
                            "metadata" => ["udf1" => "Metadata 1"],
                            "reference" => ["transaction" => "txn_01", "order" => "ord_01"],
                            "receipt" => ["email" => true, "sms" => true],
                            "customer" => ["first_name" => $user->name, "middle_name" => "", "last_name" => "", "email" => $user->email, "phone" => ["country_code" => 965, "number" => 51234567]],
                            "source" => ["id" => "src_bh.benefit"],
                            "post" => ["url" => "https://webhook.site/fd8b0712-d70a-4280-8d6f-9f14407b3bbd"],
                            "redirect" => ["url" => route('invoice.benefit.callback', [ $invoice->id, 'amount' => $get_amount])],
                        ];
                    $responseData = json_encode($userData);

                    $client = new Client();

                    try {
                        $response = $client->request('POST', 'https://api.tap.company/v2/charges', [
                            'body' => $responseData,
                            'headers' => [
                                'Authorization' => 'Bearer ' . $secret_key,
                                'accept' => 'application/json',
                                'content-type' => 'application/json',
                            ],
                        ]);
                    }catch(\Throwable $th)
                    {
                        return redirect()->back()->with('error','Currency Not Supported.Contact To Your Site Admin');
                    }
                    $data = $response->getBody();
                    $res = json_decode($data);
                    return redirect($res->transaction->url);
                }
            }

        } catch (\Throwable $e) {
            return redirect()->back()->with('error', __($e->getMessage()));
        }
    }

    public function getInvoicePaymentStatus(Request $request, $invoice_id)
    {
        if(!empty($invoice_id))
        {
            $invoice      = Invoice::find($invoice_id);

            if(Auth::check())
            {
                $user = Auth::user();
            }
            else
            {
                $user=User::where('id',$invoice->created_by)->first();
            }
            if($user->type != 'owner')
            {
                $user=User::where('id',$user->created_by)->first();
            }


            $payment_setting = Utility::getCompanyPaymentSetting($invoice->created_by);
            $secret_key = $payment_setting['benefit_secret_key'];
            $orderID  = strtoupper(str_replace('.', '', uniqid('', true)));

            if($invoice)
            {
                try
                {
                    $post = $request->all();
                    $client = new Client();
                    $response = $client->request('GET', 'https://api.tap.company/v2/charges/' . $post['tap_id'], [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $secret_key,
                            'accept' => 'application/json',
                        ],
                    ]);
                    $json = $response->getBody();
                    $data = json_decode($json);
                    $status_code = $data->gateway->response->code;

                    if ($status_code == '00')
                    {
                        $invoice_payment                 = new InvoicePayment();
                        $invoice_payment->transaction_id = $orderID;
                        $invoice_payment->invoice_id     = $invoice->id;
                        $invoice_payment->amount         = $request->has('amount') ? $request->amount : 0;
                        $invoice_payment->date           = date('Y-m-d');
                        $invoice_payment->payment_id     = 0;
                        $invoice_payment->payment_type   = 'benefit';
                        $invoice_payment->notes          = '';
                        $invoice_payment->client_id      = $user->id;
                        $invoice_payment->save();

                        if(($invoice->getDue() - $invoice_payment->amount) == 0)
                        {
                            Invoice::change_status($invoice->id, 3);
                        }
                        else
                        {
                            Invoice::change_status($invoice->id, 2);
                        }

                        $settings  = Utility::settingsById($invoice->created_by);
                        $uArr = [
                            'invoice_id' => Utility::invoiceNumberFormat($invoice->invoice_id),
                            'owner_name' => $user->name,
                        ];
                        if(isset($settings['invoice_status_notificaation']) && $settings['invoice_status_notificaation'] == 1){
                            Utility::send_slack_msg('invoice_status_updated',$uArr,$user->id);
                        }

                        if(isset($settings['telegram_invoice_status_notificaation']) && $settings['telegram_invoice_status_notificaation'] == 1){
                            Utility::send_telegram_msg('invoice_status_updated',$uArr,$user->id);
                        }
                        //webhook
                        $module ='Invoice Status Updated';

                        $webhook=  Utility::webhookSetting($module,$user->id);

                        if($webhook)
                        {
                            $parameter = json_encode($invoice_payment);
                            // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                            $status = Utility::WebhookCall($webhook['url'],$parameter,$webhook['method']);

                            if($status != true)
                            {
                                $msg= "Webhook call failed.";
                            }
                        }

                        $request->session()->forget('invoice_data');
                        if(Auth::user())
                        {
                            return redirect()->route('invoices.show', $invoice_id)->with('success', __('Invoice added Successfully'). ((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')));
                        }
                        else
                        {

                            $id= \Crypt::encrypt($invoice_id);
                            return redirect()->route('pay.invoice', $id)->with('success', __('Invoice paid Successfully!'). ((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')));
                        }
                    }else{
                            if(Auth::user())
                            {
                                return redirect()->route('invoices.show', $invoice_id)->with('error', __('Transaction fail'));
                            }
                            else{

                                $id= \Crypt::encrypt($invoice_id);
                                return redirect()->route('pay.invoice', $id)->with('error', __('Transaction fail'));
                            }
                        }
                }
                catch(\Exception $e)
                {
                    return redirect()->route('invoices.show')->with('error', __('Invoice not found!'));
                }
            }
            else
            {
                if(Auth::user())
                {
                    return redirect()->route('invoices.show', $invoice_id)->with('error', __('Invoice not found.'));
                }
                else{
                    $id= \Crypt::encrypt($invoice_id);
                    return redirect()->route('pay.invoice', $id)->with('error', __('Invoice not found.'));
                }
            }
        }
        else
        {
            return redirect()->route('invoices.index')->with('error', __('Invoice not found.'));
        }
    }
}
