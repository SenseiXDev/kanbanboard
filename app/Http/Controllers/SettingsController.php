<?php
namespace App\Http\Controllers;
use App\Mail\TestMail;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use File;

class SettingsController extends Controller
{
    public function index()
    {
        if(Auth::user()->type == 'admin')
        {
            $timezones      = config('timezones');
            $payment_detail = Utility::getPaymentSetting();
            $settings  = Utility::settings();
            return view('users.setting', compact('timezones', 'payment_detail','settings'));
        }
        else
        {
            $details        = Auth::user()->decodeDetails();
            $payment_detail = Utility::getPaymentSetting(Auth::user()->id);
            $settings  = Utility::settingsById(Auth::user()->id);
            return view('users.owner_setting', compact('details', 'payment_detail','settings'));
        }
    }

    public function store(Request $request)
    {

        $usr = Auth::user();

        if($usr->type == 'admin')
        {
            $validate = [];

            if($request->from == 'mail')
            {
                $validate = [
                    'mail_driver' => 'required|string',
                    'mail_host' => 'required|string',
                    'mail_port' => 'required|string',
                    'mail_username' => 'required|string',
                    'mail_password' => 'required|string',
                    'mail_from_address' => 'required|string',
                    'mail_from_name' => 'required|string',
                    'mail_encryption' => 'required|string',
                ];
            }
            elseif($request->from == 'payment')
            {

                $validate = [
                    'currency' => 'required|max:3',
                    'currency_code' => 'required|string|max:5',
                ];

                if(isset($request->enable_stripe) && $request->enable_stripe == 'on')
                {
                    $validate['stripe_key']    = 'required|string';
                    $validate['stripe_secret'] = 'required|string';
                }
                if(isset($request->enable_paypal) && $request->enable_paypal == 'on')
                {
                    $validate['paypal_client_id']  = 'required|string';
                    $validate['paypal_secret_key'] = 'required|string';
                }
                if(isset($request->is_paystack_enabled) && $request->is_paystack_enabled == 'on')
                {
                    $validate['paystack_public_key'] = 'required|string';
                    $validate['paystack_secret_key'] = 'required|string';
                }
                if(isset($request->is_flutterwave_enabled) && $request->is_flutterwave_enabled == 'on')
                {
                    $validate['flutterwave_public_key'] = 'required|string';
                    $validate['flutterwave_secret_key'] = 'required|string';
                }
                if(isset($request->is_razorpay_enabled) && $request->is_razorpay_enabled == 'on')
                {
                    $validate['razorpay_public_key'] = 'required|string';
                    $validate['razorpay_secret_key'] = 'required|string';
                }
                if(isset($request->is_mercado_enabled) && $request->is_mercado_enabled == 'on')
                {
                    $validate['mercado_mode']       = 'required|string';
                    $validate['mercado_access_token']     = 'required|string';
                }
                if(isset($request->is_paytm_enabled) && $request->is_paytm_enabled == 'on')
                {
                    $validate['paytm_mode']          = 'required|string';
                    $validate['paytm_merchant_id']   = 'required|string';
                    $validate['paytm_merchant_key']  = 'required|string';
                    $validate['paytm_industry_type'] = 'required|string';
                }
                if(isset($request->is_mollie_enabled) && $request->is_mollie_enabled == 'on')
                {
                    $validate['mollie_api_key']    = 'required|string';
                    $validate['mollie_profile_id'] = 'required|string';
                    $validate['mollie_partner_id'] = 'required|string';
                }
                if(isset($request->is_skrill_enabled) && $request->is_skrill_enabled == 'on')
                {
                    $validate['skrill_email']     = 'required|email';
                }
                if(isset($request->is_coingate_enabled) && $request->is_coingate_enabled == 'on')
                {
                    $validate['coingate_mode']          = 'required|string';
                    $validate['coingate_auth_token']    = 'required|string';
                }
                if(isset($request->is_paymentwall_enabled) && $request->is_paymentwall_enabled == 'on')
                {
                    $validate['paymentwall_public_key']       = 'required|string';
                    $validate['paymentwall_private_key']      = 'required|string';
                }
                if(isset($request->is_toyyibpay_enabled) && $request->is_toyyibpay_enabled == 'on')
                {
                    $validate['toyyibpay_secret_key']      = 'required|string';
                    $validate['category_code']             = 'required|string';
                }
                if(isset($request->is_payfast_enabled) && $request->is_payfast_enabled == 'on')
                {
                    $validate['payfast_merchant_id']      = 'required|string';
                    $validate['payfast_merchant_key']     = 'required|string';
                }
                if(isset($request->is_iyzipay_enabled) && $request->is_iyzipay_enabled == 'on')
                {
                    $validate['iyzipay_public_key']      = 'required|string';
                    $validate['iyzipay_secret_key']      = 'required|string';
                }
                if(isset($request->is_sspay_enabled) && $request->is_sspay_enabled == 'on')
                {
                    $validate['sspay_category_code']    = 'required|string';
                    $validate['sspay_secret_key']       = 'required|string';
                }
                if(isset($request->is_paytab_enabled) && $request->is_paytab_enabled == 'on')
                {
                    $validate['paytab_profile_id']    = 'required|string';
                    $validate['paytab_server_key']    = 'required|string';
                    $validate['paytab_region']    = 'required|string';
                }
                if(isset($request->is_benefit_enabled) && $request->is_benefit_enabled == 'on')
                {
                    $validate['benefit_api_key']        = 'required|string';
                    $validate['benefit_secret_key']       = 'required|string';
                }
                if(isset($request->is_cashfree_enabled) && $request->is_cashfree_enabled == 'on')
                {
                    $validate['cashfree_api_key']        = 'required|string';
                    $validate['cashfree_secret_key']       = 'required|string';
                }
                if(isset($request->is_aamarpay_enabled) && $request->is_aamarpay_enabled == 'on')
                {
                    $validate['aamarpay_store_id']          = 'required|string';
                    $validate['aamarpay_signature_key']    = 'required|string';
                    $validate['aamarpay_description']     = 'required|string';
                }
                if(isset($request->is_paytr_enabled) && $request->is_paytr_enabled == 'on')
                {
                    $validate['paytr_merchant_id']          = 'required|string';
                    $validate['paytr_merchant_key']    = 'required|string';
                    $validate['paytr_merchant_salt']     = 'required|string';
                }
            }
            elseif($request->from == 'pusher')
            {
                $validate = [
                    'pusher_app_id' => 'required|string',
                    'pusher_app_key' => 'required|string',
                    'pusher_app_secret' => 'required|string',
                    'pusher_app_cluster' => 'required|string',
                ];
            }

            $validator = Validator::make(
                $request->all(), $validate
            );

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }
              $dir = 'logo/';
            if($request->favicon)
            {
               // $request->validate(['favicon' => 'required|image|mimes:png|max:2048']);
                $faviconName = 'favicon.png';
              //  $request->favicon->storeAs('logo', $faviconName);

               $validator = \Validator::make($request->all(), [
                 'favicon' => 'required|image|mimes:png',]);
                    if($validator->fails())
                    {

                    $messages = $validator->getMessageBag();

                        return redirect()->back()->with('error', "logo image must have  png file.");
                    }


                $path = Utility::upload_file($request,'favicon',$faviconName,$dir,[]);


                if($path['flag'] == 1)
                {
                    $favicon = $path['url'];
                }
                else{
                    return redirect()->back()->with('error', __($path['msg']));
                }

            }
            if($request->full_logo)
            {
               // $request->validate(['full_logo' => 'required|image|mimes:png|max:2048']);
                $logoName = 'logo.png';


                $validator = \Validator::make($request->all(), [
                 'full_logo' => 'required|image|mimes:png',

                  ]);
                       if($validator->fails())
                      {

                        $messages = $validator->getMessageBag();

                          return redirect()->back()->with('error', "logo image must have  png file.");
                      }

                $path = Utility::upload_file($request,'full_logo',$logoName,$dir,[]);


                if($path['flag'] == 1)
                {
                    $full_logo = $path['url'];

                }
                else{
                    return redirect()->back()->with('error', __($path['msg']));
                }

               // $request->full_logo->storeAs('logo', $logoName);
            }

            if($request->from == 'site_setting')
            {
                $post = $request->all();
                unset($post['_token'], $post['full_logo'], $post['favicon'], $post['from'], $post['timezone']);

                $post['header_text']    = (!isset($request->header_text) && empty($request->header_text)) ? '' : $request->header_text;
                $post['footer_text']    = (!isset($request->footer_text) && empty($request->footer_text)) ? '' : $request->footer_text;
                $post['enable_landing'] = isset($request->enable_landing) ? $request->enable_landing : 'off';
                $post['enable_rtl']     = isset($request->enable_rtl) ? $request->enable_rtl : 'off';

                $post['color'] = isset($request->color) ? $request->color :'#6fd943';
                $created_at             = date('Y-m-d H:i:s');
                $updated_at             = date('Y-m-d H:i:s');
                if(!isset($request->gdpr_cookie))
                {
                    $post['gdpr_cookie'] = 'off';

                }

                if(!isset($request->SIGNUP)){
                    $post['SIGNUP'] = 'off';
                }

                if(!isset($request->verification_btn)){
                    $post['verification_btn'] = 'off';
                }

                  if(!isset($request->cookie_text)){
                    $post['cookie_text'] = '';
                }
                // $cookie_text = (!isset($post['cookie_text']) && empty($post['cookie_text'])) ? '' : $post['cookie_text'];

                foreach($post as $key => $data)
                {
                    \DB::insert(
                        'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                          $data,
                                                                                                                                                                                                                          $key,
                                                                                                                                                                                                                          $usr->id,
                                                                                                                                                                                                                          $created_at,
                                                                                                                                                                                                                          $updated_at,
                                                                                                                                                                                                                      ]
                    );
                }

                $arrEnv             = [];
                $arrEnv['TIMEZONE'] = $request->timezone;

                //Artisan::call('config:cache');
                //Artisan::call('config:clear');

                Utility::setEnvironmentValue($arrEnv);

                return redirect()->back()->with('success', __('Basic Setting updated successfully'));
            }

            if($request->from == 'mail')
            {
                $arrEnv = [
                    'MAIL_DRIVER' => $request->mail_driver,
                    'MAIL_HOST' => $request->mail_host,
                    'MAIL_PORT' => $request->mail_port,
                    'MAIL_USERNAME' => $request->mail_username,
                    'MAIL_PASSWORD' => $request->mail_password,
                    'MAIL_ENCRYPTION' => $request->mail_encryption,
                    'MAIL_FROM_ADDRESS' => $request->mail_from_address,
                    'MAIL_FROM_NAME' => $request->mail_from_name,
                ];


                //Artisan::call('config:cache');
                //Artisan::call('config:clear');

                $env = Utility::setEnvironmentValue($arrEnv);
                if($env)
                {
                    return redirect()->back()->with('success', __('Mailer Setting updated successfully'));
                }
                else
                {
                    return redirect()->back()->with('error', __('Something is wrong'));
                }
            }

            if($request->from == 'pusher')
            {
                $arrEnv = [
                    'PUSHER_APP_ID' => $request->pusher_app_id,
                    'PUSHER_APP_KEY' => $request->pusher_app_key,
                    'PUSHER_APP_SECRET' => $request->pusher_app_secret,
                    'PUSHER_APP_CLUSTER' => $request->pusher_app_cluster,
                ];

                $env = Utility::setEnvironmentValue($arrEnv);

                if($env)
                {
                    return redirect()->back()->with('success', __('Pusher Setting updated successfully'));
                }
                else
                {
                    return redirect()->back()->with('error', __('Something is wrong'));
                }
            }

            if($request->from == 'payment')
            {
                $arrEnv = [
                    'CURRENCY' => $request->currency,
                    'CURRENCY_CODE' => $request->currency_code,
                ];

                $request->user = Auth::user()->id;

               // Artisan::call('config:cache');
                //Artisan::call('config:clear');

                Utility::setEnvironmentValue($arrEnv);

                // Save Manually Detail
                if(isset($request->is_manually_enabled) && $request->is_manually_enabled == 'on')
                {
                    $post['is_manually_enabled']   = $request->is_manually_enabled;
                }
                else
                {
                    $post['is_manually_enabled']   = 'off';
                }

                // Save Bank Transfer Detail
                if(isset($request->is_bank_tranfer_enabled) && $request->is_bank_tranfer_enabled == 'on')
                {
                    $post['is_bank_tranfer_enabled'] = $request->is_bank_tranfer_enabled;
                    $post['bank_details'] = $request->bank_details;
                }
                else
                {
                    $post['is_bank_tranfer_enabled'] = 'off';
                }

                // Save Stripe Detail
                if(isset($request->enable_stripe) && $request->enable_stripe == 'on')
                {
                    $post['enable_stripe']         = $request->enable_stripe;
                    $post['stripe_key']            = $request->stripe_key;
                    $post['stripe_secret']         = $request->stripe_secret;
                    //$post['stripe_webhook_secret'] = $request->stripe_webhook_secret;
                }
                else
                {
                    $post['enable_stripe'] = 'off';
                }

                // Save Paypal Detail
                if(isset($request->enable_paypal) && $request->enable_paypal == 'on')
                {
                    $post['enable_paypal']     = $request->enable_paypal;
                    $post['paypal_mode']       = $request->paypal_mode;
                    $post['paypal_client_id']  = $request->paypal_client_id;
                    $post['paypal_secret_key'] = $request->paypal_secret_key;
                }
                else
                {
                    $post['enable_paypal'] = 'off';
                }

                // Save Paystack Detail
                if(isset($request->is_paystack_enabled) && $request->is_paystack_enabled == 'on')
                {
                    $post['is_paystack_enabled'] = $request->is_paystack_enabled;
                    $post['paystack_public_key'] = $request->paystack_public_key;
                    $post['paystack_secret_key'] = $request->paystack_secret_key;
                }
                else
                {
                    $post['is_paystack_enabled'] = 'off';
                }

                // Save Fluuterwave Detail
                if(isset($request->is_flutterwave_enabled) && $request->is_flutterwave_enabled == 'on')
                {
                    $post['is_flutterwave_enabled'] = $request->is_flutterwave_enabled;
                    $post['flutterwave_public_key'] = $request->flutterwave_public_key;
                    $post['flutterwave_secret_key'] = $request->flutterwave_secret_key;
                }
                else
                {
                    $post['is_flutterwave_enabled'] = 'off';
                }

                // Save Razorpay Detail
                if(isset($request->is_razorpay_enabled) && $request->is_razorpay_enabled == 'on')
                {
                    $post['is_razorpay_enabled'] = $request->is_razorpay_enabled;
                    $post['razorpay_public_key'] = $request->razorpay_public_key;
                    $post['razorpay_secret_key'] = $request->razorpay_secret_key;
                }
                else
                {
                    $post['is_razorpay_enabled'] = 'off';
                }

                if(isset($request->is_mercado_enabled) && $request->is_mercado_enabled == 'on')
                {
                    $request->validate(
                        [
                            'mercado_access_token' => 'required|string',
                            ]
                        );
                        $post['is_mercado_enabled'] = $request->is_mercado_enabled;
                        $post['mercado_access_token']     = $request->mercado_access_token;
                        $post['mercado_mode'] = $request->mercado_mode;
                    }
                    else
                {
                    $post['is_mercado_enabled'] = 'off';
                }

                // Save Paytm Detail
                if(isset($request->is_paytm_enabled) && $request->is_paytm_enabled == 'on')
                {
                    $post['is_paytm_enabled']    = $request->is_paytm_enabled;
                    $post['paytm_mode']          = $request->paytm_mode;
                    $post['paytm_merchant_id']   = $request->paytm_merchant_id;
                    $post['paytm_merchant_key']  = $request->paytm_merchant_key;
                    $post['paytm_industry_type'] = $request->paytm_industry_type;
                }
                else
                {
                    $post['is_paytm_enabled'] = 'off';
                }

                // Save Mollie Detail
                if(isset($request->is_mollie_enabled) && $request->is_mollie_enabled == 'on')
                {
                    $post['is_mollie_enabled'] = $request->is_mollie_enabled;
                    $post['mollie_api_key']    = $request->mollie_api_key;
                    $post['mollie_profile_id'] = $request->mollie_profile_id;
                    $post['mollie_partner_id'] = $request->mollie_partner_id;
                }
                else
                {
                    $post['is_mollie_enabled'] = 'off';
                }

                // Save Skrill Detail
                if(isset($request->is_skrill_enabled) && $request->is_skrill_enabled == 'on')
                {
                    $post['is_skrill_enabled'] = $request->is_skrill_enabled;
                    $post['skrill_email']      = $request->skrill_email;
                }
                else
                {
                    $post['is_skrill_enabled'] = 'off';
                }

                // Save Coingate Detail
                if(isset($request->is_coingate_enabled) && $request->is_coingate_enabled == 'on')
                {
                    $post['is_coingate_enabled'] = $request->is_coingate_enabled;
                    $post['coingate_mode']       = $request->coingate_mode;
                    $post['coingate_auth_token'] = $request->coingate_auth_token;
                }
                else
                {
                    $post['is_coingate_enabled'] = 'off';
                }

                //save paymentwall Detail
                if(isset($request->is_paymentwall_enabled) && $request->is_paymentwall_enabled == 'on')
                {
                    $request->validate(
                        [
                            'paymentwall_public_key' => 'required|string',
                            'paymentwall_private_key' => 'required|string',
                            ]
                        );
                        $post['is_paymentwall_enabled'] = $request->is_paymentwall_enabled;
                        $post['paymentwall_public_key'] = $request->paymentwall_public_key;
                        $post['paymentwall_private_key'] = $request->paymentwall_private_key;
                }
                else
                {
                    $post['is_paymentwall_enabled'] = 'off';
                }

                //save toyyibpay Detail
                if(isset($request->is_toyyibpay_enabled) && $request->is_toyyibpay_enabled == 'on')
                {
                    $request->validate(
                        [
                            'toyyibpay_secret_key' => 'required|string',
                            'category_code' => 'required|string',
                            ]
                        );
                        $post['is_toyyibpay_enabled'] = $request->is_toyyibpay_enabled;
                        $post['toyyibpay_secret_key'] = $request->toyyibpay_secret_key;
                        $post['category_code'] = $request->category_code;
                }
                else
                {
                    $post['is_toyyibpay_enabled'] = 'off';
                }

                //save payfast Detail
                if(isset($request->is_payfast_enabled) && $request->is_payfast_enabled == 'on')
                {
                    $request->validate(
                        [
                            'payfast_merchant_id' => 'required|string',
                            'payfast_merchant_key' => 'required|string',
                            ]
                        );
                        $post['is_payfast_enabled'] = $request->is_payfast_enabled;
                        $post['payfast_merchant_id'] = $request->payfast_merchant_id;
                        $post['payfast_merchant_key'] = $request->payfast_merchant_key;
                        $post['payfast_signature'] = $request->payfast_signature;
                        $post['payfast_mode'] = $request->payfast_mode;
                }
                else
                {
                    $post['is_payfast_enabled'] = 'off';
                }

                //save iyzipay Detail
                if(isset($request->is_iyzipay_enabled) && $request->is_iyzipay_enabled == 'on')
                {
                    $request->validate(
                        [
                            'iyzipay_secret_key' => 'required|string',
                            'iyzipay_public_key' => 'required|string',
                            ]
                        );
                        $post['is_iyzipay_enabled'] = $request->is_iyzipay_enabled;
                        $post['iyzipay_secret_key'] = $request->iyzipay_secret_key;
                        $post['iyzipay_public_key'] = $request->iyzipay_public_key;
                        $post['iyzipay_mode'] = $request->iyzipay_mode;
                }
                else
                {
                    $post['is_iyzipay_enabled'] = 'off';
                }

                //save sspay Detail
                if(isset($request->is_sspay_enabled) && $request->is_sspay_enabled == 'on')
                {
                    $request->validate(
                    [
                        'sspay_category_code' => 'required|string',
                        'sspay_secret_key' => 'required|string',
                        ]
                    );
                    $post['is_sspay_enabled'] = $request->is_sspay_enabled;
                    $post['sspay_category_code'] = $request->sspay_category_code;
                    $post['sspay_secret_key'] = $request->sspay_secret_key;
                }
                else
                {
                    $post['is_sspay_enabled'] = 'off';
                }

                //save paytab Detail
                if(isset($request->is_paytab_enabled) && $request->is_paytab_enabled == 'on')
                {
                    $request->validate(
                    [
                        'paytab_profile_id' => 'required|string',
                        'paytab_server_key' => 'required|string',
                        'paytab_region' => 'required|string',
                        ]
                    );
                    $post['is_paytab_enabled'] = $request->is_paytab_enabled;
                    $post['paytab_profile_id'] = $request->paytab_profile_id;
                    $post['paytab_server_key'] = $request->paytab_server_key;
                    $post['paytab_region']    = $request->paytab_region;
                }
                else
                {
                    $post['is_paytab_enabled'] = 'off';
                }

                //save benefit Detail
                if(isset($request->is_benefit_enabled) && $request->is_benefit_enabled == 'on')
                {
                    $request->validate(
                    [
                        'benefit_api_key' => 'required|string',
                        'benefit_secret_key' => 'required|string',
                        ]
                    );
                    $post['is_benefit_enabled'] = $request->is_benefit_enabled;
                    $post['benefit_api_key'] = $request->benefit_api_key;
                    $post['benefit_secret_key'] = $request->benefit_secret_key;
                }
                else
                {
                    $post['is_benefit_enabled'] = 'off';
                }

                //save cashfree Detail
                if(isset($request->is_cashfree_enabled) && $request->is_cashfree_enabled == 'on')
                {
                    $request->validate(
                    [
                        'cashfree_api_key' => 'required|string',
                        'cashfree_secret_key' => 'required|string',
                        ]
                    );
                    $post['is_cashfree_enabled']    = $request->is_cashfree_enabled;
                    $post['cashfree_api_key']       = $request->cashfree_api_key;
                    $post['cashfree_secret_key']    = $request->cashfree_secret_key;
                }
                else
                {
                    $post['is_cashfree_enabled'] = 'off';
                }

                //save Aamarpay Detail
                if(isset($request->is_aamarpay_enabled) && $request->is_aamarpay_enabled == 'on')
                {
                    $request->validate(
                    [
                        'aamarpay_store_id' => 'required|string',
                        'aamarpay_signature_key' => 'required|string',
                        'aamarpay_description' => 'required|string',
                        ]
                    );
                    $post['is_aamarpay_enabled']        = $request->is_aamarpay_enabled;
                    $post['aamarpay_store_id']          = $request->aamarpay_store_id;
                    $post['aamarpay_signature_key']     = $request->aamarpay_signature_key;
                    $post['aamarpay_description']       = $request->aamarpay_description;
                }
                else
                {
                    $post['is_aamarpay_enabled'] = 'off';
                }

                //save paytr Detail
                if(isset($request->is_paytr_enabled) && $request->is_paytr_enabled == 'on')
                {
                    $request->validate(
                    [
                        'paytr_merchant_id'   => 'required|string',
                        'paytr_merchant_key'  => 'required|string',
                        'paytr_merchant_salt' => 'required|string',
                        ]
                    );
                    $post['is_paytr_enabled']        = $request->is_paytr_enabled;
                    $post['paytr_merchant_id']       = $request->paytr_merchant_id;
                    $post['paytr_merchant_key']      = $request->paytr_merchant_key;
                    $post['paytr_merchant_salt']     = $request->paytr_merchant_salt;
                }
                else
                {
                    $post['is_paytr_enabled'] = 'off';
                }

                $created_at = date('Y-m-d H:i:s');
                $updated_at = date('Y-m-d H:i:s');

                foreach($post as $key => $data)
                {
                    \DB::insert(
                        'insert into payment_settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`)', [
                            $data,
                            $key,
                            $request->user,
                            $created_at,
                            $updated_at,
                            ]
                        );
                }

                return redirect()->back()->with('success', __('Payment Setting updated successfully'));

            }
        }
        else
        {
// dd($request->all());
            $details = $usr->decodeDetails();
            if($request->from == 'invoice_setting')
            {
                 $dir = 'invoice_logo/';
                if($request->light_logo)
                {
                    $image_size = $request->file('light_logo')->getSize();
                    $file_path = $details['light_logo'];
                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                    if($result == 1)
                    {
                        Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);
                        $validator = \Validator::make($request->all(), [
                        'light_logo' => 'required|image|mimes:png',

                        ]);
                        if($validator->fails())
                        {

                        $messages = $validator->getMessageBag();

                            return redirect()->back()->with('error', "logo image must have  png file.");
                        }

                        if(!empty($details['light_logo']) && $details['light_logo'] != 'logo/logo.png')
                        {
                            Utility::checkFileExistsnDelete([$details['light_logo']]);
                        }
                        $light_logo = $usr->id . '_light' . time() . '.' . $request->light_logo->getClientOriginalExtension();

                            $path = Utility::upload_file($request,'light_logo',$light_logo,$dir,[]);

                                if($path['flag'] == 1)
                                {
                                    $light_logo = $path['url'];
                                }
                                else
                                {
                                    return redirect()->back()->with('error', __($path['msg']));
                                }
                        // $request->light_logo->storeAs('invoice_logo', $light_logo);
                        $details['light_logo'] = $light_logo;
                    }
                }
                if($request->dark_logo)
                {
                   // $request->validate(['dark_logo' => 'required|image|mimes:png']);

                   $image_size = $request->file('dark_logo')->getSize();
                   $file_path = $details['dark_logo'];
                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                    if($result == 1)
                    {
                        Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);

                      $validator = \Validator::make($request->all(), [
                       'dark_logo' => 'required|image|mimes:png',

                       ]);
                       if($validator->fails())
                      {

                        $messages = $validator->getMessageBag();

                          return redirect()->back()->with('error', "logo image must have  png file.");
                      }

                    // if(!empty($details['dark_logo']) && $details['dark_logo'] != 'logo/logo.png')
                    // {
                    //     Utility::checkFileExistsnDelete([$details['dark_logo']]);
                    // }
                    $dark_logo = $usr->id . '_dark' . time() . '.' . $request->dark_logo->getClientOriginalExtension();


                    $path = Utility::upload_file($request,'dark_logo',$dark_logo,$dir,[]);
                    if($path['flag'] == 1)
                    {
                        $dark_logo = $path['url'];
                    }
                    else{
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                    //$request->dark_logo->storeAs('invoice_logo', $dark_logo);
                    $details['dark_logo'] = $dark_logo;
                        }
                }

                $details['invoice_footer_title'] = (!empty($request->invoice_footer_title)) ? $request->invoice_footer_title : '';
                $details['invoice_footer_note']  = (!empty($request->invoice_footer_note)) ? $request->invoice_footer_note : '';

                $usr->details = json_encode($details);
                $usr->save();

                return redirect()->route('settings')->with('success', __('Invoice Setting successfully updated!'));
            }
            elseif($request->from == 'billing_setting')
            {

                $validator = Validator::make(
                    $request->all(), [
                                       'address' => 'required',
                                       'city' => 'required',
                                       'state' => 'required',
                                       'zipcode' => 'required',
                                       'country' => 'required',
                                       'telephone' => 'required',
                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('settings')->with('error', $messages->first());
                }

                $post = $request->all();
                unset($post['_token'], $post['from']);

                foreach($post as $key => $data)
                {
                    $details[$key] = $data;
                }

                $usr->details = json_encode($details);

                $usr->save();

                return redirect()->route('settings')->with('success', __('Billing Setting successfully updated!'));
            }
            elseif($request->from == 'payment')
            {
                $validate = [];

                if(isset($request->enable_stripe) && $request->enable_stripe == 'on')
                {
                    $validate['stripe_key']    = 'required|string';
                    $validate['stripe_secret'] = 'required|string';
                }
                if(isset($request->enable_paypal) && $request->enable_paypal == 'on')
                {
                    $validate['paypal_client_id']  = 'required|string';
                    $validate['paypal_secret_key'] = 'required|string';
                }
                if(isset($request->is_paystack_enabled) && $request->is_paystack_enabled == 'on')
                {
                    $validate['paystack_public_key'] = 'required|string';
                    $validate['paystack_secret_key'] = 'required|string';
                }
                if(isset($request->is_flutterwave_enabled) && $request->is_flutterwave_enabled == 'on')
                {
                    $validate['flutterwave_public_key'] = 'required|string';
                    $validate['flutterwave_secret_key'] = 'required|string';
                }
                if(isset($request->is_razorpay_enabled) && $request->is_razorpay_enabled == 'on')
                {
                    $validate['razorpay_public_key'] = 'required|string';
                    $validate['razorpay_secret_key'] = 'required|string';
                }
                if(isset($request->is_mercado_enabled) && $request->is_mercado_enabled == 'on')
                {
                    $validate['mercado_mode']       = 'required|string';
                    $validate['mercado_access_token']     = 'required|string';
                }
                if(isset($request->is_paytm_enabled) && $request->is_paytm_enabled == 'on')
                {
                    $validate['paytm_mode']          = 'required|string';
                    $validate['paytm_merchant_id']   = 'required|string';
                    $validate['paytm_merchant_key']  = 'required|string';
                    $validate['paytm_industry_type'] = 'required|string';
                }
                if(isset($request->is_mollie_enabled) && $request->is_mollie_enabled == 'on')
                {
                    $validate['mollie_api_key']    = 'required|string';
                    $validate['mollie_profile_id'] = 'required|string';
                    $validate['mollie_partner_id'] = 'required|string';
                }
                if(isset($request->is_skrill_enabled) && $request->is_skrill_enabled == 'on')
                {
                    $validate['skrill_email'] = '';
                }
                if(isset($request->is_coingate_enabled) && $request->is_coingate_enabled == 'on')
                {
                    $validate['coingate_mode']       = 'required|string';
                    $validate['coingate_auth_token'] = 'required|string';
                }
                if(isset($request->is_paymentwall_enabled) && $request->is_paymentwall_enabled == 'on')
                {
                    $validate['paymentwall_public_key']       = 'required|string';
                    $validate['paymentwall_private_key'] = 'required|string';
                }
                if(isset($request->is_toyyibpay_enabled) && $request->is_toyyibpay_enabled == 'on')
                {
                    $validate['toyyibpay_secret_key']       = 'required|string';
                    $validate['category_code'] = 'required|string';
                }
                if(isset($request->is_payfast_enabled) && $request->is_payfast_enabled == 'on')
                {
                    $validate['payfast_merchant_id']      = 'required|string';
                    $validate['payfast_merchant_key'] = 'required|string';
                }
                if(isset($request->is_iyzipay_enabled) && $request->is_iyzipay_enabled == 'on')
                {
                    $validate['iyzipay_public_key']      = 'required|string';
                    $validate['iyzipay_secret_key']   = 'required|string';

                }
                if(isset($request->is_sspay_enabled) && $request->is_sspay_enabled == 'on')
                {
                    $validate['sspay_category_code']    = 'required|string';
                    $validate['sspay_secret_key']       = 'required|string';
                }
                if(isset($request->is_paytab_enabled) && $request->is_paytab_enabled == 'on')
                {
                    $validate['paytab_profile_id']     = 'required|string';
                    $validate['paytab_server_key']     = 'required|string';
                    $validate['paytab_region']     = 'required|string';
                }

                if(isset($request->is_benefit_enabled) && $request->is_benefit_enabled == 'on')
                {
                    $validate['benefit_api_key']    = 'required|string';
                    $validate['benefit_secret_key']       = 'required|string';
                }

                if(isset($request->is_cashfree_enabled) && $request->is_cashfree_enabled == 'on')
                {
                    $validate['cashfree_api_key']        = 'required|string';
                    $validate['cashfree_secret_key']       = 'required|string';
                }

                if(isset($request->is_aamarpay_enabled) && $request->is_aamarpay_enabled == 'on')
                {
                    $validate['aamarpay_store_id']          = 'required|string';
                    $validate['aamarpay_signature_key']    = 'required|string';
                    $validate['aamarpay_description']     = 'required|string';
                }

                if(isset($request->is_paytr_enabled) && $request->is_paytr_enabled == 'on')
                {
                    $validate['paytr_merchant_id']          = 'required|string';
                    $validate['paytr_merchant_key']    = 'required|string';
                    $validate['paytr_merchant_salt']     = 'required|string';
                }

                $validator = Validator::make(
                    $request->all(), $validate
                );

                if($validator->fails())
                {
                    return redirect()->back()->with('error', $validator->errors()->first());
                }

                // Save Manually Detail
                if(isset($request->is_manually_enabled) && $request->is_manually_enabled == 'on')
                {
                    $post['is_manually_enabled']   = $request->is_manually_enabled;
                }
                else
                {
                    $post['is_manually_enabled']   = 'off';
                }

                // Save Bank Transfer Detail

                if(isset($request->is_bank_tranfer_enabled) && $request->is_bank_tranfer_enabled == 'on')
                {
                    $post['is_bank_tranfer_enabled'] = $request->is_bank_tranfer_enabled;
                    $post['bank_details'] = $request->bank_details;
                }
                else
                {
                    $post['is_bank_tranfer_enabled'] = 'off';
                }
                // Save Stripe Detail
                if(isset($request->enable_stripe) && $request->enable_stripe == 'on')
                {
                    $post['enable_stripe'] = $request->enable_stripe;
                    $post['stripe_key']    = $request->stripe_key;
                    $post['stripe_secret'] = $request->stripe_secret;
                }
                else
                {
                    $post['enable_stripe'] = 'off';
                }

                // Save Paypal Detail
                if(isset($request->enable_paypal) && $request->enable_paypal == 'on')
                {
                    $post['enable_paypal']     = $request->enable_paypal;
                    $post['paypal_mode']       = $request->paypal_mode;
                    $post['paypal_client_id']  = $request->paypal_client_id;
                    $post['paypal_secret_key'] = $request->paypal_secret_key;
                }
                else
                {
                    $post['enable_paypal'] = 'off';
                }

                // Save Paystack Detail
                if(isset($request->is_paystack_enabled) && $request->is_paystack_enabled == 'on')
                {
                    $post['is_paystack_enabled'] = $request->is_paystack_enabled;
                    $post['paystack_public_key'] = $request->paystack_public_key;
                    $post['paystack_secret_key'] = $request->paystack_secret_key;
                }
                else
                {
                    $post['is_paystack_enabled'] = 'off';
                }

                // Save Fluuterwave Detail
                if(isset($request->is_flutterwave_enabled) && $request->is_flutterwave_enabled == 'on')
                {
                    $post['is_flutterwave_enabled'] = $request->is_flutterwave_enabled;
                    $post['flutterwave_public_key'] = $request->flutterwave_public_key;
                    $post['flutterwave_secret_key'] = $request->flutterwave_secret_key;
                }
                else
                {
                    $post['is_flutterwave_enabled'] = 'off';
                }

                // Save Razorpay Detail
                if(isset($request->is_razorpay_enabled) && $request->is_razorpay_enabled == 'on')
                {
                    $post['is_razorpay_enabled'] = $request->is_razorpay_enabled;
                    $post['razorpay_public_key'] = $request->razorpay_public_key;
                    $post['razorpay_secret_key'] = $request->razorpay_secret_key;
                }
                else
                {
                    $post['is_razorpay_enabled'] = 'off';
                }

                if(isset($request->is_mercado_enabled) && $request->is_mercado_enabled == 'on')
                {
                    $request->validate(
                        [
                            'mercado_access_token' => 'required|string',
                        ]
                    );
                    $post['is_mercado_enabled'] = $request->is_mercado_enabled;
                    $post['mercado_access_token']     = $request->mercado_access_token;
                    $post['mercado_mode'] = $request->mercado_mode;
                }
                else
                {
                    $post['is_mercado_enabled'] = 'off';
                }

                // Save Paytm Detail
                if(isset($request->is_paytm_enabled) && $request->is_paytm_enabled == 'on')
                {
                    $post['is_paytm_enabled']    = $request->is_paytm_enabled;
                    $post['paytm_mode']          = $request->paytm_mode;
                    $post['paytm_merchant_id']   = $request->paytm_merchant_id;
                    $post['paytm_merchant_key']  = $request->paytm_merchant_key;
                    $post['paytm_industry_type'] = $request->paytm_industry_type;
                }
                else
                {
                    $post['is_paytm_enabled'] = 'off';
                }

                // Save Mollie Detail
                if(isset($request->is_mollie_enabled) && $request->is_mollie_enabled == 'on')
                {
                    $post['is_mollie_enabled'] = $request->is_mollie_enabled;
                    $post['mollie_api_key']    = $request->mollie_api_key;
                    $post['mollie_profile_id'] = $request->mollie_profile_id;
                    $post['mollie_partner_id'] = $request->mollie_partner_id;
                }
                else
                {
                    $post['is_mollie_enabled'] = 'off';
                }

                // Save Skrill Detail
                if(isset($request->is_skrill_enabled) && $request->is_skrill_enabled == 'on')
                {
                    $post['is_skrill_enabled'] = $request->is_skrill_enabled;
                    $post['skrill_email']      = $request->skrill_email;
                }
                else
                {
                    $post['is_skrill_enabled'] = 'off';
                }

                // Save Coingate Detail
                if(isset($request->is_coingate_enabled) && $request->is_coingate_enabled == 'on')
                {
                    $post['is_coingate_enabled'] = $request->is_coingate_enabled;
                    $post['coingate_mode']       = $request->coingate_mode;
                    $post['coingate_auth_token'] = $request->coingate_auth_token;
                }
                else
                {
                    $post['is_coingate_enabled'] = 'off';
                }

                // Save Paymentwall Detail
                if(isset($request->is_paymentwall_enabled) && $request->is_paymentwall_enabled == 'on')
                {
                    $request->validate(
                        [
                            'paymentwall_public_key' => 'required|string',
                            'paymentwall_private_key' => 'required|string',
                        ]
                    );
                    $post['is_paymentwall_enabled'] = $request->is_paymentwall_enabled;
                    $post['paymentwall_public_key'] = $request->paymentwall_public_key;
                    $post['paymentwall_private_key'] = $request->paymentwall_private_key;
                }
                else
                {
                    $post['is_paymentwall_enabled'] = 'off';
                }

                //save toyyibpay Detail
                if(isset($request->is_toyyibpay_enabled) && $request->is_toyyibpay_enabled == 'on')
                {
                    $post['is_toyyibpay_enabled'] = $request->is_toyyibpay_enabled;
                    $post['toyyibpay_secret_key'] = $request->toyyibpay_secret_key;
                    $post['category_code'] = $request->category_code;
                }
                else
                {
                    $post['is_toyyibpay_enabled'] = 'off';
                }

                //save payfast Detail
                if(isset($request->is_payfast_enabled) && $request->is_payfast_enabled == 'on')
                {
                        $post['is_payfast_enabled'] = $request->is_payfast_enabled;
                        $post['payfast_merchant_id'] = $request->payfast_merchant_id;
                        $post['payfast_merchant_key'] = $request->payfast_merchant_key;
                        $post['payfast_signature'] = $request->payfast_signature;
                        $post['payfast_mode'] = $request->payfast_mode;
                }
                else
                {
                    $post['is_payfast_enabled'] = 'off';
                }

                //save iyzipay Detail
                if(isset($request->is_iyzipay_enabled) && $request->is_iyzipay_enabled == 'on')
                {
                        $post['is_iyzipay_enabled'] = $request->is_iyzipay_enabled;
                        $post['iyzipay_secret_key'] = $request->iyzipay_secret_key;
                        $post['iyzipay_public_key'] = $request->iyzipay_public_key;
                        $post['iyzipay_mode'] = $request->iyzipay_mode;
                }

                else
                {
                $post['is_iyzipay_enabled'] = 'off';
                }

                //save sspay Detail
                if(isset($request->is_sspay_enabled) && $request->is_sspay_enabled == 'on')
                {
                    $request->validate(
                    [
                        'sspay_category_code' => 'required|string',
                        'sspay_secret_key' => 'required|string',
                        ]
                    );
                    $post['is_sspay_enabled'] = $request->is_sspay_enabled;
                    $post['sspay_category_code'] = $request->sspay_category_code;
                    $post['sspay_secret_key'] = $request->sspay_secret_key;
                }
                else
                {
                    $post['is_sspay_enabled'] = 'off';
                }

                //save paytab Detail
                if(isset($request->is_paytab_enabled) && $request->is_paytab_enabled == 'on')
                {
                    $request->validate(
                    [
                        'paytab_profile_id' => 'required|string',
                        'paytab_server_key' => 'required|string',
                        'paytab_region' => 'required|string',
                    ]);
                    $post['is_paytab_enabled'] = $request->is_paytab_enabled;
                    $post['paytab_profile_id'] = $request->paytab_profile_id;
                    $post['paytab_server_key'] = $request->paytab_server_key;
                    $post['paytab_region']     = $request->paytab_region;
                }
                else
                {
                    $post['is_paytab_enabled'] = 'off';
                }

                //save benefit Detail
                if(isset($request->is_benefit_enabled) && $request->is_benefit_enabled == 'on')
                {
                    $request->validate(
                    [
                        'benefit_api_key' => 'required|string',
                        'benefit_secret_key' => 'required|string',
                    ]);
                    $post['is_benefit_enabled'] = $request->is_benefit_enabled;
                    $post['benefit_api_key'] = $request->benefit_api_key;
                    $post['benefit_secret_key'] = $request->benefit_secret_key;
                }
                else
                {
                    $post['is_benefit_enabled'] = 'off';
                }

                //save cashfree Detail
                if(isset($request->is_cashfree_enabled) && $request->is_cashfree_enabled == 'on')
                {
                    $request->validate(
                    [
                        'cashfree_api_key' => 'required|string',
                        'cashfree_secret_key' => 'required|string',
                        ]
                    );
                    $post['is_cashfree_enabled']    = $request->is_cashfree_enabled;
                    $post['cashfree_api_key']       = $request->cashfree_api_key;
                    $post['cashfree_secret_key']    = $request->cashfree_secret_key;
                }
                else
                {
                    $post['is_cashfree_enabled'] = 'off';
                }

                //save aamarpay Detail
                if(isset($request->is_aamarpay_enabled) && $request->is_aamarpay_enabled == 'on')
                {
                    $request->validate(
                    [
                        'aamarpay_store_id' => 'required|string',
                        'aamarpay_signature_key' => 'required|string',
                        'aamarpay_description' => 'required|string',
                        ]
                    );
                    $post['is_aamarpay_enabled']        = $request->is_aamarpay_enabled;
                    $post['aamarpay_store_id']          = $request->aamarpay_store_id;
                    $post['aamarpay_signature_key']     = $request->aamarpay_signature_key;
                    $post['aamarpay_description']       = $request->aamarpay_description;
                }
                else
                {
                    $post['is_aamarpay_enabled'] = 'off';
                }

                //save paytr Detail
                if(isset($request->is_paytr_enabled) && $request->is_paytr_enabled == 'on')
                {
                    $request->validate(
                    [
                        'paytr_merchant_id'   => 'required|string',
                        'paytr_merchant_key'  => 'required|string',
                        'paytr_merchant_salt' => 'required|string',
                        ]
                    );
                    $post['is_paytr_enabled']        = $request->is_paytr_enabled;
                    $post['paytr_merchant_id']       = $request->paytr_merchant_id;
                    $post['paytr_merchant_key']      = $request->paytr_merchant_key;
                    $post['paytr_merchant_salt']     = $request->paytr_merchant_salt;
                }
                else
                {
                    $post['is_paytr_enabled'] = 'off';
                }

                $created_at = date('Y-m-d H:i:s');
                $updated_at = date('Y-m-d H:i:s');

                foreach($post as $key => $data)
                {
                    \DB::insert(
                        'insert into payment_settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`)', [
                                                                                                                                                                                                                                 $data,
                                                                                                                                                                                                                                 $key,
                                                                                                                                                                                                                                 Auth::user()->id,
                                                                                                                                                                                                                                 $created_at,
                                                                                                                                                                                                                                 $updated_at,
                                                                                                                                                                                                                             ]
                    );
                }

                return redirect()->route('settings')->with('success', __('Payment Setting successfully updated!'));
            }
            elseif($request->from == 'tracker')
            {
                $validator = Validator::make(
                    $request->all(), [
                        'interval_time' => 'required|numeric',
                    ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('settings')->with('error', $messages->first());
                }

                $post = $request->all();
                unset($post['_token'], $post['from']);

                foreach($post as $key => $data)
                {
                    $details[$key] = $data;
                }

                $usr->details = json_encode($details);
                $usr->save();
                return redirect()->route('settings')->with('success', __('Tracker Setting successfully updated!'));
            }
            elseif($request->from == 'mail')
            {

                $post['mail_driver'] =  $request->mail_driver;
                $post['mail_host'] =   $request->mail_host;
                $post['mail_port'] =   $request->mail_port;
                $post['mail_username'] =   $request->mail_username;
                $post['mail_password'] =   $request->mail_password;
                $post['mail_encryption'] =   $request->mail_encryption;
                $post['mail_from_address'] =   $request->mail_from_address;
                $post['mail_from_name'] =   $request->mail_from_name;

                $created_at             = date('Y-m-d H:i:s');
                $updated_at             = date('Y-m-d H:i:s');

                foreach($post as $key => $data)
                {
                    \DB::insert(
                        'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                            $data,
                                                                                                                                                                                                                            $key,
                                                                                                                                                                                                                            $usr->id,
                                                                                                                                                                                                                            $created_at,
                                                                                                                                                                                                                            $updated_at,
                                                                                                                                                                                                                        ]
                    );
                }

                return redirect()->back()->with('success', __('Basic Setting updated successfully'));
            }
            elseif($request->from == 'site_setting')
            {
                $post = $request->all();
                unset($post['_token'], $post['full_logo'], $post['favicon'], $post['from'], $post['timezone']);

                $post['color'] = isset($request->color) ? $request->color :'#6fd943';
                $post['enable_rtl']     = isset($request->enable_rtl) ? $request->enable_rtl : 'off';
                $post['default_owner_language']     = isset($request->default_owner_language) ? $request->default_owner_language : 'en';

                $created_at             = date('Y-m-d H:i:s');
                $updated_at             = date('Y-m-d H:i:s');


                foreach($post as $key => $data)
                {
                    \DB::insert(
                        'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                          $data,
                                                                                                                                                                                                                          $key,
                                                                                                                                                                                                                          $usr->id,
                                                                                                                                                                                                                          $created_at,
                                                                                                                                                                                                                          $updated_at,
                                                                                                                                                                                                                      ]
                    );
                }


                // $arrEnv             = [];
                Artisan::call('config:cache');
                Artisan::call('config:clear');

                // Utility::setEnvironmentValue($arrEnv);

                 return redirect()->back()->with('success', __('Basic Setting updated successfully'));
            }
        }
    }

    public function testEmail(Request $request)
    {
        $user = Auth::user();
        if(($user->type == 'admin') || (Auth::user()->type == 'owner'))
        {
            $data                      = [];
            $data['mail_driver']       = $request->mail_driver;
            $data['mail_host']         = $request->mail_host;
            $data['mail_port']         = $request->mail_port;
            $data['mail_username']     = $request->mail_username;
            $data['mail_password']     = $request->mail_password;
            $data['mail_encryption']   = $request->mail_encryption;
            $data['mail_from_address'] = $request->mail_from_address;
            $data['mail_from_name']    = $request->mail_from_name;

            return view('users.test_email', compact('data'));
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function testEmailSend(Request $request)
    {
        if((Auth::user()->type == 'admin') || (Auth::user()->type == 'owner'))
        {
            $validator = Validator::make(
                $request->all(), [
                                   'email' => 'required|email',
                                   'mail_driver' => 'required',
                                   'mail_host' => 'required',
                                   'mail_port' => 'required',
                                   'mail_username' => 'required',
                                   'mail_password' => 'required',
                                   'mail_from_address' => 'required',
                                   'mail_from_name' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            try
            {
                config(
                    [
                        'mail.driver' => $request->mail_driver,
                        'mail.host' => $request->mail_host,
                        'mail.port' => $request->mail_port,
                        'mail.encryption' => $request->mail_encryption,
                        'mail.username' => $request->mail_username,
                        'mail.password' => $request->mail_password,
                        // 'mail.from.address' => $request->mail_username,
                        // 'mail.from.name' => config('name'),
                        'mail.from.address' => $request->mail_from_address,
                        'mail.from.name' => $request->mail_from_name,
                    ]
                );
                Mail::to($request->email)->send(new TestMail());
            }
            catch(\Exception $e)
            {
                return response()->json(
                    [
                        'is_success' => false,
                        'message' => $e->getMessage(),
                    ]
                );
                //            return redirect()->back()->with('error', 'Something is Wrong.');
            }

            return response()->json(
                [
                    'is_success' => true,
                    'message' => __('Email send Successfully'),
                ]
            );
        }
        else
        {
            return response()->json(
                [
                    'is_success' => false,
                    'message' => __('Permission Denied.'),
                ]
            );
        }
    }

    public function saveZoomSettings(Request $request)
    {
        $post = $request->all();

        unset($post['_token']);
        $created_by = \Auth::user()->creatorId();

        foreach($post as $key => $data)
        {
            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                                                $data,
                                                                                                                                                                                $key,
                                                                                                                                                                                $created_by,
                                                                                                                                                                                date('Y-m-d H:i:s'),
                                                                                                                                                                                date('Y-m-d H:i:s'),
                                                                                                                                                                            ]
            );
        }
        return redirect()->back()->with('success', __('Setting added successfully saved.'));
    }

    public function slack(Request $request){
        $post = [];
        $post['slack_webhook'] = $request->input('slack_webhook');
        $post['is_project_enabled'] = $request->has('is_project_enabled')?$request->input('is_project_enabled'):0;
        $post['task_notification'] = $request->has('task_notification')?$request->input('task_notification'):0;
        $post['invoice_notificaation'] = $request->has('invoice_notificaation')?$request->input('invoice_notificaation'):0;
        $post['task_move_notificaation'] = $request->has('task_move_notificaation')?$request->input('task_move_notificaation'):0;
        $post['mileston_notificaation'] = $request->has('mileston_notificaation')?$request->input('mileston_notificaation'):0;
        $post['milestone_status_notificaation'] = $request->has('milestone_status_notificaation')?$request->input('milestone_status_notificaation'):0;
        $post['invoice_status_notificaation'] = $request->has('invoice_status_notificaation')?$request->input('invoice_status_notificaation'):0;

        if(isset($post) && !empty($post) && count($post) > 0)
        {
            $created_at = $updated_at = date('Y-m-d H:i:s');

            foreach($post as $key => $data)
            {
                DB::insert(
                    'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                      $data,
                                                                                                                                                                                                                      $key,
                                                                                                                                                                                                                      Auth::user()->id,
                                                                                                                                                                                                                      $created_at,
                                                                                                                                                                                                                      $updated_at,
                                                                                                                                                                                                                  ]
                );
            }
        }

        return redirect()->back()->with('success', __('Settings updated successfully.'));
    }
    public function telegram(Request $request){

        $validator = Validator::make(
        $request->all(), [
                           'telegram_accestoken' => 'required',
                           'telegram_chatid' => 'required',
                       ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->route('settings')->with('error', $messages->first());
        }


        $post = [];
        $post['telegram_accestoken'] = $request->input('telegram_accestoken');
        $post['telegram_chatid'] = $request->input('telegram_chatid');
        $post['telegram_is_project_enabled'] = $request->has('telegram_is_project_enabled')?$request->input('telegram_is_project_enabled'):0;
        $post['telegram_task_notification'] = $request->has('telegram_task_notification')?$request->input('telegram_task_notification'):0;
        $post['telegram_invoice_notificaation'] = $request->has('telegram_invoice_notificaation')?$request->input('telegram_invoice_notificaation'):0;
        $post['telegram_task_move_notificaation'] = $request->has('telegram_task_move_notificaation')?$request->input('telegram_task_move_notificaation'):0;
        $post['telegram_mileston_notificaation'] = $request->has('telegram_mileston_notificaation')?$request->input('telegram_mileston_notificaation'):0;
        $post['telegram_milestone_status_notificaation'] = $request->has('telegram_milestone_status_notificaation')?$request->input('telegram_milestone_status_notificaation'):0;
        $post['telegram_invoice_status_notificaation'] = $request->has('telegram_invoice_status_notificaation')?$request->input('telegram_invoice_status_notificaation'):0;

        if(isset($post) && !empty($post) && count($post) > 0)
        {
            $created_at = date('Y-m-d H:i:s');

            $updated_at = date('Y-m-d H:i:s');

            foreach($post as $key => $data)
            {
                DB::insert(
                    'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                      $data,
                                                                                                                                                                                                                      $key,
                                                                                                                                                                                                                      Auth::user()->id,
                                                                                                                                                                                                                      $created_at,
                                                                                                                                                                                                                      $updated_at,
                                                                                                                                                                                                                  ]
                );
            }
        }

        return redirect()->back()->with('success', __('Settings updated successfully.'));
    }


    public function saveGoogleCalenderSettings(Request $request)
    {

        if(isset($request->is_enabled) && $request->is_enabled == 'on'){
            $validator = \Validator::make(
                $request->all(),
                [
                    'google_clender_id' => 'required',
                    'google_calender_json_file' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_enabled'] = $request->is_enabled;
        }else{
            $post['is_enabled'] = 'off';
        }


        if ($request->google_calender_json_file) {
            $dir = storage_path(). '/' .md5(time());
            if (!is_dir($dir)) {
                File::makeDirectory($dir, $mode = 0777, true, true);
            }

            $filename = $request->google_calender_json_file->getClientOriginalName();
            $file_path =  md5(time()) ."/".md5(time()).  "." . $request->google_calender_json_file->getClientOriginalExtension();

            $file = $request->file('google_calender_json_file');
            $file->move($dir, $file_path);
            $post['google_calender_json_file'] = $file_path;
        }

        if($request->google_clender_id){

            $post['google_clender_id'] = $request->google_clender_id;

            foreach($post as $key =>$data){

                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?    ) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                    $data,
                    $key,
                    \Auth::user()->id,
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s'),
                    ]
                );
            }
        }
        return redirect()->back()->with('success', __('Setting successfully saved!'));
    }
    public function recaptchaSettingStore(Request $request)
    {
        $user = \Auth::user();
        $rules = [];
        if($request->recaptcha_module == 'yes')
        {
            $rules['google_recaptcha_key'] = 'required|string|max:50';
            $rules['google_recaptcha_secret'] = 'required|string|max:50';
        }
        $validator = \Validator::make(
            $request->all(), $rules
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $arrEnv = [
            'RECAPTCHA_MODULE' => $request->recaptcha_module ?? 'no',
            'NOCAPTCHA_SITEKEY' => $request->google_recaptcha_key,
            'NOCAPTCHA_SECRET' => $request->google_recaptcha_secret,
        ];
        if(Utility::setEnvironmentValue($arrEnv))
        {
            return redirect()->back()->with('success', __('Recaptcha Settings updated successfully'));
        }
        else
        {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }


    public function storageSettingStore(Request $request)
    {

        $user = Auth::user();
        if(isset($request->storage_setting) && $request->storage_setting == 'local')
        {
            $request->validate(
                [

                    'local_storage_validation' => 'required',
                    'local_storage_max_upload_size' => 'required',
                ]
            );

            $post['storage_setting'] = $request->storage_setting;
            $local_storage_validation = implode(',', $request->local_storage_validation);
            $post['local_storage_validation'] = $local_storage_validation;
            $post['local_storage_max_upload_size'] = $request->local_storage_max_upload_size;

        }

        if(isset($request->storage_setting) && $request->storage_setting == 's3')
        {
            $request->validate(
                [
                    's3_key'                  => 'required',
                    's3_secret'               => 'required',
                    's3_region'               => 'required',
                    's3_bucket'               => 'required',
                    's3_url'                  => 'required',
                    's3_endpoint'             => 'required',
                    's3_max_upload_size'      => 'required',
                    's3_storage_validation'   => 'required',
                ]
            );
            $post['storage_setting']            = $request->storage_setting;
            $post['s3_key']                     = $request->s3_key;
            $post['s3_secret']                  = $request->s3_secret;
            $post['s3_region']                  = $request->s3_region;
            $post['s3_bucket']                  = $request->s3_bucket;
            $post['s3_url']                     = $request->s3_url;
            $post['s3_endpoint']                = $request->s3_endpoint;
            $post['s3_max_upload_size']         = $request->s3_max_upload_size;
            $s3_storage_validation              = implode(',', $request->s3_storage_validation);
            $post['s3_storage_validation']      = $s3_storage_validation;
        }

        if(isset($request->storage_setting) && $request->storage_setting == 'wasabi')
        {
            $request->validate(
                [
                    'wasabi_key'                    => 'required',
                    'wasabi_secret'                 => 'required',
                    'wasabi_region'                 => 'required',
                    'wasabi_bucket'                 => 'required',
                    'wasabi_url'                    => 'required',
                    'wasabi_root'                   => 'required',
                    'wasabi_max_upload_size'        => 'required',
                    'wasabi_storage_validation'     => 'required',
                ]
            );
            $post['storage_setting']            = $request->storage_setting;
            $post['wasabi_key']                 = $request->wasabi_key;
            $post['wasabi_secret']              = $request->wasabi_secret;
            $post['wasabi_region']              = $request->wasabi_region;
            $post['wasabi_bucket']              = $request->wasabi_bucket;
            $post['wasabi_url']                 = $request->wasabi_url;
            $post['wasabi_root']                = $request->wasabi_root;
            $post['wasabi_max_upload_size']     = $request->wasabi_max_upload_size;
            $wasabi_storage_validation          = implode(',', $request->wasabi_storage_validation);
            $post['wasabi_storage_validation']  = $wasabi_storage_validation;
        }

        foreach($post as $key => $data)
        {
            $arr = [
                $data,
                $key,
                $user->id,
            ];

            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', $arr
            );
        }

        return redirect()->back()->with('success', 'Storage setting successfully updated.');

    }

    public function seosetting(Request $request)
    {
            $validator = \Validator::make(
                $request->all(),
                [
                    'meta_keywords' => 'required',
                    'meta_description' => 'required',
                    // 'meta_image' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

        if ($request->meta_image)
        {
            $img_name = time() . '_' . 'meta_image.png';
            $dir = 'uploads/logo/';
            $validation = [

                'max:' . '20480',
            ];

            $path = Utility::upload_file($request, 'meta_image', $img_name, $dir, $validation);
            if ($path['flag'] == 1) {
                $logo_dark = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }

            $post['meta_image']  = $img_name;

        }
        $post['meta_keywords']            = $request->meta_keywords;
        $post['meta_description']            = $request->meta_description;

        foreach ($post as $key => $data) {
            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                [
                    $data,
                    $key,
                    \Auth::user()->id,
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s'),
                ]
            );
        }

        return redirect()->back()->with('success', 'SEO setting successfully updated.');
    }

    public function saveCookieSettings(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'cookie_title' => 'required',
                'cookie_description' => 'required',
                'strictly_cookie_title' => 'required',
                'strictly_cookie_description' => 'required',
                'more_information_title' => 'required',
                'contactus_url' => 'required',
            ]
        );

        $post = $request->all();

        unset($post['_token']);

        if ($request->enable_cookie)
        {
            $post['enable_cookie'] = 'on';
        }
        else{
            $post['enable_cookie'] = 'off';
        }

        if ($request->cookie_logging)
        {
            $post['cookie_logging'] = 'on';
        }
        else{

            $post['cookie_logging'] = "off";
        }

        $post['cookie_title']            = $request->cookie_title;
        $post['cookie_description']            = $request->cookie_description;
        $post['strictly_cookie_title']            = $request->strictly_cookie_title;
        $post['strictly_cookie_description']            = $request->strictly_cookie_description;
        $post['more_information_title']            = $request->more_information_title;
        $post['contactus_url']            = $request->contactus_url;
        $id= \Auth::user()->creatorId();

        $settings = Utility::settings();

        foreach ($post as $key => $data) {
            if (in_array($key, array_keys($settings)))
            {
                if(!empty($data))
                {
                    \DB::insert(
                       'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                           $data,
                           $key,
                           \Auth::user()->creatorId(),
                           date('Y-m-d H:i:s'),
                           date('Y-m-d H:i:s'),
                       ]
                   );
                }
            }
        }
        return redirect()->back()->with('success', 'Cookie setting successfully saved.');
    }

    public function CookieConsent(Request $request)
    {
        $settings = Utility::settings();
        if ($settings['enable_cookie'] == "on" && $settings['cookie_logging'] == "on") {
            $allowed_levels = ['necessary', 'analytics', 'targeting'];
            $levels = array_filter($request['cookie'], function ($level) use ($allowed_levels) {
                return in_array($level, $allowed_levels);
            });

            try {
                $ip = $_SERVER['REMOTE_ADDR'];
                // $ip = '49.36.83.154';
                $whichbrowser = new \WhichBrowser\Parser($ip);
                // Generate new CSV line
                $browser_name = $whichbrowser->browser->name ?? null;
                $os_name = $whichbrowser->os->name ?? null;
                $browser_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
                $device_type = Utility::get_device_type($_SERVER['HTTP_USER_AGENT']);


                $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));


                $date = (new \DateTime())->format('Y-m-d');
                $time = (new \DateTime())->format('H:i:s') . ' UTC';


                $new_line = implode(',', [
                    $ip, $date, $time, json_encode($request['cookie']), $device_type, $browser_language, $browser_name, $os_name,
                    isset($query) ? $query['country'] : '', isset($query) ? $query['region'] : '', isset($query) ? $query['regionName'] : '', isset($query) ? $query['city'] : '', isset($query) ? $query['zip'] : '', isset($query) ? $query['lat'] : '', isset($query) ? $query['lon'] : ''
                ]);

                if (!file_exists(storage_path() . '/uploads/sample/data.csv')) {

                    $first_line = 'IP,Date,Time,Accepted cookies,Device type,Browser language,Browser name,OS Name,Country,Region,RegionName,City,Zipcode,Lat,Lon';
                    file_put_contents(storage_path() . '/uploads/sample/data.csv', $first_line . PHP_EOL, FILE_APPEND | LOCK_EX);
                }
                file_put_contents(storage_path() . '/uploads/sample/data.csv', $new_line . PHP_EOL, FILE_APPEND | LOCK_EX);
            }
            catch (\Throwable $th)
            {

            }
            return response()->json('success');
        }
        return response()->json('error');
    }

    public function chatgptkey(Request $request)
    {

        if (\Auth::user()->type == 'admin') {
            $user = \Auth::user();
            if (!empty($request->chatgpt_key)) {
                $post = $request->all();
                $post['chatgpt_key'] = $request->chatgpt_key;

                unset($post['_token']);
                foreach ($post as $key => $data) {
                    $settings = Utility::settings();


                    if (in_array($key, array_keys($settings))) {
                        \DB::insert(
                            'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?    ) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                $data,
                                $key,
                                $user->id,
                                date('Y-m-d H:i:s'),
                                date('Y-m-d H:i:s'),
                            ]
                        );
                    }
                }
            }
            return redirect()->back()->with('success', __('Chatgptkey successfully saved.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
