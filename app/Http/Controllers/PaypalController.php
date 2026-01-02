<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Models\Project;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

// use PayPal\Rest\ApiContext;


class PaypalController extends Controller
{
    private $_api_context;
    private $user;

    public function paymentConfig()
    {
        // if(\Auth::check())
        // {
        //     $payment_setting = Utility::getAdminPaymentSetting();
        // }
        // else
        // {
        //     $payment_setting = Utility::getCompanyPaymentSetting($this->invoiceData->created_by);
        // }
        if(isset($_REQUEST['from']) && $_REQUEST['from'] == 'invoice')
        {
            $payment_setting = Utility::getCompanyPaymentSetting($_REQUEST['invoice_creator']);
            $invoice         = Invoice::find($_REQUEST['invoice_creator']);
            $this->currancy  = (isset($invoice->project)) ? $invoice->project->currency_code : 'USD';
        }
        else
        {
            $payment_setting = Utility::getAdminPaymentSetting();
            $this->currancy  = env('CURRENCY_CODE');
        }
        if($payment_setting['paypal_mode'] == 'live'){
            config([
                      'paypal.live.client_id' => isset($payment_setting['paypal_client_id']) ? $payment_setting['paypal_client_id'] : '',
                      'paypal.live.client_secret' => isset($payment_setting['paypal_secret_key']) ? $payment_setting['paypal_secret_key'] : '',
                      'paypal.mode' => isset($payment_setting['paypal_mode']) ? $payment_setting['paypal_mode'] : '',
                  ]);
        }else{
            config([
                        'paypal.sandbox.client_id' => isset($payment_setting['paypal_client_id']) ? $payment_setting['paypal_client_id'] : '',
                        'paypal.sandbox.client_secret' => isset($payment_setting['paypal_secret_key']) ? $payment_setting['paypal_secret_key'] : '',
                        'paypal.mode' => isset($payment_setting['paypal_mode']) ? $payment_setting['paypal_mode'] : '',
                    ]);
      }
    }

    public function clientPayWithPaypal(Request $request, $invoice_id)
    {


        $invoice = Invoice::find($invoice_id);

        if (\Auth::check()) {
            $this->user     = \Auth::user();
            $settings = DB::table('settings')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('value', 'name');

        } else {

            $this->user     = User::where('id', $invoice->created_by)->first();
            $settings = Utility::settingsById($invoice->created_by);
        }
        $get_amount = $request->amount;

        $request->validate(['amount' => 'required|numeric|min:0']);
        $this->paymentconfig();
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        if ($invoice) {
            if ($get_amount > $invoice->getDue()) {
                return redirect()->back()->with('error', __('Invalid amount.'));
            }
            else
            {
                $this->paymentconfig();

                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                $name = Utility::invoiceNumberFormat($invoice->invoice_id);
                $paypalToken = $provider->getAccessToken();
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('client.get.payment.status',[$invoice->id,$get_amount]),
                        "cancel_url" =>  route('client.get.payment.status',[$invoice->id,$get_amount]),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => Utility::getValByName('site_currency'),
                                "value" => $get_amount
                            ]
                        ]
                    ]
                ]);
                if (isset($response['id']) && $response['id'] != null) {
                    // redirect to approve href
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }

                    return redirect()->route('invoices.show', \Crypt::encrypt($invoice->id))->with('error', 'Something went wrong.');
                } else {
                    return redirect()->route('invoices.show', \Crypt::encrypt($invoice->id))->with('error', $response['message'] ?? 'Something went wrong.');
                }
                return redirect()->route('customer.invoice.show',\Crypt::encrypt($invoice_id))->back()->with('error', __('Unknown error occurred'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function clientGetPaymentStatus(Request $request, $invoice_id,$amount)
    {

        $invoice = Invoice::find($invoice_id);

        if(\Auth::check())
        {
            $user=\Auth::user();
        }
        else
        {
            $user= User::where('id',$invoice->created_by)->first();
        }

        $settings = DB::table('settings')->where('created_by', '=', $user->creatorId())->get()->pluck('value', 'name');

        if($invoice)
        {
            $this->paymentconfig();

            $payment_id = Session::get('paypal_payment_id');
            Session::forget('paypal_payment_id');

            if(empty($request->PayerID || empty($request->token)))
            {
                return redirect()->route(
                    'invoices.show', $invoice_id
                )->with('error', __('Payment failed'));
            }
            $order_id = strtoupper(str_replace('.', '', uniqid('', true)));

            $invoice_payment                 = new InvoicePayment();
            $invoice_payment->transaction_id = $order_id;
            $invoice_payment->invoice_id     = $invoice->id;
            $invoice_payment->amount         = $amount;
            $invoice_payment->date           = date('Y-m-d');
            $invoice_payment->payment_id     = 0;
            $invoice_payment->payment_type   = 'PAYPAL';
            $invoice_payment->client_id      = $user->id;
            $invoice_payment->notes          = '';
            $invoice_payment->save();

            $invoice = Invoice::find($invoice->id);

            if($invoice->getDue() <= 0.0)
            {
                Invoice::change_status($invoice->id, 5);
            }
            elseif($invoice->getDue() > 0)
            {
                Invoice::change_status($invoice->id, 4);
            }
            else
            {
                Invoice::change_status($invoice->id, 3);
            }
            if(\Auth::check())
            {
                    $user = Auth::user();
            }
            else
            {
                $user = User::where('id',$invoice->created_by)->first();
            }
            if($user->type != 'owner')
            {
                $user = User::where('id',$user->created_by)->first();
            }
            // $settings  = Utility::settingsById(Auth::user()->id);
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

            $webhook =  Utility::webhookSetting($module,$user->id);

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

            if(\Auth::check())
            {
                return redirect()->route('invoices.show', $invoice_id)->with('success', __('Payment added Successfully'). ((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')));
            }
            else
            {
                return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('success', __('Payment successfully added'). ((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')));

            }
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function planPayWithPaypal(Request $request)
    {

        $authuser = Auth::user();
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan   = Plan::find($planID);
        $this->paymentconfig();
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));

        if($plan){
            try
            {
                $coupon_id = 0;
                $price     = (float)$plan->{$request->paypal_payment_frequency . '_price'};
                if(!empty($request->coupon))
                {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if(!empty($coupons))
                    {
                        $usedCoupun     = $coupons->used_coupon();
                        $discount_value = ($price / 100) * $coupons->discount;
                        $price          = $price - $discount_value;
                        if($coupons->limit == $usedCoupun)
                        {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                        $coupon_id = $coupons->id;
                    }
                    else
                    {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }

                $paypalToken = $provider->getAccessToken();
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('plan.get.payment.status',[$plan->id,$price,$request->paypal_payment_frequency,$coupon_id]),
                        "cancel_url" =>  route('plan.get.payment.status',[$plan->id,$price,$request->paypal_payment_frequency,$coupon_id]),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => Utility::getValByName('site_currency'),
                                "value" => $price
                            ]
                        ]
                    ]
                ]);
                    if (isset($response['id']) && $response['id'] != null) {
                        // redirect to approve href
                        foreach ($response['links'] as $links) {
                            if ($links['rel'] == 'approve') {
                                return redirect()->away($links['href']);
                            }
                        }
                    return redirect()->route('plans.index')->with('error', 'Something went wrong.');
                } else {

                    return redirect()->route('plans.index')->with('error', $response['message'] ?? 'Something went wrong.');
                }
            }
            catch(\Exception $e)
            {
                return redirect()->route('plans.index')->with('error', __($e->getMessage()));
            }
        }else{
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function planGetPaymentStatus(Request $request, $plan_id,$amount,$frequency,$coupon)
    {

        $user = Auth::user();
        $plan = Plan::find($plan_id);

        if($plan)
        {
            $this->paymentconfig();

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);
            $payment_id = Session::get('paypal_payment_id');
            $order_id = strtoupper(str_replace('.', '', uniqid('', true)));
            if (isset($response['status']) && $response['status'] == 'COMPLETED')
            {
                if($response['status'] == 'COMPLETED'){
                   $statuses = 'success';
                }
                    $order                 = new Order();
                    $order->order_id       = $order_id;
                    $order->name           = $user->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->plan_name      = $plan->name;
                    $order->plan_id        = $plan->id;
                    $order->price          = $amount;
                    $order->price_currency = !empty($this->currancy) ? $this->currancy : 'usd';
                    $order->txn_id         = '';
                    $order->payment_type   = 'PAYPAL';
                    $order->payment_status = $statuses;
                    $order->receipt        = '';
                    $order->user_id        = $user->id;
                    $order->save();

                    if($coupon != '')
                    {
                        $coupons = Coupon::find($coupon);
                        if(!empty($coupons))
                        {
                            $userCoupon            = new UserCoupon();
                            $userCoupon->user   = $user->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order  = $order_id;
                            $userCoupon->save();
                            $usedCoupun = $coupons->used_coupon();
                            if($coupons->limit <= $usedCoupun)
                            {
                                $coupons->is_active = 0;
                                $coupons->save();
                            }
                        }
                    }

                $assignPlan = $user->assignPlan($plan->id, $frequency);
                if($assignPlan['is_success'] == true)
                {

                    return redirect()->route('home')->with('success', __('Plan activated Successfully!'));
                }
                else
                {
                    return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                }

                return redirect()->route('plans.index')->with('success', 'Transaction complete.');
            } else {

                return redirect()->route('plans.index')->with('error', $response['message'] ?? 'Something went wrong.');
            }
        }
        else
        {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }
}
