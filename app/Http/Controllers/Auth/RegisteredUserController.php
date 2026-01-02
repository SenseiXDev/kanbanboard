<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserContact;
use App\Models\Plan;
use App\Models\Utility;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function create()
    {
        if (Utility::getValByName('SIGNUP') == 'on') {
            return view('auth.register');
        } else {
            return abort('404', 'Page Not Found');
        }
    }


    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (env('RECAPTCHA_MODULE') == 'on') {
            $validation['g-recaptcha-response'] = 'required|captcha';
        } else {
            $validation = [];
        }
        $this->validate($request, $validation);


        $default_language = Utility::getValByName('default_language');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            // 'email_verified_at' => $date,
            'password' => Hash::make($request->password),
            'type' => 'owner',
            'lang' => !empty($default_language) ? $default_language : 'en',
            // 'plan' => Plan::first()->id,
            'created_by' => 1,
        ]);
        $user->assignPlan(1);

        $userrole = 'owner';
        $UserContact = UserContact::create([
            'parent_id' => 1,
            'user_id' => $user->id,
            'role' => $userrole,
        ]);

        $settings  = Utility::settings();
        Auth::login($user);

        if ($settings['verification_btn'] == 'on') {
            try {

                event(new Registered($user));

                if (empty($lang)) {
                    $lang = env('default_language');
                }
                \App::setLocale($lang);
            } catch (\Exception $e) {

                $user->delete();

                return redirect('/register/lang')->with('status', __('Email SMTP settings does not configure so please contact to your site admin.'));
            }

            return view('auth.verify', compact('lang'));
        } else {


            $uArr = [
                'email' => $user->email,
                'password' => $user->password,
            ];

            // send email
            $resp = Utility::sendUserEmailTemplate('User Invited', $user->email, $uArr);

            $user->email_verified_at = date('h:i:s');
            $user->save();
            return redirect(RouteServiceProvider::HOME);
        }

        //     try{

        //         event(new Registered($user));
        //         Auth::login($user);
        //         $user->userDefaultData();

        //     }catch(\Exception $e){

        //         $user->delete();
        //         // $UserContact->delete();
        //         return redirect('/register/lang?')->with('status', __('Email SMTP settings does not configure so please contact to your site admin.'));
        //     }

        //     //  return $user;
        //     return view('auth.verify');
        //     // return redirect(RouteServiceProvider::HOME);

    }

    // Register Form
    public function showRegistrationForm($lang = 'en')
    {
        if (Utility::getValByName('SIGNUP') == 'on') {
            \App::setLocale($lang);
            return view('auth.register', compact('lang'));
        } else {
            return abort('404', 'Page Not Found');
        }
    }
}
