<?php
    $logo_path = \App\Models\Utility::get_file('/');
    $setting = \App\Models\Utility::settings();
?>

<?php echo e(Form::open(['route' => ['projects.tasks.store', $project_id, $stage_id], 'id' => 'create_task'])); ?>

<div class="row">
    <?php if(Utility::plancheck()['enable_chatgpt'] == 'on'): ?>
        <div class="col-12 col-md-12">
            <div class="text-right">
                <a href="#" class="btn btn-sm btn-primary rounded-pill ml-2" data-url="<?php echo e(route('generate',['project_task'])); ?>" data-ajax-popup-over="true" data-bs-placement="top" data-size="md" title="<?php echo e(__('Generate')); ?>" data-title="<?php echo e(__('Generate contact with AI')); ?>">
                    <span class="btn-inner--icon"> <i class="fas fa-robot"></i></span>
                    <span class="btn-inner--text text-white"><?php echo e(__('Generate with AI')); ?></span>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <div class="col-8">
        <div class="form-group">
            <?php echo e(Form::label('name', __('Task name'), ['class' => 'form-control-label'])); ?>

            <?php echo e(Form::text('title', null, ['class' => 'form-control', 'required' => 'required'])); ?>

        </div>
    </div>
    <div class="col-4">
        <div class="form-group">
            <?php echo e(Form::label('milestone_id', __('Milestone'), ['class' => 'form-control-label'])); ?>

            <select class="form-control" name="milestone_id" id="milestone_id">
                <option value="0"></option>
                <?php if($project): ?>
                    <?php $__currentLoopData = $project->milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($m_val->id); ?>"><?php echo e($m_val->title); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </select>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <?php echo e(Form::label('description', __('Description'), ['class' => 'form-control-label'])); ?>

            <small class="form-text text-muted mb-2 mt-0"><?php echo e(__('This textarea will autosize while you type')); ?></small>
            <?php echo e(Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3', 'data-toggle' => 'autosize'])); ?>

        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <?php echo e(Form::label('estimated_hrs', __('Estimated Hours'), ['class' => 'form-control-label'])); ?>

            <small
                class="form-text text-muted mb-2 mt-0"><?php echo e(__('Total hrs of project ') . $hrs['total'] . __(' & allocated total ') . $hrs['allocated'] . __(' hrs in other tasks')); ?></small>
            <?php echo e(Form::number('estimated_hrs', null, ['class' => 'form-control', 'required' => 'required', 'min' => '0', 'maxlength' => '8'])); ?>

        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <?php echo e(Form::label('priority', __('Priority'), ['class' => 'form-control-label'])); ?>

            <small class="form-text text-muted mb-2 mt-0"><?php echo e(__('Set Priority of your task')); ?></small>
            <select class="form-control" name="priority" id="priority" required>
                <?php $__currentLoopData = \App\Models\ProjectTask::$priority; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>"><?php echo e(__($val)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <?php echo e(Form::label('start_date', __('Start Date'), ['class' => 'form-control-label'])); ?>

            <?php echo e(Form::date('start_date', date('Y-m-d'), ['class' => 'form-control'])); ?>

        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <?php echo e(Form::label('end_date', __('End Date'), ['class' => 'form-control-label'])); ?>

            <?php echo e(Form::date('end_date', date('Y-m-d'), ['class' => 'form-control'])); ?>

        </div>
    </div>
</div>

<div class="form-group">
    <label class="form-control-label"><?php echo e(__('Task members')); ?></label>
    <small class="form-text text-muted mb-2 mt-0"><?php echo e(__('Below users are assigned in your project.')); ?></small>
</div>
<div class="list-group list-group-flush mb-4">
    <div class="row">
        <?php if($project): ?>
            <?php $__currentLoopData = $project->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-6">
                    <div class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col-auto ml-3">
                                <a href="#" class="avatar avatar-sm rounded-circle">
                                     
                                    <?php if($user->avatar): ?>
                                        <img src="<?php echo e($logo_path . $user->avatar); ?>" title="<?php echo e($user->name); ?>">
                                    <?php else: ?>
                                        <img <?php echo e($user->img_avatar); ?> title="<?php echo e($user->name); ?>">
                                    <?php endif; ?>
                                </a>
                            </div>
                            <div class="col ml-n2">
                                <p class="d-block h6 text-sm mb-0"><?php echo e($user->name); ?></p>
                                <p class="card-text text-sm text-muted mb-0"><?php echo e($user->email); ?></p>
                            </div>
                            <div class="col-auto text-right add_usr" data-id="<?php echo e($user->id); ?>">
                                <button type="button"
                                    class="btn btn-xs btn-animated btn-primary rounded-pill btn-animated-y mr-3">
                                    <span class="btn-inner--visible">
                                        <i class="fas fa-plus" id="usr_icon_<?php echo e($user->id); ?>"></i>
                                    </span>
                                    <span class="btn-inner--hidden"
                                        id="usr_txt_<?php echo e($user->id); ?>"><?php echo e(__('Add')); ?></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

        <?php if(isset($settings['is_enabled']) && $settings['is_enabled'] == 'on'): ?>
            <div class="form-group col-md-6">
                <label><?php echo e(__('Synchroniz in Google Calendar ?')); ?></label>
                <div class="form-group">
                    <div class="switch__container custom-control custom-switch float-left">
                        <input id="is_enabled" class=" custom-control-input" value="google_calender" name="synchronize_type"
                            type="checkbox">
                        <label for="is_enabled" class="custom-control-label"></label>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php echo e(Form::hidden('assign_to', null)); ?>

</div>

<div class="text-right">
    <?php echo e(Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-sm btn-primary rounded-pill'])); ?>

</div>
<?php echo e(Form::close()); ?>

<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/tasks/create.blade.php ENDPATH**/ ?>