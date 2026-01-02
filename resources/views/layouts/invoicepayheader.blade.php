<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir={{ \App\Models\Utility::getValByName('enable_rtl') == 'on' ? 'rtl' : 'ltr' }}>

<head>
    @php
        $logo = \App\Models\Utility::get_file('logo/');
        $meta_logo = \App\Models\Utility::get_file('uploads/logo/');
        // if(isset($user_id))
        // {
        //     $owner_settings = \App\Models\Utility::settingsById($user_id);
        // }
        $settings = \App\Models\Utility::settings();

    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        {{ \App\Models\Utility::getValByName('header_text') ? \App\Models\Utility::getValByName('header_text') : config('app.name') }} &dash; @yield('title')</title>

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

    <link rel="icon" href="{{$logo.'favicon.png'.'?'.time()}}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('assets/libs/@fortawesome/fontawesome-free/css/all.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}"> --}}

    <link rel="stylesheet" href="{{ asset('assets/libs/animate.css/animate.min.css') }}">

    @if (isset($usr) && $usr->mode != 'light')
        @include('layouts.darkthemecolor')
        <link rel="stylesheet" href="{{ asset('assets/css/site-dark.css') }}">
    @else
        @include('layouts.lightthemecolor')
        <link rel="stylesheet" href="{{ asset('assets/css/site-light.css') }}">
    @endif


    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">

    @if (\App\Models\Utility::getValByName('enable_rtl') == 'on')

        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-rtl.css') }}">
    @endif

    {{-- <meta name="url" content="{{ url('') . '/' . config('chatify.routes.prefix') }}"
        data-user="{{ Auth::user()->id }}"> --}}
    <title>{{ __('Messages') }} &dash;
        {{ \App\Models\Utility::getValByName('header_text') ? \App\Models\Utility::getValByName('header_text') : config('app.name') }}
    </title>
    <link rel="icon" href="{{$logo.'favicon.png'.'?'.time()}}" type="image/png">

    {{-- scripts --}}
    <script src="{{ asset('js/chatify/font.awesome.min.js') }}"></script>
    <script src="{{ asset('js/chatify/autosize.js') }}"></script>
    <script src='https://unpkg.com/nprogress@0.2.0/nprogress.js'></script>

    {{-- styles --}}
    <link rel='stylesheet' href='https://unpkg.com/nprogress@0.2.0/nprogress.css' />
   <link href="{{ asset('css/chatify/style.css') }}" rel="stylesheet" />


    @stack('css')
</head>
{{-- <body class="application application-offset"> --}}
<body class="application application-offset">
    <div class="container-fluid container-application">
        <div class="main-content position-relative">
            <div class="page-content">
                @if (trim($__env->yieldContent('title')) != 'Task Calendar')
                    <div class="page-title">
                        <div class="row justify-content-between align-items-center">
                            <div
                                class="col-xs-12 col-sm-12 col-md-4 d-flex align-items-center justify-content-between justify-content-md-start mb-3 mb-md-0">
                                <div class="d-inline-block">
                                    <h5 class="h4 d-inline-block font-weight-400 mb-0 text-white">@yield('title')
                                        @if (trim($__env->yieldContent('role')))
                                            <span class="text text-sm text-white m-0 ml-2">(@yield('role'))</span>
                                        @endif
                                    </h5>
                                </div>
                            </div>
                            <div
                                class="col-xs-12 col-sm-12 col-md-8 d-md-flex d-block align-items-center justify-content-between justify-content-md-end rounded_icon">
                                @yield('action-button')
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>





</body>

<!-- Scripts -->
<script src="{{ asset('assets/js/site.core.js') }}"></script>
<script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('assets/libs/progressbar.js/dist/progressbar.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/libs/autosize/dist/autosize.min.js') }}"></script>
{{-- <script src="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.js') }}"></script> --}}
<script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
<script src="{{asset('assets/js/choices.min.js')}}"></script>

@stack('theme-script')
<script src="{{ asset('assets/js/site.js') }}"></script>
<script src="{{ asset('assets/js/letter.avatar.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>

@if($settings['enable_cookie'] == 'on')
    @include('layouts.cookie_consent')
@endif

@stack('script')

@if (Session::has('success'))
    <script>
        show_toastr('{{ __('Success') }}', '{!! session('success') !!}', 'success');
    </script>
    {{ Session::forget('success') }}
@endif
@if (Session::has('error'))
    <script>
        show_toastr('{{ __('Error') }}', '{!! session('error') !!}', 'error');
    </script>
    {{ Session::forget('error') }}
@endif

</html>
