<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Plan;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;
use Paytabscom\Laravel_paytabs\Facades\paypage;
use App\Models\UserCoupon;

class PaytabController extends Controller
{
    public $paytab_profile_id, $paytab_server_key, $paytab_region, $is_enabled;

    public function paymentConfig()
    {
        if(isset($_REQUEST['from']) && $_REQUEST['from'] == 'invoice')
        {
            $payment_setting = Utility::getCompanyPaymentSetting($_REQUEST['invoice_creator']);
            $invoice         = Invoice::find($_REQUEST['invoice_creator']);
            config([
                'paytabs.currency'   => (isset($invoice->project)) ? $invoice->project->currency_code : 'USD',
                // 'paytabs.currency'   =>'INR',
            ]);
        }
        else
        {
            $payment_setting = Utility::getAdminPaymentSetting();
            config([
                'paytabs.currency'   => !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD',
                // 'paytabs.currency'   =>'INR',
            ]);
        }
        config([
            'paytabs.profile_id' => isset($payment_setting['paytab_profile_id']) ? $payment_setting['paytab_profile_id'] : '',
            'paytabs.server_key' => isset($payment_setting['paytab_server_key']) ? $payment_setting['paytab_server_key'] : '',
            'paytabs.region'     => isset($payment_setting['paytab_region']) ? $payment_setting['paytab_region'] : '',
        ]);
    }

    public function planPayWithpaytab(Request $request)
    {
        try
        {
            $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
            $plan = Plan::find($planID);
            $this->paymentconfig();
            $user = Auth::user();
            if ($plan){
                // $get_amount = $plan->price;
                $plan->discounted_price = false;
                $price                  = $plan->{$request->paytab_payment_frequency . '_price'};

                if (!empty($request->coupon)) {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons)) {
                        $usedCoupun = $coupons->used_coupon();
                        $discount_value = ($price / 100) * $coupons->discount;
                        $price = $price - $discount_value;

                        if ($coupons->limit == $usedCoupun) {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                        if ($price <= 0) {
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
                                if (!empty($coupons))
                                {
                                    $userCoupon = new UserCoupon();
                                    $userCoupon->user = $authuser->id;
                                    $userCoupon->coupon = $coupons->id;
                                    $userCoupon->order = $orderID;
                                    $userCoupon->save();
                                    $usedCoupun = $coupons->used_coupon();
                                    if ($coupons->limit <= $usedCoupun)
                                    {
                                        $coupons->is_active = 0;
                                        $coupons->save();
                                    }
                                }
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
                                        // 'price_currency' => 'INR',
                                        'txn_id' => '',
                                        'payment_type' => 'Paytab',
                                        'payment_status' => 'success',
                                        'receipt' => null,
                                        'user_id' => $authuser->id,
                                    ]
                                );
                                $assignPlan = $authuser->assignPlan($plan->id);
                                return redirect()->back()->with('success', __('Plan Successfully Activated'));
                            }
                        }
                    } else {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }
                $coupon = (empty($request->coupon)) ? "0" : $request->coupon;

                $pay = paypage::sendPaymentCode('all')
                    ->sendTransaction('sale')
                    ->sendCart(1, $price, 'plan payment')
                    ->sendCustomerDetails(isset($user->name) ? $user->name : "", isset($user->email) ? $user->email : '', '', '', '', '', '', '', '')
                    ->sendURLs(
                        route('plan.paytab.success', ['success' => 1, 'data' => $request->all(), 'plan_id' => $plan->id, 'amount' => $price, 'coupon' => $coupon]),
                        route('plan.paytab.success', ['success' => 0, 'data' => $request->all(), 'plan_id' => $plan->id, 'amount' => $price, 'coupon' => $coupon])
                    )
                    ->sendLanguage('en')
                    ->sendFramed($on = false)
                    ->create_pay_page();
                if(empty($pay))
                {
                    return redirect()->route('plans.index')->with("error", __('Apologies, but it seems that the payment credentials are incorrect, and the desired currency is currently unavailable.'));
                }

                return $pay;
            } else {
                return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
            }
        } catch (\Exception $e) {
            return redirect()->route('plans.index')->with('error', __($e->getMessage()));
        }
    }
    public function PaytabGetPayment(Request $request)
    {
        $planId = $request->plan_id;
        $couponCode = $request->coupon;
        $getAmount = $request->amount;

        if ($couponCode != 0) {
            $coupons = Coupon::where('code', strtoupper($couponCode))->where('is_active', '1')->first();
            $request['coupon_id'] = $coupons->id;
        } else {
            $coupons = null;
        }

        $plan = Plan::find($planId);
        $user = auth()->user();
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        try {
            if ($request->respMessage == "Authorised")
            {
                $order                  = new Order();
                $order->order_id        = $orderID;
                $order->name            = $user->name;
                $order->card_number     = '';
                $order->card_exp_month  = '';
                $order->card_exp_year   = '';
                $order->plan_name       = $plan->name;
                $order->plan_id         = $plan->id;
                $order->price           = $getAmount;
                $order->price_currency  = !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD';
                // $order->price_currency  = 'INR';
                $order->payment_type    = __('Paytab');
                $order->payment_status  = 'success';
                $order->txn_id          = isset($request->transaction_id) ? $request->transaction_id : '';
                $order->receipt         = '';
                $order->user_id         = $user->id;
                $order->save();
                $assignPlan = $user->assignPlan($plan->id, $request->payment_frequency);
                $coupons = Coupon::find($request->coupon_id);
                if (!empty($request->coupon_id)) {
                    if (!empty($coupons)) {
                        $userCoupon = new Coupon();
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
        } catch (\Exception $e) {
            return redirect()->route('plans.index')->with('error', __($e->getMessage()));
        }
    }

    public function invoicePayWithpaytab(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::find($invoice_id);
        $this->paymentconfig();
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
                $pay = paypage::sendPaymentCode('all')
                    ->sendTransaction('sale')
                    ->sendCart(1, $get_amount, 'invoice payment')
                    ->sendCustomerDetails(isset($user->name) ? $user->name : "", isset($user->email) ? $user->email : '', '', '', '', '', '', '', '')
                    ->sendURLs(
                        route('invoice.paytab.success', ['success' => 1, 'data' => $request->all(), $invoice->id, 'amount' => $get_amount]),
                        route('invoice.paytab.success', ['success' => 0, 'data' => $request->all(), $invoice->id, 'amount' => $get_amount])
                    )
                    ->sendLanguage('en')
                    ->sendFramed($on = false)
                    ->create_pay_page();
                    if(empty($pay))
                    {
                        return redirect()->back()->with("error", __('Apologies, but it seems that the payment credentials are incorrect, and the desired currency is currently unavailable.'));
                    }
                return $pay;
            }
        }
    }

    public function getInvoicePaymentStatus(Request $request, $invoice_id)
    {
        if (!empty($invoice_id)) {
            $invoice    = Invoice::find($invoice_id);
            $orderID  = strtoupper(str_replace('.', '', uniqid('', true)));
            if(Auth::check())
                {
                    $user=Auth::user();
                }
                else
                {
                    $user= User::where('id',$invoice->created_by)->first();
                }
            if ($invoice)
            {
                try
                {
                    if ($request->respMessage == "Authorised")
                    {
                        $invoice_payment                 = new InvoicePayment();
                        $invoice_payment->invoice_id     = $invoice_id;
                        $invoice_payment->transaction_id = $orderID;
                        $invoice_payment->date           = Date('Y-m-d');
                        $invoice_payment->amount         = $request->has('amount') ? $request->amount : 0;
                        $invoice_payment->client_id      = $user->id;;
                        $invoice_payment->payment_type   = 'paytab';
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
