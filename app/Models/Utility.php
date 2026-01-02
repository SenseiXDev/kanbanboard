<?php

namespace App\Models;

use App\Mail\CommonEmailTemplate;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\GoogleCalendar\Event;

class Utility extends Model
{

    // get Setting
    public static function settings()
    {
        $data = DB::table('settings')->where('created_by', '=', 1)->get();

        $settings = [
            "gdpr_cookie" => "on",
            "cookie_text" => "",
            "footer_text" => "© Copyright TaskGo Saas 2023",
            "footer_link_1" => "Support",
            "footer_value_1" => "#",
            "footer_link_2" => "Terms",
            "footer_value_2" => "#",
            "footer_link_3" => "Privacy",
            "footer_value_3" => "#",
            "default_language" => "en",
            "enable_landing" => "on",
            "enable_rtl" => "off",
            "invoice_prefix" => "#INV",
            "site_date_format" => "M j, Y",
            "site_time_format" => "g:i A",
            "site_currency" => "USD",
            "site_currency_symbol" => "$",
            "site_currency_symbol_position" => "pre",
            "company_name" => "",
            "company_address" => "",
            "contract_prefix" => "#CON",
            "company_city" => "",
            "company_state" => "",
            "company_zipcode" => "",
            "company_country" => "",
            "company_telephone" => "",
            "company_email" => "",
            "company_email_from_name" => "",
            "invoice_template" => "template1",
            "invoice_color" => "ffffff",
            "invoice_logo" => "2_invoice_logo.png",
            "header_text" => "",
            "SIGNUP" => "on",
            "verification_btn" => "on",
            "color" => '#6fd943',
            "storage_setting" => "local",
            "local_storage_validation" => "jpg,png,jpeg,pdf,xls,xlsx",
            "local_storage_max_upload_size" => "2048000",
            "s3_key" => "",
            "s3_secret" => "",
            "s3_region" => "",
            "s3_bucket" => "",
            "s3_url"    => "",
            "s3_endpoint" => "",
            "s3_max_upload_size" => "",
            "s3_storage_validation" => "",
            "wasabi_key" => "",
            "wasabi_secret" => "",
            "wasabi_region" => "",
            "wasabi_bucket" => "",
            "wasabi_url" => "",
            "wasabi_root" => "",
            "wasabi_max_upload_size" => "",
            "wasabi_storage_validation" => "",
            "disable_lang" => "off",
            "Basic details" => "on",
            "Member" => "on",
            "Milestone" => "off",
            "Activity" => "off",
            "Attachment" => "on",
            "Task" => "on",
            "Tracker details" => "off",
            "Timesheet" => "off",
            "Password Protected" => "off",
            "meta_keywords" => "",
            "meta_description" => "",
            "meta_image" => "",
            "chatgpt_key" => "",
            "enable_chatgpt" => "",
            "enable_cookie" => "on",
            "necessary_cookies" => "on",
            "cookie_logging" => "on",
            "cookie_title" => "We use cookies!",
            "cookie_description" => "Hi, this website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it",
            "strictly_cookie_title" => "Strictly necessary cookies",
            "strictly_cookie_description" => "These cookies are essential for the proper functioning of my website. Without these cookies, the website would not work properly",
            "more_information_title" => "For any queries in relation to our policy on cookies and your choices, please contact us",
            "contactus_url" => "#",
        ];

        foreach ($data as $row) {

            $settings[$row->name] = $row->value;
        }
        return $settings;
    }

    public static function settingsById($user_id = null)
    {
        if (!empty($user_id)) {
            $data = DB::table('settings')->where('created_by', '=', $user_id)->get();
        } else {
            $data = DB::table('settings')->where('created_by', '=', 2)->get();
        }

        $settings = [
            "gdpr_cookie" => "",
            "cookie_text" => "",
            "footer_text" => "© Copyright TaskGo Saas 2023",
            "footer_link_1" => "Support",
            "footer_value_1" => "#",
            "footer_link_2" => "Terms",
            "footer_value_2" => "#",
            "footer_link_3" => "Privacy",
            "footer_value_3" => "#",
            "default_language" => "en",
            "default_owner_language" => "en",
            "enable_landing" => "on",
            "enable_rtl" => "off",
            "invoice_prefix" => "#INV",
            "site_date_format" => "M j, Y",
            "site_time_format" => "g:i A",
            "site_currency" => "USD",
            "site_currency_symbol" => "$",
            "site_currency_symbol_position" => "pre",
            "default_owner_language" => "en",
            "company_name" => "",
            "company_address" => "",
            "company_city" => "",
            "company_state" => "",
            "company_zipcode" => "",
            "company_country" => "",
            "company_telephone" => "",
            "company_email" => "",
            "company_email_from_name" => "",
            "invoice_template" => "template1",
            "invoice_color" => "ffffff",
            "invoice_logo" => "2_invoice_logo.png",
            'interval_time' => "",
            "telegram_accestoken" => "",
            "telegram_chatid" => "",
            "header_text" => "",
            "color" => '#6fd943',

            "storage_setting" => "local",
            "local_storage_validation" => "jpg,png,jpeg,pdf,xls,xlsx",
            "local_storage_max_upload_size" => "2048000",
            "s3_key" => "",
            "s3_secret" => "",
            "s3_region" => "",
            "s3_bucket" => "",
            "s3_url"    => "",
            "s3_endpoint" => "",
            "s3_max_upload_size" => "",
            "s3_storage_validation" => "",
            "wasabi_key" => "",
            "wasabi_secret" => "",
            "wasabi_region" => "",
            "wasabi_bucket" => "",
            "wasabi_url" => "",
            "wasabi_root" => "",
            "wasabi_max_upload_size" => "",
            "wasabi_storage_validation" => "",

            "Basic details" => "on",
            "Member" => "on",
            "Milestone" => "off",
            "Activity" => "off",
            "Attachment" => "on",
            "Task" => "on",
            "Tracker details" => "off",
            "Timesheet" => "off",
            "Password Protected" => "off",

        ];

        foreach ($data as $row) {

            $settings[$row->name] = $row->value;
        }
        return $settings;
    }
    public static function getCompanyPaymentSetting($user_id)
    {
        $data     = \DB::table('payment_settings');
        $settings = [];
        $data     = $data->where('created_by', '=', $user_id);

        $data     = $data->get();
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }

    public static function colorset()
    {
        if (\Auth::user()) {
            if (\Auth::user()->type == 'admin') {
                $user = \Auth::user();

                $setting = DB::table('settings')->where('created_by', $user->id)->pluck('value', 'name')->toArray();
            } else {
                $setting = DB::table('settings')->where('created_by', \Auth::user()->creatorId())->pluck('value', 'name')->toArray();

            }
        } else {
            $setting = DB::table('settings')->where('created_by', 1)->pluck('value', 'name')->toArray();
        }
        if (!isset($setting['color'])) {
            $setting = Utility::settings();
        }
        return $setting;
    }


    public static function getValByName($key)
    {

        $setting = self::settings();

        if (!isset($setting[$key]) || empty($setting[$key])) {
            $setting[$key] = '';
        }

        return $setting[$key];
    }

    public static function getPaymentSetting($user_id = '')
    {
        $data     = DB::table('payment_settings');
        $settings = [
            'enable_stripe' => 'off',
            'stripe_key' => '',
            'stripe_secret' => '',
            'stripe_webhook_secret' => '',
            'enable_paypal' => 'off',
            'paypal_mode' => 'sandbox',
            'paypal_client_id' => '',
            'paypal_secret_key' => '',
            'is_paystack_enabled' => 'off',
            'paystack_public_key' => '',
            'paystack_secret_key' => '',
            'is_flutterwave_enabled' => 'off',
            'flutterwave_public_key' => '',
            'flutterwave_secret_key' => '',
            'is_razorpay_enabled' => 'off',
            'razorpay_public_key' => '',
            'razorpay_secret_key' => '',
            'is_mercado_enabled' => 'off',
            'mercado_app_id' => '',
            'mercado_secret_key' => '',
            'is_paytm_enabled' => 'off',
            'paytm_mode' => 'local',
            'paytm_merchant_id' => '',
            'paytm_merchant_key' => '',
            'paytm_industry_type' => '',
            'is_mollie_enabled' => 'off',
            'mollie_api_key' => '',
            'mollie_profile_id' => '',
            'mollie_partner_id' => '',
            'is_skrill_enabled' => 'off',
            'skrill_email' => '',
            'is_coingate_enabled' => 'off',
            'coingate_mode' => 'sandbox',
            'coingate_auth_token' => '',
            'gdpr_cookie' => '',
            'cookie_text' => '',
            'is_paymentwall_enabled' => '',
            'is_toyyibpay_enabled' => '',
            'is_payfast_enabled' => '',
            'is_bank_tranfer_enabled' => '',
            'is_iyzipay_enabled' => 'off',
            'is_sspay_enabled' => 'off',
            'sspay_secret_key' => '',
            'sspay_category_code' => '',
            'is_paytab_enabled' => '',
            'paytab_profile_id' => '',
            'paytab_server_key' => '',
            'paytab_region' => '',
            'is_benefit_enabled' => '',
            'benefit_api_key' => '',
            'benefit_secret_key' => '',
            'is_cashfree_enabled' => '',
            'cashfree_api_key' => '',
            'cashfree_secret_key' => '',
            'is_paytr_enabled' => '',
            'paytr_merchant_id' => '',
            'paytr_merchant_key' => '',
            'paytr_merchant_salt' => '',

        ];

        if (Auth::check()) {
            if (!empty($user_id)) {
                $data = $data->where('created_by', '=', $user_id);
            } else {
                $data = $data->where('created_by', '=', 1);
            }
        }

        $data = $data->get();
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }

        return $settings;
    }

    // Get languages
    // public static function languages()
    // {

    //     $dir     = base_path() . '/resources/lang/';
    //     $glob    = glob($dir . "*", GLOB_ONLYDIR);
    //     $arrLang = array_map(
    //         function ($value) use ($dir) {
    //             return str_replace($dir, '', $value);
    //         },
    //         $glob
    //     );
    //     $arrLang = array_map(
    //         function ($value) use ($dir) {
    //             return preg_replace('/[0-9]+/', '', $value);
    //         },
    //         $arrLang
    //     );
    //     $arrLang = array_filter($arrLang);


    //     return $arrLang;
    // }

    public static function languages()
    {
        $languages=Utility::langList();

        if(\Schema::hasTable('languages')){
            $settings = Utility::langSetting();

            if(!empty($settings['disable_lang'])){
                $disabledlang =explode(',', $settings['disable_lang']);
                $languages = Languages::whereNotIn('code',$disabledlang)->pluck('fullname','code');
            }
            else{
                $languages = Languages::pluck('fullname','code');
            }
        }

        return $languages;
    }

    public static function langSetting(){
        $data = DB::table('settings');
        $data = $data->where('created_by', '=', 1)->get();
        if (count($data) == 0) {
            $data = DB::table('settings')->where('created_by', '=', 1)->get();
        }
        $settings= [];
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }

    public static function languagecreate()
    {
        $languages=Utility::langList();
        foreach($languages as $key => $lang)
        {
            $languageExist = Languages::where('code',$key)->first();
            if(empty($languageExist))
            {
                $language = new Languages();
                $language->code = $key;
                $language->fullname = $lang;
                $language->save();
            }
        }
    }

    public static function langList()
    {
        $languages = [
            "ar" => "Arabic",
            "zh" => "Chinese",
            "da" => "Danish",
            "de" => "German",
            "en" => "English",
            "es" => "Spanish",
            "fr" => "French",
            "he" => "Hebrew",
            "it" => "Italian",
            "ja" => "Japanese",
            "nl" => "Dutch",
            "pl" => "Polish",
            "pt" => "Portuguese",
            "ru" => "Russian",
            "tr" => "Turkish",
            "pt-br" => "Portuguese(Brazil)",

        ];
        return $languages;
    }

    public static function tax($taxes)
    {
        $taxArr = explode(',', $taxes);
        $taxes  = [];
        foreach ($taxArr as $tax) {
            $taxes[] = Tax::find($tax);
        }

        return $taxes;
    }

    // contract number formate
    public static function contractNumberFormat($number)
    {
        $settings = self::settings();
        return $settings["contract_prefix"] . sprintf("%05d", $number);
    }

    // Check File is exist and delete these
    public static function checkFileExistsnDelete(array $files)
    {
        $logo = Utility::get_file('/');

        $status = false;
        foreach ($files as $key => $file) {
            if (\File::exists($logo . $file)) {
                $status = \File::delete($logo . $file);
            }
        }

        return $status;
    }

    // Save Settings on .env file
    public static function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str     = file_get_contents($envFile);

        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $keyPosition       = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine           = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}='{$envValue}'\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
                }
            }
        }

        $str = substr($str, 0, -1);
        $str .= "\n";

        if (!file_put_contents($envFile, $str)) {
            return false;
        }

        return true;
    }


    // for invoice number format
    public static function invoiceNumberFormat($number)
    {
        return '#' . sprintf("%05d", $number);
    }

    // get project wise currency formatted amount
    public static function projectCurrencyFormat($project_id, $amount, $decimal = false)
    {
        $project = Project::find($project_id);
        if (empty($project)) {
            $project                    = new Project();
            $project->currency          = '$';
            $project->currency_position = 'pre';
        }

        if ($decimal == true) {
            $number = number_format($amount, 2);
        } else {
            $number = number_format($amount);
        }

        return (($project->currency_position == "pre") ? $project->currency : '') . $number . (($project->currency_position == "post") ? $project->currency : '');
    }

    // get progress bar color
    public static function getProgressColor($percentage)
    {
        $color = '';

        if ($percentage <= 20) {
            $color = 'danger';
        } elseif ($percentage > 20 && $percentage <= 40) {
            $color = 'warning';
        } elseif ($percentage > 40 && $percentage <= 60) {
            $color = 'info';
        } elseif ($percentage > 60 && $percentage <= 80) {
            $color = 'primary';
        } elseif ($percentage >= 80) {
            $color = 'success';
        }

        return $color;
    }

    // get date format
    public static function getDateFormated($date, $time = false)
    {

        if (!empty($date) && $date != '0000-00-00') {
            if ($time == true) {
                return date("d M Y H:i A", strtotime($date));
            } else {
                return date("d M Y", strtotime($date));
            }
        } else {
            return '';
        }
    }

    // Return timesheet sum of array
    public static function calculateTimesheetHours($times)
    {

        $minutes = 0;
        foreach ($times as $time) {
            list($hour, $minute) = explode(':', $time);
            $minutes += $hour * 60;
            $minutes += $minute;
        }
        $hours   = floor($minutes / 60);
        $minutes -= $hours * 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }

    // Return multiple time to single total hr
    public static function timeToHr($times)
    {
        $totaltime = self::calculateTimesheetHours($times);
        $timeArray = explode(':', $totaltime);
        if ($timeArray[1] <= '30') {
            $totaltime = $timeArray[0];
        }
        $totaltime = $totaltime != '00' ? $totaltime : '0';

        return $totaltime;
    }


    // Return Week first day and last day
    public static function getFirstSeventhWeekDay($week = null)
    {
        $first_day = $seventh_day = null;
        if (isset($week)) {
            $first_day   = Carbon::now()->addWeeks($week)->startOfWeek();
            $seventh_day = Carbon::now()->addWeeks($week)->endOfWeek();
        }
        $dateCollection['first_day']   = $first_day;
        $dateCollection['seventh_day'] = $seventh_day;
        $period                        = CarbonPeriod::create($first_day, $seventh_day);
        foreach ($period as $key => $dateobj) {
            $dateCollection['datePeriod'][$key] = $dateobj;
        }

        return $dateCollection;
    }


    // Return Percentage from two value
    public static function getPercentage($val1 = 0, $val2 = 0)
    {
        $percentage = 0;
        if ($val1 > 0 && $val2 > 0) {
            $percentage = intval(($val1 / $val2) * 100);
        }

        return $percentage;
    }

    // Return Last 7 Days with date & day name
    public static function getLastSevenDays()
    {
        $arrDuration   = [];
        $previous_week = strtotime("-1 week +1 day");

        for ($i = 0; $i < 7; $i++) {
            $arrDuration[date('Y-m-d', $previous_week)] = date('D', $previous_week);
            $previous_week                              = strtotime(date('Y-m-d', $previous_week) . " +1 day");
        }

        return $arrDuration;
    }

    // Common Function That used to send mail with check all cases
    public static function sendEmailTemplate($emailTemplate, $mailTo, $obj, $project_id = 0)
    {
        $usr = Auth::user();

        //Remove Current Login user Email don't send mail to them
        // unset($mailTo[$usr->id]);

        $mailTo = array_values($mailTo);

        if ($usr->type != 'admin') {
            // find template is exist or not in our record
            $template = EmailTemplate::where('name', 'LIKE', $emailTemplate)->first();

            if (isset($template) && !empty($template)) {
                // if project id not found then send mail without check
                if ($project_id == 0) {
                    $is_active            = new ProjectEmailTemplate();
                    $is_active->is_active = 1;
                } else {
                    $is_active = ProjectEmailTemplate::where('template_id', '=', $template->id)->where('project_id', '=', $project_id)->first();
                }

                // check template is active or not by project
                if ($is_active->is_active == 1) {

                    if (\Auth::user()->type == 'admin') {
                        $settings = [
                            'mail_driver'       => env('MAIL_DRIVER'),
                            'mail_host'         => env('MAIL_HOST'),
                            'mail_port'         => env('MAIL_PORT'),
                            'mail_username'     => env('MAIL_USERNAME'),
                            'mail_password'     => env('MAIL_PASSWORD'),
                            'mail_encryption'   => env('MAIL_ENCRYPTION'),
                            'mail_from_address' => env('MAIL_FROM_ADDRESS'),
                            'mail_from_name'    => env('MAIL_FROM_NAME'),
                        ];
                    } else {
                        $settings = Utility::settingsById();

                    }
                    // get email content language base
                    $content       = EmailTemplateLang::where('parent_id', '=', $template->id)->where('lang', 'LIKE', $usr->lang)->first();
                    // $content->from = $template->from;

                    if (!empty($content->content)) {
                        $content->content = self::replaceVariable($content->content, $obj);

                        // send email
                        try {
                            config([
                                'mail.driver'       => !empty($settings['mail_driver']) ? $settings['mail_driver'] : '',
                                'mail.host'         => !empty($settings['mail_host']) ? $settings['mail_host'] : '',
                                'mail.port'         => !empty($settings['mail_port']) ? $settings['mail_port'] : '',
                                'mail.username'     => !empty($settings['mail_username']) ? $settings['mail_username'] : '',
                                'mail.password'     => !empty($settings['mail_password']) ? $settings['mail_password'] : '',
                                'mail.encryption'   => !empty($settings['mail_encryption']) ? $settings['mail_encryption'] : '',
                                'mail.from.address' => !empty($settings['mail_from_address']) ? $settings['mail_from_address'] : '',
                                'mail.from.name'    => !empty($settings['mail_from_name']) ? $settings['mail_from_name'] : '',
                            ]);

                            Mail::to($mailTo)->send(new CommonEmailTemplate($content, $settings, $mailTo[0]));
                        } catch (\Exception $e) {

                            $error = __('E-Mail has been not sent due to SMTP configuration');
                        }

                        if (isset($error)) {
                            $arReturn = [
                                'is_success' => false,
                                'error' => $error,
                            ];
                        } else {
                            $arReturn = [
                                'is_success' => true,
                                'error' => false,
                            ];
                        }
                    } else {
                        $arReturn = [
                            'is_success' => false,
                            'error' => __('Mail not send, email is empty'),
                        ];
                    }

                    return $arReturn;
                } else {
                    return [
                        'is_success' => true,
                        'error' => false,
                    ];
                }
            } else {
                return [
                    'is_success' => false,
                    'error' => __('Mail not send, email not found'),
                ];
            }
        }
    }


    public static function sendUserEmailTemplate($emailTemplate, $email, $obj)
    {
        $mailTo = $email;

        $template = EmailTemplate::where('name', 'LIKE', $emailTemplate)->first();
        // check template is active or not by company
        $is_active = ProjectEmailTemplate::where('template_id', '=', $template->id)->first();

        $settings =
            [
                'mail_driver'       => env('MAIL_DRIVER'),
                'mail_host'         => env('MAIL_HOST'),
                'mail_port'         => env('MAIL_PORT'),
                'mail_username'     => env('MAIL_USERNAME'),
                'mail_password'     => env('MAIL_PASSWORD'),
                'mail_encryption'   => env('MAIL_ENCRYPTION'),
                'mail_from_address' => env('MAIL_FROM_ADDRESS'),
                'mail_from_name'    => env('MAIL_FROM_NAME'),
            ];

        // get email content language base
        $content = EmailTemplateLang::where('parent_id', '=', $template->id)->where('lang', '=','en')->first();
        $content->from = $template->from;
        if (!empty($content->content)) {
            $content->content = self::replaceVariable($content->content, $obj);

            // send email
            try {
                Mail::to($mailTo)->send(new CommonEmailTemplate($content, $settings, $mailTo));
            } catch (\Exception $e) {
                $error = __('E-Mail has been not sent due to SMTP configuration');
            }

            if (isset($error)) {
                $arReturn = [
                    'is_success' => false,
                    'error' => $error,
                ];
            } else {
                $arReturn = [
                    'is_success' => true,
                    'error' => false,
                ];
            }
        } else {
            $arReturn = [
                'is_success' => false,
                'error' => __('Mail not send, email is empty'),
            ];
        }
        return $arReturn;

    }


    // used for replace email variable (parameter 'template_name','id(get particular record by id for data)')
    public static function replaceVariable($content, $obj)
    {
        $arrVariable = [
            '{project_name}',
            '{project_status}',
            '{project_budget}',
            '{project_hours}',
            '{task_name}',
            '{task_priority}',
            '{task_project}',
            '{task_stage}',
            '{timesheet_project}',
            '{timesheet_task}',
            '{timesheet_type}',
            '{timesheet_time}',
            '{timesheet_date}',
            '{client_name}',
            '{contract_name}',
            '{contract_type}',
            '{contract_value}',
            '{start_date}',
            '{end_date}',
            '{app_name}',
            '{email}',
            '{password}',
            '{app_url}',
            '{owner_name}',
            '{invoice_id}',
            '{new_stage}',
            '{old_stage}',
            '{milestone_title}'

        ];
        $arrValue    = [
            'project_name' => '-',
            'project_status' => '-',
            'project_budget' => '-',
            'project_hours' => '-',
            'task_name' => '-',
            'task_priority' => '-',
            'task_project' => '-',
            'task_stage' => '-',
            'timesheet_project' => '-',
            'timesheet_task' => '-',
            'timesheet_type' => '-',
            'timesheet_time' => '-',
            'timesheet_date' => '-',
            'client_name' => '-',
            'contract_name' => '-',
            'contract_type' => '-',
            'contract_value' => '-',
            'start_date' => '-',
            'end_date' => '-',
            'app_name' => '-',
            'email' => '-',
            'password' => '-',
            'app_url' => '-',
            'owner_name' => '-',
            'invoice_id' => '-',
            'new_stage' => '-',
            'old_stage' => '-',
            'milestone_title' => '-',


        ];

        foreach ($obj as $key => $val) {
            $arrValue[$key] = $val;
        }

        $arrValue['app_name'] = env('APP_NAME');
        $arrValue['app_url']  = '<a href="' . env('APP_URL') . '" target="_blank">' . env('APP_URL') . '</a>';

        return str_replace($arrVariable, array_values($arrValue), $content);
    }

    // Make Entry in email_tempalte_lang table when create new language
    public static function makeEmailLang($lang)
    {
        $template = EmailTemplate::all();
        foreach ($template as $t) {
            $default_lang                 = EmailTemplateLang::where('parent_id', '=', $t->id)->where('lang', 'LIKE', 'en')->first();
            $emailTemplateLang            = new EmailTemplateLang();
            $emailTemplateLang->parent_id = $t->id;
            $emailTemplateLang->lang      = $lang;
            $emailTemplateLang->subject   = $default_lang->subject;
            $emailTemplateLang->content   = $default_lang->content;
            $emailTemplateLang->save();
        }
    }

    // Email Template Modules Function END

    // For Invoice Template
    public static function templateData()
    {
        $arr              = [];
        $arr['colors']    = [
            '003580',
            '666666',
            '6777f0',
            'f50102',
            'f9b034',
            'fbdd03',
            'c1d82f',
            '37a4e4',
            '8a7966',
            '6a737b',
            '050f2c',
            '0e3666',
            '3baeff',
            '3368e6',
            'b84592',
            'f64f81',
            'f66c5f',
            'fac168',
            '46de98',
            '40c7d0',
            'be0028',
            '2f9f45',
            '371676',
            '52325d',
            '511378',
            '0f3866',
            '48c0b6',
            '297cc0',
            'ffffff',
            '000',
        ];
        $arr['templates'] = [
            "template1" => "New York",
            "template2" => "Toronto",
            "template3" => "Rio",
            "template4" => "London",
            "template5" => "Istanbul",
            "template6" => "Mumbai",
            "template7" => "Hong Kong",
            "template8" => "Tokyo",
            "template9" => "Sydney",
            "template10" => "Paris",
        ];

        return $arr;
    }

    // get font-color code accourding to bg-color
    public static function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array(
            $r,
            $g,
            $b,
        );

        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }

    // For Font Color
    public static function getFontColor($color_code)
    {
        $rgb = self::hex2rgb($color_code);
        $R   = $G = $B = $C = $L = $color = '';

        $R = (floor($rgb[0]));
        $G = (floor($rgb[1]));
        $B = (floor($rgb[2]));

        $C = [
            $R / 255,
            $G / 255,
            $B / 255,
        ];

        for ($i = 0; $i < count($C); ++$i) {
            if ($C[$i] <= 0.03928) {
                $C[$i] = $C[$i] / 12.92;
            } else {
                $C[$i] = pow(($C[$i] + 0.055) / 1.055, 2.4);
            }
        }

        $L = 0.2126 * $C[0] + 0.7152 * $C[1] + 0.0722 * $C[2];

        if ($L > 0.179) {
            $color = 'black';
        } else {
            $color = 'white';
        }

        return $color;
    }

    // For Delete Directory
    public static function delete_directory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!self::delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    // Function not used any where just create for translate some keyword language based.
    public function extraKeyword()
    {
        [
            __('Wed'),
            __('Tue'),
            __('Mon'),
            __('Sun'),
            __('Sat'),
            __('Fri'),
            __('Thu'),
            // User Type
            __('Owner'),
            __('Client'),
            __('User'),
            __('Shared'),
            // Project Status
            __('On Hold'),
            __('In Progress'),
            __('Complete'),
            __('Canceled'),
            // Project task Status
            __('Critical'),
            __('High'),
            __('Medium'),
            __('Low'),
            // Invoice Status
            __('Not Paid'),
            __('Partialy Paid'),
            __('Paid'),
            // Activity Log
            __('Invite User'),
            __('User Assigned to the Task'),
            __('User Removed from the Task'),
            __('Upload File'),
            __('Create Milestone'),
            __('Create Task'),
            __('Move Task'),
            __('Create Expense'),
            // Others
            __('Your favorite list is empty'),
            //notification template
            __('Project Title'),
            __('Project Hours'),
            __('Project Budget'),
            __('App Url'),
            __('Task Name'),
            __('Task Priority'),
            __('Task Project'),
            __('Task Stage'),
            __('Owner Name'),
            __('Project Title'),
            __('App Name'),
            __('Invoice Name'),
            __('Owner Name'),
            __('New Stage'),
            __('old Stage'),
            __('Milestone Title'),
            __('Milestone Status'),
            __('Milestone Progress'),

        ];
    }

    // Get Messenger Migration
    public static function get_messenger_packages_migration()
    {
        $totalMigration = 0;
        $messengerPath  = glob(base_path() . '/vendor/munafio/chatify/src/database/migrations' . DIRECTORY_SEPARATOR . '*.php');
        if (!empty($messengerPath)) {
            $messengerMigration = str_replace('.php', '', $messengerPath);
            $totalMigration     = count($messengerMigration);
        }

        return $totalMigration;
    }

    public static function getAdminPaymentSetting($admin = null)
    {
        $data     = \DB::table('payment_settings');
        $settings = [];
        if (\Auth::check() || ($admin == 'construct')) {
            $user_id = 1;
            $data    = $data->where('created_by', '=', $user_id);
        }
        $data = $data->get();
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }
    public static function second_to_time($seconds = 0)
    {
        $H = floor($seconds / 3600);
        $i = ($seconds / 60) % 60;
        $s = $seconds % 60;

        $time = sprintf("%02d:%02d:%02d", $H, $i, $s);

        return $time;
    }
    public static function diffance_to_time($start, $end)
    {
        $start         = new Carbon($start);
        $end           = new Carbon($end);
        $totalDuration = $start->diffInSeconds($end);

        return $totalDuration;
    }
    public static function error_res($msg = "", $args = array())
    {
        $msg       = $msg == "" ? "error" : $msg;
        $msg_id    = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg       = $msg_id == $converted ? $msg : $converted;
        $json      = array(
            'flag' => 0,
            'msg' => $msg,
        );

        return $json;
    }

    public static function success_res($msg = "", $args = array())
    {
        $msg       = $msg == "" ? "success" : $msg;
        $msg_id    = 'success.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg       = $msg_id == $converted ? $msg : $converted;
        $json      = array(
            'flag' => 1,
            'msg' => $msg,
        );

        return $json;
    }

    public static function send_slack_msg($slug, $obj, $user)
    {
        $notification_template = NotificationTemplates::where('slug', $slug)->first();
        $user = User::where('id', $user)->first();

        if (!empty($notification_template) && !empty($obj)) {
            //     if ($user->type != 'owner') {
            //         $user = User::find($user->created_by);
            //     }
            $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', $user->lang)->where('created_by', '=', $user->id)->first();
            if (empty($curr_noti_tempLang)) {
                $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', $user->lang)->first();
            }
            if (empty($curr_noti_tempLang)) {
                $curr_noti_tempLang       = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', 'en')->first();
            }
            if (!empty($curr_noti_tempLang) && !empty($curr_noti_tempLang->content)) {
                $msg = self::replaceVariable($curr_noti_tempLang->content, $obj);
            }
        }


        if (isset($msg)) {
            $settings  = Utility::settingsById($user->id);
            try {

                if (isset($settings['slack_webhook']) && !empty($settings['slack_webhook'])) {
                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, $settings['slack_webhook']);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['text' => $msg]));

                    $headers = array();
                    $headers[] = 'Content-Type: application/json';
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error:' . curl_error($ch);
                    }
                    curl_close($ch);
                }
            } catch (\Exception $e) {
            }
        }
    }

    public static function send_telegram_msg($slug, $obj, $user)
    {
        $notification_template = NotificationTemplates::where('slug', $slug)->first();
        $user = User::where('id', $user)->first();

        if (!empty($notification_template) && !empty($obj)) {
            $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', $user->lang)->where('created_by', '=', $user->id)->first();
            if (empty($curr_noti_tempLang)) {
                $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', $user->lang)->first();
            }
            if (empty($curr_noti_tempLang)) {
                $curr_noti_tempLang       = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', 'en')->first();
            }
            if (!empty($curr_noti_tempLang) && !empty($curr_noti_tempLang->content)) {
                $msg = self::replaceVariable($curr_noti_tempLang->content, $obj);
            }
        }
        if (isset($msg)) {
            $settings  = Utility::settingsById($user->id);
            try {
                $msg = $obj;
                // Set your Bot ID and Chat ID.
                $telegrambot    = $settings['telegram_accestoken'];
                $telegramchatid = $settings['telegram_chatid'];
                // Function call with your own text or variable
                $url     = 'https://api.telegram.org/bot' . $telegrambot . '/sendMessage';
                $data    = array(
                    'chat_id' => $telegramchatid,
                    'text' => $msg,
                );
                $options = array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-Type:application/x-www-form-urlencoded\r\n",
                        'content' => http_build_query($data),
                    ),
                );
                $context = stream_context_create($options);
                $result  = file_get_contents($url, false, $context);
                $url     = $url;
            } catch (\Exception $e) {
            }
        }
    }

    ///===========================Storage Setting===========================================================================================////

    public static function upload_file($request, $key_name, $name, $path, $custom_validation = [])
    {
        try {
            $settings = Utility::settings();

            if (!empty($settings['storage_setting'])) {

                if ($settings['storage_setting'] == 'wasabi') {

                    config(
                        [
                            'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com'
                        ]
                    );

                    $max_size = !empty($settings['wasabi_max_upload_size']) ? $settings['wasabi_max_upload_size'] : '2048';
                    $mimes =  !empty($settings['wasabi_storage_validation']) ? $settings['wasabi_storage_validation'] : '';
                } else if ($settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $settings['s3_key'],
                            'filesystems.disks.s3.secret' => $settings['s3_secret'],
                            'filesystems.disks.s3.region' => $settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                            'filesystems.disks.s3.use_path_style_endpoint' => false,
                        ]
                    );
                    $max_size = !empty($settings['s3_max_upload_size']) ? $settings['s3_max_upload_size'] : '2048';
                    $mimes =  !empty($settings['s3_storage_validation']) ? $settings['s3_storage_validation'] : '';
                } else {
                    $max_size = !empty($settings['local_storage_max_upload_size']) ? $settings['local_storage_max_upload_size'] : '2048';

                    $mimes =  !empty($settings['local_storage_validation']) ? $settings['local_storage_validation'] : '';
                }


                $file = $request->$key_name;


                if (count($custom_validation) > 0) {
                    $validation = $custom_validation;
                } else {

                    $validation = [
                        'mimes:' . $mimes,
                        'max:' . $max_size,
                    ];
                }
                $validator = \Validator::make($request->all(), [
                    $key_name => $validation
                ]);

                if ($validator->fails()) {
                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];
                    return $res;
                } else {

                    $name = $name;

                    if ($settings['storage_setting'] == 'local') {

                        $request->$key_name->move(storage_path($path), $name);
                        $path = $path . $name;
                    } else if ($settings['storage_setting'] == 'wasabi') {

                        $path = \Storage::disk('wasabi')->putFileAs(
                            $path,
                            $file,
                            $name
                        );

                        // $path = $path.$name;

                    } else if ($settings['storage_setting'] == 's3') {

                        $path = \Storage::disk('s3')->putFileAs(
                            $path,
                            $file,
                            $name
                        );
                        // $path = $path.$name;

                    }


                    $res = [
                        'flag' => 1,
                        'msg'  => 'success',
                        'url'  => $path
                    ];
                    return $res;
                }
            } else {
                $res = [
                    'flag' => 0,
                    'msg' => __('Please set proper configuration for storage.'),
                ];
                return $res;
            }
        } catch (\Exception $e) {
            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }

    public static function get_file($path)
    {
        $settings = Utility::settings();

        try {
            if ($settings['storage_setting'] == 'wasabi') {
                config(
                    [
                        'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                        'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                        'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                        'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                        'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com'
                    ]
                );
            } elseif ($settings['storage_setting'] == 's3') {
                config(
                    [
                        'filesystems.disks.s3.key' => $settings['s3_key'],
                        'filesystems.disks.s3.secret' => $settings['s3_secret'],
                        'filesystems.disks.s3.region' => $settings['s3_region'],
                        'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                        'filesystems.disks.s3.use_path_style_endpoint' => false,
                    ]
                );
            } else {
            }

            return \Storage::disk($settings['storage_setting'])->url($path);
        } catch (\Throwable $th) {
            return '';
        }
    }

    public static function colorCodeData($type)
    {
        if ($type == 'event') {
            return 1;
        } elseif ($type == 'zoom_meeting') {
            return 2;
        } elseif ($type == 'task') {
            return 3;
        } elseif ($type == 'appointment') {
            return 11;
        } elseif ($type == 'rotas') {
            return 3;
        } elseif ($type == 'holiday') {
            return 4;
        } elseif ($type == 'call') {
            return 10;
        } elseif ($type == 'meeting') {
            return 5;
        } elseif ($type == 'leave') {
            return 6;
        } elseif ($type == 'work_order') {
            return 7;
        } elseif ($type == 'lead') {
            return 7;
        } elseif ($type == 'deal') {
            return 8;
        } elseif ($type == 'interview_schedule') {
            return 9;
        } else {
            return 11;
        }
    }

    public static $colorCode = [
        1 => 'event-warning',
        2 => 'event-secondary',
        3 => 'event-success',
        4 => 'event-warning',
        5 => 'event-danger',
        6 => 'event-dark',
        7 => 'event-black',
        8 => 'event-info',
        9 => 'event-secondary',
        10 => 'event-success',
        11 => 'event-warning',

    ];

    public static function googleCalendarConfig()
    {
        $setting = Utility::settingsById();
        $path = storage_path($setting['google_calender_json_file']);
        config([
            'google-calendar.default_auth_profile' => 'service_account',
            'google-calendar.auth_profiles.service_account.credentials_json' => $path,
            'google-calendar.auth_profiles.oauth.credentials_json' => $path,
            'google-calendar.auth_profiles.oauth.token_json' => $path,
            'google-calendar.calendar_id' => isset($setting['google_clender_id']) ? $setting['google_clender_id'] : '',
            'google-calendar.user_to_impersonate' => '',

        ]);
    }

    public static function addCalendarData($request, $type)
    {
        Self::googleCalendarConfig();

        $event = new Event();
        $event->name = $request->title;
        $event->startDateTime = Carbon::parse($request->start_date);
        $event->endDateTime = Carbon::parse($request->start_date);
        $event->colorId = Self::colorCodeData($type);
        $event->save();
    }

    public static function getCalendarData($type)
    {
        Self::googleCalendarConfig();

        $data = Event::get();
        $type = Self::colorCodeData($type);
        $arrMeeting = [];
        foreach ($data as $val) {
            $end_date = date_create($val->endDateTime);
            date_add($end_date, date_interval_create_from_date_string("1 days"));

            if ($val->colorId == "$type") {

                $arrMeeting[] = [
                    "id" => $val->id,
                    "title" => $val->summary,
                    "start" => $val->startDateTime,
                    "end" => date_format($end_date, "Y-m-d H:i:s"),
                    "className" => Self::$colorCode[$type],
                    "allDay" => true,
                ];
            }
        }

        return $arrMeeting;
    }

    public static function GetCacheSize()
    {

        $file_size = 0;
        foreach (\File::allFiles(storage_path('/framework')) as $file) {
            $file_size += $file->getSize();
        }
        $file_size = number_format($file_size / 1000000, 4);
        return $file_size;
    }


    public static function get_device_type($user_agent)
    {

        $mobile_regex = '/(?:phone|windows\s+phone|ipod|blackberry|(?:android|bb\d+|meego|silk|googlebot) .+? mobile|palm|windows\s+ce|opera mini|avantgo|mobilesafari|docomo)/i';
        $tablet_regex = '/(?:ipad|playbook|(?:android|bb\d+|meego|silk)(?! .+? mobile))/i';
        if (preg_match_all($mobile_regex, $user_agent)) {
            return 'mobile';
        } else {
            if (preg_match_all($tablet_regex, $user_agent)) {
                return 'tablet';
            } else {
                return 'desktop';
            }
        }
    }

    //webhook
    public static function webhookSetting($module, $user_id = null)
    {
        if (!empty($user_id)) {
            $user = User::where('id', $user_id)->first();
        } elseif (\Auth::check()) {
            $user = Auth::user();
        }

        if ($user->type != 'owner') {
            $user = User::where('id', $user->created_by)->first();
        }

        if (!empty($user)) {

            $webhook = Webhook::where('module', $module)->where('created_by', '=', $user->id)->first();

            if (!empty($webhook)) {
                $url = $webhook->url;
                $method = $webhook->method;
                $reference_url  = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                $data['method'] = $method;
                $data['reference_url'] = $reference_url;
                $data['url'] = $url;

                return $data;
            }
            return false;
        }
    }

    public static function WebhookCall($url = null, $parameter = null, $method = 'POST')
    {

        if (!empty($url) && !empty($parameter)) {
            try {
                $curlHandle = curl_init($url);
                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $parameter);
                curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, strtoupper($method));
                $curlResponse = curl_exec($curlHandle);
                curl_close($curlHandle);
                if(empty(trim($curlResponse)))
                {
                    return true;
                } else {
                    return false;
                }
            } catch (\Throwable $th) {
                return false;
            }
        } else {
            return false;
        }
    }

    // start for (plans) storage limit - for file upload size
    public static function updateStorageLimit($company_id, $image_size)
    {
        $image_size = number_format($image_size / 1048576, 2);
        $user   = User::find($company_id);
        if ($user->type != 'admin') {
            $plan   = Plan::find($user->plan);
            $total_storage = $user->storage_limit + $image_size;
            if ($plan->storage_limit <= $total_storage && $plan->storage_limit != -1) {
                $error = __('Plan storage limit is over so please upgrade the plan.');
                return $error;
            } else {
                $user->storage_limit = $total_storage;
            }
            $user->save();
        }
        return 1;
    }

    public static function changeStorageLimit($company_id, $file_path)
    {

        $files =  \File::glob(storage_path($file_path));

        $fileSize = 0;
        foreach ($files as $file) {
            $fileSize += \File::size($file);
        }

        $image_size = number_format($fileSize / 1048576, 2);
        $user   = User::find($company_id);
        $plan   = Plan::find($user->plan);

        $total_storage = $user->storage_limit - $image_size;
        $user->storage_limit = $total_storage;
        $user->save();

        $status = false;
        foreach ($files as $key => $file) {
            if (\File::exists($file)) {
                $status = \File::delete($file);
            }
        }

        return true;
    }
    // end for (plans) storage limit - for file upload size

    public static function flagOfCountry()
    {
        $arr = [
            'ar' => '🇦🇪 ar',
            'da' => '🇩🇰 da',
            'de' => '🇩🇪 de',
            'es' => '🇪🇸 es',
            'fr' => '🇫🇷 fr',
            'it' =>  '🇮🇹 it',
            'ja' => '🇯🇵 ja',
            'nl' => '🇳🇱 nl',
            'pl' => '🇵🇱 pl',
            'ru' => '🇷🇺 ru',
            'pt' => '🇵🇹 pt',
            'en' => '🇮🇳 en',
            'tr' => '🇹🇷 tr',
            'pt-br' => '🇵🇹 pt-br',
        ];
        return $arr;
    }

    public static function plancheck($user_id = null)
    {
        if (!empty($user_id)) {
            $user = User::where('id', $user_id)->first();
        } elseif (\Auth::check()) {
            $user = Auth::user();
        }
        if ($user->type != 'owner') {
            $user = User::where('id', $user->created_by)->first();
        }
        $plan_data = [
            "enable_chatgpt" => 'off',
        ];

        if ($user != null && $user->plan)
        {
            $plan = Plan::where('id', $user->plan)->first();

            $plan_data = [
                "enable_chatgpt" => $plan->enable_chatgpt,
            ];

        }
        return $plan_data;
    }

    public static function currency()
    {
        $currency = array(

            'Lek - ALL',
            'դ - AMD',
            'ƒ - ANG',
            'Kz - AOA',
            '$ - ARS',
            '$ - AUD',
            'ƒ - AWG',
            'KM - BAM',
            '$ - BBD',
            '৳ - BDT',
            'лв - BGN',
            'FBu - BIF',
            '$ - BMD',
            'BHD - BHD',
            '$ - BND',
            '$b - BOB',
            'R$ - BRL',
            '$ - BSD',
            'Nu. - BTN',
            'P - BWP',
            'p. - BYR',
            'BZ$ - BZD',
            '$ - CAD',
            'FC - CDF',
            'CHF - CHF',
            'UF - CLF',
            '$ - CLP',
            '¥ - CNY',
            '$ - COP',
            '₡ - CRC',
            '$ - CVE',
            'Fdj - DJF',
            'kr - DKK',
            'RD$ - DOP',
            'دج - DZD',
            'E£ - EGP',
            'Br - ETB',
            '€ - EUR',
            '$ - FJD',
            '£ - FKP',
            '£ - GBP',
            'ლ - GEL',
            '¢ - GHS',
            '£ - GIP',
            'D - GMD',
            'FG - GNF',
            'Q - GTQ',
            '$ - GYD',
            '$ - HKD',
            'L - HNL',
            'kn - HRK',
            'G - HTG',
            'Ft - HUF',
            'Rp - IDR',
            '₪ - ILS',
            '₹ - INR',
            '﷼ - IRR',
            'kr - ISK',
            '£ - JEP',
            'JD - JOD',
            '¥ - JPY',
            'KSh - KES',
            'лв - KGS',
            '៛ - KHR',
            'CF - KMF',
            '₨ - LKR',
            '$ - LRD',
            'L - LSL',
            'Lt - LTL',
            'Ls - LVL',
            'Ar - MGA',
            'K - MMK',
            '₮ - MNT',
            'MOP$ - MOP',
            'UM - MRO',
            '₨ - MUR',
            '.ރ - MVR',
            'MK - MWK',
            '$ - MXN',
            '$ - NAD',
            '₦ - NGN',
            'C$ - NIO',
            'kr - NOK',
            '₨ - NPR',
            '$ - NZD',
            '﷼ - OMR',
            'B/. - PAB',
            'S/. - PEN',
            'K - PGK',
            '₱ - PHP',
            '₨ - PKR',
            'Gs - PYG',
            '﷼ - QAR',
            'lei - RON',
            'Дин. - RSD',
            '₽ - RUB',
            'ر.س - RWF',
            '﷼ - SAR',
            '$ - SBD',
            '₨ - SCR',
            '£ - SDG',
            'kr - SEK',
            '$ - SGD',
            '£ - SHP',
            'Le - SLL',
            'S - SOS',
            '$ - SRD',
            'Db - STD',
            '$ - SVC',
            '£ - SYP',
            'L - SZL',
            '฿ - THB',
            'TJS - TJS',
            'm - TMT',
            'د.ت - TND',
            'T$ - TOP',
            '₤ - TRY',
            '$ - TTD',
            'NT$ - TWD',
            'TSh - TZS',
            '₴ - UAH',
            'USh - UGX',
            '$ - USD',
            '$U - UYU',
            'лв - UZS',
            'Bs - VEF',
            '₫ - VND',
            'VT - VUV',
            'WS$ - WST',
            'FCFA - XAF',
            '$ - XCD',
            'SDR - XDR',
            'FCFA - XOF',
            'F - XPF',
            '﷼ - YER',
            'R - ZAR',
            'ZK - ZMK',
            'Z$ - ZWL',
        );

        return $currency;
    }
}
