<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <tbody>
                <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <div class="font-style font-weight-bold"><?php echo e($plan->name); ?></div>
                        </td>
                        <td>
                            <div class="font-weight-bold"><?php echo e($plan->max_users); ?></div>
                            <div><?php echo e(__('Users')); ?></div>
                        </td>
                        <td>
                            <div class="font-weight-bold"><?php echo e($plan->max_projects); ?></div>
                            <div><?php echo e(__('Projects')); ?></div>
                        </td>
                        <td>
                            <?php if($user->plan == $plan->id): ?>
                                <button type="button" class="btn btn-sm btn-soft-success btn-icon rounded-pill">
                                    <span class="btn-inner--icon"><i class="fas fa-check"></i></span>
                                    <span class="btn-inner--text"><?php echo e(__('Active')); ?></span>
                                </button>
                            <?php else: ?>
                                <?php if($plan->id == 1): ?>
                                    <a href="<?php echo e(route('plan.active',[$user->id,$plan->id, 'duration' => 'monthly'])); ?>" class="btn btn-primary btn-xs" title="Click to Upgrade Plan"><i class="fas fa-cart-plus"></i> <?php echo e(__('Active')); ?></a>
                                <?php else: ?>
                                    <div>
                                        <a href="<?php echo e(route('plan.active',[$user->id,$plan->id, 'duration' => 'monthly'])); ?>" class="btn btn-primary btn-xs" title="Click to Upgrade Plan"><i class="fas fa-cart-plus"></i> <?php echo e(__('One Month')); ?></a>
                                        <a href="<?php echo e(route('plan.active',[$user->id,$plan->id, 'duration' => 'annual'])); ?>" class="btn btn-primary btn-xs" title="Click to Upgrade Plan"><i class="fas fa-cart-plus"></i> <?php echo e(__('One Year')); ?></a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/users/plan.blade.php ENDPATH**/ ?>