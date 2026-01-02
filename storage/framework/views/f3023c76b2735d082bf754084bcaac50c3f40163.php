<?php $__env->startSection('title'); ?>
    <?php echo e($project->title.__("'s Expenses")); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('role'); ?><?php echo e($expense_cnt); ?><?php $__env->stopSection(); ?>

<?php
    $permissions = \Auth::user()->getPermission($project->id);
    $logo = \App\Models\Utility::get_file('/');
?>

<?php $__env->startSection('action-button'); ?>
    <a href="<?php echo e(route('projects.show',$project->id)); ?>" class="btn btn-sm btn-white rounded-circle btn-icon-only ml-0">
        <span class="btn-inner--icon"><i class="fas fa-arrow-left"></i></span>
    </a>
    <?php if(isset($permissions) && in_array('create expense',$permissions)): ?>
        <a href="#" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-0" data-url="<?php echo e(route('projects.expenses.create',$project->id)); ?>" data-ajax-popup="true" data-size="lg" data-title="<?php echo e(__('Create Expense')); ?>">
            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table align-items-center">
                        <thead>
                        <tr>
                            <th scope="col"><?php echo e(__('Attachment')); ?></th>
                            <th scope="col"><?php echo e(__('Name')); ?></th>
                            <th scope="col"><?php echo e(__('Date')); ?></th>
                            <th scope="col"><?php echo e(__('Amount')); ?></th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        <?php if(isset($permissions) && in_array('show expense',$permissions)): ?>
                            <?php if(isset($project->expense) && !empty($project->expense) && count($project->expense) > 0): ?>
                                <?php $__currentLoopData = $project->expense; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th scope="row">
                                            <?php if(!empty($expense->attachment)): ?>
                                                <a href="<?php echo e($logo.$expense->attachment); ?>" class="btn btn-sm btn-secondary btn-icon rounded-pill" download>
                                                    <span class="btn-inner--icon"><i class="fas fa-download"></i></span>
                                                </a>
                                            <?php else: ?>
                                                <a href="#" class="btn btn-sm btn-secondary btn-icon rounded-pill">
                                                    <span class="btn-inner--icon"><i class="fas fa-times-circle"></i></span>
                                                </a>
                                            <?php endif; ?>
                                        </th>
                                        <td>
                                            <span class="h6 text-sm font-weight-bold mb-0"><?php echo e($expense->name); ?></span>
                                            <?php if(!empty($expense->task)): ?><span class="d-block text-sm text-muted"><?php echo e($expense->task->name); ?></span><?php endif; ?>
                                        </td>
                                        <td><?php echo e((!empty($expense->date)) ? \App\Models\Utility::getDateFormated($expense->date) : '-'); ?></td>
                                        <td><?php echo e(\App\Models\Utility::projectCurrencyFormat($project->id,$expense->amount)); ?></td>
                                        <td class="text-right w-15">
                                            <div class="actions">
                                                <a href="#" class="action-item px-2" data-url="<?php echo e(route('projects.expenses.edit',[$project->id,$expense->id])); ?>" data-ajax-popup="true" data-size="lg" data-title="<?php echo e(__('Edit ').$expense->name); ?>" data-toggle="tooltip" data-original-title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="#" class="action-item text-danger px-2" data-toggle="tooltip" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?')); ?>|<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-expense-<?php echo e($expense->id); ?>').submit();">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                            <?php echo Form::open(['method' => 'DELETE', 'route' => ['projects.expenses.destroy',$expense->id],'id'=>'delete-expense-'.$expense->id]); ?>

                                            <?php echo Form::close(); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <th scope="col" colspan="5"><h6 class="text-center"><?php echo e(__('No Expense Found.')); ?></h6></th>
                                </tr>
                            <?php endif; ?>
                        <?php else: ?>
                            <tr>
                                <th scope="col" colspan="5"><h6 class="text-center"><?php echo e(__('Permission Denied.')); ?></h6></th>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/expenses/index.blade.php ENDPATH**/ ?>