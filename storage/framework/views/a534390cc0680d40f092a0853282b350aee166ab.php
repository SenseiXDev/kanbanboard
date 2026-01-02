<?php $__env->startSection('title'); ?>
    <?php echo e(__('User Logs')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-button'); ?>
    <?php echo e(Form::open(['route' => ['logindetails.index'], 'method' => 'get', 'id' => 'userlogs_filter'])); ?>

            <div class="d-flex align-items-center justify-content-end">
                <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-12">
                    <div class="btn-box">
                        <?php echo e(Form::month('month',isset($_GET['month'])?$_GET['month']: '',array('class'=>'form-control'))); ?>

                    </div>
                </div>
                <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-12">
                    <div class="btn-box">
                        <?php echo e(Form::select('user', $usersList, isset($_GET['user']) ? $_GET['user'] : '', ['class' => 'form-control select ', 'id' => 'id'])); ?>

                    </div>
                </div>
                <div class="col-auto float-end ms-2">
                    <a href="#" class="btn btn-white btn-sm ml-2"
                    onclick="document.getElementById('userlogs_filter').submit(); return false;"
                    data-bs-toggle="tooltip">
                    <span class="btn-inner--icon"><i class="ti ti-search"></i><?php echo e(__('Apply')); ?></span>
                </a>
                <a href="<?php echo e(route('logindetails.index')); ?>" class="btn btn-sm btn-danger"
                    data-bs-toggle="tooltip"
                    data-original-title="Reset">
                    <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i><?php echo e(__('Reset')); ?></span>
                </a>
                </div>
            </div>
    <?php echo e(Form::close()); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="table-responsive">
            <table class="table align-items-center" id="myTable">
                <thead>
                    <tr>
                        <th><?php echo e(__('Name')); ?></th>
                        <th><?php echo e(__('Role')); ?></th>
                        <th><?php echo e(__('IP')); ?></th>
                        <th><?php echo e(__('Last Login At')); ?></th>
                        <th><?php echo e(__('Device Type')); ?></th>
                        <th><?php echo e(__('Os Name')); ?></th>
                        <th><?php echo e(__('Action')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $logindetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logindetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="font-style">
                        <?php
                            $details = json_decode($logindetail->details);
                        ?>
                        <td>
                            <?php
                                $user = $logindetail->Getuser();
                                $name = !empty($user) ? $user->name : '';
                            ?>
                            <h4 class="text-muted text-sm mb-0"><?php echo e($name); ?></h4></td>

                        <td><span class="text-capitalize badge badge-pill badge-success"><?php echo e($logindetail->type); ?></span></td>
                        <td><?php echo e($logindetail->ip); ?></td>
                        <td><?php echo e($logindetail->date); ?></td>
                        <td><?php echo e($details->device_type); ?></td>
                        <td><?php echo e($details->os_name); ?></td>
                        <td class="Action">
                            <a href="#" class="action-item px-2"
                            data-url="<?php echo e(route('logindetails.show', [$logindetail->id])); ?>"
                            data-ajax-popup="true" data-size="md"
                            data-title="<?php echo e(__('Show')); ?>" data-toggle="tooltip"
                            data-original-title="<?php echo e(__('Show')); ?>">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="#" class="action-item text-danger px-2"
                        data-toggle="tooltip"
                        data-original-title="<?php echo e(__('Delete')); ?>"
                        data-confirm="<?php echo e(__('Are You Sure?')); ?>|<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                        data-confirm-yes="document.getElementById('delete-logindetails-<?php echo e($logindetail->id); ?>').submit();">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['logindetails.destroy', $logindetail->id], 'id' => 'delete-logindetails-' . $logindetail->id]); ?>

                    <?php echo Form::close(); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/users/login_details.blade.php ENDPATH**/ ?>