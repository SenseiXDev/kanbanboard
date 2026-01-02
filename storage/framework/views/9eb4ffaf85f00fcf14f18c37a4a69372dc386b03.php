<?php $__env->startSection('title'); ?>
    <?php echo e(__('Account settings')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('theme-script'); ?>
    <script src="<?php echo e(asset('assets/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php
$logo=\App\Models\Utility::get_file('/');
?>

<?php $__env->startPush('css'); ?>
<style>
    .ahref:hover,
    .ahref.active {
        color: #fff !important;
    }

</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-4 order-lg-2">
            <div class="card">
                <div class="list-group list-group-flush" id="tabs">
                    <div data-href="#tabs-1" class="list-group-item text-primary">
                        <div class="media">
                            <i class="fas fa-user"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1"><?php echo e(__('Basic')); ?></a>
                                <p class="mb-0 text-sm"><?php echo e(__('Details about your personal information')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div data-href="#tabs-2" class="list-group-item">
                        <div class="media">
                            <i class="fas fa-lock"></i>
                            <div class="media-body ml-3">
                                <a href="#" class="stretched-link h6 mb-1"><?php echo e(__('Security')); ?></a>
                                <p class="mb-0 text-sm"><?php echo e(__('Details about your personal information')); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php if(\Auth::user()->type == 'owner'): ?>
                        <div data-href="#tabs-3" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-credit-card"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1"><?php echo e(__('Billing')); ?></a>
                                    <p class="mb-0 text-sm"><?php echo e(__('Details about your plan & purchase')); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-8 order-lg-1">
            <div class="card bg-gradient-warning hover-shadow-lg border-0">
                <div class="card-body py-3">
                    <div class="row row-grid align-items-center">
                        <div class="col-lg-8">
                            <div class="media align-items-center">

                                <?php if($user->avatar): ?>
                                <a href="#" class="avatar avatar-lg rounded-circle mr-3">
                                    <img  src="<?php echo e($logo.$user->avatar); ?>" class="avatar avatar-lg" >
                                </a>
                                <?php else: ?>
                                <a href="#" class="avatar avatar-lg rounded-circle mr-3">
                                    <img class="avatar avatar-lg" <?php echo e($user->img_avatar); ?>>
                                </a>
                                <?php endif; ?>
                                <div class="media-body">
                                    <h5 class="text-white mb-0"><?php echo e($user->name); ?></h5>
                                    <div>
                                        <?php echo e(Form::open(['route' => ['update.profile'],'enctype'=>'multipart/form-data','id' => 'update_avatar'])); ?>

                                        <input type="file" name="avatar" id="avatar" class="custom-input-file custom-input-file-link" data-multiple-caption="{count} files selected" multiple/>
                                        <label for="avatar">
                                            <span class="text-white"><?php echo e(__('Change avatar')); ?></span>
                                        </label>
                                        <?php echo e(Form::close()); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="tabs-1" class="tabs-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class=" h6 mb-0"><?php echo e(__('Basic Setting')); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php echo e(Form::open(['route' => ['update.profile'],'id' => 'update_profile'])); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('name', __('Name'),['class' => 'form-control-label'])); ?>

                                    <?php echo e(Form::text('name', $user->name, ['class' => 'form-control','required'=>'required','placeholder' => __('Enter your first name')])); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('email', __('Email'),['class' => 'form-control-label'])); ?>

                                    <?php echo e(Form::email('email', $user->email, ['class' => 'form-control','required'=>'required'])); ?>

                                    <small class="form-text text-muted mt-2"><?php echo e(__("This is the main email address that we'll send notifications.")); ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('dob', __('Birthday'),['class' => 'form-control-label'])); ?>

                                    <?php echo e(Form::date('dob', $user->dob, ['class' => 'form-control','required' => 'required','placeholder' => __('Select your birth date')])); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label"><?php echo e(__('Gender')); ?></label>
                                    <?php echo e(Form::select('gender', ['female' => __('Female'),'male' => __('Male')],$user->gender, ['class' => 'form-control'])); ?>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('phone', __('Phone'),['class' => 'form-control-label'])); ?>

                                    <?php echo e(Form::text('phone', $user->phone, ['class' => 'form-control','required'=>'required'])); ?>

                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('facebook', __('Facebook'),['class' => 'form-control-label'])); ?>

                                    <?php echo e(Form::text('facebook', $user->facebook, ['class' => 'form-control'])); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('whatsapp', __('WhatsApp'),['class' => 'form-control-label'])); ?>

                                    <?php echo e(Form::text('whatsapp', $user->whatsapp, ['class' => 'form-control'])); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('instagram', __('Instagram'),['class' => 'form-control-label'])); ?>

                                    <?php echo e(Form::text('instagram', $user->instagram, ['class' => 'form-control'])); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('likedin', __('Linkedin'),['class' => 'form-control-label'])); ?>

                                    <?php echo e(Form::text('likedin', $user->likedin, ['class' => 'form-control'])); ?>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <?php echo e(Form::label('skills', __('Skills'),['class' => 'form-control-label'])); ?>

                                    <small class="form-text text-muted mb-2 mt-0"><?php echo e(__('Seprated By Comma')); ?></small>
                                    <?php echo e(Form::text('skills', $user->skills, ['class' => 'form-control','data-toggle' => 'tags','placeholder' => __('Type here...'),])); ?>

                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="text-right">
                            <?php echo e(Form::hidden('from','profile')); ?>

                            <button type="submit" class="btn btn-sm btn-primary rounded-pill"><?php echo e(__('Save changes')); ?></button>
                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>
                </div>
            </div>
            <div id="tabs-2" class="tabs-card d-none">
                <div class="card">
                    <div class="card-header">
                        <h5 class=" h6 mb-0"><?php echo e(__('Security Setting')); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php echo e(Form::open(['route' => ['update.profile'],'id' => 'update_profile'])); ?>

                        <div class="row">
                             <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('old_password', __('Old Password'),['class' => 'form-control-label'])); ?>

                                    <?php echo e(Form::password('old_password', ['class' => 'form-control','required'=>'required','placeholder' => __('Enter your old password')])); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('password', __('Password'),['class' => 'form-control-label'])); ?>

                                    <?php echo e(Form::password('password', ['class' => 'form-control','required'=>'required','placeholder' => __('Enter your new password')])); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('password_confirmation', __('Confirm Password'),['class' => 'form-control-label'])); ?>

                                    <?php echo e(Form::password('password_confirmation', ['class' => 'form-control','required'=>'required','placeholder' => __('Enter your confirm password')])); ?>

                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="text-right">
                            <?php echo e(Form::hidden('from','password')); ?>

                            <button type="submit" class="btn btn-sm btn-primary rounded-pill"><?php echo e(__('Save changes')); ?></button>
                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>
                </div>
            </div>
            <div id="tabs-3" class="tabs-card d-none">
                <ul class="nav nav-dark nav-tabs nav-overflow" role="tablist">
                    <?php if(\Auth::user()->type != 'admin'): ?>
                        <li class="nav-item">
                            <a href="#plans" id="plans-tab" class="ahref nav-link active" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                                <i class="fas fa-award mr-2"></i><?php echo e(__('Plans')); ?>

                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#orders" id="orders-tab" class="ahref nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                                <i class="fas fa-file-invoice mr-2"></i><?php echo e(__('Orders')); ?>

                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="tab-content pt-4">
                    <?php if(\Auth::user()->type != 'admin'): ?>
                        <div class="tab-pane fade show active" id="plans" role="tabpanel" aria-labelledby="plans-tab">
                            <div class="container">
                                <?php echo $__env->make('plans.planlist',['size'=>'6','paymentSetting'=>\App\Models\Utility::getPaymentSetting()], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="tab-pane fade show <?php echo e((\Auth::user()->type == 'admin') ? 'active' : ''); ?>" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table" width="100%">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th><?php echo e(__('Order Id')); ?></th>
                                                    <th><?php echo e(__('Name')); ?></th>
                                                    <th><?php echo e(__('Plan Name')); ?></th>
                                                    <th><?php echo e(__('Price')); ?></th>
                                                    <th><?php echo e(__('Status')); ?></th>
                                                    <th><?php echo e(__('Type')); ?></th>
                                                    <th><?php echo e(__('Date')); ?></th>
                                                    <th><?php echo e(__('Coupon')); ?></th>
                                                    <th class="text-right"><?php echo e(__('Invoice')); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if($orders->count() > 0): ?>
                                                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td><?php echo e($order->order_id); ?></td>
                                                            <td><?php echo e($order->user_name); ?></td>
                                                            <td><?php echo e($order->plan_name); ?></td>
                                                            <td><?php echo e((env('CURRENCY') ? env('CURRENCY') : '$')); ?> <?php echo e(number_format($order->price)); ?></td>
                                                            <td>
                                                                <?php if($order->payment_status == 'succeeded' || $order->payment_status == 'approved'): ?>
                                                                    <span class="badge badge-success"><?php echo e(ucfirst($order->payment_status)); ?></span>
                                                                <?php else: ?>
                                                                    <span class="badge badge-danger"><?php echo e(ucfirst($order->payment_status)); ?></span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?php echo e($order->payment_type); ?></td>
                                                            <td><?php echo e($order->created_at->format('d M Y')); ?></td>
                                                            <td><?php echo e(!empty($order->use_coupon)?$order->use_coupon->coupon_detail->name:'-'); ?></td>
                                                            <td class="text-right">
                                                                <?php if($order->receipt =='free coupon'): ?>
                                                                    <p><?php echo e(__('Used 100 % discount coupon code.')); ?></p>
                                                                <?php elseif(!empty($order->receipt)): ?>
                                                                    <a href="<?php echo e($order->receipt); ?>" target="_blank" class="text-primary"><i class="fas fa-file-invoice"></i></a>
                                                                <?php else: ?>
                                                                    <?php echo e('-'); ?>

                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <th scope="col" colspan="8"><h6 class="text-center"><?php echo e(__('No Orders Found.')); ?></h6></th>
                                                    </tr>
                                                <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        $(document).ready(function () {
            $('.list-group-item').on('click', function () {
                var href = $(this).attr('data-href');
                $('.tabs-card').addClass('d-none');
                $(href).removeClass('d-none');
                $('#tabs .list-group-item').removeClass('text-primary');
                $(this).addClass('text-primary');
            });
        });
        document.getElementById("avatar").onchange = function () {
            document.getElementById("update_avatar").submit();
        };
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/users/profile.blade.php ENDPATH**/ ?>