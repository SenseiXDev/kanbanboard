    <?php
    $logo_path=\App\Models\Utility::get_file('/');
    ?>  

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
                    <th scope="col"><?php echo e(__('Assigned To')); ?></th>
                    <th scope="col"><?php echo e(__('Completion')); ?></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody class="list">
                <?php if(count($tasks) > 0): ?>
                    <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <span class="h6 text-sm font-weight-bold mb-0"><a href="<?php echo e(route('projects.tasks.index',$task->project->id)); ?>"><?php echo e($task->title); ?></a></span>
                                <span class="d-block text-sm text-muted"><?php echo e($task->project->title); ?>

                                    <span class="badge badge-xs badge-<?php echo e((\Auth::user()->checkProject($task->project_id) == 'Owner') ? 'success' : 'warning'); ?>"><?php echo e(__(\Auth::user()->checkProject($task->project_id))); ?></span>
                                </span>
                            </td>
                            <td><?php echo e($task->stage->name); ?></td>
                            <td>
                                <span class="badge badge-pill badge-sm badge-<?php echo e(__(\App\Models\ProjectTask::$priority_color[$task->priority])); ?>"><?php echo e(__(\App\Models\ProjectTask::$priority[$task->priority])); ?></span>
                            </td>
                            <td class="<?php echo e((strtotime($task->end_date) < time()) ? 'text-danger' : ''); ?>"><?php echo e(\App\Models\Utility::getDateFormated($task->end_date)); ?></td>
                            <td>
                                <div class="avatar-group">
                                    <?php if($task->users()->count() > 0): ?>
                                        <?php if($users = $task->users()): ?>
                                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($key<3): ?>
                                                    <a href="#" class="avatar rounded-circle avatar-sm">
                                                        <?php if($user->avatar): ?>
                                                            <img  src="<?php echo e($logo_path.$user->avatar); ?>" title="<?php echo e($user->name); ?>"  >
                                                      
                                                        <?php else: ?>
                                                           <img <?php echo e($user->img_avatar); ?> title="<?php echo e($user->name); ?>">
                                                        <?php endif; ?>
                                                    </a>

                                                <?php else: ?>
                                                    <?php break; ?>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                        <?php if(count($users) > 3): ?>
                                            <a href="#" class="avatar rounded-circle avatar-sm">
                                                <img avatar="+ <?php echo e(count($users)-3); ?>">
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php echo e(__('-')); ?>

                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="completion mr-2"><?php echo e($task->taskProgress()['percentage']); ?></span>
                                    
                                </div>
                            </td>
                            <td class="text-right w-15">
                                <div class="actions">
                                    <a class="action-item px-1" data-toggle="tooltip" data-original-title="<?php echo e(__('Attachment')); ?>">
                                        <i class="fas fa-paperclip mr-2"></i><?php echo e(count($task->taskFiles)); ?>

                                    </a>
                                    <a class="action-item px-1" data-toggle="tooltip" data-original-title="<?php echo e(__('Comment')); ?>">
                                        <i class="fas fa-comment-alt mr-2"></i><?php echo e(count($task->comments)); ?>

                                    </a>
                                    <a class="action-item px-1" data-toggle="tooltip" data-original-title="<?php echo e(__('Checklist')); ?>">
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
<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/tasks/list.blade.php ENDPATH**/ ?>