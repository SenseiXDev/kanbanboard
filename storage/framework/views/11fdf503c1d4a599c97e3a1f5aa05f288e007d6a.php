<?php $__env->startSection('title'); ?>
    <?php echo e(__('Tasks')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-button'); ?>
    <?php if($view == 'grid'): ?>
        <a href="<?php echo e(route('taskBoard.view', 'list')); ?>" class="btn btn-sm bg-white btn-icon rounded-pill ml-2">
            <span class="btn-inner--text text-dark"><?php echo e(__('List View')); ?></span>
        </a>
    <?php else: ?>
        <a href="<?php echo e(route('taskBoard.view', 'grid')); ?>" class="btn btn-sm bg-white btn-icon mb-2 rounded-pill ml-2">
            <span class="btn-inner--text text-dark"><?php echo e(__('Card View')); ?></span>
        </a>
    <?php endif; ?>
    <div class="bg-neutral rounded-pill d-inline-block ml-2 mb-2">
        <div class="input-group input-group-sm input-group-merge input-group-flush">
            <div class="input-group-prepend">
                <span class="input-group-text bg-transparent"><i class="fas fa-search"></i></span>
            </div>
            <input type="text" id="task_keyword" class="form-control form-control-flush" placeholder="<?php echo e(__('Search by Name')); ?>">
        </div>
    </div>
    <div class="dropdown btn btn-sm btn-white btn-icon-only rounded-circle ml-2 m-0 mb-2">
        <a href="#" class="action-item text-dark" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-filter"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-steady" id="task_sort">
            <a class="dropdown-item active" href="#" data-val="created_at-desc">
                <i class="fas fa-sort-amount-down"></i><?php echo e(__('Newest')); ?>

            </a>
            <a class="dropdown-item" href="#" data-val="created_at-asc">
                <i class="fas fa-sort-amount-up"></i><?php echo e(__('Oldest')); ?>

            </a>
            <a class="dropdown-item" href="#" data-val="title-asc">
                <i class="fas fa-sort-alpha-down"></i><?php echo e(__('From A-Z')); ?>

            </a>
            <a class="dropdown-item" href="#" data-val="title-desc">
                <i class="fas fa-sort-alpha-up"></i><?php echo e(__('From Z-A')); ?>

            </a>
        </div>
    </div>
    <div class="dropdown btn btn-sm btn-white btn-icon-only rounded-circle ml-2 m-0 mb-2">
        <a href="#" class="action-item text-dark" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-flag"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right task-filter-actions dropdown-steady" id="task_status">
            <a class="dropdown-item filter-action filter-show-all pl-4 active" href="#"><?php echo e(__('Show All')); ?></a>
            <hr class="my-0">
            <a class="dropdown-item filter-action pl-4" href="#" data-val="see_my_tasks"><?php echo e(__('See My Tasks')); ?></a>
            <hr class="my-0">
            <?php $__currentLoopData = \App\Models\ProjectTask::$priority; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a class="dropdown-item filter-action pl-4" href="#" data-val="<?php echo e($key); ?>"><?php echo e(__($val)); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <hr class="my-0">
            <a class="dropdown-item filter-action filter-other pl-4" href="#" data-val="due_today"><?php echo e(__('Due Today')); ?></a>
            <a class="dropdown-item filter-action filter-other pl-4" href="#" data-val="over_due"><?php echo e(__('Over Due')); ?></a>
            <a class="dropdown-item filter-action filter-other pl-4" href="#" data-val="starred"><?php echo e(__('Starred')); ?></a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row min-750" id="taskboard_view"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        // ready
        $(function () {
            var sort = 'created_at-desc';
            var status = '';
            ajaxFilterTaskView('created_at-desc', '', ['see_my_tasks']);

            // when change status
            $(".task-filter-actions").on('click', '.filter-action', function (e) {
                if ($(this).hasClass('filter-show-all')) {
                    $('.filter-action').removeClass('active');
                    $(this).addClass('active');
                } else {

                    $('.filter-show-all').removeClass('active');
                    if ($(this).hasClass('filter-other')) {
                        $('.filter-other').removeClass('active');
                    }
                    if ($(this).hasClass('active')) {
                        $(this).removeClass('active');
                        $(this).blur();
                    } else {
                        $(this).addClass('active');
                    }
                }

                var filterArray = [];
                var url = $(this).parents('.task-filter-actions').attr('data-url');
                $('div.task-filter-actions').find('.active').each(function () {
                    filterArray.push($(this).attr('data-val'));
                });
                status = filterArray;
                ajaxFilterTaskView(sort, $('#task_keyword').val(), status);
            });

            // when change sorting order
            $('#task_sort').on('click', 'a', function () {
                sort = $(this).attr('data-val');
                ajaxFilterTaskView(sort, $('#task_keyword').val(), status);
                $('#task_sort a').removeClass('active');
                $(this).addClass('active');
            });

            // when searching by task name
            $(document).on('keyup', '#task_keyword', function () {
                ajaxFilterTaskView(sort, $(this).val(), status);
            });
        });

        // For Filter
        function ajaxFilterTaskView(task_sort, keyword = '', status = '') {
            var mainEle = $('#taskboard_view');
            var view = '<?php echo e($view); ?>';
            var data = {
                view: view,
                sort: task_sort,
                keyword: keyword,
                status: status,
            }

            $.ajax({
                url: '<?php echo e(route('project.taskboard.view')); ?>',
                data: data,
                success: function (data) {
                    console.log(data);
                    mainEle.html(data.html);
                }
            });
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/tasks/taskboard.blade.php ENDPATH**/ ?>