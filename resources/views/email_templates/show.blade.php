@extends('layouts.admin')
@section('title')
    {{ $emailTemplate->name }}
@endsection

@section('action-button')
    <ul class="nav justify-content-center ">
        <li class="nav-item dropdown ">
            <a class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="h6 text-sm mb-0 text-white"><i class="fas fa-globe-asia"></i>
                    {{ ucfirst($languages[$currEmailTempLang->lang]) }}</span>
                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                @foreach ($languages as $code =>$lang)
                    <a href="{{ route('manage.email.language', [$emailTemplate->id, $code]) }}"
                        class="dropdown-item {{ $currEmailTempLang->lang == $lang ? 'text-danger' : '' }}">{{ ucfirst($lang) }}</a>
                @endforeach
            </div>
        </li>
    </ul>
    <ul class="nav justify-content-center justify-content-md-end  ml-5">
        <li class="nav-item dropdown ">
            <a class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <span class="h6 text-sm mb-0 text-white">{{ __('Template: ') }}
                    {{ ucfirst($emailTemplate->name) }}</span>
                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                @foreach ($EmailTemplates as $EmailTemplate)
                    <a href="{{ route('manage.email.language', [$EmailTemplate->id, \Auth::user()->lang]) }}"
                        class="dropdown-item">
                        <span>{{ $EmailTemplate->name }}</span>
                    </a>
                @endforeach
            </div>
        </li>
    </ul>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/summernote/summernote-bs4.css') }}">
@endpush
@push('theme-script')
    <script src="{{ asset('assets/libs/summernote/summernote-bs4.js') }}"></script>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="language-wrap">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 language-form-wrap">
                                <h6 class="">
                                    <i data-feather="credit-card" class="me-2"></i>{{ __('Placeholders') }}
                                </h6>
                                <div class="col-12 col-md-12">
                                    <div class="text-right">

                                        <a href="#" class="btn btn-sm btn-primary rounded-pill m-3" data-url="{{ route('generate',['email_template']) }}" data-ajax-popup-over="true" data-bs-placement="top" data-size="md" title="{{ __('Generate') }}" data-title="{{__('Generate content with AI')}}">
                                            <span class="btn-inner--text text-white"> <span><i class="fas fa-robot"></i></span> {{__('Generate with AI')}}</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach (explode(',', $emailTemplate->keyword) as $keyword)
                                                    @php($word = explode(':', $keyword))
                                                    <p class="col-4">{{ __($word[0]) }} : <span
                                                            class="pull-right text-primary">{{ $word[1] }}</span></p>
                                                @endforeach

                                                <p class="col-4">{{ __('App Name') }} : <span
                                                        class="pull-right text-primary">{app_name}</span></p>
                                                <p class="col-4">{{ __('App URL') }} : <span
                                                        class="pull-right text-primary">{app_url}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{ Form::model($currEmailTempLang, ['route' => ['store.email.language', $currEmailTempLang->parent_id], 'method' => 'POST']) }}
                                <div class="row">

                                    <div class="form-group col-6">
                                        {{ Form::label('subject', __('Subject')) }}
                                        {{ Form::text('subject', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
                                    </div>

                                    <div class="form-group col-6">
                                        {{ Form::label('from', __('From')) }}
                                        {{ Form::text('from', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            {{ Form::label('content', __('Email Message')) }}
                                            {{ Form::textarea('content', $currEmailTempLang->content, ['class' => 'summernote-simple', 'required' => 'required', 'id' => 'summernote-simple']) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 text-right">
                                    {{ Form::hidden('lang', null) }}
                                    {{ Form::submit(__('Save changes'), ['class' => 'btn btn-sm btn-primary rounded-pill']) }}
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
