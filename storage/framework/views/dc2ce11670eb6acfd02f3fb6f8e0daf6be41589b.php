<?php $__env->startSection('title'); ?>
    <?php echo e(__('Project Report')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-button'); ?>
    <div class="d-flex align-items-center justify-content-end mt-2 mt-md-0 filter">
        <div class="dropdown btn btn-sm btn-white btn-icon-only rounded-circle ml-2 m-0">
            <a href="#" class="action-item text-dark line_height_auto" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-filter"></i>
            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>
    <div class="row  display-none d-flex align-items-center justify-content-center" id="show_filter">

        <div class="bg-neutral rounded-pill d-inline-block ml-2">
            <div class="input-group input-group-sm input-group-merge input-group-flush">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent"><i class="fas fa-search"></i></span>
                </div>
                <input type="text" id="project_keyword" name="name" class="form-control form-control-flush"
                    placeholder="<?php echo e(__('Search by Name or tag')); ?>">
            </div>
        </div>
        <div class="col-auto">
            <select class="form-control form-control-sm w-auto " name="all_users" id="all_users">
                <option value="" class=""><?php echo e(__('All Users')); ?></option>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-auto">
            <select class=" form-control form-control-sm w-auto " name="status" id="status">
                <option value="" class=""><?php echo e(__('All Status')); ?></option>
                <?php $__currentLoopData = $project_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statuses): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($statuses->status); ?>"><?php echo e($statuses->status); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </select>
        </div>


        <div class=" col-auto">

            <input class="form-control form-control-sm w-auto" type="date" id="start_date" name="start_date"
                value="" autocomplete="off" required="required" placeholder="<?php echo e(__('Start Date')); ?>">
        </div>


        <div class=" col-auto">

            <input class="form-control form-control-sm w-auto" type="date" id="end_date" name="end_date" value=""
                autocomplete="off" required="required" placeholder="<?php echo e(__('End Date')); ?>">
        </div>


        <button class="btn btn-white btn-sm ml-2  col-auto btn-filter apply" id="filter"><i
                class="mdi mdi-check"></i><?php echo e(__('Apply')); ?></button>
    </div>

    <div class="row">
        <div class="table-div">
            <div class="card mt-4">
                <div class="table-responsive">
                    <table class="table align-items-center  mt-2" id="selection-datatable1">
                        <thead>
                            <tr>

                                <th scope="col" class="sort"> <?php echo e(__('#')); ?></th>
                                <th scope="col" class="sort"> <?php echo e(__('Project Name')); ?></th>
                                <th scope="col" class="sort"> <?php echo e(__('Start Date')); ?></th>
                                <th scope="col" class="sort"> <?php echo e(__('Due Date')); ?></th>
                                <th scope="col" class="sort"> <?php echo e(__('Project Member')); ?></th>
                                <th scope="col" class="sort"> <?php echo e(__('Progress')); ?></th>
                                <th scope="col" class="sort"><?php echo e(__('Project Status')); ?></th>
                                <th scope="col" class="sort"><?php echo e(__('Action')); ?></th>
                            </tr>
                        </thead>
                        <tbody class="list">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('css'); ?>
    <style>
        .display-none {
            display: none !important;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script type="text/javascript">
        $(".filter").click(function() {
            $("#show_filter").toggleClass('display-none');
        });

        $(document).on('keyup', '#project_keyword', function() {
            ajaxFilterProjectView(sort, $(this).val(), status);
        });
    </script>


    <script>
        const table = $("#selection-datatable1");
        $(document).ready(function() {

            $(document).on("click", ".btn-filter", function() {
                getData();
            });

            function getData() {

                // table.clear().draw();
                $("#selection-datatable1 tbody tr").html(
                    '<td colspan="11" class="text-center"> <?php echo e(__('Loading ...')); ?></td>');

                var data = {
                    status: $("#status").val(),
                    start_date: $("#start_date").val(),
                    end_date: $("#end_date").val(),
                    all_users: $("#all_users").val(),
                    name: $("#project_keyword").val(),
                };
                $.ajax({
                    url: '<?php echo e(route('projects.ajax')); ?>',
                    type: 'POST',
                    data: data,
                    success: function(data) {
                        // $('#formdata')[0].reset();
                        $("tbody").html("");
                        $.each(data.data, function(index, value) {

                            // console.log(value.members);
                            var row = $("<tr><td>" +
                                value.id + "</td><td>" +
                                value.name + "</td><td>" +
                                value.start_date + "</td><td>" +
                                value.end_date + "</td><td>" +
                                value.members + "</td><td>" +
                                value.Progress + "</td><td>" +
                                value.status + "</td><td>" +
                                value.action + "</td></tr>");

                            $("tbody").append(row);
                        });

                        // loadConfirm();
                    },
                    error: function(data) {
                        show_toastr('Info', data.error, 'error')
                    }
                })
            }

            getData();

        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/project_report/index.blade.php ENDPATH**/ ?>