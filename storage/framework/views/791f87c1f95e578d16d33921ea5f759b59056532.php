<?php $__env->startSection('title'); ?>
    <?php echo e(__('Notification Template')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-button'); ?>
    <div class="row">
        <div class="text-end mb-3">
            <div class="text-end">
                <div class="d-flex justify-content-end drp-languages">
                    <ul class="nav justify-content-center ">
                        <li class="nav-item dropdown ">
                            <a class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <span class="h6 text-sm mb-0 text-white"><i class="fas fa-globe-asia"></i>
                                    <?php echo e(ucfirst($languages[$curr_noti_tempLang->lang])); ?></span>
                                  
                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code =>$lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('notification-templates.index', [$notification_template->id, $code])); ?>"
                                        class="dropdown-item <?php echo e($curr_noti_tempLang->lang == $lang ? 'text-primary' : ''); ?>"><?php echo e(ucfirst($lang)); ?></a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </li>
                    </ul>
                    <ul class="nav justify-content-center justify-content-md-end  ml-5">
                        <li class="nav-item dropdown ">
                            <a class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <span class="h6 text-sm mb-0 text-white"><?php echo e(__('Template: ')); ?>

                                    <?php echo e($notification_template->name); ?></span>
                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                <?php $__currentLoopData = $notification_templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification_template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('notification-templates.index', [$notification_template->id, Request::segment(3) ? Request::segment(3) : \Auth::user()->lang])); ?>"
                                        class="dropdown-item
                                   <?php echo e($notification_template->name == $notification_template->name ? 'text-primary' : ''); ?>"><?php echo e($notification_template->name); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <h5 class="font-weight-bold "><?php echo e(__('Placeholders')); ?></h5>
                                <div class="text-right">
                                    <a href="#" class="btn btn-sm btn-primary rounded-pill m-3"
                                        data-url="<?php echo e(route('generate', ['notification_template'])); ?>" data-ajax-popup-over="true"
                                        data-bs-placement="top" data-size="md" title="<?php echo e(__('Generate')); ?>"
                                        data-title="<?php echo e(__('Generate content with AI')); ?>">
                                        <span class="btn-inner--text text-white"> <span><i class="fas fa-robot"></i></span>
                                            <?php echo e(__('Generate with AI')); ?></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-sm-12">
                            <div class="card">
                                <div class="card-header card-body">
                                    <h6 class="font-weight-bold mb-4"><?php echo e(__('Variables')); ?></h6>
                                    <div class="row text-md">
                                        <?php
                                            $variables = json_decode($curr_noti_tempLang->variables);
                                        ?>
                                        <?php if(!empty($variables) > 0): ?>
                                            <?php $__currentLoopData = $variables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $var): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-6 pb-1">
                                                    <p class="mb-1"><?php echo e(__($key)); ?> : <span
                                                            class="pull-right text-primary"><?php echo e('{' . $var . '}'); ?></span>
                                                    </p>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo e(Form::model($curr_noti_tempLang, ['route' => ['notification-templates.update', $curr_noti_tempLang->parent_id], 'method' => 'PUT'])); ?>

                        <div class="col-12">
                            <div class="form-group">
                                <?php echo e(Form::label('content', __('Notification Message'), ['class' => 'form-label text-dark '])); ?>

                                <?php echo e(Form::textarea('content', $curr_noti_tempLang->content, ['class' => 'form-control', 'required' => 'required', 'rows' => '04', 'placeholder' => 'EX. Hello, {app_name}'])); ?>

                                <small><?php echo e(__('A variable is to be used in such a way.')); ?> <span
                                        class="text-primary"><?php echo e(__('Ex. TaskGo, {app_name}')); ?></span></small>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12 text-right">
                            <?php echo e(Form::hidden('lang', null)); ?>

                            <?php echo e(Form::submit(__('Save changes'), ['class' => 'btn btn-sm btn-print-invoice btn-primary rounded-pill'])); ?>

                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>
                </div>
            </div>
        </div>

    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/notification_templates/index.blade.php ENDPATH**/ ?>