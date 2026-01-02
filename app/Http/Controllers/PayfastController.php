<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Utility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\InvoicePayment;
use App\Models\User;

class PayfastController extends Controller
{
    public $payfast_access_token;
    public $payfast_mode;
    public $is_enabled;
    public $token;
    public $mode;
    public $currancy;
    public $MerchantId;
    public $merchantKey;

    public function __construct()
    {
        // if (\Auth::user()->type == 'company') {
        //     $payment_setting = Utility::getAdminPaymentSetting();
        // } else {
        //     $payment_setting = Utility::getCompanyPaymentSetting($this->invoiceData);
        // }


        $payment_setting = Utility::getAdminPaymentSetting();

        $this->MerchantId = isset($payment_setting['payfast_merchant_id']) ? $payment_setting['payfast_merchant_id'] : '';
        $this->merchantKey = isset($payment_setting['payfast_merchant_key']) ? $payment_setting['payfast_merchant_key'] : '';
        $this->is_enabled = isset($payment_setting['is_payfast_enabled']) ? $payment_setting['is_payfast_enabled'] : 'off';
    }


    public function index(Request $request)
    {
        if (Auth::check()) {
            $payment_setting = Utility::getAdminPaymentSetting();

            $planID = Crypt::decrypt($request->plan_id);
            $plan = Plan::find($planID);

            if ($plan) {
                $plan_amount = (float)$plan->{$request->frequency . '_price'};

                $order_id = strtoupper(str_replace('.', '', uniqid('', true)));
                $user = Auth::user();

                if ($request->coupon  > 0 && $request->coupon_code != null) {

                    $coupons = Coupon::where('code', $request->coupon_code)->first();


                    if (!empty($coupons)) {
                        $userCoupon = new UserCoupon();
                        $userCoupon->user = $user->id;
                        $userCoupon->coupon = $coupons->id;
                        $userCoupon->order = $order_id;
                        $userCoupon->save();

                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit <= $usedCoupun) {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }
                        $plan_amount = $plan_amount - $request->coupon_amount;

                    }
                }

                if($request->coupon_amount != null && $request->coupon_amount > 0){
                    $plan_amount = $request->coupon_amount;
                }

                $success = Crypt::encrypt([
                    'plan' => $plan->toArray(),
                    'order_id' => $order_id,
                    'plan_amount' => $plan_amount
                ]);

                $data = array(
                    // Merchant details
                    'merchant_id' => !empty($payment_setting['payfast_merchant_id']) ? $payment_setting['payfast_merchant_id'] : '',
                    'merchant_key' => !empty($payment_setting['payfast_merchant_key']) ? $payment_setting['payfast_merchant_key'] : '',
                    'return_url' => route('payfast.payment.success',$success),
                    'cancel_url' => route('plans.index'),
                    'notify_url' => route('plans.index'),
                    // Buyer details
                    'name_first' => $user->name,
                    'name_last' => '',
                    'email_address' => $user->email,
                    // Transaction details
                    'm_payment_id' => $order_id, //Unique payment ID to pass through to notify_url
                    'amount' => number_format(sprintf('%.2f', $plan_amount), 2, '.', ''),
                    'item_name' => $plan->name,
                );

                $passphrase = !empty($payment_setting['payfast_signature']) ? $payment_setting['payfast_signature'] : '';
                $signature = $this->generateSignature($data, $passphrase);
                $data['signature'] = $signature;

                $htmlForm = '';

                foreach ($data as $name => $value) {
                    $htmlForm .= '<input name="' . $name . '" type="hidden" value=\'' . $value . '\' />';
                }

                return response()->json([
                    'success' => true,
                    'inputs' => $htmlForm,
                ]);

            }
        }

    }

    public function generateSignature($data, $passPhrase = null)
    {
        $pfOutput = '';
        foreach ($data as $key => $val) {
            if ($val !== '') {
                $pfOutput .= $key . '=' . urlencode(trim($val)) . '&';
            }
        }

        $getString = substr($pfOutput, 0, -1);
        if ($passPhrase !== null) {
            $getString .= '&passphrase=' . urlencode(trim($passPhrase));
        }
        return md5($getString);
    }

    public function success($success)
    {

       try{

            $user = Auth::user();
            $data = Crypt::decrypt($success);

            $order = new Order();
            $order->order_id = $data['order_id'];
            $order->name = $user->name;
            $order->card_number = '';
            $order->card_exp_month = '';
            $order->card_exp_year = '';
            $order->plan_name = $data['plan']['name'];
            $order->plan_id = $data['plan']['id'];
            $order->price = $data['plan_amount'];
            $order->price_currency = !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD';
            $order->txn_id = $data['order_id'];
            $order->payment_type = __('PayFast');
            $order->payment_status = 'success';
            $order->txn_id = '';
            $order->receipt = '-';
            $order->user_id = $user->id;
            $order->save();
            $assignPlan = $user->assignPlan($data['plan']['id']);

            if ($assignPlan['is_success']) {
                return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
            } else {
                return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
            }
        }
        catch(Exception $e)
        {
            return redirect()->route('plans.index')->with('error', $e->getMessage());
        }
    }

    public function invoicepaywithpayfast(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::find($invoice_id);
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

        // $this->invoiceData  = $invoice;
        // $settings = \DB::table('settings')->where('created_by', '=', $invoice->created_by)->get()->pluck('value', 'name');
        $payment_setting = Utility::getCompanyPaymentSetting($invoice->created_by);
        if ($invoice) {
            $order_id = strtoupper(str_replace('.', '', uniqid('', true)));
            $amount =  $invoice->getDue();

            if (0 >= $amount)
            {
                return redirect()->back()->with('error', __('Invalid amount.'));
            } else {
                $name = isset($user->name) ? $user->name : 'public' . " - " . $invoice->invoice_id;
            }
        }

        if($request->amount != 0){
            $amount = $request->amount;
        }

        $invoice_success = Crypt::encrypt([
            'invoice' => $invoice->toArray(),
            'order_id' => $order_id,
            'amount' => $amount,
            'invoice_id' => $invoice_id
        ]);
        $data = array(
            // Merchant details
            'merchant_id' => !empty($payment_setting['payfast_merchant_id']) ? $payment_setting['payfast_merchant_id'] : '',
            'merchant_key' => !empty($payment_setting['payfast_merchant_key']) ? $payment_setting['payfast_merchant_key'] : '',
            'return_url' => route('invoice.payfast.status',$invoice_id),
            'cancel_url' => route('invoice.payfast.status',$invoice_id),
            'notify_url' => route('invoice.payfast.status',$invoice_id),
            // Buyer details
            'name_first' => $user->name,
            'name_last' => '',
            'email_address' => $user->email,
            // Transaction details
            'm_payment_id' => $order_id, //Unique payment ID to pass through to notify_url
            'amount' => number_format(sprintf('%.2f', $amount), 2, '.', ''),
            'item_name' => $name,
        );
        $passphrase = !empty($payment_setting['payfast_signature']) ? $payment_setting['payfast_signature'] : '';
        $signature = $this->generateSignature($data, $passphrase);
        $data['signature'] = $signature;

        $htmlForm = '';

        foreach ($data as $name => $value) {
            $htmlForm .= '<input name="' . $name . '" type="hidden" value=\'' . $value . '\' />';
        }
        return response()->json([
            'success' => true,
            'inputs' => $htmlForm,
        ]);
    }

    public function invoicepayfaststatus($invoice_id)
    {
        $invoice = Invoice::find($invoice_id);
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
        // $invoice_id = Crypt::decrypt($invoice_success);

        // $get_amount = $invoice_id['amount'];

        if($invoice){
            try {

                $invoice_payment                 = new InvoicePayment();
                $invoice_payment->transaction_id = app('App\Http\Controllers\InvoiceController')->transactionNumber();
                $invoice_payment->invoice_id     = $invoice->id;
                $invoice_payment->amount         = isset($invoice_data['total_price']) ? $invoice_data['total_price'] : 0;
                $invoice_payment->date           = date('Y-m-d');
                $invoice_payment->payment_id     = 0;
                $invoice_payment->payment_type   = 'Payfast';
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
                    $invoice->save();

                    if(Auth::check()){
                        return redirect()->route('invoices.show', $invoice_id)->with('success', __('Invoice paid Successfully!'). ((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')));
                    }else{
                        return redirect()->route('pay.invoice', encrypt($invoice_id ))->with('ERROR', __('Transaction fail'). ((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')));
                    }
                } catch (\Exception $exception) {
                    if(Auth::check()){
                        return redirect()->route('invoices.show', $invoice_id)->with('error',$exception->getMessage());
                    }else{
                        return redirect()->route('pay.invoice', encrypt($invoice_id))->with('success',$exception->getMessage());
                    }
                }
        } else {
            if(Auth::check()){
                return redirect()->route('invoices.show', $invoice_id['invoice_id'])->with('error',__('Invoice not found.'));
            }else{
                return redirect()->route('pay.invoice', encrypt($invoice_id['invoice_id']))->with('success', __('Invoice not found.'));
            }
        }
    }
}

