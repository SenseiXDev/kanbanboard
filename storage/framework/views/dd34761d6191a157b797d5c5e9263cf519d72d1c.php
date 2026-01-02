<?php
    $logo_path = \App\Models\Utility::get_file('/');
?>
<?php if($project->users || $project->users != '' || $project->users != null): ?>
    <?php $__currentLoopData = $project->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <th scope="row">
                <div class="media align-items-center">
                    <div>
                        <?php if($user->avatar): ?>
                            <img src="<?php echo e($logo_path . $user->avatar); ?>" class="avatar rounded-circle avatar-sm"
                                title="<?php echo e($user->name); ?>">
                        <?php else: ?>                        
                            <img <?php echo e($user->img_avatar); ?> class="avatar rounded-circle avatar-sm">
                        <?php endif; ?>
                    </div>
                    <div class="media-body ml-3">
                        <a class="name mb-0 h6 text-sm"><?php echo e($user->name); ?></a>
                        <?php if(\Auth::user()->checkProject($project->id) == 'Owner' && Auth::user()->id != $user->id): ?>
                            <span
                                class="badge badge-xs badge-<?php echo e($user->pivot->permission == 'user' ? 'primary' : 'warning'); ?>"><?php echo e(__(ucfirst($user->pivot->permission))); ?></span>
                        <?php endif; ?>
                        <br>
                        <a class="text-sm text-muted"><?php echo e($user->email); ?></a>
                    </div>
                </div>
            </th>
            <?php if(\Auth::user()->checkProject($project->id) == 'Owner' && Auth::user()->id != $user->id): ?>
                <td>
                    <span class="text-sm ml-3 text-primary" data-toggle="tooltip"
                        data-original-title="<?php echo e(__('Permission')); ?>"
                        data-url="<?php echo e(route('projects.user.permission', [$project->id, $user->id])); ?>"
                        data-ajax-popup="true" data-size="lg" data-title="<?php echo e(__('Edit Permission')); ?>">
                        <i class="fas fa-lock"></i>
                    </span>
                    <span class="text-sm ml-3 text-danger" data-toggle="tooltip"
                        data-original-title="<?php echo e(__('Delete')); ?>"
                        data-confirm="<?php echo e(__('Are You Sure?')); ?>|<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                        data-confirm-yes="document.getElementById('remove-project-user-<?php echo e($user->pivot->id); ?>').submit();">
                        <i class="fas fa-trash-alt"></i>
                    </span>
                    <?php echo Form::open([
                        'method' => 'PATCH',
                        'route' => ['remove.user.from.project', $project->id, $user->id],
                        'id' => 'remove-project-user-' . $user->pivot->id,
                    ]); ?>

                    <?php echo Form::close(); ?>

                </td>
            <?php else: ?>
                <td></td>
            <?php endif; ?>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>                                                    
<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/projects/users.blade.php ENDPATH**/ ?>