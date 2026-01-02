@php
    $temp_lang = \App::getLocale('lang');
    if($temp_lang == 'ar' || $temp_lang == 'he'){
        $rtl = 'on';
    }
    else {
        $rtl = \App\Models\Utility::getValByName('enable_rtl');
    }
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir={{ $rtl == 'on' ? 'rtl' : 'ltr' }}>
@php
    $logo = \App\Models\Utility::get_file('logo/');
    $meta_logo = \App\Models\Utility::get_file('uploads/logo/');
    $settings = \App\Models\Utility::settings();
    $user = App\Models\User::where('type' , '=' , 'admin')->first();
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TaskGo SaaS') }} &dash; @yield('title')</title>

    <meta name="title" content="{{$settings['meta_keywords']}}">
    <meta name="description" content="{{$settings['meta_description']}}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:title" content="{{$settings['meta_keywords']}}">
    <meta property="og:description" content="{{$settings['meta_description']}}">
    <meta property="og:image" content="{{$meta_logo.$settings['meta_image']}}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:title" content="{{$settings['meta_keywords']}}">
    <meta property="twitter:description" content="{{$settings['meta_description']}}">
    <meta property="twitter:image" content="{{$meta_logo.$settings['meta_image']}}">

    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- <script src="{{ asset('assets/js/custom.js') }}"></script> --}}
    <link rel="icon" href="{{$logo.'favicon.png'.'?'.time()}}" type="image/png">
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/purpose.css') }}"> --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/libs/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/site-light.css') }}" id="stylesheet">
    @if ($rtl == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-rtl.css') }}">
    @endif
 
    @if($user->mode == 'dark')
        <style>
        .g-recaptcha {
            filter: invert(1) hue-rotate(180deg) !important;
        }
        </style>
    @endif

    @if ($user->mode == 'light')
        @include('layouts.lightthemecolor')
        <link rel="stylesheet" href="{{ asset('assets/css/site-light.css') }}">
    @else
        @include('layouts.darkthemecolor')
        <link rel="stylesheet" href="{{ asset('assets/css/site-dark.css') }}">
    @endif

    <style>
        .auth-lang {
            right: 5px;
            top: 10px;
        }

    </style>
</head>

<body class="application application-offset">
    @include('layouts.lightthemecolor')
    <div id="app">
        <div class="container-fluid container-application">
            <div class="main-content position-relative">
                <div class="page-content">
                    <div class="min-vh-100 py-5 d-flex align-items-center">
                        <div class="w-100">
                            <div class="row justify-content-center">
                                @yield('language-bar')
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($settings['enable_cookie'] == 'on')
    @include('layouts.cookie_consent')
@endif
</body>
{{-- <script src="{{asset('assets/js/site.core.js')}}"></script>
<script src="{{asset('assets/js/site.js')}}"></script> --}}
<script src="{{ asset('assets/js/purpose.core.js') }}"></script>
<script src="{{ asset('assets/js/purpose.js') }}"></script>
@stack('custom-scripts')

</html>
