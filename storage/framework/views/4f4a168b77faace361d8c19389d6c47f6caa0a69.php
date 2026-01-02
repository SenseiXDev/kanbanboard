<?php $__env->startSection('title'); ?>
    <?php echo e(__('Task Calendar')); ?>

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

<?php $__env->startSection('content'); ?>
    <div class="page-title">
        <div class="row pb-3">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <h5 class="h4 d-inline-block font-weight-400 mb-0 text-white"><?php echo e(__('Calendar')); ?></h5>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-secondary btn-sm <?php echo e($task_by == 'my' ? 'active' : ''); ?>">
                        <input type="checkbox" name="options" id="my_task" autocomplete="off"
                            <?php echo e($task_by == 'my' ? 'checked' : ''); ?>><?php echo e(__('See My Task')); ?>

                    </label>
                </div>
                <select class="form-control form-control-sm w-auto d-inline" size="sm" name="project" id="project_id">
                    <option value=""><?php echo e(__('All Projects')); ?></option>
                    <?php $__currentLoopData = Auth::user()->projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($project->id); ?>" <?php echo e($project_id == $project->id ? 'selected' : ''); ?>>
                            <?php echo e($project->title); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button class="btn btn-white btn-sm ml-2" onclick="get_data()" id="filter"><i
                        class="mdi mdi-check"></i><?php echo e(__('Apply')); ?></button>
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

                            <?php if(isset($settings['is_enabled']) && $settings['is_enabled'] == 'on'): ?>
                            <select class="form-control  <?php echo e($SITE_RTL == 'on' ? 'float-left' : 'float-right'); ?> w-120" style="width:180px" name="calender_type" id="calender_type"
                            onchange="get_data()">
                                    <option value="google_calender">Google Calendar</option>
                                    <option value="local_calender" selected="true">Local Calendar</option>
                                </select>
                            <?php endif; ?>
                        </div>
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
                    "_token": "<?php echo e(csrf_token()); ?>",
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/tasks/calendar.blade.php ENDPATH**/ ?>