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

class PaytrController extends Controller
{
    public $merchant_id;
    public $merchant_key;
    public $merchant_salt;
    public $currancy;

    public function paymentConfig($invoice_id = null)
    {
        if(Auth::check()){
            $user = Auth::user();
        }
        if(isset($_REQUEST['from']) && $_REQUEST['from'] == 'invoice')
        {
            $payment_setting = Utility::getCompanyPaymentSetting($_REQUEST['invoice_creator']);
            $invoice         = Invoice::find($invoice_id);
            $this->currancy  = (isset($invoice->project)) ? $invoice->project->currency_code : 'USD';
        }
        else
        {
            $payment_setting = Utility::getAdminPaymentSetting();
            $this->currancy  = env('CURRENCY_CODE');
        }

        $this->merchant_id = isset($payment_setting['paytr_merchant_id ']) ? $payment_setting['paytr_merchant_id  '] : '';
        $this->merchant_key = isset($payment_setting['paytr_merchant_key']) ? $payment_setting['paytr_merchant_key'] : '';
        $this->merchant_salt = isset($payment_setting['paytr_merchant_salt']) ? $payment_setting['paytr_merchant_salt'] : 'off';

        return $this;
    }


    public function planPayWithpaytr(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $paytr_merchant_id = $payment_setting['paytr_merchant_id'];
        $paytr_merchant_key = $payment_setting['paytr_merchant_key'];
        $paytr_merchant_salt = $payment_setting['paytr_merchant_salt'];
        $currency =!empty(env('CURRENCY')) ? env('CURRENCY') : 'USD';
        // $currency = 'TL';
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $authuser = Auth::user();
        $plan = Plan::find($planID);
        if ($plan) {

            $plan->discounted_price = false;
            $get_amount             = $plan->{$request->paytr_payment_frequency . '_price'};

            if (!empty($request->coupon))
            {
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun = $coupons->used_coupon();
                    $discount_value = ($plan->price / 100) * $coupons->discount;

                    $get_amount = $plan->price - $discount_value;

                    if ($coupons->limit == $usedCoupun) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    if ($get_amount <= 0) {
                        $authuser = Auth::user();
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
                                    // 'price_currency' =>'TL',
                                    'txn_id' => '',
                                    'payment_type' => 'paytr',
                                    'payment_status' => 'success',
                                    'receipt' => null,
                                    'user_id' => $authuser->id,
                                ]
                            );
                            $assignPlan = $authuser->assignPlan($plan->id);
                            return redirect()->route('plans.index')->with('success', __('Plan Successfully Activated'));
                        }
                    }
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }
            try {
                $coupon = (empty($request->coupon)) ? "0" : $request->coupon;

                $merchant_id    = $paytr_merchant_id;
                $merchant_key   = $paytr_merchant_key;
                $merchant_salt  = $paytr_merchant_salt;

                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                // $store = Store::where('id', $store_id)->get()->first();
                $email = $authuser->email;
                $payment_amount = $get_amount;
                $merchant_oid = $orderID;
                $user_name = $authuser->name;
                $user_address ='no address';
                $user_phone = '0000000000';


                $user_basket = base64_encode(json_encode(array(
                    array("Plan", $payment_amount, 1),
                )));

                if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                    $ip = $_SERVER["HTTP_CLIENT_IP"];
                } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                } else {
                    $ip = $_SERVER["REMOTE_ADDR"];
                }

                $user_ip = $ip;
                $timeout_limit = "30";
                $debug_on = 1;
                $test_mode = 0;
                $no_installment = 0;
                $max_installment = 0;
                $currency = !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD';
                // $currency = 'TL';

                $payment_amount = $payment_amount * 100;
                $hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $user_basket . $no_installment . $max_installment . $currency . $test_mode;
                $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));

                $request['orderID'] = $orderID;
                $request['plan_id'] = $plan->id;
                $request['price'] = $get_amount;
                $request['payment_status'] = 'failed';
                $payment_failed = $request->all();
                $request['payment_status'] = 'success';
                $payment_success = $request->all();

                $post_vals = array(
                    'merchant_id' => $merchant_id,
                    'user_ip' => $user_ip,
                    'merchant_oid' => $merchant_oid,
                    'email' => $email,
                    'payment_amount' => $payment_amount,
                    'paytr_token' => $paytr_token,
                    'user_basket' => $user_basket,
                    'debug_on' => $debug_on,
                    'no_installment' => $no_installment,
                    'max_installment' => $max_installment,
                    'user_name' => $user_name,
                    'user_address' => $user_address,
                    'user_phone' => $user_phone,
                    'merchant_ok_url' => route('pay.paytr.success', $payment_success),
                    'merchant_fail_url' => route('pay.paytr.success', $payment_failed),
                    'timeout_limit' => $timeout_limit,
                    'currency' => $currency,
                    'test_mode' => $test_mode
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 20);


                $result = @curl_exec($ch);

                if (curl_errno($ch)) {
                    die("PAYTR IFRAME connection error. err:" . curl_error($ch));
                }

                curl_close($ch);

                $result = json_decode($result, 1);

                if ($result['status'] == 'success') {
                    $token = $result['token'];
                } else {
                    return redirect()->route('plans.index')->with('error', $result['reason']);
                }
                return view('paytr_payment.index', compact('token'));
            } catch (\Throwable $th) {
                return redirect()->route('plans.index')->with('error', $th->getMessage());
            }
        }
    }

    public function paytrsuccess(Request $request)
    {
        if ($request->payment_status == "success") {
            try {
                $user = Auth::user();
                $planID = $request->plan_id;
                $plan = Plan::find($planID);
                $couponCode = $request->coupon;
                $getAmount = $request->price;

                if ($couponCode != 0) {
                    $coupons = Coupon::where('code', strtoupper($couponCode))->where('is_active', '1')->first();
                    $request['coupon_id'] = $coupons->id;
                } else {
                    $coupons = null;
                }

                $order = new Order();
                $order->order_id = $request->orderID;
                $order->name = $user->name;
                $order->card_number = '';
                $order->card_exp_month = '';
                $order->card_exp_year = '';
                $order->plan_name = $plan->name;
                $order->plan_id = $plan->id;
                $order->price = $getAmount;
                $order->price_currency = env('CURRENCY');
                // $order->price_currency = 'TL';
                $order->txn_id = $request->orderID;
                $order->payment_type = __('PayTR');
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
                        $userCoupon->order = $request->orderID;
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
            } catch (\Exception $e) {
                return redirect()->route('plans.index')->with('error', __($e));
            }
        } else {
            return redirect()->route('plans.index')->with('error', __('Your Transaction is fail please try again.'));
        }
    }

    public function invoicepaywithpaytr(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::find($invoice_id);
        $authuser      = User::find($invoice->created_by);
        $payment_setting = Utility::getCompanyPaymentSetting($authuser->id);
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
                $coupon = (empty($request->coupon)) ? "0" : $request->coupon;

                $merchant_id    = $payment_setting['paytr_merchant_id'];
                $merchant_key   = $payment_setting['paytr_merchant_key'];
                $merchant_salt  = $payment_setting['paytr_merchant_salt'];

                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                // $store = Store::where('id', $store_id)->get()->first();
                $email = $authuser->email;
                $payment_amount = $get_amount;
                $merchant_oid = $orderID;
                $user_name = $authuser->name;
                $user_address ='no address';
                $user_phone = '0000000000';


                $user_basket = base64_encode(json_encode(array(
                    array("Plan", $payment_amount, 1),
                )));

                if (isset($_SERVER["HTTP_CLIENT_IP"]))
                {
                    $ip = $_SERVER["HTTP_CLIENT_IP"];
                } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                } else {
                    $ip = $_SERVER["REMOTE_ADDR"];
                }

                $user_ip = $ip;
                $timeout_limit = "30";
                $debug_on = 1;
                $test_mode = 0;
                $no_installment = 0;
                $max_installment = 0;
                $currency = !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD';
                // $currency = 'TL';

                $payment_amount = $payment_amount * 100;
                $hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $user_basket . $no_installment . $max_installment . $currency . $test_mode;
                $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));
                $request['orderID'] = $orderID;
                $request['price'] = $get_amount;
                $request['payment_status'] = 'failed';
                $payment_failed = $request->all();
                $request['payment_status'] = 'success';
                $payment_success = $request->all();

                $post_vals = array(
                    'merchant_id' => $merchant_id,
                    'user_ip' => $user_ip,
                    'merchant_oid' => $merchant_oid,
                    'email' => $email,
                    'payment_amount' => $payment_amount,
                    'paytr_token' => $paytr_token,
                    'user_basket' => $user_basket,
                    'debug_on' => $debug_on,
                    'no_installment' => $no_installment,
                    'max_installment' => $max_installment,
                    'user_name' => $user_name,
                    'user_address' => $user_address,
                    'user_phone' => $user_phone,
                    'merchant_ok_url' => route('invoice.pay.paytr.success', $payment_success),
                    'merchant_fail_url' => route('invoice.pay.paytr.success', $payment_failed),
                    'timeout_limit' => $timeout_limit,
                    'currency' => $currency,
                    'test_mode' => $test_mode
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 20);


                $result = @curl_exec($ch);

                if (curl_errno($ch)) {
                    die("PAYTR IFRAME connection error. err:" . curl_error($ch));
                }

                curl_close($ch);

                $result = json_decode($result, 1);

                if ($result['status'] == 'success') {
                    $token = $result['token'];
                } else {
                    return redirect()->route('invoice.view')->with('error', $result['reason']);
                }
                return view('paytr_payment.index', compact('token'));
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
                    if ($request->payment_status == "success")
                    {
                        $invoice_payment                 = new InvoicePayment();
                        $invoice_payment->invoice_id     = $invoice_id;
                        $invoice_payment->transaction_id = $orderID;
                        $invoice_payment->date           = Date('Y-m-d');
                        $invoice_payment->amount         = $request->has('amount') ? $request->amount : 0;
                        $invoice_payment->client_id      = $user->id;;
                        $invoice_payment->payment_type   = 'paytr';
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

