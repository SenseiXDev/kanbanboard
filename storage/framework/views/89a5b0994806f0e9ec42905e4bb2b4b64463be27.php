
<?php
$logo_path = \App\Models\Utility::get_file('/');
?>
<?php if(isset($projects) && !empty($projects) && count($projects) > 0): ?>
    <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-xl-4 col-lg-4 col-sm-6">
            <div class="card hover-shadow-lg">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 <?php echo e((strtotime($project->end_date) < time()) ? 'text-danger' : ''); ?>" data-toggle="tooltip" data-original-title="<?php echo e(__('End Date')); ?>"><?php echo e(\App\Models\Utility::getDateFormated($project->end_date)); ?></h6>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-xs badge-<?php echo e((\Auth::user()->checkProject($project->id) == 'Owner') ? 'success' : 'warning'); ?>" data-toggle="tooltip" data-original-title="<?php echo e(__('You are ') .__(ucfirst($project->permission()))); ?>"><?php echo e(__(\Auth::user()->checkProject($project->id))); ?></span>
                        </div>
                    </div>
                </div>
                <div class="card-body text-center">
                    <a href="<?php echo e(route('projects.show',$project)); ?>" class="avatar rounded-circle avatar-lg hover-translate-y-n3">

                                  <?php if($project->image): ?>

                                    <img src="<?php echo e($logo_path.$project->image); ?>" alt="<?php echo e($project->title); ?>"  class="avatar avatar-xl fix_size_show_img" id="blah" />
                                    <?php else: ?>

                                   <img <?php echo e($project->img_image); ?> title="<?php echo e($project->title); ?>">
                                    <?php endif; ?>
                       <!--  <img <?php echo e($project->img_image); ?> > -->
                    </a>
                    <h5 class="h6 my-4">
                        <?php if($project->is_active == 1): ?>
                            <a href="<?php echo e(route('projects.show',$project)); ?>"><?php echo e($project->title); ?></a>
                        <?php else: ?>
                            <a href="#"><?php echo e($project->title); ?></a>
                        <?php endif; ?>
                        <br>
                    </h5>
                    <div class="avatar-group hover-avatar-ungroup mb-3" id="project_<?php echo e($project->id); ?>">
                        <?php if(isset($project->users) && !empty($project->users) && count($project->users) > 0): ?>
                            <?php $__currentLoopData = $project->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($key < 3): ?>
                                    <a href="#" class="avatar rounded-circle avatar-sm">


                                   <?php if($user->avatar): ?>

                                    <img src="<?php echo e($logo_path.$user->avatar); ?>" alt="<?php echo e($user->name); ?>"  class="" />
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
                        <?php endif; ?>
                    </div>
                    <span class="clearfix"></span>
                    <span class="badge badge-pill badge-<?php echo e(\App\Models\Project::$status_color[$project->status]); ?>"><?php echo e(__(\App\Models\Project::$status[$project->status])); ?></span>
                </div>
                <div class="progress w-100 height-2">
                    <div class="progress-bar bg-<?php echo e($project->project_progress()['color']); ?>" role="progressbar" aria-valuenow="<?php echo e($project->project_progress()['percentage']); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo e($project->project_progress()['percentage']); ?>;"></div>
                </div>
                <div class="card-footer">
                    <?php if($project->is_active == 1): ?>
                        <?php if(\Auth::user()->checkProject($project->id) == 'Owner'): ?>
                            <div class="actions d-flex justify-content-between px-4">
                                <a href="#" data-url="<?php echo e(route('invite.project.member.view', $project->id)); ?>" data-ajax-popup="true" data-size="lg" data-title="<?php echo e(__('Invite Member')); ?>" class="action-item" data-toggle="tooltip" data-original-title="<?php echo e(__('Invite Member')); ?>">
                                    <i class="fas fa-paper-plane"></i>
                                </a>

                                <a href="#" data-url="<?php echo e(route('projects.copy', $project->id)); ?>" data-ajax-popup="true" data-size="lg" data-title="<?php echo e(__('Duplicate Project')); ?>" class="action-item" data-toggle="tooltip" data-original-title="<?php echo e(__('Duplicate')); ?>">
                                    <i class="fas fa-copy"></i>
                                </a>

                                <a href="<?php echo e(route('projects.edit',$project)); ?>" class="action-item" data-toggle="tooltip" data-original-title="<?php echo e(__('Edit')); ?>">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="action-item text-danger" data-toggle="tooltip" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?')); ?>|<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-project-<?php echo e($project->id); ?>').submit();">
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
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php else: ?>
    <div class="col-xl-12 col-lg-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h6 class="text-center mb-0"><?php echo e(__('No Projects Found.')); ?></h6>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/projects/grid.blade.php ENDPATH**/ ?>