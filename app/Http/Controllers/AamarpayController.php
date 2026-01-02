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

class AamarpayController extends Controller
{
    public function planPayWithaamarpay(Request $request)
    {
        $url = 'https://sandbox.aamarpay.com/request.php';
        $payment_setting = Utility::getAdminPaymentSetting();
        $aamarpay_store_id = $payment_setting['aamarpay_store_id'];
        $aamarpay_signature_key = $payment_setting['aamarpay_signature_key'];
        $aamarpay_description = $payment_setting['aamarpay_description'];
        $currency = !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD';
        // $currency = 'USD';
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $authuser = Auth::user();
        $plan = Plan::find($planID);
        if ($plan)
        {
            $plan->discounted_price = false;
            $get_amount             = $plan->{$request->aamarpay_payment_frequency . '_price'};
            try {
                if (!empty($request->coupon)) {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons)) {
                        $usedCoupun = $coupons->used_coupon();
                        $discount_value = ($get_amount / 100) * $coupons->discount;
                        $get_amount = $get_amount - $discount_value;

                        if ($coupons->limit == $usedCoupun) {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                        if ($get_amount <= 0)
                        {
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
                                        // 'price_currency' => 'USD',
                                        'txn_id' => '',
                                        'payment_type' => 'Aamarpay',
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
                        return redirect()->route('plans.index')->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }
                $coupon = (empty($request->coupon)) ? "0" : $request->coupon;
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                $fields = array(
                    'store_id' => $aamarpay_store_id,
                    //store id will be aamarpay,  contact integration@aamarpay.com for test/live id
                    'amount' => $get_amount,
                    //transaction amount
                    'payment_type' => '',
                    //no need to change
                    'currency' => $currency,
                    //currenct will be USD/BDT
                    'tran_id' => $orderID,
                    //transaction id must be unique from your end
                    'cus_name' => $authuser->name,
                    //customer name
                    'cus_email' => $authuser->email,
                    //customer email address
                    'cus_add1' => '',
                    //customer address
                    'cus_add2' => '',
                    //customer address
                    'cus_city' => '',
                    //customer city
                    'cus_state' => '',
                    //state
                    'cus_postcode' => '',
                    //postcode or zipcode
                    'cus_country' => '',
                    //country
                    'cus_phone' => '1234567890',
                    //customer phone number
                    'success_url' => route('pay.aamarpay.success', \Crypt::encrypt(['response'=>'success','coupon' => $coupon, 'plan_id' => $plan->id, 'price' => $get_amount, 'order_id' => $orderID])),
                    //your success route
                    'fail_url' => route('pay.aamarpay.success', \Crypt::encrypt(['response'=>'failure','coupon' => $coupon, 'plan_id' => $plan->id, 'price' => $get_amount, 'order_id' => $orderID])),
                    //your fail route
                    'cancel_url' => route('pay.aamarpay.success', \Crypt::encrypt(['response'=>'cancel'])),
                    //your cancel url
                    'signature_key' => $aamarpay_signature_key,
                    'desc' => $aamarpay_description,
                ); //signature key will provided aamarpay, contact integration@aamarpay.com for test/live signature key


                $fields_string = http_build_query($fields);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_VERBOSE, true);
                curl_setopt($ch, CURLOPT_URL, $url);

                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $url_forward = str_replace('"', '', stripslashes(curl_exec($ch)));
                curl_close($ch);

                $this->redirect_to_merchant($url_forward);
            } catch (\Exception $e)
            {
                return redirect()->route('plans.index')->with('error', $e);
            }
        } else {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }

    }

    function redirect_to_merchant($url)
    {

        $token = csrf_token();
        ?>
        <html xmlns="http://www.w3.org/1999/xhtml">

        <head>
            <script type="text/javascript">
                function closethisasap() { document.forms["redirectpost"].submit(); }
            </script>
        </head>

        <body onLoad="closethisasap();">

            <form name="redirectpost" method="post" action="<?php echo 'https://sandbox.aamarpay.com/' . $url; ?>">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
        </body>

        </html>
        <?php
        exit;
    }

    public function aamarpaysuccess($data, Request $request)
    {
        $data = \Crypt::decrypt($data);
        $user = \Auth::user();

        if ($data['response'] == "success")
        {
            $plan = Plan::find($data['plan_id']);
            $couponCode = $data['coupon'];
            $getAmount = $data['price'];
            $orderID = $data['order_id'];
            if ($couponCode != 0) {
                $coupons = Coupon::where('code', strtoupper($couponCode))->where('is_active', '1')->first();
                $request['coupon_id'] = $coupons->id;
            } else {
                $coupons = null;
            }

            $order = new Order();
            $order->order_id = $orderID;
            $order->name = $user->name;
            $order->card_number = '';
            $order->card_exp_month = '';
            $order->card_exp_year = '';
            $order->plan_name = $plan->name;
            $order->plan_id = $plan->id;
            $order->price = $getAmount;
            $order->price_currency = 'USD';
            $order->payment_type = __('Aamarpay');
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
        }
        elseif ($data['response'] == "cancel")
        {
            return redirect()->route('plans.index')->with('error', __('Your payment is cancel'));
        }
        else {
            return redirect()->route('plans.index')->with('error', __('Your Transaction is fail please try again'));
        }

    }

    public function invoicepaywithaamarpay(Request $request)
    {
        $url = 'https://sandbox.aamarpay.com/request.php';
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::find($invoice_id);
        $authuser      = User::find($invoice->created_by);
        $payment_setting = Utility::getCompanyPaymentSetting($authuser->id);
        if (\Auth::check()) {
            $user = Auth::user();
        } else {
            $user = User::where('id', $invoice->created_by)->first();
        }

        try {
            $get_amount = $request->amount;

            if ($invoice && $get_amount != 0) {

                if ($get_amount > $invoice->getDue())
                {
                    return redirect()->back()->with('error', __('Invalid amount.'));
                }
            }
            try {

                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                $fields = array(
                    'store_id' => $payment_setting['aamarpay_store_id'],
                    //store id will be aamarpay,  contact integration@aamarpay.com for test/live id
                    'amount' => $get_amount,
                    //transaction amount
                    'payment_type' => '',
                    //no need to change
                    'currency' =>(isset($invoice->project)) ? $invoice->project->currency_code : 'USD',
                    //currenct will be USD/BDT
                    'tran_id' => $orderID,
                    //transaction id must be unique from your end
                    'cus_name' => $user['name'],
                    //customer name
                    'cus_email' => $user['email'],
                    //customer email address
                    'cus_add1' => '',
                    //customer address
                    'cus_add2' => '',
                    //customer address
                    'cus_city' => '',
                    //customer city
                    'cus_state' => '',
                    //state
                    'cus_postcode' => '',
                    //postcode or zipcode
                    'cus_country' => '',
                    //country
                    'cus_phone' => '1234567890',
                    //customer phone number
                    'success_url' => route('invoice.pay.aamarpay.success',\Crypt::encrypt(['response'=>'success','invoice_id' => $invoice_id, 'amount' => $get_amount, 'order_id' => $orderID]) ),
                    //your success route
                    'fail_url' => route('invoice.pay.aamarpay.success',\Crypt::encrypt(['response'=>'failure','invoice_id' => $invoice_id, 'amount' => $get_amount, 'order_id' => $orderID]) ),
                    //your fail route
                    'cancel_url' => route('invoice.pay.aamarpay.success', \Crypt::encrypt(['response'=>'cancel'])),
                    //your cancel url
                    'signature_key' => $payment_setting['aamarpay_signature_key'],
                    'desc' => $payment_setting['aamarpay_description'],
                ); //signature key will provided aamarpay, contact integration@aamarpay.com for test/live signature key

                $fields_string = http_build_query($fields);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_VERBOSE, true);
                curl_setopt($ch, CURLOPT_URL, $url);

                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $url_forward = str_replace('"', '', stripslashes(curl_exec($ch)));
                curl_close($ch);
                $this->redirect_to_merchant($url_forward);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e);
            }

        } catch (\Throwable $e) {
            return redirect()->back()->with('error', __($e->getMessage()));
        }
    }
    public function getInvoicePaymentStatus($data)
    {
        try {
            $data = \Crypt::decrypt($data);
            $getAmount = $data['amount'];
            $invoice    = Invoice::find($data['invoice_id']);
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
            if ($data['response'] == "success")
            {
                $invoice_payment                 = new InvoicePayment();
                $invoice_payment->transaction_id = $orderID;
                $invoice_payment->invoice_id     = $invoice->id;
                $invoice_payment->amount         = isset($getAmount) ? $getAmount : 0;
                $invoice_payment->date           = date('Y-m-d');
                $invoice_payment->payment_id     = 0;
                $invoice_payment->payment_type   = 'Aamarpay';
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
                if(Auth::user())
                {
                    return redirect()->route('invoices.show', $data['invoice_id'])->with('success', __('Invoice paid Successfully!'). ((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')));
                }
                else
                {
                    return redirect()->route('pay.invoice', encrypt($data['invoice_id']))->with('success', __('Invoice paid Successfully!'). ((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')));
                }
            }
            elseif ($data['response'] == "cancel")
            {
                return redirect()->back()->with('error', __('Your payment is cancel'));
            }
            else {
                return redirect()->back()->with('error', __('Your Transaction is fail please try again'));
            }
        } catch (\Throwable $th)
        {
            return redirect()->back()->with('error',$th);

        }
    }

}
