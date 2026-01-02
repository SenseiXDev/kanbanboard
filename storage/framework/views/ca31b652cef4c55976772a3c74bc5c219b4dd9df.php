<?php $__env->startSection('title'); ?>
    <?php echo e(__('Register')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('custom-scripts'); ?>
    <?php if(env('RECAPTCHA_MODULE') == 'yes'): ?>
            <?php echo NoCaptcha::renderJs(); ?>

    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php
    $logo = \App\Models\Utility::get_file('logo/');
?>

<?php $__env->startSection('language-bar'); ?>
    <div class="form-group auth-lang">
        <select name="language" id="language" class="form-control px-3" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
            <?php $__currentLoopData = \App\Models\Utility::languages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option <?php if($lang == $code): ?> selected <?php endif; ?> value="<?php echo e(route('register',$code)); ?>"><?php echo e(ucfirst($language)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="col-sm-8 col-lg-5">
        <div class="text-center pb-4">
            <img src="<?php echo e($logo.'logo.png'.'?'.time()); ?>" style="height: 40px; width:163px;">
        </div>

        <div class="card shadow zindex-100 mb-0">
            <div class="card-body px-md-5 py-5">
                <?php if(session('status')): ?>
                    <div class="mb-4 font-medium text-lg text-green-600 text-danger">
                        <?php echo e(__('Email SMTP settings does not configured so please contact to your site admin.')); ?>

                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <h6 class="h3"><?php echo e(__('Create account')); ?></h6>
                </div>

                <span class="clearfix"></span>
                <form method="POST" action="<?php echo e(route('register')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="interested_plan_id" value="<?php echo e(request()->has('plan') ? request()->plan : 0); ?>">
                    <input type="hidden" name="is_register_trial" value="<?php echo e(request()->has('trial') ? request()->has('trial') : 0); ?>">
                    <div class="form-group">
                        <label class="form-control-label"><?php echo e(__('Name')); ?></label>
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input id="name" type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="name" value="<?php echo e(old('name')); ?>" required autocomplete="name" autofocus>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback" role="alert">
                                <strong><?php echo e($message); ?></strong>
                            </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label"><?php echo e(__('Email address')); ?></label>
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email">
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback" role="alert">
                                <strong><?php echo e($message); ?></strong>
                            </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-control-label"><?php echo e(__('Password')); ?></label>
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input id="password" type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" required autocomplete="new-password">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                  <a href="#" data-toggle="password-text" data-target="#password">
                                    <i class="fas fa-eye"></i>
                                  </a>
                                </span>
                            </div>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($message); ?></strong>
                                </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label"><?php echo e(__('Confirm password')); ?></label>
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>
                    <div class="my-4">
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="check-terms" required>
                            <label class="custom-control-label" for="check-terms"><?php echo e(__('I agree to the')); ?> <a href="#"><?php echo e(__('terms and conditions')); ?></a></label>
                        </div>
                    </div>
                    <?php if(env('RECAPTCHA_MODULE') == 'yes'): ?>
                        <div class="form-group col-lg-12 col-md-12 mt-3">
                            <?php echo NoCaptcha::display(); ?>

                            <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="small text-danger" role="alert">
                                <strong><?php echo e($message); ?></strong>
                            </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    <?php endif; ?>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-sm btn-primary btn-icon rounded-pill">
                            <span class="btn-inner--text"><?php echo e(__('Create my account')); ?></span>
                            <span class="btn-inner--icon"><i class="fas fa-long-arrow-alt-right"></i></span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer px-md-5"><small><?php echo e(__('Already have an acocunt?')); ?></small>
                <a href="<?php echo e(route('login',$lang)); ?>" class="small font-weight-bold"><?php echo e(__('Sign in')); ?></a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/auth/register.blade.php ENDPATH**/ ?>