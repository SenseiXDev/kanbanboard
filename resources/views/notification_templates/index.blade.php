@extends('layouts.admin')
@section('title')
    {{ __('Notification Template') }}
@endsection

@section('action-button')
    <div class="row">
        <div class="text-end mb-3">
            <div class="text-end">
                <div class="d-flex justify-content-end drp-languages">
                    <ul class="nav justify-content-center ">
                        <li class="nav-item dropdown ">
                            <a class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <span class="h6 text-sm mb-0 text-white"><i class="fas fa-globe-asia"></i>
                                    {{ ucfirst($languages[$curr_noti_tempLang->lang]) }}</span>
                                  
                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                @foreach ($languages as $code =>$lang)
                                    <a href="{{ route('notification-templates.index', [$notification_template->id, $code]) }}"
                                        class="dropdown-item {{ $curr_noti_tempLang->lang == $lang ? 'text-primary' : '' }}">{{ ucfirst($lang) }}</a>
                                @endforeach
                            </div>
                        </li>
                    </ul>
                    <ul class="nav justify-content-center justify-content-md-end  ml-5">
                        <li class="nav-item dropdown ">
                            <a class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <span class="h6 text-sm mb-0 text-white">{{ __('Template: ') }}
                                    {{ $notification_template->name }}</span>
                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                @foreach ($notification_templates as $notification_template)
                                    <a href="{{ route('notification-templates.index', [$notification_template->id, Request::segment(3) ? Request::segment(3) : \Auth::user()->lang]) }}"
                                        class="dropdown-item
                                   {{ $notification_template->name == $notification_template->name ? 'text-primary' : '' }}">{{ $notification_template->name }}
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <h5 class="font-weight-bold ">{{ __('Placeholders') }}</h5>
                                <div class="text-right">
                                    <a href="#" class="btn btn-sm btn-primary rounded-pill m-3"
                                        data-url="{{ route('generate', ['notification_template']) }}" data-ajax-popup-over="true"
                                        data-bs-placement="top" data-size="md" title="{{ __('Generate') }}"
                                        data-title="{{ __('Generate content with AI') }}">
                                        <span class="btn-inner--text text-white"> <span><i class="fas fa-robot"></i></span>
                                            {{ __('Generate with AI') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-sm-12">
                            <div class="card">
                                <div class="card-header card-body">
                                    <h6 class="font-weight-bold mb-4">{{ __('Variables') }}</h6>
                                    <div class="row text-md">
                                        @php
                                            $variables = json_decode($curr_noti_tempLang->variables);
                                        @endphp
                                        @if (!empty($variables) > 0)
                                            @foreach ($variables as $key => $var)
                                                <div class="col-6 pb-1">
                                                    <p class="mb-1">{{ __($key) }} : <span
                                                            class="pull-right text-primary">{{ '{' . $var . '}' }}</span>
                                                    </p>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::model($curr_noti_tempLang, ['route' => ['notification-templates.update', $curr_noti_tempLang->parent_id], 'method' => 'PUT']) }}
                        <div class="col-12">
                            <div class="form-group">
                                {{ Form::label('content', __('Notification Message'), ['class' => 'form-label text-dark ']) }}
                                {{ Form::textarea('content', $curr_noti_tempLang->content, ['class' => 'form-control', 'required' => 'required', 'rows' => '04', 'placeholder' => 'EX. Hello, {app_name}']) }}
                                <small>{{ __('A variable is to be used in such a way.') }} <span
                                        class="text-primary">{{ __('Ex. TaskGo, {app_name}') }}</span></small>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12 text-right">
                            {{ Form::hidden('lang', null) }}
                            {{ Form::submit(__('Save changes'), ['class' => 'btn btn-sm btn-print-invoice btn-primary rounded-pill']) }}
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>

    @endsection
