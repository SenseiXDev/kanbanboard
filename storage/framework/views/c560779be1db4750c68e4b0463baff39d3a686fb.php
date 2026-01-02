<form class="pl-3 pr-3" method="post" action="<?php echo e(route('plans.update',$plan->id)); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('put'); ?>


    <div class="row">
        <div class="col-12 col-md-12">
            <div class="text-right">
                <a href="#" class="btn btn-sm btn-primary rounded-pill ml-2" data-url="<?php echo e(route('generate',['plans'])); ?>" data-ajax-popup-over="true" data-bs-placement="top" data-size="md" title="<?php echo e(__('Generate')); ?>" data-title="<?php echo e(__('Generate content with AI')); ?>">
                    <span class="btn-inner--text text-white"> <span><i class="fas fa-robot"></i></span> <?php echo e(__('Generate with AI')); ?></span>
                </a>
            </div>
        </div>
        <div class="<?php echo e(($plan->id == 1) ? 'col-12' : 'col-xs-12 col-sm-12 col-md-6 col-lg-6'); ?>">
            <div class="form-group">
                <label class="form-control-label" for="name"><?php echo e(__('Name')); ?></label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo e($plan->name); ?>" required/>
            </div>
        </div>
        <?php if($plan->id != 1): ?>
            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                <div class="form-group">
                    <div class="custom-control custom-switch mt-5 ml-5">
                        <input type="checkbox" class="custom-control-input" name="status" id="status" value="1" <?php echo e(($plan->status == 1) ? 'checked' : ''); ?>>
                        <label class="custom-control-label form-control-label" for="status"><?php echo e(__('Status')); ?></label>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <div class="form-group">
                <div class="custom-control custom-switch mt-5 ml-5">
                    <input type="checkbox" class="custom-control-input" name="enable_chatgpt" id="enable_chatgpt" value="1" <?php echo e(($plan->enable_chatgpt == 'on') ? 'checked' : ''); ?>>
                    <label class="custom-control-label form-control-label" for="enable_chatgpt"><?php echo e(__('Enable Chatgpt')); ?></label>
                </div>
            </div>
        </div>
    </div>

    <?php if($plan->id != 1): ?>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="form-group">
                    <label class="form-control-label" for="monthly_price"><?php echo e(__('Monthly Price')); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><?php echo e((env('CURRENCY') ? env('CURRENCY') : '$')); ?></span>
                        </div>
                        <input type="number" min="0" class="form-control" id="monthly_price" name="monthly_price" value="<?php echo e($plan->monthly_price); ?>" required/>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="form-group">
                    <label class="form-control-label" for="annual_price"><?php echo e(__('Annual Price')); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><?php echo e((env('CURRENCY') ? env('CURRENCY') : '$')); ?></span>
                        </div>
                        <input type="number" min="0" class="form-control" id="annual_price" name="annual_price" value="<?php echo e($plan->annual_price); ?>" required/>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <?php if($plan->id != 1): ?>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="form-group">
                    <label class="form-control-label" for="trial_days"><?php echo e(__('Trial Days')); ?></label>
                    <input type="number" class="form-control" id="trial_days" name="trial_days" value="<?php echo e($plan->trial_days); ?>" required/>
                    <span><small><?php echo e(__('Note: "-1" for Unlimited')); ?></small></span>
                </div>
            </div>
        <?php endif; ?>
        <div class="<?php echo e(($plan->id == 1) ? 'col-6' : 'col-xs-12 col-sm-12 col-md-6 col-lg-6'); ?>">
            <div class="form-group">
                <label class="form-control-label" for="max_users"><?php echo e(__('Maximum Users')); ?></label>
                <input type="number" class="form-control" id="max_users" name="max_users" value="<?php echo e($plan->max_users); ?>" required/>
                <span><small><?php echo e(__('Note: "-1" for Unlimited')); ?></small></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="<?php echo e(($plan->id == 1) ? 'col-6' : 'col-xs-12 col-sm-12 col-md-6 col-lg-6'); ?>">
            <div class="form-group">
                <label class="form-control-label" for="max_projects"><?php echo e(__('Maximum Projects')); ?></label>
                <input type="number" class="form-control" id="max_projects" name="max_projects" value="<?php echo e($plan->max_projects); ?>" required/>
                <span><small><?php echo e(__('Note: "-1" for Unlimited')); ?></small></span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label class="form-control-label" for="storage_limit"><?php echo e(__('Storage Limit')); ?></label>
                <div class="input-group">
                    <input type="number" min="-100" class="form-control" id="storage_limit" name="storage_limit"  value="<?php echo e($plan->storage_limit); ?>" required/>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?php echo e(__('MB')); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
                <label class="form-control-label" for="description"><?php echo e(__('Description')); ?></label>
                <textarea class="form-control" data-toggle="autosize" rows="3" id="description" name="description"><?php echo e($plan->description); ?></textarea>
            </div>
        </div>
    </div>
    <div class="text-right">
        <button class="btn btn-sm btn-primary rounded-pill" type="submit"><?php echo e(__('Update Plan')); ?></button>
    </div>
</form>
<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/plans/edit.blade.php ENDPATH**/ ?>