<?php $__env->startSection('title'); ?>
    <?php echo e((\Auth::user()->id != $user->id) ? $user->name.__("'s Overview") : __('My Overview')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-button'); ?>
<?php $__env->stopSection(); ?>



<?php
$logo = \App\Models\Utility::get_file('tasks/');
$logo_path=\App\Models\Utility::get_file('/');
?>

<?php $__env->startPush('theme-script'); ?>
    <script src="<?php echo e(asset('assets/libs/apexcharts/dist/apexcharts.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-<?php echo e(($role['role'] == 'Client') ? '6':'4'); ?>">
            <div class="card card-fluid">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="#" class="avatar rounded-circle">
                               

                                <?php if($user->avatar): ?>
                                    <img  src="<?php echo e($logo_path.$user->avatar); ?>" >
                              
                                <?php else: ?>
                                  <img <?php echo e($user->img_avatar); ?> >
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="col ml-md-n2">
                            <a href="#!" class="d-block h6 mb-0"><?php echo e($user->name); ?> <span class="badge badge-xs badge-<?php echo e($role['color']); ?> ml-2"><?php echo e($role['role']); ?></span></a>
                            <small class="d-block text-muted"><?php echo e($user->email); ?></small>
                            <small class="d-block text-muted"><?php echo e((!empty($user->phone)) ? $user->phone : ''); ?></small>
                        </div>
                        <?php if(Auth::user()->id == $user->id): ?>
                            <div class="col-auto">
                                <a href="<?php echo e(route('profile')); ?>">
                                    <button type="button" class="btn btn-xs btn-primary btn-icon rounded-pill">
                                        <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                                        <span class="btn-inner--text"><?php echo e(__('Edit')); ?></span>
                                    </button>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(!empty($user->skills)): ?>
                        <?php $__currentLoopData = explode(',',$user->skills); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge badge-pill badge-primary d-inline-block mt-2"><?php echo e($skill); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <span><?php echo e(__('No Skills Found..!')); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-<?php echo e(($role['role'] == 'Client') ? '6':'4'); ?>">
            <div class="card card-fluid">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h6 class="text-sm mb-0">
                                <i class="fab fa-facebook mr-2"></i><?php echo e(__('Facebook')); ?>

                            </h6>
                        </div>
                        <div class="col-12">
                            <span class="text-sm"><?php echo e((!empty($user->facebook) ? $user->facebook : '-')); ?></span>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h6 class="text-sm mb-0">
                                <i class="fab fa-whatsapp mr-2"></i><?php echo e(__('WhatsApp')); ?>

                            </h6>
                        </div>
                        <div class="col-12">
                            <span class="text-sm"><?php echo e((!empty($user->whatsapp) ? $user->whatsapp : '-')); ?></span>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h6 class="text-sm mb-0">
                                <i class="fab fa-instagram mr-2"></i><?php echo e(__('Instagram')); ?>

                            </h6>
                        </div>
                        <div class="col-12">
                            <span class="text-sm"><?php echo e((!empty($user->instagram) ? $user->instagram : '-')); ?></span>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h6 class="text-sm mb-0">
                                <i class="fab fa-linkedin mr-2"></i><?php echo e(__('LinkedIn')); ?>

                            </h6>
                        </div>
                        <div class="col-12">
                            <span class="text-sm"><?php echo e((!empty($user->likedin) ? $user->likedin : '-')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if($role['role'] != 'Client'): ?>
            <div class="col-lg-4">
                <div class="card card-fluid">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-sm mb-0"><?php echo e(__('Time Logged on Timesheet')); ?></h6>
                                <span class="text-nowrap h6 text-muted text-sm"><?php echo e($user_data['timesheet_timelog']); ?></span>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-sm mb-0"><?php echo e(__('Total Completed Task')); ?></h6>
                                <span class="text-nowrap h6 text-muted text-sm"><?php echo e($user_data['complete_task']); ?></span>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-sm mb-0"><?php echo e(__('Total Open Task')); ?></h6>
                                <span class="text-nowrap h6 text-muted text-sm"><?php echo e($user_data['open_task']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <?php if($role['role'] != 'Client'): ?>
            <div class="col-xl-8 col-md-6">
                <div class="card card-fluid">
                    <div class="card-header">
                        <h6 class="mb-0"><?php echo e(__('Timesheet Logged Hours')); ?></h6>
                        <small class="text-muted"><?php echo e(__('Last 7 days')); ?></small>
                    </div>
                    <div class="card-body">
                        <div id="timesheet_logged_hrs" data-color="primary" data-height="410"></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-xl-<?php echo e(($role['role'] == 'Client') ? '6':'4'); ?> col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0"><?php echo e(__('My Projects')); ?></h6>
                        </div>
                    </div>
                </div>
                <?php echo $__env->make('users.project', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
        <?php if($role['role'] == 'Client'): ?>
            <div class="col-lg-6">
                <div class="card card-fluid">
                    <div class="card-header">
                        <h6 class="mb-0"><?php echo e(__('Top Due Tasks')); ?></h6>
                    </div>
                    <div class="card-body">
                        <?php echo $__env->make('users.due_tasks', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php if($role['role'] != 'Client'): ?>
        <div class="row">
            <div class="col-lg-4">
                <div class="card card-fluid">
                    <div class="card-header">
                        <h6 class="mb-0"><?php echo e(__('Total Tasks Report')); ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="progress-circle progress-lg mx-auto" data-progress="<?php echo e($user_data['task_report']['percentage']); ?>" data-text="<?php echo e($user_data['task_report']['percentage']); ?>%" data-color="warning"></div>
                        <div class="d-flex my-4 px-5 text-center">
                            <div class="col">
                            <span class="badge badge-dot badge-lg h6">
                                <i class="bg-warning"></i><?php echo e($user_data['task_report']['done']); ?>

                            </span>
                                <small class="d-block text-muted"><?php echo e(__('Done')); ?></small>
                            </div>
                            <div class="col">
                            <span class="badge badge-dot badge-lg h6">
                                <i class="bg-success"></i><?php echo e($user_data['task_report']['open']); ?>

                            </span>
                                <small class="d-block text-muted"><?php echo e(__('Open')); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-fluid">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0"><?php echo e(__('Recent Attachments')); ?></h6>
                                <small class="text-muted"><?php echo e(__('That uploaded for this user\'s assigned project')); ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="scrollbar-inner">
                        <div class="min-h-300 mh-300">
                            <div class="card-wrapper p-3">
                                <?php if($user_data['task_files']->count() > 0): ?>
                                    <?php $__currentLoopData = $user_data['task_files']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task_file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="card mb-3 border shadow-none">
                                            <div class="px-3 py-3">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <img src="<?php echo e(asset('assets/img/icons/files/'.$task_file->extension.'.png')); ?>" class="img-fluid" style="width: 40px;">
                                                    </div>
                                                    <div class="col ml-n2">
                                                        <h6 class="text-sm mb-0">
                                                            <a href="#!"><?php echo e($task_file->name); ?></a>
                                                        </h6>
                                                        <p class="card-text small text-muted">
                                                            <?php echo e($task_file->file_size); ?>

                                                        </p>
                                                    </div>
                                                    <div class="col-auto actions">
                                                        <a href="<?php echo e($logo.$task_file->file); ?>" download class="action-item" role="button">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <div class="py-5">
                                        <h6 class="h6 text-center"><?php echo e(__('No Attachments Found.')); ?></h6>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-fluid">
                    <div class="card-header">
                        <h6 class="mb-0"><?php echo e(__('Top Due Tasks')); ?></h6>
                    </div>
                    <div class="card-body">
                        <?php echo $__env->make('users.due_tasks', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <?php if($role['role'] != 'Client'): ?>
        <script>
            var e = $("#timesheet_logged_hrs");
            var t = {
                chart: {width: "100%", type: "bar", zoom: {enabled: !1}, toolbar: {show: !1}, shadow: {enabled: !1}},
                plotOptions: {bar: {horizontal: !1, columnWidth: "30%", endingShape: "rounded"}},
                stroke: {show: !0, width: 2, colors: ["transparent"]},
                series: [{name: "Timesheet hours ", data: <?php echo json_encode(array_values($user_data['timesheet_chart'])); ?>}],
                xaxis: {labels: {style: {colors: SiteStyle.colors.gray[600], fontSize: "14px", fontFamily: SiteStyle.fonts.base, cssClass: "apexcharts-xaxis-label"}}, axisBorder: {show: !1}, axisTicks: {show: !0, borderType: "solid", color: SiteStyle.colors.gray[300], height: 6, offsetX: 0, offsetY: 0}, type: "category", categories: <?php echo json_encode(array_keys($user_data['timesheet_chart'])); ?>},
                yaxis: {labels: {style: {color: SiteStyle.colors.gray[600], fontSize: "12px", fontFamily: SiteStyle.fonts.base}}, axisBorder: {show: !1}, axisTicks: {show: !0, borderType: "solid", color: SiteStyle.colors.gray[300], height: 6, offsetX: 0, offsetY: 0}},
                fill: {type: "solid"},
                markers: {size: 4, opacity: .7, strokeColor: "#fff", strokeWidth: 3, hover: {size: 7}},
                grid: {borderColor: SiteStyle.colors.gray[300], strokeDashArray: 5},
                dataLabels: {enabled: !1}
            }, a = (e.data().dataset, e.data().labels, e.data().color), n = e.data().height;
            e.data().type, t.colors = [SiteStyle.colors.theme[a]], t.markers.colors = [SiteStyle.colors.theme[a]], t.chart.height = n || 350;
            var o = new ApexCharts(e[0], t);
            setTimeout(function () {
                o.render()
            }, 300);
        </script>
    <?php endif; ?>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/users/info.blade.php ENDPATH**/ ?>