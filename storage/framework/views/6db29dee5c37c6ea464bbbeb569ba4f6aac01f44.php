<?php
    $logo_path = \App\Models\Utility::get_file('/');
?>

<div class="col-12">
    <div class="card">
        <div class="row">
            <?php if(count($tasks) > 0): ?>
                <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $permissions = \Auth::user()->getPermission($task->project_id);
                    ?>
                    <div class="col-md-4">
                        <div class="card m-3 card-progress border shadow-none" id="<?php echo e($task->id); ?>"
                            style="<?php echo e(!empty($task->priority_color) ? 'border-left: 2px solid ' . $task->priority_color . ' !important' : ''); ?>;">
                            <div class="card-body">
                                <div class="row align-items-center mb-2">
                                    <div class="col-6">
                                        <span
                                            class="badge badge-pill badge-xs badge-<?php echo e(\App\Models\ProjectTask::$priority_color[$task->priority]); ?>"><?php echo e(\App\Models\ProjectTask::$priority[$task->priority]); ?></span>
                                    </div>
                                    <div class="col-6 text-right">
                                        <?php if(str_replace('%', '', $task->taskProgress()['percentage']) > 0): ?>
                                            <span class="text-sm"><?php echo e($task->taskProgress()['percentage']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if(isset($permissions) && in_array('show task', $permissions)): ?>
                                    <a class="h6 task-name-break"
                                        href="<?php echo e(route('projects.tasks.index', $task->project->id)); ?>"><?php echo e($task->title); ?></a>
                                <?php else: ?>
                                    <a class="h6 task-name-break" href="#"><?php echo e($task->title); ?></a>
                                <?php endif; ?>
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <div class="actions d-inline-block">
                                            <?php if(count($task->taskFiles) > 0): ?>
                                                <div class="action-item mr-2"><i
                                                        class="fas fa-paperclip mr-2"></i><?php echo e(count($task->taskFiles)); ?>

                                                </div>
                                            <?php endif; ?>
                                            <?php if(count($task->comments) > 0): ?>
                                                <div class="action-item mr-2"><i
                                                        class="fas fa-comment-alt mr-2"></i><?php echo e(count($task->comments)); ?>

                                                </div>
                                            <?php endif; ?>
                                            <?php if($task->checklist->count() > 0): ?>
                                                <div class="action-item mr-2"><i
                                                        class="fas fa-tasks mr-2"></i><?php echo e($task->countTaskChecklist()); ?>

                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <?php if(!empty($task->end_date) && $task->end_date != '0000-00-00'): ?>
                                            <small
                                                <?php if(strtotime($task->end_date) < time()): ?> class="text-danger" <?php endif; ?>><?php echo e(\App\Models\Utility::getDateFormated($task->end_date)); ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-7 text-right">
                                        <?php if($users = $task->users()): ?>
                                            <div class="avatar-group">
                                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($key < 3): ?>
                                                        <a href="#" class="avatar rounded-circle avatar-sm">
                                                            <?php if($user->avatar): ?>
                                                                <img src="<?php echo e($logo_path . $user->avatar); ?>"
                                                                    title="<?php echo e($user->name); ?>">
                                                            <?php else: ?>
                                                                <img <?php echo e($user->img_avatar); ?>

                                                                    title="<?php echo e($user->name); ?>">
                                                            <?php endif; ?>
                                                        </a>
                                                            <?php else: ?>
                                                    <?php break; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(count($users) > 3): ?>
                                                <a href="#" class="avatar rounded-circle avatar-sm">
                                                    <img avatar="+ <?php echo e(count($users) - 3); ?>">
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="col-md-12">
                <h6 class="text-center m-3"><?php echo e(__('No tasks found')); ?></h6>
            </div>
        <?php endif; ?>
    </div>
</div>
</div>
<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/tasks/grid.blade.php ENDPATH**/ ?>