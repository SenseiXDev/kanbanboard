@extends('layouts.auth')

@section('title')
    {{__('Copylink')}}
@endsection

@php
    $logo = \App\Models\Utility::get_file('logo/');
@endphp

@section('content')
  <div class="col-sm-8 col-lg-5">
      <div class="text-center pb-4">
          <img src="{{$logo.'logo.png'.'?'.time()}}" class="w200">
      </div>

      <div class="card shadow zindex-100 mb-0">
          <div class="card-body px-md-5 py-5">
              <div class="mb-3">
                  <h2 class="h3">{{__('Password required')}}</h2>
                  <h6>{{ __('This document is password-protected. Please enter a password.') }}</h6>

              </div>

              <span class="clearfix"></span>
              <form method="POST" action="{{ route('projects.link', \Illuminate\Support\Facades\Crypt::encrypt($id)) }}">
                  @csrf
                  <div class="form-group mb-2">
                      <label class="form-control-label">{{__('Password')}}</label>
                      <div class="input-group input-group-merge">

                          <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                          <div class="input-group-append">
                              <span class="input-group-text">
                                <a href="#" data-toggle="password-text" data-target="#password">
                                  <i class="fas fa-eye"></i>
                                </a>
                              </span>
                          </div>
                          @error('password')
                          <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>
                  </div>

                  <div class="mt-4">
                      <button type="submit" class="btn btn-sm btn-primary btn-icon rounded-pill">
                          <span class="btn-inner--text">{{__('Save')}}</span>

                      </button>
                  </div>
              </form>
          </div>
      </div>
  </div>
@endsection

