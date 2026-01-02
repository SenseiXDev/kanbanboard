<?php echo e(Form::model($milestone, array('route' => array('project.milestone.update', $milestone->id), 'method' => 'POST'))); ?>

<div class="row">
    <?php if(Utility::plancheck()['enable_chatgpt'] == 'on'): ?>
        <div class="col-12 col-md-12">
            <div class="text-right">
                <a href="#" class="btn btn-sm btn-primary rounded-pill ml-2" data-url="<?php echo e(route('generate',['project_milestone'])); ?>" data-ajax-popup-over="true" data-bs-placement="top" data-size="md" title="<?php echo e(__('Generate')); ?>" data-title="<?php echo e(__('Generate contact with AI')); ?>">
                    <span class="btn-inner--icon"> <i class="fas fa-robot"></i></span>
                    <span class="btn-inner--text text-white"><?php echo e(__('Generate with AI')); ?></span>
                </a>
            </div>
        </div>
    <?php endif; ?>
    <div class="form-group col-md-6">
        <?php echo e(Form::label('title', __('Title'),['class' => 'form-control-label'])); ?>

        <?php echo e(Form::text('title', null, array('class' => 'form-control','required'=>'required'))); ?>

        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="invalid-title" role="alert">
            <strong class="text-danger"><?php echo e($message); ?></strong>
        </span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="form-group  col-md-6">
        <?php echo e(Form::label('status', __('Status'),['class' => 'form-control-label'])); ?>

        <?php echo Form::select('status',\App\Models\Project::$status, null,array('class' => 'form-control selectric','required'=>'required')); ?>

        <?php $__errorArgs = ['client'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="invalid-client" role="alert">
            <strong class="text-danger"><?php echo e($message); ?></strong>
        </span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>
     <div class="row">
        <div class="col-md-6">
           <div class="form-group ">
               <label class="form-control-label"><?php echo e(__('Start Date')); ?></label>
            <div class="input-group date ">
                <input class="form-control" type="date" id="start_date" name="start_date" value="<?php echo e($milestone->start_date); ?>" autocomplete="off">

            </div>
            </div>
        </div>
        <div class="col-md-6">
              <div class="form-group">
                   <label class="form-control-label"><?php echo e(__('End Date')); ?></label>
                <div class="input-group date ">
               <input class="form-control" type="date" id="end_date" name="end_date" value="<?php echo e($milestone->end_date); ?>" autocomplete="off" >

                </div>
            </div>
        </div>
    </div>
<div class="row">
    <div class="form-group  col-md-12">
        <?php echo e(Form::label('description', __('Description'),['class' => 'form-control-label'])); ?>

        <?php echo Form::textarea('description', null, ['class'=>'form-control','rows'=>'3']); ?>

        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="invalid-description" role="alert">
            <strong class="text-danger"><?php echo e($message); ?></strong>
        </span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

       <div class="col-md-12">
            <div class="form-group">
                  <label for="task-summary" class="form-control-label"><?php echo e(__('Progress')); ?></label>
                <input type="range" class="slider w-100 mb-0 " name="progress" id="myRange" value="<?php echo e(($milestone->progress)?$milestone->progress:'0'); ?>" min="0" max="100" oninput="ageOutputId.value = myRange.value">
                <output name="ageOutputName" id="ageOutputId"><?php echo e(($milestone->progress)?$milestone->progress:"0"); ?></output>
                %
            </div>
        </div>
    <div class="col-md-12 text-right">
        <?php echo e(Form::button(__('Update'), ['type' => 'submit','class' => 'btn btn-sm btn-primary rounded-pill'])); ?>

        <button type="button" class="btn btn-sm btn-secondary rounded-pill" data-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
    </div>
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/projects/milestoneEdit.blade.php ENDPATH**/ ?>