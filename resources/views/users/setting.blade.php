@extends('layouts.admin')

@section('title')
    {{ __('Settings') }}
@endsection

@php

    if ($settings['color']) {
        $color = $settings['color'];
    }

@endphp

@push('css')
    <style>
        .doc-img>a,
        .theme-color>a {
            position: relative;
            width: 35px;
            height: 25px;
            border-radius: 3px;
            display: inline-block;
            background: #f8f9fd;
            overflow: hidden;
            box-shadow: 0 1px 2px rgb(0 0 0 / 28%);
        }
    </style>
@endpush


@php
    $logo = \App\Models\Utility::get_file('logo/');
    $file_type = config('files_types');

    $local_storage_validation = $settings['local_storage_validation'];
    $local_storage_validations = explode(',', $local_storage_validation);

    $s3_storage_validation = $settings['s3_storage_validation'];
    $s3_storage_validations = explode(',', $s3_storage_validation);

    $wasabi_storage_validation = $settings['wasabi_storage_validation'];
    $wasabi_storage_validations = explode(',', $wasabi_storage_validation);
@endphp



@section('content')
    <div class="row">
        <div class="col-lg-4 order-lg-2">
            <div class="card">
                <div class="list-group list-group-flush" id="tabs">
                    <div data-href="#brand-settings" class="list-group-item text-primary">
                        <div class="media">
                            <i class="fas fa-cog pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{ __('Brand Settings') }}</a>
                                <p class="mb-0 text-sm">{{ __('Edit your brand details.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#tabs-2" class="list-group-item">
                        <div class="media">
                            <i class="fas fa-envelope pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{ __('Email Settings') }}</a>
                                <p class="mb-0 text-sm">{{ __('Edit your email details.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#tabs-4" class="list-group-item">
                        <div class="media">
                            <i class="fas fa-comments pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{ __('Pusher Settings') }}</a>
                                <p class="mb-0 text-sm">{{ __('Edit your pusher details.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#tabs-3" class="list-group-item">
                        <div class="media">
                            <i class="fas fa-money-check-alt pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{ __('Payment Settings') }}</a>
                                <p class="mb-0 text-sm">{{ __('Edit your Payment details.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#tabs-5" class="list-group-item">
                        <div class="media">
                            <i class="fas fa-money-check-alt pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{ __('ReCaptcha Settings') }}</a>
                                <p class="mb-0 text-sm">
                                    {{ __('Edit your recaptcha details.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#storage_setting" class="list-group-item">
                        <div class="media">
                            <i class="fas fa-store pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{ __('Storage Settings') }}</a>
                                <p class="mb-0 text-sm">
                                    {{ __('Edit your storage details.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div data-href="#tabs-6" class="list-group-item">
                        <div class="media">
                            <i class="fas fa-poll"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{ __('SEO Settings') }}</a>
                                <p class="mb-0 text-sm">
                                    {{ __('Edit your SEO Settings details.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div data-href="#tabs-7" class="list-group-item">
                        <div class="media">
                            <i class="fas fa-database"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{ __('Cache Settings') }}</a>
                                <p class="mb-0 text-sm">
                                    {{ __('Clear your cache details.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div data-href="#cookie-settings" class="list-group-item">
                        <div class="media">
                            <i class="fas fa-cookie"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{ __('Cookie Settings') }}</a>
                                <p class="mb-0 text-sm">
                                    {{ __('Edit your Cookie Settings.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div data-href="#pills-chatgpt-settings" class="list-group-item">
                        <div class="media">
                            <i class="fas fa-comment-dots"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{ __('Chat GPT Key Settings') }}</a>
                                <p class="mb-0 text-sm">
                                    {{ __('Edit your key details.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 order-lg-1">

            <div id="brand-settings" class="tabs-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h6 mb-0">{{ __('Brand Settings') }}</h5>
                    </div>
                    <div class="card-body">
                        {{ Form::open(['route' => ['settings.store'], 'id' => 'update_setting', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('full_logo', __('Logo'), ['class' => 'form-control-label']) }}
                                    <input type="file" name="full_logo" id="full_logo" class="custom-input-file"
                                        onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])" />
                                    <label for="full_logo">
                                        <i class="fa fa-upload"></i>
                                        <span>{{ __('Choose a file…') }}</span>
                                    </label>
                                    @error('full_logo')
                                        <span class="full_logo" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 pt-5">
                                <a href="{{ $logo . 'logo.png'.'?'.time() }}" target="_blank">
                                    <img src="{{ $logo . 'logo.png'.'?'.time() }}" id="blah" class="img_setting" />
                                </a>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('favicon', __('Favicon'), ['class' => 'form-control-label']) }}
                                    <input type="file" name="favicon" id="favicon" class="custom-input-file"
                                        onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])" />
                                    <label for="favicon">
                                        <i class="fa fa-upload"></i>
                                        <span>{{ __('Choose a file…') }}</span>
                                    </label>
                                    @error('favicon')
                                        <span class="favicon" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 pt-5">
                                <a href="{{ $logo . 'favicon.png'.'?'.time() }}" target="_blank">
                                    <img src="{{ $logo . 'favicon.png'.'?'.time() }}" id="blah1" class="img_setting" />
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('header_text', __('Title Text'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('header_text', \App\Models\Utility::getValByName('header_text'), ['class' => 'form-control', 'placeholder' => __('Enter Header Title Text')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('footer_text', __('Footer Text'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('footer_text', \App\Models\Utility::getValByName('footer_text'), ['class' => 'form-control', 'placeholder' => __('Enter Footer Text')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('default_language', __('Default Language'), ['class' => 'form-control-label text-dark']) }}
                                    <select name="default_language" id="default_language" class="form-control select2">
                                        @foreach (App\Models\Utility::languages() as $code => $lang)
                                            <option @if (App\Models\Utility::getValByName('default_language') == $code) selected @endif
                                                value="{{ $code }}">{{ ucfirst($lang) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('timezone', __('Timezone'), ['class' => 'form-control-label text-dark']) }}
                                    <select name="timezone" id="timezone" class="form-control select2">
                                        @foreach ($timezones as $k => $timezone)
                                            <option value="{{ $k }}"
                                                {{ env('TIMEZONE') == $k ? 'selected' : '' }}>{{ $timezone }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 mt-lg-5">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="verification_btn"
                                        id="verification_btn"
                                        {{ isset($settings['verification_btn']) && $settings['verification_btn'] == 'on' ? 'checked="checked"' : '' }}>
                                    <label class="custom-control-label form-control-label"
                                        for="verification_btn">{{ __('Email Verification') }}</label>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 mt-lg-5">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="enable_rtl"
                                        id="enable_rtl"
                                        {{ \App\Models\Utility::getValByName('enable_rtl') == 'on' ? 'checked' : '' }}>
                                    <label class="custom-control-label form-control-label"
                                        for="enable_rtl">{{ __('Enable RTL') }}</label>
                                </div>
                            </div>
                            {{-- <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 mt-lg-5">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input gdpr_fulltime gdpr_type"
                                        name="gdpr_cookie" id="gdpr_cookie"
                                        {{ isset($settings['gdpr_cookie']) && $settings['gdpr_cookie'] == 'on' ? 'checked="checked"' : '' }}>
                                    <label class="custom-control-label form-control-label"
                                        for="gdpr_cookie">{{ __('GDPR Notification') }}</label>
                                </div>
                            </div> --}}

                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 mt-lg-5">
                                <div class="custom-control custom-switch">

                                    <input type="checkbox" class="custom-control-input" name="SIGNUP" id="SIGNUP"
                                        {{ isset($settings['SIGNUP']) && $settings['SIGNUP'] == 'on' ? 'checked="checked"' : '' }}>
                                    <label class="custom-control-label form-control-label"
                                        for="SIGNUP">{{ __('Enable Sign-Up Page') }}</label>
                                </div>
                            </div>


                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 mt-lg-5">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="enable_landing"
                                        id="enable_landing"
                                        {{ \App\Models\Utility::getValByName('enable_landing') == 'on' ? 'checked' : '' }}>
                                    <label class="custom-control-label form-control-label"
                                        for="enable_landing">{{ __('Enable Landing Page') }}</label>
                                </div>
                            </div>

                            {{-- <div class="form-group col-lg-12">
                                {{ Form::label('cookie_text', __('GDPR Cookie Text'), ['class' => 'fulltime']) }}
                                {!! Form::textarea('cookie_text', $settings['cookie_text'], ['class' => 'form-control fulltime', 'rows' => '4']) !!}

                            </div> --}}

                            <div class="col-4">
                                <h6 class="">
                                    <i data-feather="credit-card" class="me-2"></i>{{ __('Primary color settings') }}
                                </h6>
                                <hr class="my-2" />
                                <div class="theme-color themes-color">

                                    <a href="#!" class="theme-2 {{ $color == '#6fd943' ? 'active_color' : '' }}"
                                        data-value="#6fd943" onclick="check_theme('#6fd943')"></a>
                                    <input type="radio" class="theme_color " name="color" value="#6fd943"
                                        style="display: none;" {{ $color == '#6fd943' ? 'checked' : '' }}>

                                    <a href="#!" class="theme-1 {{ $color == '#a83f85' ? 'active_color' : '' }}"
                                        data-value="#a83f85" onclick="check_theme('#a83f85')"></a>
                                    <input type="radio" class="theme_color " name="color" value="#a83f85"
                                        style="display: none;" {{ $color == '#a83f85' ? 'checked' : '' }}>

                                    <a href="#!" class="theme-3 {{ $color == 'theme-3' ? 'active_color' : '' }}"
                                        data-value="theme-3" onclick="check_theme('#449fc6')"></a>
                                    <input type="radio" class="theme_color " name="color" value="#449fc6"
                                        style="display: none;" {{ $color == '#449fc6' ? 'checked' : '' }}>

                                    <a href="#!" class="theme-4 {{ $color == '#51459d' ? 'active_color' : '' }}"
                                        data-value="theme-4" onclick="check_theme('#51459d')"></a>
                                    <input type="radio" class="theme_color " name="color" value="#51459d"
                                        style="display: none;" {{ $color == '#51459d' ? 'checked' : '' }}>

                                </div>
                            </div>


                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('footer_link_1', __('Footer Link Title 1'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('footer_link_1', \App\Models\Utility::getValByName('footer_link_1'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Footer Link Title 1')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('footer_value_1', __('Footer Link href 1'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('footer_value_1', \App\Models\Utility::getValByName('footer_value_1'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Footer Link 1')]) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('footer_link_2', __('Footer Link Title 2'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('footer_link_2', \App\Models\Utility::getValByName('footer_link_2'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Footer Link Title 2')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('footer_value_2', __('Footer Link href 2'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('footer_value_2', \App\Models\Utility::getValByName('footer_value_2'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Footer Link 2')]) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('footer_link_3', __('Footer Link Title 3'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('footer_link_3', \App\Models\Utility::getValByName('footer_link_3'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Footer Link Title 3')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('footer_value_3', __('Footer Link href 3'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('footer_value_3', \App\Models\Utility::getValByName('footer_value_3'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Footer Link 3')]) }}
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            {{ Form::hidden('from', 'site_setting') }}
                            <button type="submit"
                                class="btn btn-sm btn-primary rounded-pill">{{ __('Save changes') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>


            <div id="tabs-2" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h6 mb-0">{{ __('Email Settings') }}</h5>
                    </div>
                    <div class="card-body">
                        {{ Form::open(['route' => ['settings.store'], 'id' => 'update_setting']) }}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('mail_driver', __('Mail Driver'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('mail_driver', env('MAIL_DRIVER'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Mail Driver')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('mail_host', __('Mail Host'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('mail_host', env('MAIL_HOST'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Mail Host')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('mail_port', __('Mail Port'), ['class' => 'form-control-label']) }}
                                    {{ Form::number('mail_port', env('MAIL_PORT'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Mail Port'), 'min' => '0']) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('mail_username', __('Mail Username'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('mail_username', env('MAIL_USERNAME'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Mail Username')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('mail_password', __('Mail Password'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('mail_password', env('MAIL_PASSWORD'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Mail Password')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('mail_encryption', env('MAIL_ENCRYPTION'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Mail Encryption')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('mail_from_address', __('Mail From Address'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('mail_from_address', env('MAIL_FROM_ADDRESS'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Mail From Address')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('mail_from_name', __('Mail From Name'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('mail_from_name', env('MAIL_FROM_NAME'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Mail From Name')]) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="text-left">
                                    <button type="button" class="btn btn-sm btn-warning rounded-pill send_email"
                                        data-title="{{ __('Send Test Mail') }}"
                                        data-url="{{ route('test.email') }}">{{ __('Send Test Mail') }}</button>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="text-md-right mt-2 mt-md-0">
                                    {{ Form::hidden('from', 'mail') }}
                                    <button type="submit"
                                        class="btn btn-sm btn-primary rounded-pill">{{ __('Save changes') }}</button>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            <div id="tabs-4" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h6 mb-0">{{ __('Pusher Settings') }}</h5>
                    </div>
                    <div class="card-body">
                        {{ Form::open(['route' => ['settings.store'], 'id' => 'update_setting']) }}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('pusher_app_id', __('Pusher App Id'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('pusher_app_id', env('PUSHER_APP_ID'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Pusher App Id')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('pusher_app_key', __('Pusher App Key'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('pusher_app_key', env('PUSHER_APP_KEY'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Pusher App Key')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('pusher_app_secret', __('Pusher App Secret'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('pusher_app_secret', env('PUSHER_APP_SECRET'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Pusher App Secret')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('pusher_app_cluster', __('Pusher App Cluster'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('pusher_app_cluster', env('PUSHER_APP_CLUSTER'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Pusher App Cluster')]) }}
                                </div>
                            </div>
                            <div class="col-12">
                                <small><a href="https://pusher.com/channels"
                                        target="_blank">{{ __('You can Make Pusher channel Account from here and Get your App Id and Secret key') }}</a></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-right">
                                    {{ Form::hidden('from', 'pusher') }}
                                    <button type="submit"
                                        class="btn btn-sm btn-primary rounded-pill">{{ __('Save changes') }}</button>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div id="tabs-3" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h6 mb-0">{{ __('Payment Settings') }}</h5>
                        <small>{{ __('These details will be used to collect subscription plan payments.Each subscription plan will have a payment button based on the below configuration.') }}</small>
                    </div>
                    <div class="card-body">
                        {{ Form::open(['route' => ['settings.store'], 'id' => 'update_setting']) }}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('currency', __('Currency'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('currency', env('CURRENCY'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Currency')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('currency_code', __('Currency Code'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('currency_code', env('CURRENCY_CODE'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Currency Code')]) }}
                                    <small>{{ __('Note : Add currency code as per three-letter ISO code.') }} <a
                                            class="text-primary" href="https://stripe.com/docs/currencies"
                                            target="_blank">{{ __(' You can find out how to do that here..') }}</a></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="payment-gateways" class="accordion accordion-spaced">

                                    <!-- manually -->
                                    <div class="card">
                                        <div class="card-header py-4" id="manually-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-manually" aria-expanded="false"
                                            aria-controls="collapse-manually">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('Manually') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_manually_enabled" id="is_manually_enabled"
                                                        {{ isset($payment_detail['is_manually_enabled']) && $payment_detail['is_manually_enabled'] == 'on' ? 'checked' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_manually_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-manually" class="collapse" aria-labelledby="manually-payment"
                                            data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">
                                                        <p>{{ __('Requesting Manual Payment For The Planned Amount For The Subscriptions Plan.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bank Transfer -->
                                    <div class="card">
                                        <div class="card-header py-4" id="bank_transfer-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-bank_transfer" aria-expanded="false"
                                            aria-controls="collapse-bank_transfer">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('Bank Transfer') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_bank_tranfer_enabled" id="is_bank_tranfer_enabled"
                                                        {{ isset($payment_detail['is_bank_tranfer_enabled']) && $payment_detail['is_bank_tranfer_enabled'] == 'on' ? 'checked' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_bank_tranfer_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-bank_transfer" class="collapse"
                                            aria-labelledby="bank_transfer-payment" data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">
                                                        {!! Form::label('inputname', 'Bank Details', ['class' => 'col-form-label']) !!}
                                                        @php
                                                            $bank_details = !empty($payment_detail['bank_details']) ? $payment_detail['bank_details'] : '';
                                                        @endphp
                                                        {!! Form::textarea('bank_details', $bank_details, [
                                                            'class' => 'form-control',
                                                            'rows' => '6',
                                                            'required' => 'required',
                                                        ]) !!}
                                                        <small class="text-xs">
                                                            {{ __('Example : Bank : Bank Name <br> Account Number : 0000 0000 <br>') }}.
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Stripe -->
                                    <div class="card">
                                        <div class="card-header py-4" id="stripe-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-stripe" aria-expanded="false"
                                            aria-controls="collapse-stripe">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('Stripe') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="enable_stripe" id="enable_stripe"
                                                        {{ isset($payment_detail['enable_stripe']) && $payment_detail['enable_stripe'] == 'on' ? 'checked' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="enable_stripe">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-stripe" class="collapse" aria-labelledby="stripe-payment"
                                            data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">
                                                        <h5 class="h5">{{ __('Stripe') }}</h5>
                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="form-group">
                                                            {{ Form::label('stripe_key', __('Stripe Key'), ['class' => 'form-control-label']) }}
                                                            {{ Form::text('stripe_key', isset($payment_detail['stripe_key']) && !empty($payment_detail['stripe_key']) ? $payment_detail['stripe_key'] : '', ['class' => 'form-control', 'placeholder' => __('Stripe Key')]) }}
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="form-group">
                                                            {{ Form::label('stripe_secret', __('Stripe Secret'), ['class' => 'form-control-label']) }}
                                                            {{ Form::text('stripe_secret', isset($payment_detail['stripe_secret']) && !empty($payment_detail['stripe_secret']) ? $payment_detail['stripe_secret'] : '', ['class' => 'form-control', 'placeholder' => __('Stripe Secret')]) }}
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Paypal -->
                                    <div class="card">
                                        <div class="card-header py-4" id="paypal-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-paypal" aria-expanded="false"
                                            aria-controls="collapse-paypal">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('Paypal') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="enable_paypal" id="enable_paypal"
                                                        {{ isset($payment_detail['enable_paypal']) && $payment_detail['enable_paypal'] == 'on' ? 'checked' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="enable_paypal">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-paypal" class="collapse" aria-labelledby="paypal-payment"
                                            data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">
                                                        <h5 class="h5">{{ __('Paypal') }}</h5>
                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pb-4">
                                                        <label class="paypal-label form-control-label"
                                                            for="paypal_mode">{{ __('Paypal Mode') }}</label> <br>
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label
                                                                class="btn btn-primary btn-sm {{ !isset($payment_detail['paypal_mode']) || empty($payment_detail['paypal_mode']) || $payment_detail['paypal_mode'] == 'sandbox' ? 'active' : '' }}">
                                                                <input type="radio" name="paypal_mode" value="sandbox"
                                                                    {{ !isset($payment_detail['paypal_mode']) || empty($payment_detail['paypal_mode']) || $payment_detail['paypal_mode'] == 'sandbox' ? 'checked' : '' }}>{{ __('Sandbox') }}
                                                            </label>
                                                            <label
                                                                class="btn btn-primary btn-sm {{ isset($payment_detail['paypal_mode']) && $payment_detail['paypal_mode'] == 'live' ? 'active' : '' }}">
                                                                <input type="radio" name="paypal_mode" value="live"
                                                                    {{ isset($payment_detail['paypal_mode']) && $payment_detail['paypal_mode'] == 'live' ? 'checked' : '' }}>{{ __('Live') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="form-group">
                                                            {{ Form::label('paypal_client_id', __('Client ID'), ['class' => 'form-control-label']) }}
                                                            {{ Form::text('paypal_client_id', isset($payment_detail['paypal_client_id']) ? $payment_detail['paypal_client_id'] : '', ['class' => 'form-control', 'placeholder' => __('Client ID')]) }}
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="form-group">
                                                            {{ Form::label('paypal_secret_key', __('Secret Key'), ['class' => 'form-control-label']) }}
                                                            {{ Form::text('paypal_secret_key', isset($payment_detail['paypal_secret_key']) ? $payment_detail['paypal_secret_key'] : '', ['class' => 'form-control', 'placeholder' => __('Secret Key')]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Paystack -->
                                    <div class="card">
                                        <div class="card-header py-4" id="paystack-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-paystack" aria-expanded="false"
                                            aria-controls="collapse-paystack">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('Paystack') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_paystack_enabled" id="is_paystack_enabled"
                                                        {{ isset($payment_detail['is_paystack_enabled']) && $payment_detail['is_paystack_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_paystack_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-paystack" class="collapse" aria-labelledby="paystack-payment"
                                            data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">
                                                        <h5 class="h5">{{ __('Paystack') }}</h5>
                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="paypal_client_id">{{ __('Public Key') }}</label>
                                                            <input type="text" name="paystack_public_key"
                                                                id="paystack_public_key" class="form-control"
                                                                value="{{ isset($payment_detail['paystack_public_key']) ? $payment_detail['paystack_public_key'] : '' }}"
                                                                placeholder="{{ __('Public Key') }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="paystack_secret_key">{{ __('Secret Key') }}</label>
                                                            <input type="text" name="paystack_secret_key"
                                                                id="paystack_secret_key" class="form-control"
                                                                value="{{ isset($payment_detail['paystack_secret_key']) ? $payment_detail['paystack_secret_key'] : '' }}"
                                                                placeholder="{{ __('Secret Key') }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FLUTTERWAVE -->
                                    <div class="card">
                                        <div class="card-header py-4" id="flutterwave-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-flutterwave" aria-expanded="false"
                                            aria-controls="collapse-flutterwave">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('Flutterwave') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_flutterwave_enabled" id="is_flutterwave_enabled"
                                                        {{ isset($payment_detail['is_flutterwave_enabled']) && $payment_detail['is_flutterwave_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_flutterwave_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-flutterwave" class="collapse"
                                            aria-labelledby="flutterwave-payment" data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">
                                                        <h5 class="h5">{{ __('Flutterwave') }}</h5>
                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="paypal_client_id">{{ __('Public Key') }}</label>
                                                            <input type="text" name="flutterwave_public_key"
                                                                id="flutterwave_public_key" class="form-control"
                                                                value="{{ isset($payment_detail['flutterwave_public_key']) ? $payment_detail['flutterwave_public_key'] : '' }}"
                                                                placeholder="{{ __('Public Key') }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="paystack_secret_key">{{ __('Secret Key') }}</label>
                                                            <input type="text" name="flutterwave_secret_key"
                                                                id="flutterwave_secret_key" class="form-control"
                                                                value="{{ isset($payment_detail['flutterwave_secret_key']) ? $payment_detail['flutterwave_secret_key'] : '' }}"
                                                                placeholder="{{ __('Secret Key') }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Razorpay -->
                                    <div class="card">
                                        <div class="card-header py-4" id="razorpay-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-razorpay" aria-expanded="false"
                                            aria-controls="collapse-razorpay">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('Razorpay') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_razorpay_enabled" id="is_razorpay_enabled"
                                                        {{ isset($payment_detail['is_razorpay_enabled']) && $payment_detail['is_razorpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_razorpay_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-razorpay" class="collapse" aria-labelledby="razorpay-payment"
                                            data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">
                                                        <h5 class="h5">{{ __('Razorpay') }}</h5>
                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="paypal_client_id">{{ __('Public Key') }}</label>
                                                            <input type="text" name="razorpay_public_key"
                                                                id="razorpay_public_key" class="form-control"
                                                                value="{{ isset($payment_detail['razorpay_public_key']) ? $payment_detail['razorpay_public_key'] : '' }}"
                                                                placeholder="{{ __('Public Key') }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="paystack_secret_key">{{ __('Secret Key') }}</label>
                                                            <input type="text" name="razorpay_secret_key"
                                                                id="razorpay_secret_key" class="form-control"
                                                                value="{{ isset($payment_detail['razorpay_secret_key']) ? $payment_detail['razorpay_secret_key'] : '' }}"
                                                                placeholder="{{ __('Secret Key') }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mercado Pago-->
                                    <div class="card">
                                        <div class="card-header py-4" id="mercado_pago-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-mercado_pago" aria-expanded="false"
                                            aria-controls="collapse-mercado_pago">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('Mercado Pago') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="hidden" name="is_mercado_enabled" value="off">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_mercado_enabled" id="is_mercado_enabled"
                                                        {{ isset($payment_detail['is_mercado_enabled']) && $payment_detail['is_mercado_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_mercado_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-mercado_pago" class="collapse"
                                            aria-labelledby="mercado_pago-payment" data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">
                                                        <h5 class="h5">{{ __('Mercado Pago') }}</h5>
                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>
                                                    <div class="col-md-12 pb-4">
                                                        <label class="coingate-label form-control-label"
                                                            for="mercado_mode">{{ __('Mercado Mode') }}</label> <br>
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label
                                                                class="btn btn-primary btn-sm {{ isset($payment_detail['mercado_mode']) && $payment_detail['mercado_mode'] == 'sandbox' ? 'active' : '' }}">
                                                                <input type="radio" name="mercado_mode" value="sandbox"
                                                                    {{ (isset($payment_detail['mercado_mode']) && $payment_detail['mercado_mode'] == '') || (isset($payment_detail['mercado_mode']) && $payment_detail['mercado_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>{{ __('Sandbox') }}
                                                            </label>
                                                            <label
                                                                class="btn btn-primary btn-sm {{ isset($payment_detail['mercado_mode']) && $payment_detail['mercado_mode'] == 'live' ? 'active' : '' }}">
                                                                <input type="radio" name="mercado_mode" value="live"
                                                                    {{ isset($payment_detail['mercado_mode']) && $payment_detail['mercado_mode'] == 'live' ? 'checked="checked"' : '' }}>{{ __('Live') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label
                                                                for="mercado_access_token">{{ __('Access Token') }}</label>
                                                            <input type="text" name="mercado_access_token"
                                                                id="mercado_access_token" class="form-control"
                                                                value="{{ isset($payment_detail['mercado_access_token']) ? $payment_detail['mercado_access_token'] : '' }}"
                                                                placeholder="{{ __('Access Token') }}" />
                                                            @if ($errors->has('mercado_secret_key'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('mercado_access_token') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Paytm -->
                                    <div class="card">
                                        <div class="card-header py-4" id="paytm-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-paytm" aria-expanded="false"
                                            aria-controls="collapse-paytm">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('Paytm') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_paytm_enabled" id="is_paytm_enabled"
                                                        {{ isset($payment_detail['is_paytm_enabled']) && $payment_detail['is_paytm_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_paytm_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-paytm" class="collapse" aria-labelledby="paytm-payment"
                                            data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">
                                                        <h5 class="h5">{{ __('Paytm') }}</h5>
                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>

                                                    <div class="col-md-12 pb-4">
                                                        <label class="paypal-label form-control-label"
                                                            for="paypal_mode">{{ __('Paytm Environment') }}</label>
                                                        <br>
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label
                                                                class="btn btn-primary btn-sm {{ isset($payment_detail['paytm_mode']) && $payment_detail['paytm_mode'] == 'local' ? 'active' : '' }}">
                                                                <input type="radio" name="paytm_mode" value="local"
                                                                    {{ (isset($payment_detail['paytm_mode']) && $payment_detail['paytm_mode'] == '') || (isset($payment_detail['paytm_mode']) && $payment_detail['paytm_mode'] == 'local') ? 'checked="checked"' : '' }}>{{ __('Local') }}
                                                            </label>
                                                            <label
                                                                class="btn btn-primary btn-sm {{ isset($payment_detail['paytm_mode']) && $payment_detail['paytm_mode'] == 'live' ? 'active' : '' }}">
                                                                <input type="radio" name="paytm_mode"
                                                                    value="production"
                                                                    {{ isset($payment_detail['paytm_mode']) && $payment_detail['paytm_mode'] == 'production' ? 'checked="checked"' : '' }}>{{ __('Production') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="paytm_public_key">{{ __('Merchant ID') }}</label>
                                                            <input type="text" name="paytm_merchant_id"
                                                                id="paytm_merchant_id" class="form-control"
                                                                value="{{ isset($payment_detail['paytm_merchant_id']) ? $payment_detail['paytm_merchant_id'] : '' }}"
                                                                placeholder="{{ __('Merchant ID') }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="paytm_secret_key">{{ __('Merchant Key') }}</label>
                                                            <input type="text" name="paytm_merchant_key"
                                                                id="paytm_merchant_key" class="form-control"
                                                                value="{{ isset($payment_detail['paytm_merchant_key']) ? $payment_detail['paytm_merchant_key'] : '' }}"
                                                                placeholder="{{ __('Merchant Key') }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="paytm_industry_type">{{ __('Industry Type') }}</label>
                                                            <input type="text" name="paytm_industry_type"
                                                                id="paytm_industry_type" class="form-control"
                                                                value="{{ isset($payment_detail['paytm_industry_type']) ? $payment_detail['paytm_industry_type'] : '' }}"
                                                                placeholder="{{ __('Industry Type') }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mollie -->
                                    <div class="card">
                                        <div class="card-header py-4" id="mollie-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-mollie" aria-expanded="false"
                                            aria-controls="collapse-mollie">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('Mollie') }}

                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_mollie_enabled" id="is_mollie_enabled"
                                                        {{ isset($payment_detail['is_mollie_enabled']) && $payment_detail['is_mollie_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_mollie_enabled">{{ __('Enable') }}</label>
                                                </div>

                                            </h6>
                                        </div>
                                        <div id="collapse-mollie" class="collapse" aria-labelledby="mollie-payment"
                                            data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">
                                                        <h5 class="h5">{{ __('Mollie') }}</h5>
                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-control-label"
                                                            for="mollie_api_key">{{ __('Mollie Api Key') }}</label>
                                                        <input type="text" name="mollie_api_key" id="mollie_api_key"
                                                            class="form-control"
                                                            value="{{ isset($payment_detail['mollie_api_key']) ? $payment_detail['mollie_api_key'] : '' }}"
                                                            placeholder="{{ __('Mollie Api Key') }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-control-label"
                                                            for="mollie_profile_id">{{ __('Mollie Profile Id') }}</label>
                                                        <input type="text" name="mollie_profile_id"
                                                            id="mollie_profile_id" class="form-control"
                                                            value="{{ isset($payment_detail['mollie_profile_id']) ? $payment_detail['mollie_profile_id'] : '' }}"
                                                            placeholder="{{ __('Mollie Profile Id') }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-control-label"
                                                            for="mollie_partner_id">{{ __('Mollie Partner Id') }}</label>
                                                        <input type="text" name="mollie_partner_id"
                                                            id="mollie_partner_id" class="form-control"
                                                            value="{{ isset($payment_detail['mollie_partner_id']) ? $payment_detail['mollie_partner_id'] : '' }}"
                                                            placeholder="{{ __('Mollie Partner Id') }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Skrill -->
                                    <div class="card">
                                        <div class="card-header py-4" id="skrill-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-skrill" aria-expanded="false"
                                            aria-controls="collapse-skrill">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('Skrill') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_skrill_enabled" id="is_skrill_enabled"
                                                        {{ isset($payment_detail['is_skrill_enabled']) && $payment_detail['is_skrill_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_skrill_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-skrill" class="collapse" aria-labelledby="skrill-payment"
                                            data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6 py-2">
                                                        <h5 class="h5">{{ __('Skrill') }}</h5>
                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="mollie_api_key">{{ __('Skrill Email') }}</label>
                                                            <input type="email" name="skrill_email" id="skrill_email"
                                                                class="form-control"
                                                                value="{{ isset($payment_detail['skrill_email']) ? $payment_detail['skrill_email'] : '' }}"
                                                                placeholder="{{ __('Skrill Email') }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- CoinGate -->
                                    <div class="card">
                                        <div class="card-header py-4" id="coingate-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-coingate" aria-expanded="false"
                                            aria-controls="collapse-coingate">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('CoinGate') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_coingate_enabled" id="is_coingate_enabled"
                                                        {{ isset($payment_detail['is_coingate_enabled']) && $payment_detail['is_coingate_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_coingate_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-coingate" class="collapse" aria-labelledby="coingate-payment"
                                            data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6 py-2">
                                                        <h5 class="h5">{{ __('CoinGate') }}</h5>
                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>

                                                    <div class="col-md-12 pb-4">
                                                        <label class="coingate-label form-control-label"
                                                            for="coingate_mode">{{ __('CoinGate Mode') }}</label>
                                                        <br>
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label
                                                                class="btn btn-primary btn-sm {{ isset($payment_detail['coingate_mode']) && $payment_detail['coingate_mode'] == 'sandbox' ? 'active' : '' }}">
                                                                <input type="radio" name="coingate_mode"
                                                                    value="sandbox"
                                                                    {{ (isset($payment_detail['coingate_mode']) && $payment_detail['coingate_mode'] == '') || (isset($payment_detail['coingate_mode']) && $payment_detail['coingate_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>{{ __('Sandbox') }}
                                                            </label>
                                                            <label
                                                                class="btn btn-primary btn-sm {{ isset($payment_detail['coingate_mode']) && $payment_detail['coingate_mode'] == 'live' ? 'active' : '' }}">
                                                                <input type="radio" name="coingate_mode" value="live"
                                                                    {{ isset($payment_detail['coingate_mode']) && $payment_detail['coingate_mode'] == 'live' ? 'checked="checked"' : '' }}>{{ __('Live') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="coingate_auth_token">{{ __('CoinGate Auth Token') }}</label>
                                                            <input type="text" name="coingate_auth_token"
                                                                id="coingate_auth_token" class="form-control"
                                                                value="{{ isset($payment_detail['coingate_auth_token']) ? $payment_detail['coingate_auth_token'] : '' }}"
                                                                placeholder="{{ __('CoinGate Auth Token') }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Paymentwall -->
                                    <div class="card">
                                        <div class="card-header py-4" id="paymentwall-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-paymentwall" aria-expanded="false"
                                            aria-controls="collapse-paymentwall">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('PaymentWall') }}

                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="hidden" name="is_paymentwall_enabled" value="off">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_paymentwall_enabled" id="is_paymentwall_enabled"
                                                        {{ isset($payment_detail['is_paymentwall_enabled']) && $payment_detail['is_paymentwall_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_paymentwall_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-paymentwall" class="collapse"
                                            aria-labelledby="paymentwall-payment" data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">
                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paymentwall_public_key"
                                                                class="form-control-label">{{ __('Public Key') }}</label>
                                                            <input type="text" name="paymentwall_public_key"
                                                                id="paymentwall_public_key" class="form-control"
                                                                value="{{ isset($payment_detail['paymentwall_public_key']) ? $payment_detail['paymentwall_public_key'] : '' }}"
                                                                placeholder="{{ __('Public Key') }}" />
                                                            @if ($errors->has('paymentwall_public_key'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('paymentwall_public_key') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paymentwall_private_key"
                                                                class="form-control-label">{{ __('Private Key') }}</label>
                                                            <input type="text" name="paymentwall_private_key"
                                                                id="paymentwall_private_key"
                                                                class="form-control form-control-label"
                                                                value="{{ isset($payment_detail['paymentwall_private_key']) ? $payment_detail['paymentwall_private_key'] : '' }}"
                                                                placeholder="{{ __('Private Key') }}" />
                                                            @if ($errors->has('flutterwave_secret_key'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('paymentwall_private_key') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Toyyibpay -->
                                    <div class="card">
                                        <div class="card-header py-4" id="toyyibpay-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-toyyibpay" aria-expanded="false"
                                            aria-controls="collapse-toyyibpay">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('Toyyibpay') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="hidden" name="is_toyyibpay_enabled" value="off">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_toyyibpay_enabled" id="is_toyyibpay_enabled"
                                                        {{ isset($payment_detail['is_toyyibpay_enabled']) && $payment_detail['is_toyyibpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_toyyibpay_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-toyyibpay" class="collapse" aria-labelledby="toyyibpay-payment"
                                            data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">

                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="toyyibpay_secret_key"
                                                                class="form-control-label">{{ __('Secret Key') }}</label>
                                                            <input type="text" name="toyyibpay_secret_key"
                                                                id="toyyibpay_secret_key" class="form-control"
                                                                value="{{ isset($payment_detail['toyyibpay_secret_key']) ? $payment_detail['toyyibpay_secret_key'] : '' }}"
                                                                placeholder="{{ __('Secret Key') }}" />
                                                            @if ($errors->has('toyyibpay_secret_key'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('toyyibpay_secret_key') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="category_code"
                                                                class="form-control-label">{{ __('Category Code') }}</label>
                                                            <input type="text" name="category_code" id="category_code"
                                                                class="form-control"
                                                                value="{{ isset($payment_detail['category_code']) ? $payment_detail['category_code'] : '' }}"
                                                                placeholder="{{ __('Category Code') }}" />
                                                            @if ($errors->has('category_code'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('category_code') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payfast -->
                                    <div class="card">
                                        <div class="card-header py-4" id="payfast-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-payfast" aria-expanded="false"
                                            aria-controls="collapse-payfast">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('Payfast') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_payfast_enabled" id="is_payfast_enabled"
                                                        {{ isset($payment_detail['is_payfast_enabled']) && $payment_detail['is_payfast_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_payfast_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-payfast" class="collapse" aria-labelledby="payfast-payment"
                                            data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">
                                                        <h5 class="h5">{{ __('Payfast') }}</h5>
                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>
                                                    <div class="col-md-12 pb-4">
                                                        <label class="paypal-label form-control-label"
                                                            for="paypal_mode">{{ __('Payfast Environment') }}</label>
                                                        <br>
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label
                                                                class="btn btn-primary btn-sm {{ isset($payment_detail['payfast_mode']) && $payment_detail['payfast_mode'] == 'sandbox' ? 'active' : '' }}">
                                                                <input type="radio" name="payfast_mode" value="sandbox"
                                                                    {{ (isset($payment_detail['payfast_mode']) && $payment_detail['payfast_mode'] == '') || (isset($payment_detail['payfast_mode']) && $payment_detail['payfast_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>{{ __('Sandbox') }}
                                                            </label>
                                                            <label
                                                                class="btn btn-primary btn-sm {{ isset($payment_detail['payfast_mode']) && $payment_detail['payfast_mode'] == 'live' ? 'active' : '' }}">
                                                                <input type="radio" name="payfast_mode" value="live"
                                                                    {{ isset($payment_detail['payfast_mode']) && $payment_detail['payfast_mode'] == 'live' ? 'checked="checked"' : '' }}>{{ __('Live') }}
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="paytm_public_key">{{ __('Merchant ID') }}</label>
                                                            <input type="text" name="payfast_merchant_id"
                                                                id="payfast_merchant_id" class="form-control"
                                                                value="{{ isset($payment_detail['payfast_merchant_id']) ? $payment_detail['payfast_merchant_id'] : '' }}"
                                                                placeholder="{{ __('Merchant ID') }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="paytm_secret_key">{{ __('Merchant Key') }}</label>
                                                            <input type="text" name="payfast_merchant_key"
                                                                id="payfast_merchant_key" class="form-control"
                                                                value="{{ isset($payment_detail['payfast_merchant_key']) ? $payment_detail['payfast_merchant_key'] : '' }}"
                                                                placeholder="{{ __('Merchant Key') }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="paytm_secret_key">{{ __('Payfast Signature') }}</label>
                                                            <input type="text" name="payfast_signature"
                                                                id="payfast_signature" class="form-control"
                                                                value="{{ isset($payment_detail['payfast_signature']) ? $payment_detail['payfast_signature'] : '' }}"
                                                                placeholder="{{ __('Payfast Signature') }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- iyzipay -->
                                    <div class="card">
                                        <div class="card-header py-4" id="iyzipay-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-iyzipay" aria-expanded="false"
                                            aria-controls="collapse-iyzipay">

                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('Iyzipay') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_iyzipay_enabled" id="is_iyzipay_enabled"
                                                        {{ isset($payment_detail['is_iyzipay_enabled']) && $payment_detail['is_iyzipay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_iyzipay_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>

                                        <div id="collapse-iyzipay" class="collapse" aria-labelledby="iyzipay-payment"
                                            data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">
                                                        <h5 class="h5">{{ __('iyzipay') }}</h5>
                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>
                                                    <div class="col-md-12 pb-4">
                                                        <label class="iyzipay-label form-control-label"
                                                            for="iyzipay_mode">{{ __('iyzipay Environment') }}</label>
                                                        <br>
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label
                                                                class="btn btn-primary btn-sm {{ isset($payment_detail['iyzipay_mode']) && $payment_detail['iyzipay_mode'] == 'sandbox' ? 'active' : '' }}">
                                                                <input type="radio" name="iyzipay_mode" value="sandbox"
                                                                    {{ (isset($payment_detail['iyzipay_mode']) && $payment_detail['iyzipay_mode'] == '') || (isset($payment_detail['iyzipay_mode']) && $payment_detail['iyzipay_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>{{ __('Sandbox') }}
                                                            </label>
                                                            <label
                                                                class="btn btn-primary btn-sm {{ isset($payment_detail['iyzipay_mode']) && $payment_detail['iyzipay_mode'] == 'live' ? 'active' : '' }}">
                                                                <input type="radio" name="iyzipay_mode" value="live"
                                                                    {{ isset($payment_detail['iyzipay_mode']) && $payment_detail['iyzipay_mode'] == 'live' ? 'checked="checked"' : '' }}>{{ __('Live') }}
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="iyzipay_public_key">{{ __('Iyzipay Key') }}</label>
                                                            <input type="text" name="iyzipay_public_key"
                                                                id="iyzipay_public_key" class="form-control"
                                                                value="{{ isset($payment_detail['iyzipay_public_key']) ? $payment_detail['iyzipay_public_key'] : '' }}"
                                                                placeholder="{{ __('Iyzipay Key') }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="iyzipay_secret_key">{{ __('iyzipay Secret') }}</label>
                                                            <input type="text" name="iyzipay_secret_key"
                                                                id="iyzipay_secret_key" class="form-control"
                                                                value="{{ isset($payment_detail['iyzipay_secret_key']) ? $payment_detail['iyzipay_secret_key'] : '' }}"
                                                                placeholder="{{ __('Iyzipay Secret') }}" />
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                     <!-- sspay -->
                                     <div class="card">
                                        <div class="card-header py-4" id="sspay-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-sspay" aria-expanded="false"
                                            aria-controls="collapse-sspay">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('SSpay') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="hidden" name="is_sspay_enabled" value="off">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_sspay_enabled" id="is_sspay_enabled"
                                                        {{ isset($payment_detail['is_sspay_enabled']) && $payment_detail['is_sspay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_sspay_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-sspay" class="collapse" aria-labelledby="sspay-payment"
                                            data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">

                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sspay_secret_key"
                                                                class="form-control-label">{{ __('Secret Key') }}</label>
                                                            <input type="text" name="sspay_secret_key"
                                                                id="sspay_secret_key" class="form-control"
                                                                value="{{ isset($payment_detail['sspay_secret_key']) ? $payment_detail['sspay_secret_key'] : '' }}"
                                                                placeholder="{{ __('Secret Key') }}" />
                                                            @if ($errors->has('sspay_secret_key'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('sspay_secret_key') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sspay_category_code"
                                                                class="form-control-label">{{ __('Category Code') }}</label>
                                                            <input type="text" name="sspay_category_code" id="sspay_category_code"
                                                                class="form-control"
                                                                value="{{ isset($payment_detail['sspay_category_code']) ? $payment_detail['sspay_category_code'] : '' }}"
                                                                placeholder="{{ __('Category Code') }}" />
                                                            @if ($errors->has('sspay_category_code'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('sspay_category_code') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- paytab -->
                                    <div class="card">
                                        <div class="card-header py-4" id="paytab-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-paytab" aria-expanded="false"
                                            aria-controls="collapse-paytab">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('Paytab') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="hidden" name="is_paytab_enabled" value="off">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_paytab_enabled" id="is_paytab_enabled"
                                                        {{ isset($payment_detail['is_paytab_enabled']) && $payment_detail['is_paytab_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_paytab_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-paytab" class="collapse" aria-labelledby="paytab-payment"
                                            data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">

                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paytab_profile_id"
                                                                class="form-control-label">{{ __('Profile Id') }}</label>
                                                            <input type="text" name="paytab_profile_id"
                                                                id="paytab_profile_id" class="form-control"
                                                                value="{{ isset($payment_detail['paytab_profile_id']) ? $payment_detail['paytab_profile_id'] : '' }}"
                                                                placeholder="{{ __('Profile Id') }}" />
                                                            @if ($errors->has('paytab_profile_id'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('paytab_profile_id') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paytab_server_key"
                                                                class="form-control-label">{{ __('Server Key') }}</label>
                                                            <input type="text" name="paytab_server_key" id="paytab_server_key"
                                                                class="form-control"
                                                                value="{{ isset($payment_detail['paytab_server_key']) ? $payment_detail['paytab_server_key'] : '' }}"
                                                                placeholder="{{ __('Server Key') }}" />
                                                            @if ($errors->has('paytab_server_key'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('paytab_server_key') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paytab_region"
                                                                class="form-control-label">{{ __('Paytab Region') }}</label>
                                                            <input type="text" name="paytab_region" id="paytab_region"
                                                                class="form-control"
                                                                value="{{ isset($payment_detail['paytab_region']) ? $payment_detail['paytab_region'] : '' }}"
                                                                placeholder="{{ __('Paytab Region') }}" />
                                                            @if ($errors->has('paytab_region'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('paytab_region') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Benefit -->
                                    <div class="card">
                                        <div class="card-header py-4" id="benefit-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-benefit" aria-expanded="false"
                                            aria-controls="collapse-benefit">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('Benefit') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="hidden" name="is_benefit_enabled" value="off">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_benefit_enabled" id="is_benefit_enabled"
                                                        {{ isset($payment_detail['is_benefit_enabled']) && $payment_detail['is_benefit_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_benefit_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-benefit" class="collapse" aria-labelledby="benefit-payment"
                                            data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">

                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="benefit_api_key"
                                                                class="form-control-label">{{ __('Benefit Key') }}</label>
                                                            <input type="text" name="benefit_api_key"
                                                                id="benefit_api_key" class="form-control"
                                                                value="{{ isset($payment_detail['benefit_api_key']) ? $payment_detail['benefit_api_key'] : '' }}"
                                                                placeholder="{{ __('Benefit Key') }}" />
                                                            @if ($errors->has('benefit_api_key'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('benefit_api_key') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="benefit_secret_key"
                                                                class="form-control-label">{{ __('Benefit Secret Key') }}</label>
                                                            <input type="text" name="benefit_secret_key" id="benefit_secret_key"
                                                                class="form-control"
                                                                value="{{ isset($payment_detail['benefit_secret_key']) ? $payment_detail['benefit_secret_key'] : '' }}"
                                                                placeholder="{{ __('Benefit Secret Key') }}" />
                                                            @if ($errors->has('benefit_secret_key'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('benefit_secret_key') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- cashfree -->
                                    <div class="card">
                                        <div class="card-header py-4" id="cashfree-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-cashfree" aria-expanded="false"
                                            aria-controls="collapse-cashfree">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('Cashfree') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="hidden" name="is_cashfree_enabled" value="off">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_cashfree_enabled" id="is_cashfree_enabled"
                                                        {{ isset($payment_detail['is_cashfree_enabled']) && $payment_detail['is_cashfree_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_cashfree_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-cashfree" class="collapse" aria-labelledby="cashfree-payment"
                                            data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">

                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="cashfree_api_key"
                                                                class="form-control-label">{{ __('Cashfree Key') }}</label>
                                                            <input type="text" name="cashfree_api_key"
                                                                id="cashfree_api_key" class="form-control"
                                                                value="{{ isset($payment_detail['cashfree_api_key']) ? $payment_detail['cashfree_api_key'] : '' }}"
                                                                placeholder="{{ __('Cashfree Key') }}" />
                                                            @if ($errors->has('cashfree_api_key'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('cashfree_api_key') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="cashfree_secret_key"
                                                                class="form-control-label">{{ __('Cashfree Secret Key') }}</label>
                                                            <input type="text" name="cashfree_secret_key" id="cashfree_secret_key"
                                                                class="form-control"
                                                                value="{{ isset($payment_detail['cashfree_secret_key']) ? $payment_detail['cashfree_secret_key'] : '' }}"
                                                                placeholder="{{ __('Cashfree Secret Key') }}" />
                                                            @if ($errors->has('cashfree_secret_key'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('cashfree_secret_key') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Aamarpay -->
                                    <div class="card">
                                        <div class="card-header py-4" id="aamarpay-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-aamarpay" aria-expanded="false"
                                            aria-controls="collapse-aamarpay">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('Aamarpay') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="hidden" name="is_aamarpay_enabled" value="off">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_aamarpay_enabled" id="is_aamarpay_enabled"
                                                        {{ isset($payment_detail['is_aamarpay_enabled']) && $payment_detail['is_aamarpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_aamarpay_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-aamarpay" class="collapse" aria-labelledby="aamarpay-payment"
                                            data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">

                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="aamarpay_store_id"
                                                                class="form-control-label">{{ __('Store Id') }}</label>
                                                            <input type="text" name="aamarpay_store_id"
                                                                id="aamarpay_store_id" class="form-control"
                                                                value="{{ isset($payment_detail['aamarpay_store_id']) ? $payment_detail['aamarpay_store_id'] : '' }}"
                                                                placeholder="{{ __('Store Id') }}" />
                                                            @if ($errors->has('aamarpay_store_id'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('aamarpay_store_id') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="aamarpay_signature_key"
                                                                class="form-control-label">{{ __('Signature Key') }}</label>
                                                            <input type="text" name="aamarpay_signature_key" id="aamarpay_signature_key"
                                                                class="form-control"
                                                                value="{{ isset($payment_detail['aamarpay_signature_key']) ? $payment_detail['aamarpay_signature_key'] : '' }}"
                                                                placeholder="{{ __('Signature Key') }}" />
                                                            @if ($errors->has('aamarpay_signature_key'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('aamarpay_signature_key') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="aamarpay_description"
                                                                class="form-control-label">{{ __('Description') }}</label>
                                                            <input type="text" name="aamarpay_description" id="aamarpay_description"
                                                                class="form-control"
                                                                value="{{ isset($payment_detail['aamarpay_description']) ? $payment_detail['aamarpay_description'] : '' }}"
                                                                placeholder="{{ __('Description') }}" />
                                                            @if ($errors->has('aamarpay_description'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('aamarpay_description') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                      <!-- PayTR -->
                                      <div class="card">
                                        <div class="card-header py-4" id="Paytr-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-Paytr" aria-expanded="false"
                                            aria-controls="collapse-Paytr">
                                            <h6 class="mb-0"><i
                                                    class="far fa-credit-card mr-3"></i>{{ __('PayTR') }}
                                                <div class="custom-control custom-switch float-right mx-3">
                                                    <input type="hidden" name="is_paytr_enabled" value="off">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_paytr_enabled" id="is_paytr_enabled"
                                                        {{ isset($payment_detail['is_paytr_enabled']) && $payment_detail['is_paytr_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_paytr_enabled">{{ __('Enable') }}</label>
                                                </div>
                                            </h6>
                                        </div>
                                        <div id="collapse-Paytr" class="collapse" aria-labelledby="Paytr-payment"
                                            data-parent="#payment-gateways">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 py-2">

                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paytr_merchant_id"
                                                                class="form-control-label">{{ __('Merchant Id') }}</label>
                                                            <input type="text" name="paytr_merchant_id"
                                                                id="paytr_merchant_id" class="form-control"
                                                                value="{{ isset($payment_detail['paytr_merchant_id']) ? $payment_detail['paytr_merchant_id'] : '' }}"
                                                                placeholder="{{ __('Merchant Id') }}" />
                                                            @if ($errors->has('paytr_merchant_id'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('paytr_merchant_id') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paytr_merchant_key"
                                                                class="form-control-label">{{ __('Merchant Key') }}</label>
                                                            <input type="text" name="paytr_merchant_key" id="paytr_merchant_key"
                                                                class="form-control"
                                                                value="{{ isset($payment_detail['paytr_merchant_key']) ? $payment_detail['paytr_merchant_key'] : '' }}"
                                                                placeholder="{{ __('Merchant Key') }}" />
                                                            @if ($errors->has('paytr_merchant_key'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('paytr_merchant_key') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paytr_merchant_salt"
                                                                class="form-control-label">{{ __('Merchant Salt') }}</label>
                                                            <input type="text" name="paytr_merchant_salt" id="paytr_merchant_salt"
                                                                class="form-control"
                                                                value="{{ isset($payment_detail['paytr_merchant_salt']) ? $payment_detail['paytr_merchant_salt'] : '' }}"
                                                                placeholder="{{ __('Merchant Salt') }}" />
                                                            @if ($errors->has('paytr_merchant_salt'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('paytr_merchant_salt') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-right">
                                    {{ Form::hidden('from', 'payment') }}
                                    <button type="submit"
                                        class="btn btn-sm btn-primary rounded-pill">{{ __('Save changes') }}</button>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            <div id="tabs-5" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h6 mb-0">{{ __('ReCaptcha Settings') }}</h5>
                        <small>{{ __('Test to tell human and bots apart by adding Recaptcha setting.') }}</small>
                    </div>
                    <div id="recaptcha-settings" class="tab-pane">
                        <div class="col-md-12">
                            <div class="row justify-content-between align-items-center">
                                <div class="col-md-6 col-sm-6 mb-3 mb-md-0">
                                    {{-- <h4 class="h4 font-weight-400 float-left pb-2">{{ __('ReCaptcha settings') }}</h4> --}}
                                </div>
                            </div>
                            <div class="p-3">
                                <form method="POST" action="{{ route('recaptcha.settings.store') }}"
                                    accept-charset="UTF-8">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input"
                                                    name="recaptcha_module" id="recaptcha_module" value="yes"
                                                    {{ env('RECAPTCHA_MODULE') == 'yes' ? 'checked="checked"' : '' }}>
                                                <label class="custom-control-label form-control-label"
                                                    for="recaptcha_module">
                                                    {{ __('Google Recaptcha') }}
                                                    <a href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/"
                                                        target="_blank" class="text-blue">
                                                        <small>({{ __('How to Get Google reCaptcha Site and Secret key') }})</small>
                                                    </a>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                            <label for="google_recaptcha_key"
                                                class="form-control-label">{{ __('Google Recaptcha Key') }}</label>
                                            <input class="form-control"
                                                placeholder="{{ __('Enter Google Recaptcha Key') }}"
                                                name="google_recaptcha_key" type="text"
                                                value="{{ env('NOCAPTCHA_SITEKEY') }}" id="google_recaptcha_key">
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                            <label for="google_recaptcha_secret"
                                                class="form-control-label">{{ __('Google Recaptcha Secret') }}</label>
                                            <input class="form-control "
                                                placeholder="{{ __('Enter Google Recaptcha Secret') }}"
                                                name="google_recaptcha_secret" type="text"
                                                value="{{ env('NOCAPTCHA_SECRET') }}" id="google_recaptcha_secret">
                                        </div>
                                    </div>
                                    <div class="col-lg-12  text-right">
                                        <input type="submit" value="{{ __('Save Changes') }}"
                                            class="btn btn-sm btn-primary rounded-pill">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--storage Setting-->

            <div id="storage_setting" class="tabs-card d-none">
                <div class="card">
                    {{ Form::open(['route' => 'storage.setting.store', 'enctype' => 'multipart/form-data']) }}
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <h5 class="">{{ __('Storage Settings') }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="pe-2">
                                <input type="radio" class="btn-check" name="storage_setting" id="local-outlined"
                                    autocomplete="off" {{ $settings['storage_setting'] == 'local' ? 'checked' : '' }}
                                    value="local" checked>
                                <label class="btn btn-outline-primary"
                                    for="local-outlined">{{ __('Local') }}</label>
                            </div>
                            <div class="pe-2">
                                <input type="radio" class="btn-check" name="storage_setting" id="s3-outlined"
                                    autocomplete="off" {{ $settings['storage_setting'] == 's3' ? 'checked' : '' }}
                                    value="s3">
                                <label class="btn btn-outline-primary" for="s3-outlined">
                                    {{ __('AWS S3') }}</label>
                            </div>

                            <div class="pe-2">
                                <input type="radio" class="btn-check" name="storage_setting" id="wasabi-outlined"
                                    autocomplete="off" {{ $settings['storage_setting'] == 'wasabi' ? 'checked' : '' }}
                                    value="wasabi">
                                <label class="btn btn-outline-primary"
                                    for="wasabi-outlined">{{ __('Wasabi') }}</label>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="local-setting row ">

                                <div class="form-group col-8 switch-width">
                                    {{ Form::label('local_storage_validation', __('Only Upload Files'), ['class' => ' form-control-label']) }}
                                    <select name="local_storage_validation[]" class="multi-select"
                                        data-toggle="select2" id="local_storage_validation" multiple="multiple">
                                        @foreach ($file_type as $f)
                                            <option @if (in_array($f, $local_storage_validations)) selected @endif>
                                                {{ $f }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label"
                                            for="local_storage_max_upload_size">{{ __('Max upload size ( In KB)') }}</label>
                                        <input type="number" name="local_storage_max_upload_size"
                                            class="form-control"
                                            value="{{ !isset($settings['local_storage_max_upload_size']) || is_null($settings['local_storage_max_upload_size']) ? '' : $settings['local_storage_max_upload_size'] }}"
                                            placeholder="{{ __('Max upload size') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="s3-setting row {{ $settings['storage_setting'] == 's3' ? ' ' : 'd-none' }}">

                                <div class=" row ">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label"
                                                for="s3_key">{{ __('S3 Key') }}</label>
                                            <input type="text" name="s3_key" class="form-control"
                                                value="{{ !isset($settings['s3_key']) || is_null($settings['s3_key']) ? '' : $settings['s3_key'] }}"
                                                placeholder="{{ __('S3 Key') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label"
                                                for="s3_secret">{{ __('S3 Secret') }}</label>
                                            <input type="text" name="s3_secret" class="form-control"
                                                value="{{ !isset($settings['s3_secret']) || is_null($settings['s3_secret']) ? '' : $settings['s3_secret'] }}"
                                                placeholder="{{ __('S3 Secret') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label"
                                                for="s3_region">{{ __('S3 Region') }}</label>
                                            <input type="text" name="s3_region" class="form-control"
                                                value="{{ !isset($settings['s3_region']) || is_null($settings['s3_region']) ? '' : $settings['s3_region'] }}"
                                                placeholder="{{ __('S3 Region') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label"
                                                for="s3_bucket">{{ __('S3 Bucket') }}</label>
                                            <input type="text" name="s3_bucket" class="form-control"
                                                value="{{ !isset($settings['s3_bucket']) || is_null($settings['s3_bucket']) ? '' : $settings['s3_bucket'] }}"
                                                placeholder="{{ __('S3 Bucket') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label"
                                                for="s3_url">{{ __('S3 URL') }}</label>
                                            <input type="text" name="s3_url" class="form-control"
                                                value="{{ !isset($settings['s3_url']) || is_null($settings['s3_url']) ? '' : $settings['s3_url'] }}"
                                                placeholder="{{ __('S3 URL') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label"
                                                for="s3_endpoint">{{ __('S3 Endpoint') }}</label>
                                            <input type="text" name="s3_endpoint" class="form-control"
                                                value="{{ !isset($settings['s3_endpoint']) || is_null($settings['s3_endpoint']) ? '' : $settings['s3_endpoint'] }}"
                                                placeholder="{{ __('S3 Bucket') }}">
                                        </div>
                                    </div>
                                    <div class="form-group col-8 switch-width">
                                        {{ Form::label('s3_storage_validation', __('Only Upload Files'), ['class' => ' form-control-label']) }}
                                        <select name="s3_storage_validation[]" class="multi-select"
                                            data-toggle="select2" id="s3_storage_validation" multiple="multiple">
                                            @foreach ($file_type as $f)
                                                <option @if (in_array($f, $s3_storage_validations)) selected @endif>
                                                    {{ $f }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label"
                                                for="s3_max_upload_size">{{ __('Max upload size ( In KB)') }}</label>
                                            <input type="number" name="s3_max_upload_size" class="form-control"
                                                value="{{ !isset($settings['s3_max_upload_size']) || is_null($settings['s3_max_upload_size']) ? '' : $settings['s3_max_upload_size'] }}"
                                                placeholder="{{ __('Max upload size') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="wasabi-setting row {{ $settings['storage_setting'] == 'wasabi' ? ' ' : 'd-none' }}">
                                <div class=" row ">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label"
                                                for="s3_key">{{ __('Wasabi Key') }}</label>
                                            <input type="text" name="wasabi_key" class="form-control"
                                                value="{{ !isset($settings['wasabi_key']) || is_null($settings['wasabi_key']) ? '' : $settings['wasabi_key'] }}"
                                                placeholder="{{ __('Wasabi Key') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label"
                                                for="s3_secret">{{ __('Wasabi Secret') }}</label>
                                            <input type="text" name="wasabi_secret" class="form-control"
                                                value="{{ !isset($settings['wasabi_secret']) || is_null($settings['wasabi_secret']) ? '' : $settings['wasabi_secret'] }}"
                                                placeholder="{{ __('Wasabi Secret') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label"
                                                for="s3_region">{{ __('Wasabi Region') }}</label>
                                            <input type="text" name="wasabi_region" class="form-control"
                                                value="{{ !isset($settings['wasabi_region']) || is_null($settings['wasabi_region']) ? '' : $settings['wasabi_region'] }}"
                                                placeholder="{{ __('Wasabi Region') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label"
                                                for="wasabi_bucket">{{ __('Wasabi Bucket') }}</label>
                                            <input type="text" name="wasabi_bucket" class="form-control"
                                                value="{{ !isset($settings['wasabi_bucket']) || is_null($settings['wasabi_bucket']) ? '' : $settings['wasabi_bucket'] }}"
                                                placeholder="{{ __('Wasabi Bucket') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label"
                                                for="wasabi_url">{{ __('Wasabi URL') }}</label>
                                            <input type="text" name="wasabi_url" class="form-control"
                                                value="{{ !isset($settings['wasabi_url']) || is_null($settings['wasabi_url']) ? '' : $settings['wasabi_url'] }}"
                                                placeholder="{{ __('Wasabi URL') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label"
                                                for="wasabi_root">{{ __('Wasabi Root') }}</label>
                                            <input type="text" name="wasabi_root" class="form-control"
                                                value="{{ !isset($settings['wasabi_root']) || is_null($settings['wasabi_root']) ? '' : $settings['wasabi_root'] }}"
                                                placeholder="{{ __('Wasabi Bucket') }}">
                                        </div>
                                    </div>
                                    <div class="form-group col-8 switch-width">
                                        {{ Form::label('wasabi_storage_validation', __('Only Upload Files'), ['class' => 'form-control-label']) }}

                                        <select name="wasabi_storage_validation[]" class="multi-select"
                                            data-toggle="select2" id="wasabi_storage_validation" multiple='multiple'>
                                            @foreach ($file_type as $f)
                                                <option @if (in_array($f, $wasabi_storage_validations)) selected @endif>
                                                    {{ $f }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label"
                                                for="wasabi_root">{{ __('Max upload size ( In KB)') }}</label>
                                            <input type="number" name="wasabi_max_upload_size" class="form-control"
                                                value="{{ !isset($settings['wasabi_max_upload_size']) || is_null($settings['wasabi_max_upload_size']) ? '' : $settings['wasabi_max_upload_size'] }}"
                                                placeholder="{{ __('Max upload size') }}">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card-footer  text-right">
                            <input type="submit"
                                value="{{ __('Save Changes') }}"class="btn btn-sm btn-primary rounded-pill">
                        </div>

                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            <div id="tabs-6" class="tabs-card d-none">
                {{ Form::open(['route' => ['seo.settings'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="h6 md-0">{{ __('SEO Settings') }}</h5>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="text-right">
                                    <a href="#" class="btn btn-sm btn-primary rounded-pill ml-2" data-url="{{ route('generate',['seo']) }}" data-ajax-popup-over="true" data-bs-placement="top" data-size="md" title="{{ __('Generate') }}" data-title="{{__('Generate content with AI')}}">
                                        <span class="btn-inner--text text-white"> <span><i class="fas fa-robot"></i></span> {{__('Generate with AI')}}</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-group">
                                        {{ Form::label('meta_keywords', __('Meta Keywords'), ['class' => 'form-control-label']) }}
                                        {{ Form::text('meta_keywords', isset($settings['meta_keywords']) ? $settings['meta_keywords'] : '', ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('meta_image', __('Meta Image'), ['class' => 'form-control-label']) }}
                                    <input type="file" name="meta_image" id="meta_image"
                                        class="custom-input-file"
                                        onchange="document.getElementById('meta_image_pre').src = window.URL.createObjectURL(this.files[0])" />
                                    <label for="meta_image">
                                        <i class="fa fa-upload"></i>
                                        <span>{{ __('Choose a file…') }}</span>
                                    </label>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 rtl-img-set" style="left:50px">
                                    @php
                                        $src = isset($settings['meta_image']) && !empty($settings['meta_image']) ? asset(Storage::url('uploads/logo/' . $settings['meta_image'])) : asset(Storage::url('uploads/logo/meta_image.png'));
                                    @endphp
                                    <a href="{{ $src }}" target="_blank">
                                        <img src="{{ $src }}" id="meta_image_pre" class="img_setting"
                                            style="height:100px" />
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        {{ Form::label('meta_description', __('Meta Description'), ['class' => 'form-control-label']) }}
                                        {{ Form::textarea('meta_description', isset($settings['meta_description']) ? $settings['meta_description'] : '', ['class' => 'form-control', 'rows' => '3', 'data-toggle' => 'autosize']) }}
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit"
                                class="btn btn-sm btn-primary rounded-pill">{{ __('Save changes') }}</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>

            <div id="tabs-7" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="h6 md-0">{{ __('Cache Settings') }}</h5>
                                <small>This is a page meant for more advanced users, simply ignore it if you don't
                                    understand what cache is.</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="Current cache size">{{ __('Current cache size') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="input-group search-form">
                                <input type="text" value="{{ Utility::GetCacheSize() }}" class="form-control"
                                    readonly>
                                <span class="input-group-text bg-transparent">{{ __('MB') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="text-right col-lg-12">
                            <a href="{{ url('config-cache') }}"
                                class="btn btn-sm btn-primary rounded-pill">{{ __('Clear Cache') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div id="cookie-settings" class="tabs-card d-none">
                {{ Form::model($settings, ['route' => 'cookie.setting', 'method' => 'post']) }}
                <div class="card">
                    <div
                        class="card-header flex-column flex-lg-row  d-flex align-items-lg-center gap-2 justify-content-between">
                        <h6>{{ __('Cookie Settings') }}</h6>

                        <div class="custom-control custom-switch" onclick="enablecookie()">
                            <input type="checkbox" class="custom-control-input" name="enable_cookie"
                                id="enable_cookie" {{ $settings['enable_cookie'] == 'on' ? ' checked ' : '' }}>
                            <label class="custom-control-label form-control-label" for="enable_cookie">
                                {{ __('Enable cookie') }}
                                <a href="#" target="_blank" class="text-blue"></a>
                            </label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-12 col-md-12">
                            <div class="text-right">
                                <a href="#" class="btn btn-sm btn-primary rounded-pill ml-2" data-url="{{ route('generate',['cookie']) }}" data-ajax-popup-over="true" data-bs-placement="top" data-size="md" title="{{ __('Generate') }}" data-title="{{__('Generate content with AI')}}">
                                    <span class="btn-inner--text text-white"> <span><i class="fas fa-robot"></i></span> {{__('Generate with AI')}}</span>
                                </a>
                            </div>
                        </div>
                        <div class="row cookieDiv {{ $settings['enable_cookie'] == 'off' ? 'disabledCookie ' : '' }}">
                            <div class="col-md-12">

                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="cookie_logging"
                                        id="cookie_logging"
                                        {{ $settings['cookie_logging'] == 'on' ? ' checked ' : '' }}>
                                    <label class="custom-control-label form-control-label" for="cookie_logging">
                                        {{ __('Enable logging') }}
                                        <a href="#" target="_blank" class="text-blue"></a>
                                    </label>
                                </div>

                                <div class="form-group col-lg-12">
                                    {{ Form::label('cookie_title', __('Cookie Title'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('cookie_title', null, ['class' => 'form-control']) }}
                                </div>

                                <div class="form-group col-lg-12">
                                    {{ Form::label('cookie_description', __('Cookie Description'), ['class' => 'form-control-label']) }}
                                    {!! Form::textarea('cookie_description', $settings['cookie_description'], [
                                        'class' => 'form-control',
                                        'rows' => '3',
                                    ]) !!}
                                </div>

                            </div>

                            <div class="col-md-12">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="necessary_cookies"
                                        id="necessary_cookies" value="on" checked onclick="return false">
                                    <label class="custom-control-label form-control-label" for="necessary_cookies">
                                        {{ __('Strictly necessary cookies') }}
                                        <a href="#" target="_blank" class="text-blue"></a>
                                    </label>
                                </div>

                                <div class="form-group col-lg-12">
                                    {{ Form::label('strictly_cookie_title', __(' Strictly Cookie Title'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('strictly_cookie_title', null, ['class' => 'form-control']) }}
                                </div>

                                <div class="form-group col-lg-12">
                                    {{ Form::label('strictly_cookie_description', __('Strictly Cookie Description'), ['class' => 'form-control-label']) }}
                                    {!! Form::textarea('strictly_cookie_description', $settings['strictly_cookie_description'], [
                                        'class' => 'form-control',
                                        'rows' => '3',
                                    ]) !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <h5>{{ __('More Information') }}</h5>

                                <div class="form-group col-lg-12">
                                    {{ Form::label('more_information_title', __(' Contact Us Description'), ['class' => 'form-control-label']) }}
                                    {{ Form::textarea('more_information_title', null, ['class' => 'form-control', 'rows' => '3']) }}
                                </div>

                                <div class="form-group col-lg-12">
                                    {{ Form::label('contactus_url', __('Contact Us URL'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('contactus_url', null, ['class' => 'form-control']) }}
                                </div>

                            </div>

                        </div>

                        <div
                            class="d-flex align-items-center gap-2 flex-sm-column flex-lg-row justify-content-between cookieDiv {{ $settings['cookie_logging'] == 'off' ? 'disabledCookie ' : '' }}">
                            @if (isset($settings['cookie_logging']) && $settings['cookie_logging'] == 'on')
                                <div class="col-md-12 mb-2">
                                    <div class="align-items-center justify-content-between">
                                        {{ Form::label('file', __('Download cookie accepted data'), ['class' => 'form-control-label w-auto m-0']) }}
                                        <div>
                                            <a href="{{ asset(Storage::url('uploads/sample')) . '/data.csv' }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fa fa-download"></i> {{ __('Download') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="text-right">
                            <button type="submit"
                                class="btn btn-sm btn-primary rounded-pill">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>

            <div id="pills-chatgpt-settings" class="tabs-card d-none">
                {{ Form::open(['route' => ['settings.chatgptkey'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="h6 md-0">{{ __('Chat GPT Key Settings') }}</h5>
                                <small>{{ __('Edit your key details') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        {{ Form::text('chatgpt_key',isset($settings['chatgpt_key']) ? $settings['chatgpt_key'] : '',['class'=>'form-control','placeholder'=>__('Enter Chatgpt Key Here')]) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit"
                                class="btn btn-sm btn-primary rounded-pill">{{ __('Save changes') }}</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
            @endsection

            @push('script')
                <script>
                    // For Sidebar Tabs
                    $(document).ready(function() {
                        $('.list-group-item').on('click', function() {
                            var href = $(this).attr('data-href');
                            $('.tabs-card').addClass('d-none');
                            $(href).removeClass('d-none');
                            $('#tabs .list-group-item').removeClass('text-primary');
                            $(this).addClass('text-primary');
                        });
                    });

                    // For Test Email Send
                    $(document).on("click", '.send_email', function(e) {
                        e.preventDefault();
                        var title = $(this).attr('data-title');
                        var size = 'md';
                        var url = $(this).attr('data-url');
                        if (typeof url != 'undefined') {
                            $("#commonModal .modal-title").html(title);
                            $("#commonModal .modal-dialog").addClass('modal-' + size);
                            $("#commonModal").modal('show');

                            $.post(url, {
                                mail_driver: $("#mail_driver").val(),
                                mail_host: $("#mail_host").val(),
                                mail_port: $("#mail_port").val(),
                                mail_username: $("#mail_username").val(),
                                mail_password: $("#mail_password").val(),
                                mail_encryption: $("#mail_encryption").val(),
                                mail_from_address: $("#mail_from_address").val(),
                                mail_from_name: $("#mail_from_name").val(),
                            }, function(data) {
                                $('#commonModal .modal-body').html(data);
                            });
                        }
                    });
                    $(document).on('submit', '#test_email', function(e) {
                        e.preventDefault();
                        $("#email_sanding").show();
                        var post = $(this).serialize();
                        var url = $(this).attr('action');
                        $.ajax({
                            type: "post",
                            url: url,
                            data: post,
                            cache: false,
                            success: function(data) {
                                if (data.is_success) {
                                    show_toastr('Success', data.message, 'success');
                                } else {
                                    show_toastr('Error', data.message, 'error');
                                }
                                $("#email_sanding").hide();
                            }
                        });
                    })
                </script>

                {{-- <script>
                $(document).ready(function() {
                    if ($('.gdpr_fulltime').is(':checked')) {

                        $('.fulltime').show();
                    } else {

                        $('.fulltime').hide();
                    }

                    $('#gdpr_cookie').on('change', function() {
                        if ($('.gdpr_fulltime').is(':checked')) {

                            $('.fulltime').show();
                        } else {

                            $('.fulltime').hide();
                        }
                    });
                });
            </script> --}}

                <script>
                    function check_theme(color_val) {
                        $('body').removeClass('.theme_color').val();
                        $('body').addClass(color_val);
                        $('input[value="' + color_val + '"]').attr('checked', true);
                        $('input[value="' + color_val + '"]').prop('checked', true);
                        $('a[data-value]').removeClass('active_color');
                        $('a[data-value="' + color_val + '"]').addClass('active_color');
                    }

                    var themescolors = document.querySelectorAll(".themes-color > a");
                    for (var h = 0; h < themescolors.length; h++) {
                        var c = themescolors[h];
                        c.addEventListener("click", function(event) {
                            var targetElement = event.target;
                            if (targetElement.tagName == "SPAN") {
                                targetElement = targetElement.parentNode;
                            }
                            var temp = targetElement.getAttribute("data-value");
                            removeClassByPrefix(document.querySelector("body"), "theme-");
                            document.querySelector("body").classList.add(temp);
                        });
                    }

                    if ($(".multi-select").length > 0) {
                        $($(".multi-select")).each(function(index, element) {
                            var id = $(element).attr('id');
                            var multipleCancelButton = new Choices(
                                '#' + id, {
                                    removeItemButton: true,

                                }
                            );
                        });
                    }

                    $(document).on('change', '[name=storage_setting]', function() {
                        if ($(this).val() == 's3') {
                            $('.s3-setting').removeClass('d-none');
                            $('.wasabi-setting').addClass('d-none');
                            $('.local-setting').addClass('d-none');
                        } else if ($(this).val() == 'wasabi') {
                            $('.s3-setting').addClass('d-none');
                            $('.wasabi-setting').removeClass('d-none');
                            $('.local-setting').addClass('d-none');
                        } else {
                            $('.s3-setting').addClass('d-none');
                            $('.wasabi-setting').addClass('d-none');
                            $('.local-setting').removeClass('d-none');
                        }
                    });
                </script>

                <script type="text/javascript">
                    function enablecookie() {
                        const element = $('#enable_cookie').is(':checked');
                        $('.cookieDiv').addClass('disabledCookie');
                        if (element == true) {
                            $('.cookieDiv').removeClass('disabledCookie');
                            $("#cookie_logging").attr('checked', true);
                        } else {
                            $('.cookieDiv').addClass('disabledCookie');
                            $("#cookie_logging").attr('checked', false);
                        }
                    }
                </script>
            @endpush
