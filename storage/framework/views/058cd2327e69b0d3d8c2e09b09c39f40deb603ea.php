<?php $__env->startSection('title'); ?>
    <?php echo e(__('Email Verification')); ?>

<?php $__env->stopSection(); ?>

<?php
    $logo = \App\Models\Utility::get_file('logo/');
    if (empty($lang)) {
        $lang = Utility::getValByName('default_language');
    }
?>

<?php $__env->startSection('language-bar'); ?>
    <div class="form-group auth-lang">
        <select name="language" id="language" class="form-control px-3"
            onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
            <?php $__currentLoopData = \App\Models\Utility::languages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option <?php if($lang == $code): ?> selected <?php endif; ?>
                    value="<?php echo e(route('verification.notice', $code)); ?>"><?php echo e(ucfirst($language)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="col-sm-8 col-lg-5 col-xl-4">
        <div class="text-center pb-4">
            <img src="<?php echo e($logo . 'logo.png'.'?'.time()); ?>" class="w200">
        </div>
        <div class="card shadow zindex-100 mb-0">
            <div class="card-body px-md-5 py-5">
                <div class="mb-5 mt-3">
                    <h6 class="h3"><?php echo e(__('Verify Your Email Address')); ?></h6>
                    <?php if(session('status') == 'verification-link-sent'): ?>
                        <div class="text-primary" role="alert">
                            <?php echo e(__('A fresh verification link has been sent to your email address.')); ?>

                        </div>
                    <?php endif; ?>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <?php echo e(__('Before proceeding, please check your email for a verification link.')); ?>

                    <?php echo e(__('If you did not receive the email')); ?>,
                    <div class="row mt-3">
                        <div class="col-auto">
                            <form method="POST" action="<?php echo e(route('verification.send')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-primary btn-sm"><?php echo e(__('Resend Verification Email')); ?>

                                </button>
                            </form>
                        </div>
                        <div class="col-auto">
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-danger btn-sm"><?php echo e(__('Logout')); ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/auth/verify.blade.php ENDPATH**/ ?>