
<?php
$logo_path = \App\Models\Utility::get_file('/');
$profile = Utility::get_file('/avatars');
?>
<div class="col-12">
    <div class="card">
        <div class="table-responsive">
            <table class="table align-items-center">
                <thead>
                <tr>
                    <th scope="col" class="sort" data-sort="name"><?php echo e(__('Project')); ?></th>
                    <th scope="col" class="sort" data-sort="status"><?php echo e(__('Status')); ?></th>
                    <th scope="col"><?php echo e(__('Users')); ?></th>
                    <th scope="col" class="sort" data-sort="completion"><?php echo e(__('Completion')); ?></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody class="list">
                <?php if(isset($projects) && !empty($projects) && count($projects) > 0): ?>
                    <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <th scope="row">
                                <div class="media align-items-center">
                                    <div>


                                    <?php if($project->image): ?>

                                    <img src="<?php echo e($logo_path.$project->image); ?>" alt="<?php echo e($project->title); ?>"  class="avatar rounded-circle" id="blah" />
                                    <?php else: ?>

                                     <img <?php echo e($project->img_image); ?> class="avatar rounded-circle">
                                    <?php endif; ?>



                                    </div>
                                    <div class="media-body ml-4">
                                        <?php if($project->is_active == 1): ?>
                                            <a href="<?php echo e(route('projects.show',$project)); ?>" class="name mb-0 h6 text-sm"><?php echo e($project->title); ?></a>
                                        <?php else: ?>
                                            <a href="#" class="name mb-0 h6 text-sm"><?php echo e($project->title); ?></a>
                                        <?php endif; ?>
                                        <br>
                                        <span class="badge badge-xs badge-<?php echo e((\Auth::user()->checkProject($project->id) == 'Owner') ? 'success' : 'warning'); ?>" data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo e(__('You are ') .__(ucfirst($project->permission()))); ?>"><?php echo e(__(\Auth::user()->checkProject($project->id))); ?></span>
                                    </div>
                                </div>
                            </th>
                            <td>
                                <span class="badge badge-dot mr-4">
                                      <i class="bg-<?php echo e(\App\Models\Project::$status_color[$project->status]); ?>"></i>
                                      <span class="status"><?php echo e(__(\App\Models\Project::$status[$project->status])); ?></span>
                                </span>
                            </td>
                            <td>
                                <div class="avatar-group" id="project_<?php echo e($project->id); ?>">
                                    <?php if(isset($project->users) && !empty($project->users) && count($project->users) > 0): ?>
                                        <?php $__currentLoopData = $project->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($key < 3): ?>
                                                <a href="#" class="avatar rounded-circle avatar-sm">
                                                       <?php if($user->avatar): ?>

                                                        <img src="<?php echo e($logo_path.$user->avatar); ?>" alt="<?php echo e($user->name); ?>" title="<?php echo e($user->name); ?>" />
                                                        <?php else: ?>

                                                         <img <?php echo e($user->img_avatar); ?> title="<?php echo e($user->name); ?>">
                                                        <?php endif; ?>
                                                </a>
                                            <?php else: ?>
                                                <?php break; ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(count($project->users) > 3): ?>
                                            <a href="#" class="avatar rounded-circle avatar-sm">
                                                <img avatar="+ <?php echo e(count($project->users)-3); ?>">
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php echo e(__('-')); ?>

                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="completion mr-2"><?php echo e($project->project_progress()['percentage']); ?></span>
                                    <div>
                                        <div class="progress" style="width: 100px;">
                                            <div class="progress-bar bg-<?php echo e($project->project_progress()['color']); ?>" role="progressbar" aria-valuenow="<?php echo e($project->project_progress()['percentage']); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo e($project->project_progress()['percentage']); ?>;"></div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-right w-15">
                                <?php if($project->is_active == 1): ?>
                                    <?php if(\Auth::user()->checkProject($project->id) == 'Owner'): ?>
                                        <div class="actions">
                                            <a href="#" data-url="<?php echo e(route('invite.project.member.view', $project->id)); ?>" data-ajax-popup="true" data-size="lg" data-title="<?php echo e(__('Invite Member')); ?>" class="action-item px-2" data-toggle="tooltip" data-original-title="<?php echo e(__('Invite Member')); ?>">
                                                <i class="fas fa-paper-plane"></i>
                                            </a>
                                            <a href="#" data-url="<?php echo e(route('projects.copy', $project->id)); ?>" data-ajax-popup="true" data-size="lg" data-title="<?php echo e(__('Duplicate Project')); ?>" class="action-item" data-toggle="tooltip" data-original-title="<?php echo e(__('Duplicate')); ?>">
                                                <i class="fas fa-copy"></i>
                                            </a>
                                            <a href="<?php echo e(route('projects.edit',$project)); ?>" class="action-item px-2" data-toggle="tooltip" data-original-title="<?php echo e(__('Edit')); ?>">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="action-item text-danger px-2" data-toggle="tooltip" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?')); ?>|<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-project-<?php echo e($project->id); ?>').submit();">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                        <?php echo Form::open(['method' => 'DELETE', 'route' => ['projects.destroy',$project->id],'id'=>'delete-project-'.$project->id]); ?>

                                        <?php echo Form::close(); ?>

                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="actions text-center px-4">
                                        <a href="#" class="action-item" data-toggle="tooltip" data-original-title="<?php echo e(__('Locked')); ?>">
                                            <i class="fas fa-lock"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <tr>
                        <th scope="col" colspan="7"><h6 class="text-center"><?php echo e(__('No Projects Found.')); ?></h6></th>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/projects/list.blade.php ENDPATH**/ ?>