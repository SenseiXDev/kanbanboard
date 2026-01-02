@extends('layouts.admin')
@section('title')
    {{ __('User Logs') }}
@endsection
@section('action-button')
    {{ Form::open(['route' => ['logindetails.index'], 'method' => 'get', 'id' => 'userlogs_filter']) }}
            <div class="d-flex align-items-center justify-content-end">
                <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-12">
                    <div class="btn-box">
                        {{Form::month('month',isset($_GET['month'])?$_GET['month']: '',array('class'=>'form-control'))}}
                    </div>
                </div>
                <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-12">
                    <div class="btn-box">
                        {{ Form::select('user', $usersList, isset($_GET['user']) ? $_GET['user'] : '', ['class' => 'form-control select ', 'id' => 'id']) }}
                    </div>
                </div>
                <div class="col-auto float-end ms-2">
                    <a href="#" class="btn btn-white btn-sm ml-2"
                    onclick="document.getElementById('userlogs_filter').submit(); return false;"
                    data-bs-toggle="tooltip">
                    <span class="btn-inner--icon"><i class="ti ti-search"></i>{{ __('Apply') }}</span>
                </a>
                <a href="{{ route('logindetails.index') }}" class="btn btn-sm btn-danger"
                    data-bs-toggle="tooltip"
                    data-original-title="Reset">
                    <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i>{{ __('Reset')}}</span>
                </a>
                </div>
            </div>
    {{ Form::close() }}
@endsection

@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table align-items-center" id="myTable">
                <thead>
                    <tr>
                        <th>{{ __('Name')}}</th>
                        <th>{{ __('Role')}}</th>
                        <th>{{ __('IP')}}</th>
                        <th>{{ __('Last Login At')}}</th>
                        <th>{{ __('Device Type')}}</th>
                        <th>{{ __('Os Name')}}</th>
                        <th>{{ __('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logindetails as $logindetail)
                    <tr class="font-style">
                        @php
                            $details = json_decode($logindetail->details);
                        @endphp
                        <td>
                            @php
                                $user = $logindetail->Getuser();
                                $name = !empty($user) ? $user->name : '';
                            @endphp
                            <h4 class="text-muted text-sm mb-0">{{ $name }}</h4></td>

                        <td><span class="text-capitalize badge badge-pill badge-success">{{ $logindetail->type }}</span></td>
                        <td>{{ $logindetail->ip }}</td>
                        <td>{{ $logindetail->date }}</td>
                        <td>{{ $details->device_type }}</td>
                        <td>{{ $details->os_name }}</td>
                        <td class="Action">
                            <a href="#" class="action-item px-2"
                            data-url="{{ route('logindetails.show', [$logindetail->id]) }}"
                            data-ajax-popup="true" data-size="md"
                            data-title="{{ __('Show') }}" data-toggle="tooltip"
                            data-original-title="{{ __('Show') }}">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="#" class="action-item text-danger px-2"
                        data-toggle="tooltip"
                        data-original-title="{{ __('Delete') }}"
                        data-confirm="{{ __('Are You Sure?') }}|{{ __('This action can not be undone. Do you want to continue?') }}"
                        data-confirm-yes="document.getElementById('delete-logindetails-{{ $logindetail->id }}').submit();">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                    {!! Form::open(['method' => 'DELETE', 'route' => ['logindetails.destroy', $logindetail->id], 'id' => 'delete-logindetails-' . $logindetail->id]) !!}
                    {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
