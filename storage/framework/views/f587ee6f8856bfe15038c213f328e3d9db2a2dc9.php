<?php
$logo_path = \App\Models\Utility::get_file('/');
?>

<?php if(isset($users) && !empty($users) && count($users) > 0): ?>
    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php ($commonData = $user->usrCommonData()); ?>

        <div class="col-lg-3 col-sm-6">
            <div class="card hover-shadow-lg">
                <div class="card-header border-0 pb-0 pt-2 px-3">
                    <div class="text-right">
                        <span class="badge badge-xs badge-<?php echo e($user->usrRole()['color']); ?>"><?php echo e(__($user->usrRole()['role'])); ?></span>
                    </div>
                </div>
                <div class="card-body text-center">
                    <div class="avatar-parent-child">
                        <?php if($user->avatar): ?>
                        <img src="<?php echo e($logo_path.$user->avatar); ?>" alt="<?php echo e($user->name); ?>"  class="avatar rounded-circle avatar-lg" />
                        <?php else: ?>
                        <img <?php echo e($user->img_avatar); ?>  class="avatar rounded-circle avatar-lg" title="<?php echo e($user->name); ?>">
                        <?php endif; ?>
                    </div>
                    <h5 class="h6 mt-4 mb-0">
                        <?php if($user->is_active == 1): ?>
                            <a href="<?php echo e(route('users.info',$user->id)); ?>"><?php echo e($user->name); ?></a>
                        <?php else: ?>
                            <a href="#"><?php echo e($user->name); ?></a>
                        <?php endif; ?>
                    </h5>
                    <p class="d-block text-sm text-muted mb-3"><?php echo e($user->email); ?></p>
                    <?php if($user->is_active == 1): ?>

                        <div class="actions px-4 pb-2">
                            <a href="<?php echo e(route('users.info',$user->id)); ?>" class="action-item mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="#" class="action-item mr-3" data-size="lg" data-url="<?php echo e(route('user.reset',\Crypt::encrypt($user->id))); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Forgot Password')); ?>" data-toggle="tooltip" data-original-title="<?php echo e(__('Forgot Password')); ?>">
                                <i class="fas fa-key"></i>
                            </a>
                            <a href="#" class="action-item" data-toggle="tooltip" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?')); ?>|<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-user-<?php echo e($user->id); ?>').submit();">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                            <?php echo Form::open(['method' => 'DELETE', 'route' => ['user.destroy',$user->id],'id'=>'delete-user-'.$user->id]); ?>

                            <?php echo Form::close(); ?>

                        </div>
                    <?php else: ?>
                        <div class="actions px-4 pb-2">
                            <a href="#" class="action-item" data-toggle="tooltip" data-original-title="<?php echo e(__('Locked')); ?>">
                                <i class="fas fa-lock"></i>
                            </a>
                        </div>
                    <?php endif; ?>

                    <small data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo e(__('Last Login')); ?>"><?php echo e((!empty($user->last_login_at)) ? Utility::getDateFormated($user->last_login_at,true) : '-'); ?></small>
                </div>
                <div class="card-body border-top">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-6">
                            <div class="max-w-120">
                                <div class="spark-chart" data-toggle="spark-chart" data-type="line" data-height="50" data-color="success" data-dataset="<?php echo e(json_encode(array_values($commonData['timesheet']))); ?>"></div>
                            </div>
                        </div>
                        <div class="col-auto text-center">
                            <span class="d-block h4 mb-0"><?php echo e($commonData['open_task']); ?></span>
                            <span class="d-block text-sm text-muted"><?php echo e(__('Open tasks')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="actions d-flex justify-content-between">
                        <a href="#" data-url="<?php echo e(route('user.info.popup',[$user->id,'project'])); ?>" data-ajax-popup="true" data-size="md" data-title="<?php echo e($user->name.__("'s Projects")); ?>" class="action-item">
                            <span class="btn-inner--icon"><?php echo e(__('Projects')); ?></span>
                        </a>
                        <a href="#" data-url="<?php echo e(route('user.info.popup',[$user->id,'due_task'])); ?>" data-ajax-popup="true" data-size="md" data-title="<?php echo e($user->name.__("'s Top Due Tasks")); ?>" class="action-item">
                            <span class="btn-inner--icon"><?php echo e(__('Due Tasks')); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php else: ?>
    <div class="col-xl-12 col-lg-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h6 class="text-center mb-0"><?php echo e(__('No User Found.')); ?></h6>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/users/grid.blade.php ENDPATH**/ ?>