@extends('layouts.admin')

@section('title')
    {{ __('Task Calendar') }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
@endpush

{{-- @push('theme-script')
    <script src="{{ asset('assets/libs/dragula/dist/dragula.min.js') }}"></script>
@endpush --}}
@php
    $setting = \App\Models\Utility::settings();
    $settings = \App\Models\Utility::settingsById();
    // $SITE_RTL = Cookie::get('enable_rtl');
    $SITE_RTL=\App\Models\Utility::getValByName('enable_rtl')
@endphp

@section('content')
    <div class="page-title">
        <div class="row pb-3">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <h5 class="h4 d-inline-block font-weight-400 mb-0 text-white">{{ __('Calendar') }}</h5>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-secondary btn-sm {{ $task_by == 'my' ? 'active' : '' }}">
                        <input type="checkbox" name="options" id="my_task" autocomplete="off"
                            {{ $task_by == 'my' ? 'checked' : '' }}>{{ __('See My Task') }}
                    </label>
                </div>
                <select class="form-control form-control-sm w-auto d-inline" size="sm" name="project" id="project_id">
                    <option value="">{{ __('All Projects') }}</option>
                    @foreach (Auth::user()->projects as $project)
                        <option value="{{ $project->id }}" {{ $project_id == $project->id ? 'selected' : '' }}>
                            {{ $project->title }}</option>
                    @endforeach
                </select>
                <button class="btn btn-white btn-sm ml-2" onclick="get_data()" id="filter"><i
                        class="mdi mdi-check"></i>{{ __('Apply') }}</button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6 align-items-center d-flex">
                            <h5>Calendar</h5>

                        </div>
                        <div class="col-6" id="google_cal">

                            @if (isset($settings['is_enabled']) && $settings['is_enabled'] == 'on')
                            <select class="form-control  {{ $SITE_RTL == 'on' ? 'float-left' : 'float-right' }} w-120" style="width:180px" name="calender_type" id="calender_type"
                            onchange="get_data()">
                                    <option value="google_calender">Google Calendar</option>
                                    <option value="local_calender" selected="true">Local Calendar</option>
                                </select>
                            @endif
                        </div>
                        <div class="card-body">
                            <div id='calendar' class='calendar'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('theme-script')
    <script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.min.js') }}"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            get_data();
        });

        function get_data() {

            var element = document.getElementById("calender_type");
            var calender_type = $('#calender_type :selected').val();
            var project_id = $('#project_id :selected').val();
            $('#calendar').removeClass('local_calender');
            $('#calendar').removeClass('google_calender');
                if(calender_type == undefined){
                    calender_type = 'local_calender';
                }
            $('#calendar').addClass('calender_type');
            if (project_id) {
                document.getElementById("google_cal").style.display = "none";
            } else {
                document.getElementById("google_cal").style.display = "block";
            }
            $.ajax({

                url: $("#path_admin").val() + "/calendar/all",
                method: "GET",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'calender_type': calender_type,
                    'project_id': project_id,
                },

                success: function(data) {
                    (function() {
                        var etitle;
                        var etype;
                        var etypeclass;
                        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                            },
                            buttonText: {
                                timeGridDay: "{{ __('Day') }}",
                                timeGridWeek: "{{ __('Week') }}",
                                dayGridMonth: "{{ __('Month') }}"
                            },
                            themeSystem: '',
                            slotDuration: '00:10:00',
                            navLinks: true,
                            droppable: true,
                            selectable: true,
                            selectMirror: true,
                            editable: true,
                            dayMaxEvents: true,
                            handleWindowResize: true,
                            events: data,
                            eventClick: function(e) {
                                e.jsEvent.preventDefault();
                                var title = e.title;
                                var url = e.el.href;

                                if(calender_type=='local_calender')
                                {
                                    if (typeof url != 'undefined') {
                                    $("#commonModal .modal-title").html(e.event.title);
                                    $("#commonModal .modal-dialog").addClass('modal-md');
                                    $("#commonModal").modal('show');
                                    $.get(url, {}, function(data) {
                                        // console.log()
                                        $('#commonModal .modal-body ').html(data);
                                    });
                                    return false;
                                    }
                                }
                            }

                        });
                        calendar.render();
                    })();
                }
            });

        }

    </script>
@endpush
