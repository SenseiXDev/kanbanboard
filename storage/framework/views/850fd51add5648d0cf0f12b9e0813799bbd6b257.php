<?php echo e(Form::model($project, array('route' => array('projects.copy.store', $project->id), 'method' => 'POST'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <div class="form-group m-2">
                <div class="form-check">
                    <?php echo e(Form::checkbox('project','all','', ['class' => 'form-check-input ','id'=>'all'])); ?>

                    <?php echo e(Form::label('all', __('All'),['class'=>'form-check-label'])); ?>

                </div>
            </div>
            
            <div class="form-group m-2">
                <div class="form-check">
                    <?php echo e(Form::checkbox('task[]','task','', ['class' => 'form-check-input checkbox','id'=>'task'])); ?>

                    <?php echo e(Form::label('task', __('Task'),['class'=>'form-check-label'])); ?>

                </div>
            </div>
            <div class="row mx-4">
                <div class="col-4 form-group">
                    <div class="form-check">
                        <?php echo e(Form::checkbox('task[]','checklist','', ['class' => 'form-check-input checkbox task','id'=>'sub_task'])); ?>

                        <?php echo e(Form::label('checklist', __('checklist'),['class'=>'form-check-label'])); ?>

                    </div>
                </div>
                <div class="col-4 form-group">
                    <div class="form-check">
                        <?php echo e(Form::checkbox('task[]','task_comment','', ['class' => 'form-check-input checkbox task','id'=>'task_comment'])); ?>

                        <?php echo e(Form::label('task_comment', __('Comment'),['class'=>'form-check-label'])); ?>

                    </div>
                </div>
                <div class="col-4 form-group">
                    <div class="form-check">
                        <?php echo e(Form::checkbox('task[]','task_files','', ['class' => 'form-check-input checkbox task','id'=>'task_files'])); ?>

                        <?php echo e(Form::label('task_files', __('Attachments'),['class'=>'form-check-label'])); ?>

                    </div>
                </div>
            </div>

            <div class="form-group m-2">
                <div class="form-check">
                    <?php echo e(Form::checkbox('members[]','members','', ['class' => 'form-check-input checkbox','id'=>"user"])); ?>

                    <?php echo e(Form::label('members', __('Members'),['class'=>'form-check-label'])); ?>

                </div>
            </div>
            <div class="form-group m-2">
                <div class="form-check">
                    <?php echo e(Form::checkbox('milestone[]','milestone','', ['class' => 'form-check-input checkbox','id'=>"milestone"])); ?>

                    <?php echo e(Form::label('milestone', __('Milestone'),['class'=>'form-check-label'])); ?>

                </div>
            </div>
            <div class="form-group m-2">
                <div class="form-check">
                    <?php echo e(Form::checkbox('project_file[]','project_file','', ['class' => 'form-check-input checkbox','id'=>"project_file"])); ?>

                    <?php echo e(Form::label('project_file', __('Project File'),['class'=>'form-check-label'])); ?>

                </div>
            </div>
            <div class="form-group m-2">
                <div class="form-check">
                    <?php echo e(Form::checkbox('activity[]','activity','', ['class' => 'form-check-input checkbox','id'=>"activity"])); ?>

                    <?php echo e(Form::label('activity', __('Activity'),['class'=>'form-check-label'])); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="text-right">
    <?php echo e(Form::button(__('Copy'), ['type' => 'submit', 'class' => 'btn btn-sm btn-primary rounded-pill'])); ?>

    <button type="button" class="btn btn-sm btn-secondary rounded-pill"
        data-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
</div>
<?php echo e(Form::close()); ?>



<script>
    $(document).ready(function(){
        $('#all').on('click',function(){
            if(this.checked)
            {
                $('.checkbox').each(function(){
                    this.checked = true;
                });
            }
            else
            {
                $('.checkbox').each(function(){
                    this.checked = false;
                });
            }
        });
    });
</script>

<script>
    $(document).ready(function(){
        $("#sub_task").click(function(){
            $("#task").prop("checked", true);
        });
        $("#task_comment").click(function(){
            $("#task").prop("checked", true);
        });
        $("#task_files").click(function(){
            $("#task").prop("checked", true);
        });
        $("#bug_comment").click(function(){
            $("#bug").prop("checked", true);
        });
        $("#bug_files").click(function(){
            $("#bug").prop("checked", true);
        });

        $('#task').on('click',function(){
            $('.task').each(function(){
                this.checked = false;
            });
        });
        $('#bug').on('click',function(){
            $('.bug').each(function(){
                this.checked = false;
            });
        });
    });
</script>
<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/projects/duplicate.blade.php ENDPATH**/ ?>