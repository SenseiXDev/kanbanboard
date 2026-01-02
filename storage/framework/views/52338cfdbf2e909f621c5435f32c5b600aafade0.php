<?php $__env->startSection('title'); ?>
    <?php echo e(__('Zoom Meeting')); ?>

<?php $__env->stopSection(); ?>

<?php
    $logo = \App\Models\Utility::get_file('logo/');
    $logo_path = \App\Models\Utility::get_file('/');
?>

<?php $__env->startSection('action-button'); ?>
    <?php if(\Auth::user()->type == 'owner'): ?>
        <a href="#" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-2"
            data-url="<?php echo e(route('zoommeeting.create')); ?>" data-ajax-popup="true" data-size="lg"
            data-title="<?php echo e(__('Create Meeting')); ?>">
            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
        </a>
    <?php endif; ?>
    <a href="<?php echo e(route('zoommeeting.calendar')); ?>" class="btn btn-sm btn-white rounded-pill ml-2">
        <span class="btn-inner--icon"><?php echo e(__('Calendar')); ?></span>
    </a>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="table-responsive">
            <table class="table align-items-center" id="myTable">
                <thead>
                    <tr>
                        <th> <?php echo e(__('TITLE')); ?> </th>
                        <th> <?php echo e(__('PROJECT')); ?> </th>
                        <?php if(\Auth::user()->type == 'owner'): ?>
                            <th> <?php echo e(__('CLIENT')); ?> </th>
                            <th> <?php echo e(__('EMPLOYEE')); ?> </th>
                        <?php endif; ?>
                        <th> <?php echo e(__('MEETING TIME')); ?> </th>
                        <th> <?php echo e(__('DURATION')); ?> </th>
                        <th> <?php echo e(__('JOIN URL')); ?> </th>
                        <th> <?php echo e(__('STATUS')); ?> </th>
                        <?php if(\Auth::user()->type == 'owner'): ?>
                            <th class="text-right"> <?php echo e(__('Action')); ?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $meetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($item->title); ?></td>
                            <td><?php echo e(!empty($item->projectName) ? $item->projectName->title : ''); ?></td>
                            <?php if(\Auth::user()->type == 'owner'): ?>
                                <td>
                                    <?php if($item->client_id != '0'): ?>
                                        <?php $__currentLoopData = $item->clients($item->client_id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $projectClient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="#" class="avatar rounded-circle avatar-sm img_group_merge">
                                                <img alt=""
                                                    <?php if(!empty($projectClient->avatar)): ?> src="<?php echo e($logo_path . $projectClient->avatar); ?>" <?php else: ?>  avatar="<?php echo e(!empty($projectClient) ? $projectClient->name : ''); ?>" <?php endif; ?>
                                                    data-original-title="<?php echo e(!empty($projectClient) ? $projectClient->name : ''); ?>"
                                                    data-toggle="tooltip"
                                                    data-original-title="<?php echo e(!empty($projectClient) ? $projectClient->name : ''); ?>"
                                                    class="">
                                            </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php $__currentLoopData = $item->users($item->user_id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $projectUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="#" class="avatar rounded-circle avatar-sm img_group_merge">
                                            <img alt=""
                                                <?php if(!empty($projectUser->avatar)): ?> src="<?php echo e($logo_path . $projectUser->avatar); ?>" <?php else: ?>  avatar="<?php echo e(!empty($projectUser) ? $projectUser->name : ''); ?>" <?php endif; ?>
                                                data-original-title="<?php echo e(!empty($projectUser) ? $projectUser->name : ''); ?>"
                                                data-toggle="tooltip"
                                                data-original-title="<?php echo e(!empty($projectUser) ? $projectUser->name : ''); ?>"
                                                class="">
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                            <?php endif; ?>
                            <td><?php echo e($item->start_date); ?></td>
                            <td><?php echo e($item->duration); ?> <?php echo e(__('Minutes')); ?></td>

                            <td>
                                <?php if($item->created_by == \Auth::user()->id && $item->checkDateTime()): ?>
                                    <a href="<?php echo e($item->start_url); ?>" target="_blank"> <?php echo e(__('Start meeting')); ?> <i
                                            class="fas fa-external-link-square-alt "></i></a>
                                <?php elseif($item->checkDateTime()): ?>
                                    <a href="<?php echo e($item->join_url); ?>" target="_blank"> <?php echo e(__('Join meeting')); ?> <i
                                            class="fas fa-external-link-square-alt "></i></a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>

                            </td>
                            <td>
                                <?php if($item->checkDateTime()): ?>
                                    <?php if($item->status == 'waiting'): ?>
                                        <span class="badge badge-info"><?php echo e(ucfirst($item->status)); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-success"><?php echo e(ucfirst($item->status)); ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge badge-danger"><?php echo e(__('End')); ?></span>
                                <?php endif; ?>
                            </td>
                            <?php if(\Auth::user()->type == 'owner'): ?>
                                <td class="text-right">

                                    <a href="#!" class="action-item " data-toggle="tooltip"
                                        data-original-title="<?php echo e(__('Delete')); ?>"
                                        data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?"
                                        data-confirm-yes="document.getElementById('delete-form-<?php echo e($item->id); ?>').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php echo Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['zoommeeting.destroy', $item->id],
                                        'id' => 'delete-form-' . $item->id,
                                    ]); ?>

                                    <?php echo Form::close(); ?>

                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/zoommeeting/index.blade.php ENDPATH**/ ?>