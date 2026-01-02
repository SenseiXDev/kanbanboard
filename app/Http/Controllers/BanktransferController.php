<?php

namespace App\Http\Controllers;

use App\Models\Banktransfer;
use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;

class BanktransferController extends Controller
{
    public  $callBackUrl, $returnUrl;

    public function planPayWithBanktransfer(Request $request)
    {
        $validator  = \Validator::make(
            $request->all(),
            [
                'payment_receipt' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }


        $filenameWithExt  = $request->file('payment_receipt')->getClientOriginalName();
        $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension        = $request->file('payment_receipt')->getClientOriginalExtension();
        $fileNameToStores = $filename . '_' . time() . '.' . $extension;

        $settings = Utility::Settings();
        if($settings['storage_setting']=='local'){
            $dir  = 'payment_receipt/';
        }

        $path = Utility::upload_file($request,'payment_receipt',$fileNameToStores,$dir,[]);
        if($path['flag'] == 1)
        {
            $file = $path['url'];
        }
        else
        {
            return redirect()->back()->with('error', __($path['msg']));
        }

        $authuser   = Auth::user();
        $planID     = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan       = Plan::find($planID);
        $coupons_id = '';

        $order = Order::where('plan_id' , $plan->id)->where('payment_status' , 'Pending')->first();
        if($order)
        {
            return redirect()-route('plans.index')->with('error', __('You already send Payment request to this plan.'));
        }

        if($plan)
        {
            /* Check for code usage */
            $plan->discounted_price = false;
            $price                  = $plan->{$request->banktransfer_payment_frequency . '_price'};
            if( !empty($request->coupon))
            {
                $request->coupon = trim($request->coupon);
                $coupons         = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if(!empty($coupons))
                {
                    $usedCoupun             = $coupons->used_coupon();
                    $discount_value         = ($price / 100) * $coupons->discount;
                    $plan->discounted_price = $price - $discount_value;

                    if($usedCoupun >= $coupons->limit)
                    {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    $price      = $price - $discount_value;
                    $coupons_id = $coupons->id;
                }
                else
                {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            if($price)
            {
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
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
                        'price_currency' => !empty(env('CURRENCY_CODE')) ? env('CURRENCY_CODE') : 'usd',
                        'txn_id' => '',
                        'payment_type' => __('Bank Transfer'),
                        'payment_status' => 'pending',
                        'receipt' => $file,
                        'user_id' => $authuser->id,
                    ]
                );
                if($request->coupon != '')
                {
                        $coupons = Coupon::where('code',$request->coupon)->first();


                        if(!empty($coupons))
                        {
                            $userCoupon         = new UserCoupon();
                            $userCoupon->user   = $authuser->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order  = $orderID;
                            $userCoupon->save();
                            $usedCoupun = $coupons->used_coupon();
                            if($coupons->limit <= $usedCoupun)
                            {
                                $coupons->is_active = 0;
                                $coupons->save();
                            }
                        }
                    }

                return redirect()->route('plans.index')->with('success', __('Plan payment request send successfully'));
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Plan is deleted.');
        }
    }

    public function bank_transfer_show($order_id)
    {

        $admin_payments_setting = Utility::getAdminPaymentSetting();

        $order = Order::find($order_id);

        return view('plans.view', compact('order','admin_payments_setting'));
    }

    public function StatusEdit(Request $request, $order_id)
    {

        $order = Order::find($order_id);
        $order->payment_status = $request->status;
        $order->update();

        if($request->status == 'Approved')
        {
            $user =  User::where('id',$order->user_id)->first();
            $assignPlan = $user->assignPlan($order->plan_id);
        }

        return redirect()->back()->with('success', __('Plan payment successfully updated.'));

    }

    public function invoicePayWithbank(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::find($request->invoice_id);
        $validator  = \Validator::make(
            $request->all(),
            [
                'payment_receipt' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

    //storage limit
    $image_size = $request->file('payment_receipt')->getSize();

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

    // $result = Utility::updateStorageLimit($user->id, $image_size);

    // if($result == 1)
    // {
        $filenameWithExt  = $request->file('payment_receipt')->getClientOriginalName();
        $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension        = $request->file('payment_receipt')->getClientOriginalExtension();
        $fileNameToStores = $filename . '_' . time() . '.' . $extension;

        $settings = Utility::Settings();
        if($settings['storage_setting']=='local'){
            $dir  = 'payment_receipt/';
        }
        $path = Utility::upload_file($request,'payment_receipt',$fileNameToStores,$dir,[]);

        if($path['flag'] == 1)
        {
            $file = $path['url'];
        }
        else
        {
            return redirect()->back()->with('error', __($path['msg']));
        }
    // }
        $amount = $request->amount;

        if ($invoice) {
            if ($amount > $invoice->getDue())
            {
                return redirect()->back()->with('error', __('Invalid amount.'));
            }
            else
            {
                $order_id = strtoupper(str_replace('.', '', uniqid('', true)));
                $bankpayments = Banktransfer::create(
                    [
                        'invoice_id' => $invoice_id,
                        'order_id' => $order_id,
                        'amount' => $amount,
                        'status' => 'pending',
                        'receipt' => $file,
                        'date' => date('Y-m-d'),
                        'created_by' => $user->id,
                    ]
                );

                return redirect()->back();
            }
        }
    }

    public function banktransferdestory(Banktransfer $banktransfer ,$id)
    {
        $banktransfer = Banktransfer::find($id);

        if ($banktransfer)
        {
            $file =$banktransfer->receipt;
                if (\File::exists('storage/'.$file))
                {
                    \File::delete('storage/'.$file);
                }
            $banktransfer->delete();
            return redirect()->back()->with('success', __('payment Successfully Deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }
    }

    public function invoice_bank_transfer_show($bankpayment_id)
    {
        $admin_payments_setting = Utility::settingsById();

        $payment        = Banktransfer::find($bankpayment_id);
        $usr            = Auth::user();
        $plan           = Plan::find($usr->plan);
        $total_storage  = $usr->storage_limit;

        return view('invoices.view', compact('usr','plan','total_storage','payment','admin_payments_setting'));
    }

    public function invoicebankstatus(Request $request, $payment_id)
    {
        $banktransfer = Banktransfer::find($payment_id);

        if(\Auth::check())
        {
            $user=\Auth::user();
        }
        else
        {
            $user= User::where('id',$banktransfer->created_by)->first();
        }
        if($banktransfer){

            if($request->status == 'Approved')
            {
                $invoice_payment                    = new InvoicePayment();
                $invoice_payment->transaction_id    = $banktransfer->order_id;
                $invoice_payment->invoice_id        = $banktransfer->invoice_id;
                $invoice_payment->amount            = $banktransfer->amount;
                $invoice_payment->date              = date('Y-m-d');
                $invoice_payment->payment_id        = 0;
                $invoice_payment->payment_type      = 'Bank transfer';
                $invoice_payment->client_id         = $user->id;
                $invoice_payment->notes             = '';
                $invoice_payment->save();
            }
            $banktransfer->delete();
            return redirect()->back()->with('success', __('Plan payment successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }

    public function invoicebankPaymentDestroy($payment_id)
    {
        $payment_show = InvoicePayment::find($payment_id);

        if($payment_show)
        {
            $payment_show->delete();
            return redirect()->back()->with('success', __('Payment Successfully Deleted'));
        }
        else
        {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }
    }
}

