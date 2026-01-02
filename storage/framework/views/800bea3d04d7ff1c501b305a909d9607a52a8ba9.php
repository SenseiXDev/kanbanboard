<form method="post" action="<?php echo e(route('projects.user.permission.store',[$project->id,$user->id])); ?>">
    <?php echo csrf_field(); ?>
    <table class="table align-items-center">
        <thead>
        <tr>
            <th><?php echo e(__('Module')); ?></th>
            <th><?php echo e(__('Permissions')); ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?php echo e(__('Milestone')); ?></td>
            <td>
                <div class="row cust-checkbox-row">
                    <div class="col-2 custom-control custom-checkbox">
                        <input class="custom-control-input" id="permission3" <?php if(in_array('create milestone',$permissions)): ?> checked="checked" <?php endif; ?> name="permissions[]" type="checkbox" value="create milestone">
                        <label for="permission3" class="custom-control-label pt-1"><?php echo e(__('Create')); ?></label><br>
                    </div>
                    <div class="col-2 custom-control custom-checkbox">
                        <input class="custom-control-input" id="permission4" <?php if(in_array('edit milestone',$permissions)): ?> checked="checked" <?php endif; ?> name="permissions[]" type="checkbox" value="edit milestone">
                        <label for="permission4" class="custom-control-label pt-1"><?php echo e(__('Edit')); ?></label><br>
                    </div>
                    <div class="col-2 custom-control custom-checkbox">
                        <input class="custom-control-input" id="permission5" <?php if(in_array('delete milestone',$permissions)): ?> checked="checked" <?php endif; ?> name="permissions[]" type="checkbox" value="delete milestone">
                        <label for="permission5" class="custom-control-label pt-1"><?php echo e(__('Delete')); ?></label><br>
                    </div>
                    <div class="col"></div>
                </div>
            </td>
        </tr>
        <tr>
            <td><?php echo e(__('Task')); ?></td>
            <td>
                <div class="row cust-checkbox-row">
                    <div class="col-2 custom-control custom-checkbox">
                        <input class="custom-control-input" id="permission7" <?php if(in_array('create task',$permissions)): ?> checked="checked" <?php endif; ?> name="permissions[]" type="checkbox" value="create task">
                        <label for="permission7" class="custom-control-label pt-1"><?php echo e(__('Create')); ?></label><br>
                    </div>
                    <div class="col-2 custom-control custom-checkbox">
                        <input class="custom-control-input" id="permission8" <?php if(in_array('edit task',$permissions)): ?> checked="checked" <?php endif; ?> name="permissions[]" type="checkbox" value="edit task">
                        <label for="permission8" class="custom-control-label pt-1"><?php echo e(__('Edit')); ?></label><br>
                    </div>
                    <div class="col-2 custom-control custom-checkbox">
                        <input class="custom-control-input" id="permission9" <?php if(in_array('delete task',$permissions)): ?> checked="checked" <?php endif; ?> name="permissions[]" type="checkbox" value="delete task">
                        <label for="permission9" class="custom-control-label pt-1"><?php echo e(__('Delete')); ?></label><br>
                    </div>
                    <div class="col-2 custom-control custom-checkbox">
                        <input class="custom-control-input" id="permission6" <?php if(in_array('show task',$permissions)): ?> checked="checked" <?php endif; ?> name="permissions[]" type="checkbox" value="show task">
                        <label for="permission6" class="custom-control-label pt-1"><?php echo e(__('Show')); ?></label><br>
                    </div>
                    <div class="col-2 custom-control custom-checkbox">
                        <input class="custom-control-input" id="permission10" <?php if(in_array('move task',$permissions)): ?> checked="checked" <?php endif; ?> name="permissions[]" type="checkbox" value="move task">
                        <label for="permission10" class="custom-control-label pt-1"><?php echo e(__('Move')); ?></label><br>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td><?php echo e(__('Timesheet')); ?></td>
            <td>
                <div class="row cust-checkbox-row">
                    <div class="col-2 custom-control custom-checkbox">
                        <input class="custom-control-input" id="permission16" <?php if(in_array('create timesheet',$permissions)): ?> checked="checked" <?php endif; ?> name="permissions[]" type="checkbox" value="create timesheet">
                        <label for="permission16" class="custom-control-label pt-1"><?php echo e(__('Create')); ?></label><br>
                    </div>
                    <div class="col-2 custom-control custom-checkbox">
                        <input class="custom-control-input" id="permission17" <?php if(in_array('show as admin timesheet',$permissions)): ?> checked="checked" <?php endif; ?> name="permissions[]" type="checkbox" value="show as admin timesheet">
                        <label for="permission17" class="custom-control-label pt-1"><?php echo e(__('Show as Admin')); ?></label><br>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td><?php echo e(__('Expense')); ?></td>
            <td>
                <div class="row cust-checkbox-row">
                    <div class="col-2 custom-control custom-checkbox">
                        <input class="custom-control-input" id="permission11" <?php if(in_array('create expense',$permissions)): ?> checked="checked" <?php endif; ?> name="permissions[]" type="checkbox" value="create expense">
                        <label for="permission11" class="custom-control-label pt-1"><?php echo e(__('Create')); ?></label><br>
                    </div>
                    <div class="col-2 custom-control custom-checkbox">
                        <input class="custom-control-input" id="permission12" <?php if(in_array('show expense',$permissions)): ?> checked="checked" <?php endif; ?> name="permissions[]" type="checkbox" value="show expense">
                        <label for="permission12" class="custom-control-label pt-1"><?php echo e(__('Show')); ?></label><br>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td><?php echo e(__('Activity')); ?></td>
            <td>
                <div class="row cust-checkbox-row">
                    <div class="col-2 custom-control custom-checkbox">
                        <input class="custom-control-input" id="permission1" <?php if(in_array('show activity',$permissions)): ?> checked="checked" <?php endif; ?> name="permissions[]" type="checkbox" value="show activity">
                        <label for="permission1" class="custom-control-label pt-1"><?php echo e(__('Show')); ?></label><br>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td><?php echo e(__('Setting')); ?></td>
            <td>
                <div class="row cust-checkbox-row">
                    <div class="col-2 custom-control custom-checkbox">
                        <input class="custom-control-input" id="permission22" <?php if(in_array('project setting',$permissions)): ?> checked="checked" <?php endif; ?> name="permissions[]" type="checkbox" value="project setting">
                        <label for="permission22" class="custom-control-label pt-1"><?php echo e(__('Project Setting')); ?></label><br>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="form-group mt-3 text-right">
        <button class="btn btn-sm btn-primary rounded-pill" type="submit"><?php echo e(__('Update Permission')); ?></button>
    </div>
</form>
<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/projects/user_permission.blade.php ENDPATH**/ ?>