<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SspayController extends Controller
{
    public $secretKey, $callBackUrl, $returnUrl, $categoryCode, $is_enabled, $invoiceData;

    public function __construct()
    {

        $payment_setting = Utility::getPaymentSetting();

        $this->secretKey      = isset($payment_setting['sspay_secret_key']) ? $payment_setting['sspay_secret_key'] : '';
        $this->categoryCode   = isset($payment_setting['sspay_category_code']) ? $payment_setting['sspay_category_code'] : '';
        $this->is_enabled     = isset($payment_setting['is_sspay_enabled']) ? $payment_setting['is_sspay_enabled'] : 'off';
        return $this;

    }

    public function SspayPaymentPrepare(Request $request)
    {

        try
        {
            $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
            $plan   = Plan::find($planID);
            if ($plan) {
                $payment_frequency      = $request->sspay_payment_frequency;
                $get_amount = $plan->{$payment_frequency . '_price'};

                if (!empty($request->coupon)) {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons)) {
                        $usedCoupun     = $coupons->used_coupon();
                        $discount_value = ($plan->price / 100) * $coupons->discount;
                        $get_amount          = $plan->price - $discount_value;

                        if ($coupons->limit == $usedCoupun) {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                    } else {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }
                $coupon = (empty($request->coupon)) ? "0" : $request->coupon;
                $this->callBackUrl = route('plan.sspay.callback', [$plan->id, $get_amount, $coupon]);
                $this->returnUrl = route('plan.sspay.callback', [$plan->id, $get_amount, $coupon]);



                $Date = date('d-m-Y');
                $ammount = $get_amount;
                $billName = $plan->name;
                $description = $plan->name;
                $billExpiryDays = 3;
                $billExpiryDate = date('d-m-Y', strtotime($Date . ' + 3 days'));
                $billContentEmail = "Thank you for purchasing our product!";

                $some_data = array(
                    'userSecretKey' => $this->secretKey,
                    'categoryCode' => $this->categoryCode,
                    'billName' => $billName,
                    'billDescription' => $description,
                    'billPriceSetting' => 1,
                    'billPayorInfo' => 1,
                    'billAmount' => 100 * $ammount,
                    'billReturnUrl' => $this->returnUrl,
                    'billCallbackUrl' => $this->callBackUrl,
                    'billExternalReferenceNo' => 'AFR341DFI',
                    'billTo' => \Auth::user()->name,
                    'billEmail' => \Auth::user()->email,
                    'billPhone' => '000000000',
                    'billSplitPayment' => 0,
                    'billSplitPaymentArgs' => '',
                    'billPaymentChannel' => '0',
                    'billContentEmail' => $billContentEmail,
                    'billChargeToCustomer' => 1,
                    'billExpiryDate' => $billExpiryDate,
                    'billExpiryDays' => $billExpiryDays
                );

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_URL, 'https://sspay.my/index.php/api/createBill');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);
                $result = curl_exec($curl);
                $info = curl_getinfo($curl);
                curl_close($curl);
                $obj = json_decode($result);
                if($obj != null){
                    try
                    {
                        return redirect('https://sspay.my/' . $obj[0]->BillCode);
                    }
                    catch (\Exception $e) {
                        return redirect()->back()->with('error', __($e->getMessage()));
                    }
                }
                return redirect()->route('plans.index')->with('error', __('Unknown error occurred'));
            } else {
                return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
            }
        } catch (\Exception $e) {

            return redirect()->route('plans.index')->with('error', __($e->getMessage()));
        }
    }

    public function SspayPlanGetPayment(Request $request, $planId, $getAmount, $couponCode)
    {
        if ($couponCode != 0) {
            $coupons = Coupon::where('code', strtoupper($couponCode))->where('is_active', '1')->first();
            $request['coupon_id'] = $coupons->id;
        } else {
            $coupons = null;
        }

        $plan = Plan::find($planId);
        $user = auth()->user();
        // $request['status_id'] = 1;

        // 1=success, 2=pending, 3=fail
        try {
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            if ($request->status_id == 3) {
                $statuses = 'Fail';
                $order                 = new Order();
                $order->order_id       = $orderID;
                $order->name           = $user->name;
                $order->card_number    = '';
                $order->card_exp_month = '';
                $order->card_exp_year  = '';
                $order->plan_name      = $plan->name;
                $order->plan_id        = $plan->id;
                $order->price          = $getAmount;
                $order->price_currency = env('CURRENCY');
                $order->txn_id         = isset($request->transaction_id) ? $request->transaction_id : '';
                $order->payment_type   = __('Sspay');
                $order->payment_status = $statuses;
                $order->receipt        = '';
                $order->user_id        = $user->id;
                $order->save();
                return redirect()->route('plans.index')->with('error', __('Your Transaction is fail please try again'));
            } else if ($request->status_id == 2) {
                $statuses = 'pandding';
                $order                 = new Order();
                $order->order_id       = $orderID;
                $order->name           = $user->name;
                $order->card_number    = '';
                $order->card_exp_month = '';
                $order->card_exp_year  = '';
                $order->plan_name      = $plan->name;
                $order->plan_id        = $plan->id;
                $order->price          = $getAmount;
                $order->price_currency = env('CURRENCY');
                $order->txn_id         = isset($request->transaction_id) ? $request->transaction_id : '';
                $order->payment_type   = __('Sspay');
                $order->payment_status = $statuses;
                $order->receipt        = '';
                $order->user_id        = $user->id;
                $order->save();
                return redirect()->route('plans.index')->with('error', __('Your transaction on pending'));
            } else if ($request->status_id == 1) {
                $statuses = 'success';
                $order                 = new Order();
                $order->order_id       = $orderID;
                $order->name           = $user->name;
                $order->card_number    = '';
                $order->card_exp_month = '';
                $order->card_exp_year  = '';
                $order->plan_name      = $plan->name;
                $order->plan_id        = $plan->id;
                $order->price          = $getAmount;
                $order->price_currency = env('CURRENCY');
                $order->txn_id         = isset($request->transaction_id) ? $request->transaction_id : '';
                $order->payment_type   = __('Sspay');
                $order->payment_status = $statuses;
                $order->receipt        = '';
                $order->user_id        = $user->id;
                $order->save();
                $assignPlan = $user->assignPlan($plan->id);
                $coupons = Coupon::find($request->coupon_id);
                if (!empty($request->coupon_id))
                {
                    if (!empty($coupons))
                    {
                        $userCoupon         = new Coupon();
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
                if ($assignPlan['is_success']) {
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
                } else {
                    return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                }
            } else {
                return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
            }
        } catch (\Exception $e) {
            return redirect()->route('plans.index')->with('error', __($e->getMessage()));
        }
    }

    public function invoicepaywithsspaypay(Request $request ,$invoice_id)
    {
        $invoice = Invoice::find($invoice_id);
        $this->invoiceData = $invoice;

        if (Auth::check()) {
            $user     = Auth::user();
            $settings = \DB::table('settings')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('value', 'name');
        } else {
            $user = User::where('id', $invoice->created_by)->first();
            $settings = Utility::settingsById($invoice->created_by);
        }

        $get_amount = $request->amount;

        if ($invoice && $get_amount != 0)
        {
            if ($get_amount > $invoice->getDue())
            {
                return redirect()->back()->with('error', __('Invalid amount.'));
            } else
            {
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                // $name = Utility::invoiceNumberFormat($settings, $invoice->invoice_id);
                $this->callBackUrl = route('invoice.sspay', [$invoice->id, $get_amount]);
                $this->returnUrl = route('invoice.sspay', [$invoice->id, $get_amount]);

            }
            $Date = date('d-m-Y');
            $ammount = $get_amount;
            $billExpiryDays = 3;
            $billExpiryDate = date('d-m-Y', strtotime($Date . ' + 3 days'));
            $billContentEmail = "Thank you for purchasing our product!";
            $some_data = array(
                'userSecretKey' => $this->secretKey,
                'categoryCode' => $this->categoryCode,
                'billName' => "invoice",
                'billDescription' => "invoice",
                'billPriceSetting' => 1,
                'billPayorInfo' => 1,
                'billAmount' => 100 * $ammount,
                'billReturnUrl' => $this->returnUrl,
                'billCallbackUrl' => $this->callBackUrl,
                'billExternalReferenceNo' => 'AFR341DFI',
                'billTo' => $user->name,
                'billEmail' => $user->email,
                'billPhone' => '0000000000',
                'billSplitPayment' => 0,
                'billSplitPaymentArgs' => '',
                'billPaymentChannel' => '0',
                'billContentEmail' => $billContentEmail,
                'billChargeToCustomer' => 1,
                'billExpiryDate' => $billExpiryDate,
                'billExpiryDays' => $billExpiryDays,
            );
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_URL, 'https://sspay.my/index.php/api/createBill');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);
            $result = curl_exec($curl);
            $info = curl_getinfo($curl);
            curl_close($curl);
            $obj = json_decode($result);
            if($obj != null){
                try{
                    return redirect('https://sspay.my/' . $obj[0]->BillCode);
                }catch (\Exception $e) {
                    return redirect()->back()->with('error', __($e->getMessage()));
                }
            }

            return redirect()->route('pay.invoice', \Crypt::encrypt($invoice))->back()->with('error', __('Unknown error occurred'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getInvoicePaymentStatus(Request $request, $invoice_id, $amount)
    {

        $invoice    = Invoice::find($invoice_id);

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


        if ($request->status_id == 3) {
            if(Auth::user()){
                return redirect()->route('invoices.index')->with('error', __('Your Transaction is failed, please try again'));
            }
            else{
                $id= \Crypt::encrypt($invoice_id);
                return redirect()->route('pay.invoice',$id)->with('error', __('Your Transaction is failed, please try again!'));
            }

        }else if( $request->status_id == 2){
            if(Auth::user()){
                return redirect()->route('invoices.index')->with('error', __('Your transaction is pending'));
            }
            else{
                $id= \Crypt::encrypt($invoice_id);
                return redirect()->route('pay.invoice',$id)->with('error', __('Your transaction is pending!'));
            }
        }else if( $request->status_id == 1){


            if($invoice)
            {

                $order_id = strtoupper(str_replace('.', '', uniqid('', true)));

                $invoice_payment                 = new InvoicePayment();
                $invoice_payment->transaction_id = $order_id;
                $invoice_payment->invoice_id     = $invoice->id;
                $invoice_payment->amount         = isset($invoice_data['price_amount']) ? $invoice_data['price_amount'] : 0;
                $invoice_payment->date           = date('Y-m-d');
                $invoice_payment->payment_id     = 0;
                $invoice_payment->payment_type   = 'Sspay';
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
                    return redirect()->route('invoices.show', $invoice_id)->with('success', __('Invoice paid Successfully!'). ((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')));
                }
                else
                {
                    $id= \Crypt::encrypt($invoice_id);
                    return redirect()->route('pay.invoice',$id)->with('success', __('Invoice paid Successfully!'). ((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')));
                }
            }
            else
            {
                return redirect()->route('invoices.show',[$invoice_id,])->with('error', __('Invoice not found.'));
            }
        }
    }
}
