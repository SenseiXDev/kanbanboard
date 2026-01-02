<?php $__env->startSection('title'); ?>
    <?php echo e(__('Manage Plans')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-button'); ?>
    <a href="#" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-2" data-url="<?php echo e(route('plans.create')); ?>" data-ajax-popup="true" data-size="lg" data-title="<?php echo e(__('Create Plans')); ?>">
        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 mt-4">
                <div class="card card-pricing popular text-center px-3 mb-5 mb-lg-0">
                    <span class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-primary text-white"><?php echo e($plan->name); ?></span>
                    <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="lg" data-title="<?php echo e(__('Edit Plan')); ?>" data-url="<?php echo e(route('plans.edit',$plan->id)); ?>"><i class="mdi mdi-pencil mr-1"></i><?php echo e(__('Edit')); ?></a>
                    <div class="card-body delimiter-top">
                        <ul class="list-unstyled mb-4">
                            <?php if($plan->id != 1): ?>
                                <li><?php echo e(__('Trial')); ?> : <?php echo e($plan->trial_days); ?> <?php echo e(__('Days')); ?></li>
                                <li><?php echo e(__('Monthly Price')); ?> : <?php echo e((env('CURRENCY') ? env('CURRENCY') : '$')); ?><?php echo e($plan->monthly_price); ?></li>
                                <li><?php echo e(__('Annual Price')); ?> : <?php echo e((env('CURRENCY') ? env('CURRENCY') : '$')); ?><?php echo e($plan->annual_price); ?></li>
                            <?php endif; ?>
                            <li><?php echo e(($plan->max_users < 0)?__('Unlimited'):$plan->max_users); ?> <?php echo e(__('Users')); ?></li>
                            <li><?php echo e(($plan->max_projects < 0)?__('Unlimited'):$plan->max_projects); ?> <?php echo e(__('Projects')); ?></li>
                            <li><?php echo e(($plan->storage_limit < 0)?__('Unlimited'):$plan->storage_limit); ?> <?php echo e(__('Storage Limit')); ?></li>
                            <?php if($plan->description): ?>
                                <li>
                                    <small><?php echo e($plan->description); ?></small>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        $(document).ready(function () {
            $(document).on('click', '#prevent_plan', function () {
                show_toastr('Error', '<?php echo e(__('Please Enter Stripe or PayPal Payment Details.')); ?>', 'error');
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/plans/index.blade.php ENDPATH**/ ?>