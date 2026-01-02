<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Utility;
use App\Models\InvoicePayment;
use App\Models\UserCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use LivePixel\MercadoPago\MP;
use \Crypt;
use Exception;

use function Ramsey\Uuid\v1;

class MercadoPaymentController extends Controller
{
    public $mercado_access_token;
    public $mercado_mode;
    public $is_enabled;
    public $currancy;

    public function __construct()
    {
        $this->middleware(
            [
                'XSS',
            ]
        );

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

        $this->mercado_access_token = isset($payment_setting['mercado_access_token']) ? $payment_setting['mercado_access_token'] : '';

        $this->mercado_mode = isset($payment_setting['mercado_mode']) ? $payment_setting['mercado_mode'] : '';
        $this->is_enabled = isset($payment_setting['is_mercado_enabled']) ? $payment_setting['is_mercado_enabled'] : 'off';
    }


    public function planPayWithMercado(Request $request)
    {
        $planID   = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan     = Plan::find($planID);
        $authuser = \Auth::user();

        $coupons_id = '';
        if($plan)
        {
            $price                  = $plan->{$request->mercado_payment_frequency . '_price'};
            if(isset($request->coupon) && !empty($request->coupon))
            {
                $request->coupon = trim($request->coupon);
                $coupons         = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if(!empty($coupons))
                {
                    $usedCoupun             = $coupons->used_coupon();
                    $discount_value         = ($price / 100) * $coupons->discount;
                    $plan->discounted_price = $price - $discount_value;
                    $coupons_id             = $coupons->id;
                    if($usedCoupun >= $coupons->limit)
                    {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    $price = $price - $discount_value;
                }
                else
                {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            if($price <= 0)
            {

                $authuser->plan = $plan->id;

                $authuser->save();

                $assignPlan = $authuser->assignPlan($plan->id);

                if($assignPlan['is_success'] == true && !empty($plan))
                {
                    if(!empty($authuser->payment_subscription_id) && $authuser->payment_subscription_id != '')
                    {
                        try
                        {
                            $authuser->cancel_subscription($authuser->id);
                        }
                        catch(\Exception $exception)
                        {
                            \Log::debug($exception->getMessage());
                        }
                    }

                    $orderID = time();
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
                            'price' => $price == null ? 0 : $price,
                            'price_currency' => !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD',
                            'txn_id' => '',
                            'payment_type' => __('Mercado'),
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );


                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                }
                else
                {
                    return redirect()->route('plans.index')->with('error', __('Plan fail to upgrade.'));
                }
            }

            \MercadoPago\SDK::setAccessToken($this->mercado_access_token);
            try
            {

                // Create a preference object
                $preference = new \MercadoPago\Preference();
                // Create an item in the preference
                $item              = new \MercadoPago\Item();
                $item->title       = "Plan : " . $plan->name;
                $item->quantity    = 1;
                $item->unit_price  = (float)$price;
                $preference->items = array($item);


                $success_url = route(
                    'plan.mercado', [
                                    $request->plan_id,
                                    $price,
                                    'payment_frequency=' . $request->mercado_payment_frequency,
                                    'coupon_id=' . $coupons_id,
                                    'flag' => 'success',
                                ]
                );
                $failure_url = route(
                    'payment', [
                                Crypt::encrypt($request->plan_id),
                            ]
                );
                $pending_url = route(
                    'plan.mercado', [
                                    $request->plan_id,
                                    $price,
                                    'payment_frequency=' . $request->mercado_payment_frequency,
                                    'coupon_id=' . $coupons_id,
                                    'flag' => 'pending',
                                ]
                );

                $preference->back_urls = array(
                    "success" => $success_url,
                    "failure" => $failure_url,
                    "pending" => $pending_url,
                );
                $preference->auto_return = "approved";
                $preference->save();

                // Create a customer object
                $payer = new \MercadoPago\Payer();
                // Create payer information
                $payer->name    = \Auth::user()->name;
                $payer->email   = \Auth::user()->email;
                $payer->address = array(
                    "street_name" => '',
                );

                if($this->mercado_mode == 'live')
                {
                    $redirectUrl = $preference->init_point;
                }
                else
                {
                    $redirectUrl = $preference->sandbox_init_point;
                }
                return redirect($redirectUrl);
            }
            catch(Exception $e)
            {

                return redirect()->back()->with('error', $e->getMessage());
            }

        }
        else
        {
            return redirect()->back()->with('error', 'Plan is deleted.');
        }

    }

    public function getPaymentStatus(Request $request,$plan)
    {
        // Log::info(json_encode($request->all()));
        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($plan);
        $plan           = Plan::find($planID);
        $price = $plan->{$request->payment_frequency . '_price'};
        $user = Auth::user();
        $orderID = time();
        if ($plan) {
            try
            {
            if ($plan && $request->has('status')) {
                if ($request->status == 'approved' && $request->flag == 'success') {
                    if (!empty($user->payment_subscription_id) && $user->payment_subscription_id != '') {
                        try {
                            $user->cancel_subscription($user->id);
                        } catch (\Exception $exception) {
                            \Log::debug($exception->getMessage());
                        }
                    }

                    if ($request->has('coupon_id') && $request->coupon_id != '') {
                        $coupons = Coupon::find($request->coupon_id);

                        if (!empty($coupons)) {
                            $userCoupon            = new UserCoupon();
                            $userCoupon->user   = $user->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order  = $orderID;
                            $userCoupon->save();

                            $usedCoupun = $coupons->used_coupon();
                            if ($coupons->limit <= $usedCoupun) {
                                $coupons->is_active = 0;
                                $coupons->save();
                            }
                        }
                    }

                    $order                 = new Order();
                    $order->order_id       = $orderID;
                    $order->name           = $user->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->plan_name      = $plan->name;
                    $order->plan_id        = $plan->id;
                    $order->price          = $price ? $price : 0;
                    $order->price_currency = env('CURRENCY');
                    $order->txn_id         = $request->has('preference_id') ? $request->preference_id : '';
                    $order->payment_type   = 'Mercado Pago';
                    $order->payment_status = 'succeeded';
                    $order->receipt        = '';
                    $order->user_id        = $user->id;
                    $order->save();
                    $assignPlan = $user->assignPlan($plan->id, $request->payment_frequency);
                    if ($assignPlan['is_success']) {
                        return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                    } else {
                        return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                    }
                } else {
                    return redirect()->route('plans.index')->with('error', __('Transaction has been failed! '));
                }
            } else {
                return redirect()->route('plans.index')->with('error', __('Transaction has been failed! '));
            }
            }
            catch(\Exception $e)
            {
                return redirect()->route('plans.index')->with('error', __('Plan not found!'));
            }
        }
    }

    public function invoicePayWithMercado(Request $request)
    {

        $validatorArray = [
            'amount' => 'required',
            'invoice_id' => 'required',
        ];
        $validator      = Validator::make($request->all(), $validatorArray)->setAttributeNames(
            ['invoice_id' => 'Invoice']
        );

        if($validator->fails())
        {
            return redirect()->back()->with('error', __($validator->errors()->first()));
        }
        $invoice = Invoice::find($request->invoice_id);

        $payment_setting = Utility::getCompanyPaymentSetting($invoice->created_by);

        $this->token = isset($payment_setting['mercado_access_token'])?$payment_setting['mercado_access_token']:'';
        $this->mode      = isset($payment_setting['mercado_mode'])?$payment_setting['mercado_mode']:'';
        $this->is_enabled = isset($payment_setting['is_mercado_enabled'])?$payment_setting['is_mercado_enabled']:'off';
        // $settings = Utility::settingsById($invoice->created_by);

        if($invoice->getDue() < $request->amount)
        {
            return redirect()->route('invoices.show', $invoice->id)->with('error', __('Invalid amount.'));
        }

        $preference_data = array(
            "items" => array(
                array(
                    "title" => "Invoice : " . $request->invoice_id,
                    "quantity" => 1,
                    "currency_id" => $this->currancy,
                    "unit_price" => (float)$request->amount,
                ),
            ),
        );

        \MercadoPago\SDK::setAccessToken($this->token);
        // try {

            // Create a preference object
            $preference = new \MercadoPago\Preference();
            // Create an item in the preference
            $item = new \MercadoPago\Item();
            $item->title = "Invoice : " . $request->invoice_id;
            $item->quantity = 1;
            $item->unit_price = (float)$request->amount;
            $preference->items = array($item);

            $success_url = route('invoice.mercado.callback',['amount'=>(float)$request->amount,'flag'=>'success',encrypt($invoice->id)]);
            $failure_url = route('invoice.mercado.callback',[encrypt($invoice->id),'flag'=>'failure']);
            $pending_url = route('invoice.mercado.callback',[encrypt($invoice->id),'flag'=>'pending']);
            $preference->back_urls = array(
                "success" => $success_url,
                "failure" => $failure_url,
                "pending" => $pending_url
            );
            $preference->auto_return = "approved";
            $preference->save();

            if(\Auth::check())
            {
                    $user = Auth::user();
            }
            else
            {
                $user= User::where('id',$invoice->created_by)->first();
            }
            if($user->type != 'owner')
            {
                $user=User::where('id',$user->created_by)->first();
            }

            // Create a customer object
            $payer = new \MercadoPago\Payer();
            // Create payer information
            $payer->name = $user->name;
            $payer->email = $user->email;
            $payer->address = array(
                "street_name" => ''
            );

            if($this->mode =='live'){
                $redirectUrl = $preference->init_point;
            }else{
                $redirectUrl = $preference->sandbox_init_point;
            }
            return redirect($redirectUrl);
        // } catch (Exception $e) {
        //     return redirect()->back()->with('error', $e->getMessage());
        // }

    }

    public function getInvoicePaymentStatus(Request $request,$invoice_id)
    {
        if(!empty($invoice_id))
        {
            $invoice_id = decrypt($invoice_id);
            $invoice    = Invoice::find($invoice_id);
            $orderID  = strtoupper(str_replace('.', '', uniqid('', true)));
            if($invoice && $request->has('status'))
            {
                // try
                // {

                    if($request->status == 'approved' && $request->flag =='success')
                    {

                        $new                 = new InvoicePayment();
                        $new->invoice_id     = $invoice_id;
                        $new->transaction_id = $orderID;
                        $new->date           = Date('Y-m-d');
                        $new->amount         = $request->has('amount')?$request->amount:0;
                        $new->client_id      = $invoice->id;
                        $new->payment_type = 'Mercado Pago';
                        $new->payment_id     = 0;

                        $new->save();

                        if(($invoice->getDue() - $new->amount) == 0)
                        {
                            Invoice::change_status($invoice->id, 3);
                        }
                        else
                        {
                            Invoice::change_status($invoice->id, 2);
                        }
                        $invoice->save();

                        $settings  = Utility::settingsById($invoice->created_by);

                        if(\Auth::check())
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
                        $uArr = [
                            'invoice_id' => Utility::invoiceNumberFormat($invoice->invoice_id),
                            'owner_name' => $user->name,
                        ];
                        if(isset($settings['invoice_status_notificaation']) && $settings['invoice_status_notificaation'] == 1){
                            Utility::send_slack_msg('invoice_status_updated',$uArr ,$user->id);
                        }

                        if(isset($settings['telegram_invoice_status_notificaation']) && $settings['telegram_invoice_status_notificaation'] == 1){
                            Utility::send_telegram_msg('invoice_status_updated',$uArr,$user->id);
                        }

                        //webhook
                        $module ='Invoice Status Updated';

                        $webhook=  Utility::webhookSetting($module,$user->id);

                        if($webhook)
                        {
                            $parameter = json_encode($new);
                            // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                            $status = Utility::WebhookCall($webhook['url'],$parameter,$webhook['method']);
                            if($status != true)
                            {
                                $msg= "Webhook call failed.";
                            }
                        }
                        if(Auth::user())
                        {
                            return redirect()->route('invoices.show', $invoice_id)->with('success', __('Invoice paid Successfully!'). ((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')));
                        }
                        else
                        {
                            $id= \Crypt::encrypt($invoice_id);
                            return redirect()->route('pay.invoice', $id)->with('success', __('Invoice paid Successfully!'). ((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')));
                        }

                    }else{
                        if(Auth::user())
                        {
                            return redirect()->route('invoices.show', $invoice_id)->with('error', __('Transaction fail!'));
                        }
                        else
                        {
                            $id= \Crypt::encrypt($invoice_id);
                            return redirect()->route('pay.invoice', $id)->with('error', __('Transaction fail!'));
                        }
                    }
                // }
                // catch(\Exception $e)
                // {
                //     return redirect()->route('invoices.index')->with('error', __('Plan not found!'));
                // }
            }else
            {
                if(Auth::user())
                {
                    return redirect()->route('invoices.show', $invoice_id)->with('error', __('Invoice not found'));
                }
                else
                {
                    $id= \Crypt::encrypt($invoice_id);

                    return redirect()->route('pay.invoice', $id)->with('error', __('Transaction fail!'));
                }
            }
        }else{
            return redirect()->route('invoices.index')->with('error', __('Invoice not found.'));
        }
    }
}
