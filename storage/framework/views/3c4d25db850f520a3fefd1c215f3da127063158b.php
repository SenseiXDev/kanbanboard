<div class="p-2">
    <div class="row mb-4">
        <div class="col-md-6">
            <span class="font-weight-bold lab-title"><?php echo e(__('Status')); ?> : </span>
            <span class="badge badge-pill badge-<?php echo e(\App\Models\Project::$status_color[$milestone->status]); ?>"><?php echo e(__(\App\Models\Project::$status[$milestone->status])); ?></span>
        </div>
        <div class="col-md-12 pt-4">
            <div class="font-weight-bold lab-title"><?php echo e(__('Description')); ?> :</div>
            <p class="mt-1 lab-val"><?php echo e((!empty($milestone->description)) ? $milestone->description : '-'); ?></p>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table align-items-center">
                        <thead>
                        <tr>
                            <th scope="col"><?php echo e(__('Name')); ?></th>
                            <th scope="col"><?php echo e(__('Stage')); ?></th>
                            <th scope="col"><?php echo e(__('Priority')); ?></th>
                            <th scope="col"><?php echo e(__('End Date')); ?></th>
                            <th scope="col"><?php echo e(__('Completion')); ?></th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        <?php if(count($milestone->tasks) > 0): ?>
                            <?php $__currentLoopData = $milestone->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <span class="h6 text-sm"><?php echo e($task->title); ?></span>
                                    </td>
                                    <td><?php echo e($task->stage->name); ?></td>
                                    <td>
                                        <span class="badge badge-pill badge-sm badge-<?php echo e(__(\App\Models\ProjectTask::$priority_color[$task->priority])); ?>"><?php echo e(__(\App\Models\ProjectTask::$priority[$task->priority])); ?></span>
                                    </td>
                                    <td class="<?php echo e((strtotime($task->end_date) < time()) ? 'text-danger' : ''); ?>"><?php echo e(\App\Models\Utility::getDateFormated($task->end_date)); ?></td>
                                    <td>
                                        <?php echo e($task->taskProgress()['percentage']); ?>

                                    </td>
                                    <td class="text-right w-15">
                                        <div class="actions">
                                            <a class="action-item px-2" data-toggle="tooltip" data-original-title="<?php echo e(__('Attachment')); ?>">
                                                <i class="fas fa-paperclip mr-2"></i><?php echo e(count($task->taskFiles)); ?>

                                            </a>
                                            <a class="action-item px-2" data-toggle="tooltip" data-original-title="<?php echo e(__('Comment')); ?>">
                                                <i class="fas fa-comment-alt mr-2"></i><?php echo e(count($task->comments)); ?>

                                            </a>
                                            <a class="action-item px-2" data-toggle="tooltip" data-original-title="<?php echo e(__('Checklist')); ?>">
                                                <i class="fas fa-tasks mr-2"></i><?php echo e($task->countTaskChecklist()); ?>

                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <tr>
                                <th scope="col" colspan="7"><h6 class="text-center"><?php echo e(__('No tasks found')); ?></h6></th>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/projects/milestoneShow.blade.php ENDPATH**/ ?>