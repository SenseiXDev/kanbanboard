<div class="scrollbar-inner">
    <div class="min-h-300 mh-300">
        <div class="container">
            <?php if($user_data['due_task']->count() > 0): ?>
                <?php $__currentLoopData = $user_data['due_task']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $due_task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="row mb-3">
                        <div class="col-9">
                            <div class="progress-wrapper">
                                <span class="progress-label text-muted text-sm" data-toggle="tooltip" data-original-title="<?php echo e($due_task->project->title); ?>"><a href="<?php echo e(route('projects.tasks.index',$due_task->project->id)); ?>" class="text-muted"><?php echo e($due_task->name); ?></a></span>
                                <div class="progress mt-1 mb-2 height-5">
                                    <div class="progress-bar bg-<?php echo e($due_task->taskProgress()['color']); ?>" role="progressbar" aria-valuenow="<?php echo e($due_task->taskProgress()['percentage']); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo e($due_task->taskProgress()['percentage']); ?>;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3 align-self-end text-right">
                            <span class="h6 mb-0"><?php echo e($due_task->taskProgress()['percentage']); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="py-5">
                    <h6 class="h6 text-center"><?php echo e(__('No Tasks Found.')); ?></h6>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/users/due_tasks.blade.php ENDPATH**/ ?>