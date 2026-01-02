@extends('layouts.admin')

@section('title')
    {{ __('Site Settings') }}
@endsection


@php
    $logo = \App\Models\Utility::get_file('logo/');
    $path_imgs = \App\Models\Utility::get_file('/');
    $setting = \App\Models\Utility::settings();
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
    if ($settings['color']) {
        $color = $settings['color'];
        // dd($color);
    }
@endphp

@section('content')
    <div class="row">
        <div class="col-lg-4 order-lg-2">
            <div class="card">
                <div class="list-group list-group-flush" id="tabs">
                    @if (Auth::user()->type != 'client')
                        <div data-href="#tabs-9" class="list-group-item text-primary">
                            <div class="media">
                                <i class="fas fa-cog pt-1"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Theme Settings') }}</a>
                                    <p class="mb-0 text-sm">{{ __('Edit your Theme.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (Auth::user()->type != 'client')
                        <div data-href="#tabs-1" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-cog pt-1"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Invoice Settings') }}</a>
                                    <p class="mb-0 text-sm">{{ __('Edit your Invoice details.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (Auth::user()->type != 'client')
                        <div data-href="#tabs-4" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-money-check-alt pt-1"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Payment Settings') }}</a>
                                    <p class="mb-0 text-sm">{{ __('Edit your Payment details.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div data-href="#tabs-2" class="list-group-item">
                        <div class="media">
                            <i class="fas fa-file pt-1"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1">{{ __('My Billing Detail') }}</a>
                                <p class="mb-0 text-sm">{{ __('This detail will show in your Invoice.') }}</p>
                            </div>
                        </div>
                    </div>

                    @if (Auth::user()->type != 'client')
                        <div data-href="#tabs-3" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-percent pt-1"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Tax') }}</a>
                                    <p class="mb-0 text-sm">{{ __('You can manage your tax rate here.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (Auth::user()->type == 'owner')
                        <div data-href="#tabs-10" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-cog pt-1"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Contract type') }}</a>
                                    <p class="mb-0 text-sm">{{ __('You can manage your Contract type here.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (Auth::user()->type != 'client')
                        <div data-href="#tabs-5" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-stopwatch pt-1"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Tracker Settings') }}</a>
                                    <p class="mb-0 text-sm">{{ __('You can manage your tracker interval time here.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (\Auth::user()->type == 'owner')
                        <div data-href="#tabs-6" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-stopwatch pt-1"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Zoom Meeting') }}</a>
                                    <p class="mb-0 text-sm">{{ __('You can manage your Meeting setting Information.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (\Auth::user()->type == 'owner')
                        <div data-href="#tabs-7" class="list-group-item">
                            <div class="media">
                                <i class="fab fa-slack"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Slack Notification') }}</a>
                                    <p class="mb-0 text-sm">{{ __('You can manage your slack setting information.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (\Auth::user()->type == 'owner')
                        <div data-href="#tabs-8" class="list-group-item">
                            <div class="media">
                                <i class="fa fa-paper-plane"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Telegram Notification') }}</a>
                                    <p class="mb-0 text-sm">{{ __('You can manage your telegram setting information.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (\Auth::user()->type == 'owner')
                        <div data-href="#tabs-11" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-calendar-week"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Google Calendar') }}</a>
                                    <p class="mb-0 text-sm">{{ __('You can manage your Calendar setting information.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (\Auth::user()->type == 'owner')
                        <div data-href="#tabs-12" class="list-group-item">
                            <div class="media">
                                <i class="fab fa-atlassian"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Webhook Setting') }}</a>
                                    <p class="mb-0 text-sm">{{ __('You can manage your Webhook setting information.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (\Auth::user()->type == 'owner')
                        <div data-href="#tabs-13" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-envelope pt-1"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Email Settings') }}</a>
                                    <p class="mb-0 text-sm">{{ __('Edit your email details.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-8 order-lg-1">
            @if (Auth::user()->type != 'client')
                <div id="tabs-9" class="tabs-card">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h6 mb-0">{{ __('Theme Settings') }}</h5>
                        </div>
                        <div class="card-body">
                            {{ Form::open(['route' => ['settings.store'], 'id' => 'update_setting', 'enctype' => 'multipart/form-data']) }}
                            {{-- <form action="" id="update_setting" enctype="multipart/form-data"> --}}
                            <div class="row">
                                <div class="col-6">
                                    <h6 class="">
                                        <i data-feather="credit-card"
                                            class="me-2"></i>{{ __('Primary color settings') }}
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

                                        <a href="#!" class="theme-3 {{ $color == '#449fc6' ? 'active_color' : '' }}"
                                            data-value="theme-3" onclick="check_theme('#449fc6')"></a>
                                        <input type="radio" class="theme_color " name="color" value="#449fc6"
                                            style="display: none;" {{ $color == '#449fc6' ? 'checked' : '' }}>

                                        <a href="#!" class="theme-4 {{ $color == '#51459d' ? 'active_color' : '' }}"
                                            data-value="theme-4" onclick="check_theme('#51459d')"></a>
                                        <input type="radio" class="theme_color " name="color" value="#51459d"
                                            style="display: none;" {{ $color == '#51459d' ? 'checked' : '' }}>

                                    </div>
                                </div>

                                <div class="col-6">
                                    <h6 class="">
                                        <i data-feather="credit-card" class="me-2"></i>{{ __('RTL settings') }}
                                    </h6>

                                    <div class="col-6 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="enable_rtl"
                                                id="enable_rtl"
                                                {{ \App\Models\Utility::settingsById(\Auth::user()->creatorId())['enable_rtl'] == 'on' ? 'checked' : '' }}>
                                            <label class="custom-control-label form-control-label"
                                                for="enable_rtl">{{ __('Enable RTL') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-0 mt-3">
                                            {{ Form::label('default_owner_language', __('Default Language'), ['class' => 'form-control-label text-dark']) }}
                                            <select name="default_owner_language" id="default_owner_language"
                                                class="form-control select2">
                                                @foreach (App\Models\Utility::languages() as $code => $lang)
                                                    <option @if (App\Models\Utility::getValByName('default_owner_language') == $code) selected @endif
                                                        value="{{ $code }}">{{ ucfirst($lang) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <hr />

                            <div class="text-right">
                                {{ Form::hidden('from', 'site_setting') }}
                                <button type="submit"
                                    class="btn btn-sm btn-primary rounded-pill">{{ __('Save changes') }}</button>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            @endif

            @if (Auth::user()->type != 'client')
                <div id="tabs-1" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h6 mb-0">{{ __('Invoice Settings') }}</h5>
                        </div>
                        <div class="card-body">
                            {{ Form::open(['route' => ['settings.store'], 'id' => 'update_setting', 'enctype' => 'multipart/form-data']) }}
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('light_logo', __('Light Logo'), ['class' => 'form-control-label']) }}
                                        <input type="file" name="light_logo" id="light_logo"
                                            class="custom-input-file"
                                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])" />
                                        <label for="light_logo">
                                            <i class="fa fa-upload"></i>
                                            <span>{{ __('Choose a file…') }}</span>
                                        </label>
                                        @error('light_logo')
                                            <span class="light_logo" role="alert">
                                                <small class="text-danger">{{ $message }}</small>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 pt-5">
                                    @if (!empty($details['light_logo']))
                                        <a href="{{ $path_imgs . $details['light_logo'] }}" target="_blank">
                                            <img src="{{ $path_imgs . $details['light_logo'] }}" id="blah"
                                                class="img_setting" />
                                        </a>
                                    @else
                                        <a href="{{ $logo . 'logo.png' . '?' . time() }}" target="_blank">
                                            <img src="{{ $logo . 'logo.png' . '?' . time() }}" class="img_setting" />
                                        </a>
                                    @endif
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('dark_logo', __('Dark Logo'), ['class' => 'form-control-label']) }}
                                        <input type="file" name="dark_logo" id="dark_logo" class="custom-input-file"
                                            onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])" />
                                        <label for="dark_logo">
                                            <i class="fa fa-upload"></i>
                                            <span>{{ __('Choose a file…') }}</span>
                                        </label>
                                        @error('dark_logo')
                                            <span class="dark_logo" role="alert">
                                                <small class="text-danger">{{ $message }}</small>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 pt-5">
                                    @if (!empty($details['dark_logo']))
                                        <a href="{{ $path_imgs . $details['dark_logo'] }}" target="_blank">
                                            <img src="{{ $path_imgs . $details['dark_logo'] }}" id="blah1"
                                                class="img_setting" />
                                        </a>
                                    @else
                                        <a href="{{ $logo . 'logo-dark.png' . '?' . time() }}" target="_blank">
                                            <img src="{{ $logo . 'logo-dark.png' . '?' . time() }}" id="blah1"
                                                class="img_setting" />
                                        </a>
                                    @endif
                                </div>
                                {{-- @if (Utility::plancheck()['enable_chatgpt'] == 'on')
                                <div class="col-12 col-md-12">
                                    <div class="text-right">
                                        <a href="#" class="btn btn-sm btn-primary rounded-pill ml-2" data-url="{{ route('generate',['invoices_settings']) }}" data-ajax-popup-over="true" data-bs-placement="top" data-size="md" title="{{ __('Generate') }}" data-title="{{__('Generate content with AI')}}">
                                            <span class="btn-inner--text text-white"> <span><i class="fas fa-robot"></i></span> {{__('Generate with AI')}}</span>
                                        </a>
                                    </div>
                                </div>
                            @endif --}}
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ Form::label('invoice_footer_title', __('Invoice Footer Title'), ['class' => 'form-control-label']) }}
                                        <input type="text" name="invoice_footer_title" id="invoice_footer_title"
                                            class="form-control" value="{{ $details['invoice_footer_title'] }}" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ Form::label('invoice_footer_note', __('Invoice Footer Note'), ['class' => 'form-control-label']) }}
                                        <small
                                            class="form-text text-muted mb-2 mt-0">{{ __('This textarea will autosize while you type') }}</small>
                                        {{ Form::textarea('invoice_footer_note', $details['invoice_footer_note'], ['class' => 'form-control', 'rows' => '3', 'data-toggle' => 'autosize']) }}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 pt-3">
                                    <a href="{{ route('invoice.template.setting') }}"
                                        class="btn btn-sm btn-primary rounded-pill">{{ __('Invoice Template Setting') }}</a>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-md-right pt-3">
                                    {{ Form::hidden('from', 'invoice_setting') }}
                                    <button type="submit"
                                        class="btn btn-sm btn-primary rounded-pill ">{{ __('Save changes') }}</button>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            @endif
            @if (Auth::user()->type != 'client')
                <div id="tabs-2" class="tabs-card d-none">
                @else
                    <div id="tabs-2" class="tabs-card">
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="h6 mb-0">{{ __('My Billing Detail') }}</h5>
                    <small>{{ __('This detail will show in your Invoice.') }}</small>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => ['settings.store'], 'id' => 'update_billing_setting', 'enctype' => 'multipart/form-data']) }}
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                {{ Form::label('address', __('Address'), ['class' => 'form-control-label']) }}
                                {{ Form::text('address', $details['address'], ['class' => 'form-control', 'required' => 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('city', __('City'), ['class' => 'form-control-label']) }}
                                {{ Form::text('city', $details['city'], ['class' => 'form-control', 'required' => 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('state', __('State'), ['class' => 'form-control-label']) }}
                                {{ Form::text('state', $details['state'], ['class' => 'form-control', 'required' => 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('zipcode', __('Zip/Post Code'), ['class' => 'form-control-label']) }}
                                {{ Form::text('zipcode', $details['zipcode'], ['class' => 'form-control', 'required' => 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('country', __('Country'), ['class' => 'form-control-label']) }}
                                {{ Form::text('country', $details['country'], ['class' => 'form-control', 'required' => 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('telephone', __('Telephone'), ['class' => 'form-control-label']) }}
                                {{ Form::text('telephone', $details['telephone'], ['class' => 'form-control', 'required' => 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        {{ Form::hidden('from', 'billing_setting') }}
                        <button type="submit"
                            class="btn btn-sm btn-primary rounded-pill">{{ __('Save changes') }}</button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        @if (Auth::user()->type != 'client')
            <div id="tabs-3" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="mb-0">{{ __('Tax') }}</h6>
                            </div>
                            <div class="col-auto">
                                <div class="actions">
                                    <a href="#" class="action-item" data-url="{{ route('taxes.create') }}"
                                        data-ajax-popup="true" data-size="md" data-title="{{ __('Add Tax') }}">
                                        <i class="fas fa-plus"></i>
                                        <span class="d-sm-inline-block">{{ __('Add') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Rate %') }}</th>
                                        <th class="w-25">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    @if (Auth::user()->taxes->count() > 0)
                                        @foreach (Auth::user()->taxes as $tax)
                                            <tr>
                                                <td>{{ $tax->name }}</td>
                                                <td>{{ $tax->rate }}</td>
                                                <td>
                                                    <div class="actions">
                                                        <a href="#" class="action-item px-2"
                                                            data-url="{{ route('taxes.edit', $tax) }}"
                                                            data-ajax-popup="true" data-size="md"
                                                            data-title="{{ __('Edit') }}" data-toggle="tooltip"
                                                            data-original-title="{{ __('Edit') }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="#" class="action-item text-danger px-2"
                                                            data-toggle="tooltip"
                                                            data-original-title="{{ __('Delete') }}"
                                                            data-confirm="{{ __('Are You Sure?') }}|{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="document.getElementById('delete-tax-{{ $tax->id }}').submit();">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['taxes.destroy', $tax->id], 'id' => 'delete-tax-' . $tax->id]) !!}
                                                    {!! Form::close() !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <th scope="col" colspan="3">
                                                <h6 class="text-center">{{ __('No Taxes Found.') }}</h6>
                                            </th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (Auth::user()->type == 'owner')
            <div id="tabs-10" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="mb-0">{{ __('Contract type') }}</h6>
                            </div>
                            <div class="col-auto">
                                <div class="actions">
                                    <a href="#" class="action-item" data-url="{{ route('contract.create') }}"
                                        data-ajax-popup="true" data-size="md"
                                        data-title="{{ __('Add Contract Type') }}">
                                        <i class="fas fa-plus"></i>
                                        <span class="d-sm-inline-block">{{ __('Add') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">


                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th class="w-25">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- {{ dd(Auth::user()->contracttype) }} --}}
                                    @if (Auth::user()->contracttype->count() > 0)
                                        @foreach (Auth::user()->contracttype as $contract)
                                            <tr>

                                                <td>{{ $contract->name }}</td>

                                                <td>
                                                    <div class="actions">
                                                        <a href="#" class="action-item px-2"
                                                            data-url="{{ route('contract.edit', $contract) }}"
                                                            data-ajax-popup="true" data-size="md"
                                                            data-title="{{ __('Edit') }}" data-toggle="tooltip"
                                                            data-original-title="{{ __('Edit') }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="#" class="action-item text-danger px-2"
                                                            data-toggle="tooltip"
                                                            data-original-title="{{ __('Delete') }}"
                                                            data-confirm="{{ __('Are You Sure?') }}|{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="document.getElementById('delete-contract-{{ $contract->id }}').submit();">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['contract.destroy', $contract->id],
                                                        'id' => 'delete-contract-' . $contract->id,
                                                    ]) !!}
                                                    {!! Form::close() !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <th scope="col" colspan="3">
                                                <h6 class="text-center">{{ __('No Contract Found.') }}</h6>
                                            </th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (Auth::user()->type != 'client')
            <div id="tabs-4" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h6 mb-0">{{ __('Payment Settings') }}</h5>
                        <small>{{ __('These details will be used to collect invoice payments. Each invoice will have a payment button based on the below configuration.') }}</small>
                    </div>
                    <div class="card-body">
                        {{ Form::open(['route' => ['settings.store'], 'id' => 'update_setting', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <div class="col-12">
                                <div id="payment-gateways" class="accordion accordion-spaced">

                                    <!-- bank transfer -->
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
                                                            for="paypal_mode">{{ __('Paytm Environment') }}</label> <br>
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


                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="mollie_api_key">{{ __('Mollie Api Key') }}</label>
                                                            <input type="text" name="mollie_api_key"
                                                                id="mollie_api_key" class="form-control"
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
                                                    <div class="col-12 py-2">
                                                        <h5 class="h5">{{ __('Skrill') }}</h5>
                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="skrill_api_key">{{ __('Skrill Email') }}</label>
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
                                                    <div class="col-12 py-2">
                                                        <h5 class="h5">{{ __('CoinGate') }}</h5>
                                                        <small>
                                                            {{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>

                                                    <div class="col-md-12 pb-4">
                                                        <label class="coingate-label form-control-label"
                                                            for="coingate_mode">{{ __('CoinGate Mode') }}</label> <br>
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
                                                        <h5 class="h5">{{ __('PaymentWall') }}</h5>
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

                                                    <div class="col-md-12">
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

                                                    <div class="col-md-12">
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

                                    <!-- Iyzipay -->
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
                                                        <small>{{ __('Note: This detail will use for make checkout of plan.') }}</small>
                                                    </div>

                                                    <div class="col-md-12 pb-4">
                                                        <label class="paypal-label form-control-label"
                                                            for="paypal_mode">{{ __('Iyzipay Environment') }}</label>
                                                        <br>
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label
                                                                class="btn btn-primary btn-sm {{ isset($payment_detail['iyzipay_mode']) && $payment_detail['iyzipay_mode'] == 'sandbox' ? 'active' : '' }}">
                                                                <input type="radio" name="iyzipay_mode"
                                                                    value="sandbox"
                                                                    {{ (isset($payment_detail['iyzipay_mode']) && $payment_detail['iyzipay_mode'] == '') || (isset($payment_detail['iyzipay_mode']) && $payment_detail['iyzipay_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>{{ __('Sandbox') }}
                                                            </label>
                                                            <label
                                                                class="btn btn-primary btn-sm {{ isset($payment_detail['iyzipay_mode']) && $payment_detail['iyzipay_mode'] == 'live' ? 'active' : '' }}">
                                                                <input type="radio" name="iyzipay_mode"
                                                                    value="live"
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
                                                    class="far fa-credit-card mr-3"></i>{{ __('Sspay') }}
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
                                                            <input type="text" name="sspay_category_code"
                                                                id="sspay_category_code" class="form-control"
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
                                                            <input type="text" name="paytab_server_key"
                                                                id="paytab_server_key" class="form-control"
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
                                                            <input type="text" name="paytab_region"
                                                                id="paytab_region" class="form-control"
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

                                    <!-- benefit -->
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
                                                            <input type="text" name="benefit_secret_key"
                                                                id="benefit_secret_key" class="form-control"
                                                                value="{{ isset($payment_detail['benefit_secret_key']) ? $payment_detail['benefit_secret_key'] : '' }}"
                                                                placeholder="{{ __('Server Key') }}" />
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
                                        <div id="collapse-cashfree" class="collapse"
                                            aria-labelledby="cashfree-payment" data-parent="#payment-gateways">
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
                                                            <input type="text" name="cashfree_secret_key"
                                                                id="cashfree_secret_key" class="form-control"
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

                                    <!-- aamarpay -->
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
                                        <div id="collapse-aamarpay" class="collapse"
                                            aria-labelledby="aamarpay-payment" data-parent="#payment-gateways">
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
                                                            <input type="text" name="aamarpay_signature_key"
                                                                id="aamarpay_signature_key" class="form-control"
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
                                                            <input type="text" name="aamarpay_description"
                                                                id="aamarpay_description" class="form-control"
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


                                    <!-- paytr -->
                                    <div class="card">
                                        <div class="card-header py-4" id="paytr-payment" data-toggle="collapse"
                                            role="button" data-target="#collapse-paytr" aria-expanded="false"
                                            aria-controls="collapse-paytr">
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
                                        <div id="collapse-paytr" class="collapse" aria-labelledby="paytr-payment"
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
                                                            <input type="text" name="paytr_merchant_key"
                                                                id="paytr_merchant_key" class="form-control"
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
                                                            <input type="text" name="paytr_merchant_salt"
                                                                id="paytr_merchant_salt" class="form-control"
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
        @endif
        @if (Auth::user()->type != 'client')
            <div id="tabs-5" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h6 mb-0">{{ __('Tracker Settings') }}</h5>
                        <small>{{ __('You can manage your tracker interval time here.') }}</small>
                    </div>
                    <div class="card-body">
                        {{ Form::open(['route' => ['settings.store'], 'id' => 'update_billing_setting', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('address', __('URL'), ['class' => 'form-control-label']) }}
                                    {{-- {{ Form::text('', $details['address'], ['class' => 'form-control','required' => 'required']) }} --}}
                                    <input type="text" value="{{ url('') }}" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('interval_time', __('Tracking Interval'), ['class' => 'form-control-label']) }}
                                    <small
                                        class="ml-2">{{ __('Image Screenshort Take Interval time ( 1 = 1 min)') }}</small>
                                    {{ Form::number('interval_time', $details['interval_time'], ['class' => 'form-control', 'required' => 'required']) }}
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            {{ Form::hidden('from', 'tracker') }}
                            <button type="submit"
                                class="btn btn-sm btn-primary rounded-pill">{{ __('Save changes') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        @endif
        @if (Auth::user()->type != 'client')
            <div id="tabs-6" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h6 mb-0">{{ __('Zoom Meeting') }}</h5>
                        <small>{{ __('You can manage your Zoom Meeting Information.') }}</small>
                    </div>
                    <div class="card-body">
                        {{ Form::open(['url' => route('setting.ZoomSettings'), 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('zoom_account_id', __('Zoom Account ID'), ['class' => 'form-control-label']) }}
                                    <input type="text" name="zoom_account_id" placeholder="Zoom Account Id"
                                        value="{{ !empty($settings['zoom_account_id']) ? $settings['zoom_account_id'] : '' }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('zoom_client_id', __('Zoom Client ID'), ['class' => 'form-control-label']) }}
                                    <input type="text" name="zoom_client_id" class="form-control"
                                        placeholder="Zoom Secret Key"
                                        value="{{ !empty($settings['zoom_client_id']) ? $settings['zoom_client_id'] : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('zoom_client_secret', __('Zoom Client Secret'), ['class' => 'form-control-label']) }}
                                    <input type="text" name="zoom_client_secret" class="form-control"
                                        placeholder="Zoom Client Secret"
                                        value="{{ !empty($settings['zoom_client_secret']) ? $settings['zoom_client_secret'] : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit"
                                class="btn btn-sm btn-primary rounded-pill">{{ __('Save changes') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        @endif
        @if (Auth::user()->type != 'client')
            <div id="tabs-7" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h6 mb-0">{{ __('Slack Notification') }}</h5>
                        <small>{{ __('You can manage your slack notification Information.') }}</small>
                    </div>
                    <div class="card-body">
                        {{ Form::open(['route' => 'slack.setting', 'method' => 'post', 'class' => 'd-contents']) }}
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="small-title">{{ __('Slack Webhook URL') }}</h6>
                                <div class="col-md-12">
                                    {{ Form::text('slack_webhook', isset($settings['slack_webhook']) ? $settings['slack_webhook'] : '', ['class' => 'form-control w-100', 'placeholder' => __('Enter Slack Webhook URL'), 'required' => 'required']) }}
                                </div>
                            </div>

                            <div class="col-md-12 mt-4 mb-2">
                                <h6 class="small-title">{{ __('Module Setting') }}</h6>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <li class="slack_table">
                                        <span>{{ __('New Project') }}</span>
                                        <div class="custom-control custom-switch float-right">


                                            {{ Form::checkbox('is_project_enabled', '1', isset($settings['is_project_enabled']) && $settings['is_project_enabled'] == '1' ? 'checked' : '', ['class' => 'custom-control-input', 'id' => 'is_project_enabled']) }}
                                            <label class="custom-control-label" for="is_project_enabled"></label>
                                        </div>
                                    </li>
                                    <li class="slack_table">
                                        <span>{{ __('New Task') }}</span>

                                        {{-- <div class="col-6 py-2"> --}}
                                        <div class="custom-control custom-switch float-right">
                                            {{ Form::checkbox('task_notification', '1', isset($settings['task_notification']) && $settings['task_notification'] == '1' ? 'checked' : '', ['class' => 'custom-control-input', 'id' => 'task_notification']) }}

                                            <label class="custom-control-label" for="task_notification"></label>
                                        </div>
                                        {{-- </div> --}}
                                    </li>
                                    <li class="slack_table">
                                        <span>{{ __('New Invoice') }}</span>
                                        <div class="custom-control custom-switch float-right">
                                            {{ Form::checkbox('invoice_notificaation', '1', isset($settings['invoice_notificaation']) && $settings['invoice_notificaation'] == '1' ? 'checked' : '', ['class' => 'custom-control-input', 'id' => 'invoice_notificaation']) }}
                                            <label class="custom-control-label" for="invoice_notificaation"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <li class="slack_table">
                                        <span>{{ __('Task Stage Updated') }}</span>
                                        <div class="custom-control custom-switch float-right">
                                            {{ Form::checkbox('task_move_notificaation', '1', isset($settings['task_move_notificaation']) && $settings['task_move_notificaation'] == '1' ? 'checked' : '', ['class' => 'custom-control-input', 'id' => 'task_move_notificaation']) }}
                                            <label class="custom-control-label" for="task_move_notificaation"></label>
                                        </div>
                                    </li>
                                    <li class="slack_table">
                                        <span> {{ __('New Milestone') }}</span>
                                        <div class="custom-control custom-switch float-right">
                                            {{ Form::checkbox('mileston_notificaation', '1', isset($settings['mileston_notificaation']) && $settings['mileston_notificaation'] == '1' ? 'checked' : '', ['class' => 'custom-control-input', 'id' => 'mileston_notificaation']) }}
                                            <label class="custom-control-label" for="mileston_notificaation"></label>
                                        </div>
                                    </li>
                                    <li class="slack_table">
                                        <span> {{ __('Milestone Status Updated') }}</span>
                                        <div class="custom-control custom-switch float-right">
                                            {{ Form::checkbox('milestone_status_notificaation', '1', isset($settings['milestone_status_notificaation']) && $settings['milestone_status_notificaation'] == '1' ? 'checked' : '', ['class' => 'custom-control-input', 'id' => 'milestone_status_notificaation']) }}
                                            <label class="custom-control-label"
                                                for="milestone_status_notificaation"></label>
                                        </div>
                                    </li>
                                    <li class="slack_table">
                                        <span> {{ __('Invoice Status Updated') }}</span>
                                        <div class="custom-control custom-switch float-right">
                                            {{ Form::checkbox('invoice_status_notificaation', '1', isset($settings['invoice_status_notificaation']) && $settings['invoice_status_notificaation'] == '1' ? 'checked' : '', ['class' => 'custom-control-input', 'id' => 'invoice_status_notificaation']) }}
                                            <label class="custom-control-label"
                                                for="invoice_status_notificaation"></label>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                        <div class="card-footer text-right mt-5">
                            <input class="btn btn-sm btn-primary rounded-pill" type="submit" value="Save Changes">
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        @endif
        @if (Auth::user()->type != 'client')
            <div id="tabs-8" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h6 mb-0">{{ __('Telegram Notification') }}</h5>
                        <small>{{ __('You can manage your Telegram notification Information.') }}</small>
                    </div>
                    <div class="card-body">
                        {{ Form::open(['route' => 'telegram.setting', 'id' => 'telegram-setting', 'method' => 'post', 'class' => 'd-contents']) }}
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-control-label mb-0">{{ __('Telegram AccessToken') }}</label> <br>
                                {{-- {{ dd( $settings) }} --}}
                                {{ Form::text('telegram_accestoken', isset($settings['telegram_accestoken']) ? $settings['telegram_accestoken'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Telegram AccessToken')]) }}
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-control-label mb-0">{{ __('Telegram ChatID') }}</label> <br>
                                {{ Form::text('telegram_chatid', isset($settings['telegram_chatid']) ? $settings['telegram_chatid'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Telegram ChatID')]) }}
                            </div>

                            <div class="col-md-12 mt-4 mb-2">
                                <h6 class="small-title">{{ __('Module Setting') }}</h6>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <li class="telegram_table">
                                        <span>{{ __('New Project') }}</span>
                                        <div class="custom-control custom-switch float-right">
                                            {{ Form::checkbox('telegram_is_project_enabled', '1', isset($settings['telegram_is_project_enabled']) && $settings['telegram_is_project_enabled'] == '1' ? 'checked' : '', ['class' => 'custom-control-input', 'id' => 'telegram_is_project_enabled']) }}
                                            <label class="custom-control-label"
                                                for="telegram_is_project_enabled"></label>
                                        </div>
                                    </li>
                                    <li class="telegram_table">
                                        <span>{{ __('New Task') }}</span>
                                        <div class="custom-control custom-switch float-right">
                                            {{ Form::checkbox('telegram_task_notification', '1', isset($settings['telegram_task_notification']) && $settings['telegram_task_notification'] == '1' ? 'checked' : '', ['class' => 'custom-control-input', 'id' => 'telegram_task_notification']) }}
                                            <label class="custom-control-label"
                                                for="telegram_task_notification"></label>
                                        </div>
                                    </li>
                                    <li class="telegram_table">
                                        <span>{{ __('New Invoice') }}</span>
                                        <div class="custom-control custom-switch float-right">
                                            {{ Form::checkbox('telegram_invoice_notificaation', '1', isset($settings['telegram_invoice_notificaation']) && $settings['telegram_invoice_notificaation'] == '1' ? 'checked' : '', ['class' => 'custom-control-input', 'id' => 'telegram_invoice_notificaation']) }}
                                            <label class="custom-control-label"
                                                for="telegram_invoice_notificaation"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <li class="telegram_table">
                                        <span>{{ __('Task Stage Updated') }}</span>
                                        <div class="custom-control custom-switch float-right">
                                            {{ Form::checkbox('telegram_task_move_notificaation', '1', isset($settings['telegram_task_move_notificaation']) && $settings['telegram_task_move_notificaation'] == '1' ? 'checked' : '', ['class' => 'custom-control-input', 'id' => 'telegram_task_move_notificaation']) }}
                                            <label class="custom-control-label"
                                                for="telegram_task_move_notificaation"></label>
                                        </div>
                                    </li>
                                    <li class="telegram_table">
                                        <span> {{ __('New Milestone') }}</span>
                                        <div class="custom-control custom-switch float-right">
                                            {{ Form::checkbox('telegram_mileston_notificaation', '1', isset($settings['telegram_mileston_notificaation']) && $settings['telegram_mileston_notificaation'] == '1' ? 'checked' : '', ['class' => 'custom-control-input', 'id' => 'telegram_mileston_notificaation']) }}
                                            <label class="custom-control-label"
                                                for="telegram_mileston_notificaation"></label>
                                        </div>
                                    </li>
                                    <li class="telegram_table">
                                        <span> {{ __('Milestone Status Updated') }}</span>
                                        <div class="custom-control custom-switch float-right">
                                            {{ Form::checkbox('telegram_milestone_status_notificaation', '1', isset($settings['telegram_milestone_status_notificaation']) && $settings['telegram_milestone_status_notificaation'] == '1' ? 'checked' : '', ['class' => 'custom-control-input', 'id' => 'telegram_milestone_status_notificaation']) }}
                                            <label class="custom-control-label"
                                                for="telegram_milestone_status_notificaation"></label>
                                        </div>
                                    </li>
                                    <li class="telegram_table">
                                        <span> {{ __('Invoice Status Updated') }}</span>
                                        <div class="custom-control custom-switch float-right">
                                            {{ Form::checkbox('telegram_invoice_status_notificaation', '1', isset($settings['telegram_invoice_status_notificaation']) && $settings['telegram_invoice_status_notificaation'] == '1' ? 'checked' : '', ['class' => 'custom-control-input', 'id' => 'telegram_invoice_status_notificaation']) }}
                                            <label class="custom-control-label"
                                                for="telegram_invoice_status_notificaation"></label>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                        <div class="card-footer text-right mt-5">
                            <input class="btn btn-sm btn-primary rounded-pill" type="submit" value="Save Changes">
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        @endif
        @if (Auth::user()->type != 'client')
            <div id="tabs-11" class="tabs-card d-none">
                {{ Form::open(['route' => ['calendar.setting'], 'id' => 'update_setting', 'enctype' => 'multipart/form-data']) }}
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="h6 md-0">{{ __('Google Calendar') }}</h5>
                            </div>
                            <div class=" col-6 text-end">
                                <div class="switch__container custom-control custom-switch float-right">
                                    {{ Form::checkbox('is_enabled', 'on', isset($settings['is_enabled']) && $settings['is_enabled'] == 'on' ? 'checked' : '', ['class' => 'custom-control-input', 'id' => 'is_enabled']) }}
                                    <label for="is_enabled" class="custom-control-label"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label"
                                        for="google_clender_id">{{ __('Google Calendar Id') }}</label>
                                    {{-- <input type="text" name="google_clender_id" id="google_clender_id"
                                        class="form-control"/> --}}
                                    {{ Form::text('google_clender_id', isset($settings['google_clender_id']) ? $settings['google_clender_id'] : '', ['class' => 'form-control']) }}

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('google_calender_json_file', __('Google Calendar Json File'), ['class' => 'form-control-label']) }}
                                    <input type="file" name="google_calender_json_file"
                                        id="google_calender_json_file" class="custom-input-file"
                                        onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])" />
                                    <label for="google_calender_json_file">
                                        <i class="fa fa-upload"></i>
                                        <span>{{ __('Choose a file…') }}</span>
                                    </label>

                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            {{ Form::hidden('from', 'google_setting') }}
                            <button type="submit"
                                class="btn btn-sm btn-primary rounded-pill">{{ __('Save changes') }}</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        @endif
        @if (Auth::user()->type != 'client')
            <div id="tabs-12" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="mb-0">{{ __('Webhook') }}</h6>
                            </div>
                            <div class="col-auto">
                                <div class="actions">
                                    <a href="#" class="action-item" data-url="{{ route('webhook.create') }}"
                                        data-ajax-popup="true" data-size="md" data-title="{{ __('Add Webhook') }}">
                                        <i class="fas fa-plus"></i>
                                        <span class="d-sm-inline-block">{{ __('Add') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('Module') }}</th>
                                        <th>{{ __('URL') }}</th>
                                        <th>{{ __('Method') }}</th>
                                        <th class="w-25">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                @php
                                    $webhooks = App\Models\Webhook::where('created_by', Auth::user()->id)->get();
                                @endphp
                                <tbody>
                                    @foreach ($webhooks as $webhook)
                                        <tr>
                                            <td>{{ $webhook->module }}</td>

                                            <td>{{ $webhook->url }}</td>
                                            <td>{{ $webhook->method }}</td>
                                            <td>
                                                <div class="actions">
                                                    <a href="#" class="action-item px-2"
                                                        data-url="{{ route('webhook.edit', $webhook) }}"
                                                        data-ajax-popup="true" data-size="md"
                                                        data-title="{{ __('Edit') }}" data-toggle="tooltip"
                                                        data-original-title="{{ __('Edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="#" class="action-item text-danger px-2"
                                                        data-toggle="tooltip"
                                                        data-original-title="{{ __('Delete') }}"
                                                        data-confirm="{{ __('Are You Sure?') }}|{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="document.getElementById('delete-webhook-{{ $webhook->id }}').submit();">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['webhook.destroy', $webhook->id],
                                                    'id' => 'delete-webhook-' . $webhook->id,
                                                ]) !!}
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (Auth::user()->type != 'client')
            <div id="tabs-13" class="tabs-card d-none">
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
                                    {{ Form::text('mail_driver', isset($settings['mail_driver']) ? $settings['mail_driver'] : '' , ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Mail Driver')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('mail_host', __('Mail Host'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('mail_host', isset($settings['mail_host']) ? $settings['mail_host'] : '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Mail Host')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('mail_port', __('Mail Port'), ['class' => 'form-control-label']) }}
                                    {{ Form::number('mail_port',isset($settings['mail_port']) ? $settings['mail_port'] : '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Mail Port'), 'min' => '0']) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('mail_username', __('Mail Username'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('mail_username', isset($settings['mail_username']) ? $settings['mail_username'] : '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Mail Username')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('mail_password', __('Mail Password'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('mail_password', isset($settings['mail_password']) ? $settings['mail_password'] : '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Mail Password')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('mail_encryption', isset($settings['mail_encryption']) ? $settings['mail_encryption'] : '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Mail Encryption')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('mail_from_address', __('Mail From Address'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('mail_from_address', isset($settings['mail_from_address']) ? $settings['mail_from_address'] : '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Mail From Address')]) }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('mail_from_name', __('Mail From Name'), ['class' => 'form-control-label']) }}
                                    {{ Form::text('mail_from_name', isset($settings['mail_from_name']) ? $settings['mail_from_name'] : '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Mail From Name')]) }}
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
        @endif
    </div>
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

        $(document).on('click', '.custom-control-input', function() {
            var pp = $(this).parents('.tabs-card').removeClass('d-none');
        });
    </script>

    <script>
        function check_theme(color_val) {
            // alert(color_val);
            $('input[value="' + color_val + '"]').prop('checked', true);
            $('input[value="' + color_val + '"]').attr('checked', true);
            $('a[data-value]').removeClass('active_color');
            $('a[data-value="' + color_val + '"]').addClass('active_color');
        }
    </script>
    <script>
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
@endpush
