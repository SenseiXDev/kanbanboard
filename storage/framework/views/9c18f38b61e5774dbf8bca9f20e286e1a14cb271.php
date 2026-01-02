<?php echo e(Form::open(['url' => 'projects', 'method' => 'post','enctype' => 'multipart/form-data'])); ?>

    <div class="row">
        <div class="col-12 col-md-12">
            <?php if(Utility::plancheck()['enable_chatgpt'] == 'on'): ?>
                <div class="text-right">
                    <a href="#" class="btn btn-sm btn-primary rounded-pill ml-2" data-url="<?php echo e(route('generate',['project'])); ?>" data-ajax-popup-over="true" data-bs-placement="top" data-size="md" title="<?php echo e(__('Generate')); ?>" data-title="<?php echo e(__('Generate content with AI')); ?>">
                        <span class="btn-inner--text text-white"> <span><i class="fas fa-robot"></i></span> <?php echo e(__('Generate with AI')); ?></span>
                    </a>

                </div>
            <?php endif; ?>
        </div>

        <div class="col-12 col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('title', __('Project name'), ['class' => 'form-control-label'])); ?>

                <?php echo e(Form::text('title', null, ['class' => 'form-control', 'required' => 'required'])); ?>

            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('start_date', __('Start date'), ['class' => 'form-control-label'])); ?>

                <?php echo e(Form::date('start_date', date('Y-m-d'), ['class' => 'form-control'])); ?>

            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('end_date', __('End date'), ['class' => 'form-control-label'])); ?>

                <?php echo e(Form::date('end_date', date('Y-m-d'), ['class' => 'form-control'])); ?>

            </div>
        </div>
        <div class="col-12 col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('image', __('Project Image'), ['class' => 'form-control-label'])); ?>

                <input type="file" name="image" id="image" class="custom-input-file" accept="image/*"
                    data-filename="edit-product-image"
                    onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])" />

                <label for="image">
                    <i class="fa fa-upload"></i>
                    <span><?php echo e(__('Choose a file…')); ?></span>
                </label>

                <img src="" id="blah" class="rounded-circle p-4" style="width: 250px !important;" />

            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="form-group">
                <?php echo e(Form::label('estimated_hrs', __('Estimated Hours'), ['class' => 'form-control-label'])); ?>

                <?php echo e(Form::number('estimated_hrs', null, ['class' => 'form-control', 'min' => '0', 'maxlength' => '8'])); ?>

            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="form-group">
                <?php echo e(Form::label('budget', __('Budget'), ['class' => 'form-control-label'])); ?>

                <?php echo e(Form::number('budget', null, ['class' => 'form-control', 'step' => '0.01'])); ?>

            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="form-group">
                

                <?php echo e(Form::label('currency',__('currency'),array('class'=>'form-control-label'))); ?>

                <select name="currency" class="form-select form-control" id="currency">
                    <?php $__currentLoopData = App\Models\Utility::currency(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>"><?php echo e($value); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('descriptions', __('Description'), ['class' => 'form-control-label'])); ?>

                <small
                    class="form-text text-muted mb-2 mt-0"><?php echo e(__('This textarea will autosize while you type')); ?></small>
                <?php echo e(Form::textarea('descriptions', null, ['class' => 'form-control', 'rows' => '3', 'data-toggle' => 'autosize'])); ?>

            </div>
        </div>
        <div class="col-12 col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('tags', __('Tags'), ['class' => 'form-control-label'])); ?>

                <small class="form-text text-muted mb-2 mt-0"><?php echo e(__('Seprated By Comma')); ?></small>
                <?php echo e(Form::text('tags', null, ['class' => 'form-control', 'data-toggle' => 'tags', 'placeholder' => __('Type here..')])); ?>

            </div>
        </div>
    </div>

<div class="text-right">
    <?php echo e(Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-sm btn-primary rounded-pill'])); ?>

    <button type="button" class="btn btn-sm btn-secondary rounded-pill"
        data-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/projects/create.blade.php ENDPATH**/ ?>