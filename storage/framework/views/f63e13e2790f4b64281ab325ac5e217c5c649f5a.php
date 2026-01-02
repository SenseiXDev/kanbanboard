<?php $__env->startSection('title'); ?>
    <?php echo e(__('Calendar')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/main.css')); ?>">
<?php $__env->stopPush(); ?>
<?php
    $setting = \App\Models\Utility::settings();
    $settings = \App\Models\Utility::settingsById();
    // $SITE_RTL = Cookie::get('enable_rtl');    
    $SITE_RTL=\App\Models\Utility::getValByName('enable_rtl') 
?>

<?php $__env->startSection('action-button'); ?>
    <a href="<?php echo e(route('zoommeeting.index')); ?>" class="btn btn-sm bg-white btn-icon rounded-pill ml-0">
        <span class="btn-inner--text text-dark"><?php echo e(__('List')); ?></span>
    </a>
    <?php if(\Auth::user()->type == 'owner'): ?>
        <a href="#" data-url="<?php echo e(route('zoommeeting.create')); ?>" data-size="lg" data-ajax-popup="true"
            data-title="<?php echo e(__('Create New Zoom Meeting')); ?>" class="btn btn-sm btn-white btn-icon-only rounded-circle"
            data-toggle="tooltip">
            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6 align-items-center d-flex">
                            <h5>Calendar</h5>
                        </div>
                        
                        <?php if(isset($settings['is_enabled']) && $settings['is_enabled'] == 'on'): ?>
                            <div class="col-6">
                               
                                <select class="form-control  <?php echo e($SITE_RTL == 'on' ? 'float-left' : 'float-right'); ?> w-120" style="width:180px" name="calender_type" id="calender_type"
                                onchange="get_data()">
                                    <option value="google_calender">Google Calendar</option>
                                    <option value="local_calender" selected="true">Local Calendar</option>
                                </select>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <div id='calendar' class='calendar'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('theme-script'); ?>
    <script src="<?php echo e(asset('assets/libs/moment/min/moment.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/main.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        $(document).ready(function() {
            get_data();
        });

        function get_data() {

            var calender_type = $('#calender_type :selected').val();
            $('#calendar').removeClass('local_calender');
            $('#calendar').removeClass('google_calender');
                if(calender_type == undefined){
                    calender_type = 'local_calender';
                }
            $('#calendar').addClass('calender_type');

            $.ajax({

                url: $("#path_admin").val() + "/calendar/zoommeeting/data",
                method: "POST",
                data: {
                    "_token": "<?php echo e(csrf_token()); ?>",
                    'calender_type': calender_type
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
                                timeGridDay: "<?php echo e(__('Day')); ?>",
                                timeGridWeek: "<?php echo e(__('Week')); ?>",
                                dayGridMonth: "<?php echo e(__('Month')); ?>"
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/zoommeeting/calendar.blade.php ENDPATH**/ ?>