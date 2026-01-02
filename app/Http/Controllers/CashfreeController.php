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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashfreeController extends Controller
{
    public $currancy;

    public function paymentConfig($invoice_id = null)
    {
        if(isset($_REQUEST['from']) && $_REQUEST['from'] == 'invoice')
        {
            $payment_setting = Utility::getCompanyPaymentSetting($_REQUEST['invoice_creator']);
            $invoice         = Invoice::find($invoice_id);
            $this->currancy  = (isset($invoice->project)) ? $invoice->project->currency_code : 'USD';
            config(
                [
                    'services.cashfree.key' => isset($payment_setting['cashfree_api_key']) ? $payment_setting['cashfree_api_key'] : '',
                    'services.cashfree.secret' => isset($payment_setting['cashfree_secret_key']) ? $payment_setting['cashfree_secret_key'] : '',

                ]
            );
        }
        else
        {
            $payment_setting = Utility::getAdminPaymentSetting();
            config(
                [
                    'services.cashfree.key' => isset($payment_setting['cashfree_api_key']) ? $payment_setting['cashfree_api_key'] : '',
                    'services.cashfree.secret' => isset($payment_setting['cashfree_secret_key']) ? $payment_setting['cashfree_secret_key'] : '',
                ]
            );
        }
    }

    public function planpaywithcashfree(Request $request)
    {
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        $user = \Auth::user();
        $this->paymentConfig();

        $url = config('services.cashfree.url');

        if ($plan)
        {
            $plan->discounted_price = false;
            $get_amount                  = $plan->{$request->cashfree_payment_frequency . '_price'};

            try
            {
                if (!empty($request->coupon))
                {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons))
                    {
                        $usedCoupun = $coupons->used_coupon();
                        $discount_value = ($get_amount / 100) * $coupons->discount;
                        $get_amount = $get_amount - $discount_value;

                        if ($coupons->limit == $usedCoupun)
                        {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                        if ($get_amount <= 0)
                        {
                            $authuser = \Auth::user();
                            $authuser->plan = $plan->id;
                            $authuser->save();
                            $assignPlan = $authuser->assignPlan($plan->id);
                            if ($assignPlan['is_success'] == true && !empty($plan))
                            {
                                if (!empty($authuser->payment_subscription_id) && $authuser->payment_subscription_id != '')
                                {
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
                                        'txn_id' => '',
                                        'payment_type' => 'Cashfree',
                                        'payment_status' => 'success',
                                        'receipt' => null,
                                        'user_id' => $authuser->id,
                                    ]
                                );
                                $assignPlan = $authuser->assignPlan($plan->id);
                                return redirect()->route('plans.index')->with('success', __('Plan Successfully Activated'));
                            }
                        }
                    } else
                    {
                        return redirect()->route('plans.index')->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }
                    $coupon = (empty($request->coupon)) ? "0" : $request->coupon;
                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                    $headers = array(
                        "Content-Type: application/json",
                        "x-api-version: 2022-01-01",
                        "x-client-id: " . config('services.cashfree.key'),
                        "x-client-secret: " . config('services.cashfree.secret')
                    );

                    $data = json_encode([
                        'order_id' => $orderID,
                        'order_amount' => $get_amount,
                        "order_currency" => !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD',
                        "order_name" => $plan->name,
                        "customer_details" => [
                            "customer_id" => 'customer_' . $user->id,
                            "customer_name" => $user->name,
                            "customer_email" => $user->email,
                            "customer_phone" => '1234567890',
                        ],
                        "order_meta" => [
                            "return_url" => route('cashfreePayment.success') . '?order_id={order_id}&order_token={order_token}&plan_id=' . $plan->id . '&amount=' . $get_amount . '&coupon=' . $coupon . ''

                        ]
                    ]);
                    try
                    {
                        $curl = curl_init($url);
                        curl_setopt($curl, CURLOPT_URL, $url);
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

                        $resp = curl_exec($curl);
                        curl_close($curl);
                        return redirect()->to(json_decode($resp)->payment_link);
                    } catch (\Throwable $th)
                    {
                        return redirect()->route('plans.index')->with('error', 'Currency Not Supported.Contact To Your Site Admin');
                    }
            } catch (\Exception $e){

                return redirect()->route('plans.index')->with('error', $e);
            }

        } else {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function cashfreePaymentSuccess(Request $request)
    {
        $this->paymentConfig();
        $user = \Auth::user();
        $plan = Plan::find($request->plan_id);
        $couponCode = $request->coupon;
        $getAmount = $request->amount;

        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        if ($couponCode != 0) {
            $coupons = Coupon::where('code', strtoupper($couponCode))->where('is_active', '1')->first();
            $request['coupon_id'] = $coupons->id;
        } else {
            $coupons = null;
        }

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', config('services.cashfree.url') . '/' . $request->get('order_id') . '/settlements', [
                'headers' => [
                    'accept' => 'application/json',
                    'x-api-version' => '2022-09-01',
                    "x-client-id" => config('services.cashfree.key'),
                    "x-client-secret" => config('services.cashfree.secret')
                ],
            ]);


            $respons = json_decode($response->getBody());
            if ($respons->order_id && $respons->cf_payment_id != NULL) {

                $response = $client->request('GET', config('services.cashfree.url') . '/' . $respons->order_id . '/payments/' . $respons->cf_payment_id . '', [
                    'headers' => [
                        'accept' => 'application/json',
                        'x-api-version' => '2022-09-01',
                        'x-client-id' => config('services.cashfree.key'),
                        'x-client-secret' => config('services.cashfree.secret'),
                    ],
                ]);
                $info = json_decode($response->getBody());

                if ($info->payment_status == "SUCCESS")
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
                    // $order->price_currency ='INR';
                    $order->payment_type = __('Cashfree');
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

                } else {
                    return redirect()->route('plans.index')->with('error', __('Your Transaction is fail please try again'));
                }
            } else {
                return redirect()->route('plans.index')->with('error', 'Payment Failed.');
            }
            return redirect()->route('plans.index')->with('success', 'Plan activated Successfully.');
        } catch (\Exception $e) {
            return redirect()->route('plans.index')->with('error', __($e->getMessage()));
        }
    }

    public function invoicepayWithCashfree(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::find($invoice_id);
        $url = config('services.cashfree.url');

        $this->paymentconfig($invoice_id);
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = User::where('id', $invoice->created_by)->first();
        }
        if ($user->type != 'owner') {
            $user = User::where('id', $user->created_by)->first();
        }

        $get_amount = $request->amount;
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        if ($invoice && $get_amount != 0)
        {
            if ($get_amount > $invoice->getDue())
            {
                return redirect()->back()->with('error', __('Invalid amount.'));
            }
            else{
                $headers = array(
                    "Content-Type: application/json",
                    "x-api-version: 2022-01-01",
                    "x-client-id: " . config('services.cashfree.key'),
                    "x-client-secret: " . config('services.cashfree.secret')
                );

                $data = json_encode([
                    'order_id' => $orderID,
                    'order_amount' => $get_amount,
                    "order_currency" =>!empty($this->currancy) ? $this->currancy : 'USD',
                    "order_name" => $invoice->name,
                    "customer_details" => [
                        "customer_id" => 'customer_' . $user->id,
                        "customer_name" => $user->name,
                        "customer_email" => $user->email,
                        "customer_phone" => '1234567890',
                    ],
                    "order_meta" => [
                        "return_url" => route('invoice.cashfree.success') . '?order_id={order_id}&order_token={order_token}&invoice_id=' . $invoice->id . '&amount=' . $get_amount .   ''

                    ]
                ]);
                try
                {
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

                    $resp = curl_exec($curl);
                    curl_close($curl);
                    return redirect()->to(json_decode($resp)->payment_link);
                } catch (\Throwable $th)
                {

                    return redirect()->back()->with('error', 'Currency Not Supported.Contact To Your Site Admin');
                }
            }
        }
    }

    public function getInvoicePaymentStatus(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $this->paymentconfig($invoice_id);
        if (!empty($invoice_id)) {
            $invoice    = Invoice::find($invoice_id);
            $orderID  = strtoupper(str_replace('.', '', uniqid('', true)));
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
            if ($invoice)
            {
                try
                {
                    $client = new \GuzzleHttp\Client();

                    $response = $client->request('GET', config('services.cashfree.url') . '/' . $request->get('order_id') . '/settlements', [
                        'headers' => [
                            'accept' => 'application/json',
                            'x-api-version' => '2022-09-01',
                            "x-client-id" => config('services.cashfree.key'),
                            "x-client-secret" => config('services.cashfree.secret')
                        ],
                    ]);

                    $respons = json_decode($response->getBody());
                    if ($respons->order_id && $respons->cf_payment_id != NULL) {

                        $response = $client->request('GET', config('services.cashfree.url') . '/' . $respons->order_id . '/payments/' . $respons->cf_payment_id . '', [
                            'headers' => [
                                'accept' => 'application/json',
                                'x-api-version' => '2022-09-01',
                                'x-client-id' => config('services.cashfree.key'),
                                'x-client-secret' => config('services.cashfree.secret'),
                            ],
                        ]);

                    $info = json_decode($response->getBody());

                    if ($info->payment_status == "SUCCESS")
                    {
                        $invoice_payment                 = new InvoicePayment();
                        $invoice_payment->invoice_id     = $invoice_id;
                        $invoice_payment->transaction_id = $orderID;
                        $invoice_payment->date           = Date('Y-m-d');
                        $invoice_payment->amount         = $request->has('amount') ? $request->amount : 0;
                        $invoice_payment->client_id      = $user->id;;
                        $invoice_payment->payment_type   = 'Cashfree';
                        $invoice_payment->payment_id     = 0;
                        $invoice_payment->save();

                        if(($invoice->getDue() - $invoice_payment->amount) == 0)
                        {
                            Invoice::change_status($invoice->id, 3);
                        } else
                        {
                            Invoice::change_status($invoice->id, 2);
                        }
                        $invoice->save();

                        $settings  = Utility::settingsById($invoice->created_by);

                        $uArr = [
                            'invoice_id' => Utility::invoiceNumberFormat($invoice->invoice_id),
                            'owner_name' => $user->name,
                        ];
                        if (isset($settings['invoice_status_notificaation']) && $settings['invoice_status_notificaation'] == 1) {
                            Utility::send_slack_msg('invoice_status_updated', $uArr, $user->id);
                        }

                        if (isset($settings['telegram_invoice_status_notificaation']) && $settings['telegram_invoice_status_notificaation'] == 1) {
                            Utility::send_telegram_msg('invoice_status_updated', $uArr, $user->id);
                        }

                        //webhook
                        $module = 'Invoice Status Updated';

                        $webhook =  Utility::webhookSetting($module, $user->id);

                        if ($webhook)
                        {
                            $parameter = json_encode($invoice_payment);
                            // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                            $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                            if ($status != true)
                            {
                                $msg = "Webhook call failed.";
                            }
                        }
                        if (Auth::user())
                        {
                            return redirect()->route('invoices.show', $invoice_id)->with('success', __('Invoice paid Successfully!') . ((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')));
                        } else {
                            $id = \Crypt::encrypt($invoice_id);
                            return redirect()->route('pay.invoice', $id)->with('success', __('Invoice paid Successfully!') . ((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')));
                        }
                    }
                    }else
                    {
                        if (Auth::user())
                        {
                            return redirect()->route('invoices.show', $invoice_id)->with('error', __('Transaction fail!'));
                        } else {
                            $id = \Crypt::encrypt($invoice_id);
                            return redirect()->route('pay.invoice', $id)->with('error', __('Transaction fail!'));
                        }
                    }
                } catch (\Exception $e)
                {
                    return redirect()->route('invoices.show', $invoice_id)->with('error', __($e->getMessage()));
                }
            }

            else
            {
                if (Auth::user())
                {
                    return redirect()->route('invoices.show', $invoice_id)->with('error', __('Invoice not found'));
                }
                else{
                    $id = \Crypt::encrypt($invoice_id);

                    return redirect()->route('pay.invoice', $id)->with('error', __('Transaction fail!'));
                }
            }
        } else
        {
            return redirect()->route('invoices.index')->with('error', __('Invoice not found.'));
        }
    }

    }
