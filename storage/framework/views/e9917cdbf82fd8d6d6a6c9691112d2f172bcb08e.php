<?php
$logo_path = \App\Models\Utility::get_file('/');
?>

<?php if(isset($users) && !empty($users) && count($users) > 0): ?>
    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-lg-3 col-sm-6">
            <div class="card hover-shadow-lg">
                <div class="card-body text-center">
                    <div class="avatar-parent-child">
                        <?php if($user->avatar): ?>
                            <img src="<?php echo e($logo_path.$user->avatar); ?>" class="avatar rounded-circle avatar-lg"  alt="<?php echo e($user->name); ?>" title="<?php echo e($user->name); ?>"  class="" />
                        <?php else: ?>
                            <img <?php echo e($user->img_avatar); ?>  class="avatar rounded-circle avatar-lg" title="<?php echo e($user->name); ?>">
                        <?php endif; ?>
                    </div>
                    <h5 class="h6 mt-4 mb-0">
                        <p class="mb-1"><?php echo e($user->name); ?></p>
                    </h5>
                    <p class="d-block text-sm text-muted mb-1"><?php echo e($user->email); ?></p>
                    <small data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo e(__('Last Login')); ?>"><?php echo e((!empty($user->last_login_at)) ? Utility::getDateFormated($user->last_login_at,true) : '-'); ?></small>
                </div>
                <div class="card-body border-top">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-6 text-center">
                            <span class="d-block font-weight-bold mb-0"><?php echo e($user->getPlan() ? $user->getPlan()->name : '-'); ?></span>
                        </div>
                        <div class="col-6 text-center">
                            <a href="#" class="btn rounded btn-xs btn-primary" data-url="<?php echo e(route('plan.upgrade',$user->id)); ?>" data-size="lg" data-ajax-popup="true" data-title="<?php echo e(__('Upgrade Plan')); ?>"><?php echo e(__('Upgrade')); ?></a>
                        </div>
                        <div class="col-12">
                            <hr class="my-3">
                        </div>
                        <div class="col-6 text-center">
                            <span class="d-block h4 mb-0"><?php echo e(count($user->contacts)); ?></span>
                            <span class="d-block text-sm text-muted"><?php echo e(__('Members')); ?></span>
                        </div>
                        <div class="col-6 text-center">
                            <span class="d-block h4 mb-0"><?php echo e($user->projects->count()); ?></span>
                            <span class="d-block text-sm text-muted"><?php echo e(__('Projects')); ?></span>
                        </div>
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
<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/admin/view.blade.php ENDPATH**/ ?>