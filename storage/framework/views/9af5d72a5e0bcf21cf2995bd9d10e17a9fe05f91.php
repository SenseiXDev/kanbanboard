<?php echo e(Form::open(array('url' => 'contract'))); ?>

<div class="row">
    <?php if(Utility::plancheck()['enable_chatgpt'] == 'on'): ?>
        <div class="col-12 col-md-12">
            <div class="text-right">
                <a href="#" class="btn btn-sm btn-primary rounded-pill " data-url="<?php echo e(route('generate',['contract_type'])); ?>" data-ajax-popup-over="true" data-bs-placement="top" data-size="md" title="<?php echo e(__('Generate')); ?>" data-title="<?php echo e(__('Generate content with AI')); ?>">
                    <span class="btn-inner--icon"> <i class="fas fa-robot"></i></span>
                    <span class="btn-inner--text text-white"><?php echo e(__('Generate with AI')); ?></span>
                </a>
            </div>
        </div>
    <?php endif; ?>
    <div class="col-12">
        <div class="form-group">
            <?php echo e(Form::label('name', __('Name'),['class' => 'form-control-label'])); ?>

            <?php echo e(Form::text('name', null, ['class' => 'form-control','required'=>'required'])); ?>

        </div>
    </div>
</div>

<div class="text-right pt-3">
    <?php echo e(Form::button(__('Create'), ['type' => 'submit','class' => 'btn btn-sm btn-primary rounded-pill'])); ?>

    <button type="button" class="btn btn-sm btn-secondary rounded-pill" data-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/contracttype/create.blade.php ENDPATH**/ ?>