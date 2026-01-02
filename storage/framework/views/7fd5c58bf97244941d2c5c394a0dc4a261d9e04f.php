<?php $__env->startSection('title'); ?>
    <?php echo e(__('Members')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('theme-script'); ?>
    <script src="<?php echo e(asset('assets/libs/apexcharts/dist/apexcharts.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('action-button'); ?>
    <div class="bg-neutral rounded-pill d-inline-block ml-2 mb-2 mb-sm-1">
        <div class="input-group input-group-sm input-group-merge input-group-flush">
            <div class="input-group-prepend">
                <span class="input-group-text bg-transparent"><i class="fas fa-search"></i></span>
            </div>
            <input type="text" id="user_keyword" class="form-control form-control-flush" placeholder="<?php echo e(__('Search by Name or skill')); ?>">
        </div>
    </div>
    <div class="dropdown btn btn-sm btn-white btn-icon-only rounded-circle ml-2 m-0 mb-1">
        <a href="#" class="action-item text-dark" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-filter"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-steady" id="user_sort">
            <a class="dropdown-item active" href="#" data-val="all"><?php echo e(__('Show All')); ?></a>
            <a class="dropdown-item" href="#" data-val="user"><?php echo e(__('Users')); ?></a>
            <a class="dropdown-item" href="#" data-val="client"><?php echo e(__('Clients')); ?></a>
        </div>
    </div>
    <?php if($allow == true): ?>
        <a href="#" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-2 m-0 mb-1" data-url="<?php echo e(route('add.user.view')); ?>" data-ajax-popup="true" data-size="lg" data-title="<?php echo e(__('Add Member')); ?>">
            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
        </a>
    <?php else: ?>
        <a href="#" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-2 m-0 mb-1" id="prevent_user">
            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
        </a>
    <?php endif; ?>
<a href="<?php echo e(route('logindetails.index')); ?>" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-2 m-0 mb-1" data-bs-toggle="tooltip" title="<?php echo e(__('User Log')); ?>">
        <i class="fas fa-history"></i>
    </a>
    <a href="<?php echo e(route('members.export')); ?>" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-2 mb-1" data-title="<?php echo e(__('Export Members CSV file')); ?>" data-toggle="tooltip">
        <i class="fa fa-file-excel"></i>
    </a>

    <a href="#" class="btn btn-sm btn-white btn-icon-only rounded-circle mb-1" data-url="<?php echo e(route('members.file.import')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Import Members CSV file')); ?>" data-toggle="tooltip">
        <i class="fa fa-file-csv"></i>
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row min-750" id="user_view"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        $(document).ready(function () {
            var role = 'all';
            ajaxFilterUserView();
            // when searching by user name
            $(document).on('keyup', '#user_keyword', function () {
                ajaxFilterUserView($(this).val(), role);
            });

            // when change sorting order
            $('#user_sort').on('click', 'a', function () {
                role = $(this).attr('data-val');
                ajaxFilterUserView($('#user_keyword').val(), role);
                $('#user_sort a').removeClass('active');
                $(this).addClass('active');
            });

            // add user modal code
            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

            $(document).on('click', '.check-add-user', function () {
                var ele = $(this);
                var emailele = $('#add_email');
                var email = emailele.val();
                var userrole = $('input[name="user_type"]:checked').val();

                // Email Field Validation
                $('.email-error-message').remove();
                if (email == '') {
                    emailele.focus().after('<small class="email-error-message text-danger"><?php echo e(__("This field is required.")); ?></small>');
                    return false;
                }

                if (!emailReg.test(email)) {
                    emailele.focus().after('<small class="email-error-message text-danger"><?php echo e(__("Please enter valid email address.")); ?></small>');
                    return false;
                } else {
                    $('.invite_usr').addClass('d-none');

                    $.ajax({
                        url: '<?php echo e(route('add.user.exists')); ?>',
                        dataType: 'json',
                        data: {
                            'email': email,
                            'userrole': userrole
                        },
                        success: function (data) {
                            if (data.code == '202') {
                                $('#commonModal').modal('hide');
                                show_toastr(data.status, data.success, 'success');
                            } else if (data.code == '200') {
                                $('#commonModal').modal('hide');
                                show_toastr(data.status, data.success, 'success');
                                location.reload();
                            } else if (data.code == '404') {
                                $('.add_user_div').removeClass('d-none');
                                $('.invite-warning').text(data.error).show();
                                $('#add_email').prop('readonly', true);
                            }
                            ele.removeClass('check-add-user').addClass('add-user');
                        }
                    });
                }
            });

            $(document).on('click', '.add-user', function () {
                var useremail = $('#add_email').val();
                var username = $('#username').val();
                var userpassword = $('#userpassword').val();
                var userrole = $('input[name="user_type"]:checked').val();

                $('.username-error-message').remove();
                if (username == '') {
                    $('#username').focus().after('<small class="username-error-message text-danger"><?php echo e(__("This field is required.")); ?></small>');
                    return false;
                }

                $('.userpassword-error-message').remove();
                if (userpassword == '') {
                    $('#userpassword').focus().after('<small class="userpassword-error-message text-danger"><?php echo e(__("This field is required.")); ?></small>');
                    return false;
                }

                $('.email-error-message').remove();
                if (useremail == '') {
                    $('#add_email').focus().after('<small class="email-error-message text-danger"><?php echo e(__("This field is required.")); ?></small>');
                    return false;
                }

                if (!emailReg.test(useremail)) {
                    $('#add_email').focus().after('<small class="email-error-message text-danger"><?php echo e(__("Please enter valid email address.")); ?></small>');
                    return false;
                } else {
                    $.ajax({
                        url: '<?php echo e(route('add.user.member')); ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            'useremail': useremail,
                            'username': username,
                            'userpassword': userpassword,
                            'userrole': userrole
                        },
                        success: function (data) {
                            if (data.code == '200') {
                                $('#commonModal').modal('hide');
                                show_toastr(data.status, data.success, 'success')
                                ajaxFilterUserView();
                            } else if (data.code == '404') {
                                show_toastr(data.status, data.error, 'error')
                            }
                        }
                    });
                }
            });
            // end

            $(document).on('click', '#prevent_user', function () {
                show_toastr('Error', '<?php echo e(__('Your user limit is over, Please upgrade plan.')); ?>', 'error');
            });
        });

        // For Filter
        var currentRequest = null;

        function ajaxFilterUserView(keyword = '', role = 'all') {
            var mainEle = $('#user_view');
            var view = '<?php echo e($view); ?>';
            var data = {
                view: view,
                keyword: keyword,
                role: role
            }

            currentRequest = $.ajax({
                url: '<?php echo e(route('filter.user.view')); ?>',
                data: data,
                beforeSend: function () {
                    if (currentRequest != null) {
                        currentRequest.abort();
                    }
                },
                success: function (data) {
                    mainEle.html(data.html);
                    $('[id^=fire-modal]').remove();
                    loadConfirm();

                    // load chart
                    var e = $('[data-toggle="spark-chart"]');
                    e.length && e.each(function () {
                        !function (e) {
                            var t = {
                                chart: {width: "100%", sparkline: {enabled: !0}}, series: [], labels: [], stroke: {width: 2, curve: "smooth"}, markers: {size: 0}, colors: [], tooltip: {
                                    fixed: {enabled: !1}, x: {show: !1}, y: {
                                        title: {
                                            formatter: function (e) {
                                                return "<?php echo e(__('Timesheet')); ?>"
                                            }
                                        }
                                    }, marker: {show: !1}
                                }
                            }, a = e.data().dataset, n = e.data().labels, o = e.data().color, i = e.data().height, s = e.data().type;
                            t.series = [{data: a}], n && (t.labels = [n]), t.colors = [SiteStyle.colors.theme[o]], t.chart.height = i || 35, t.chart.type = s || "line";
                            var r = new ApexCharts(e[0], t);
                            setTimeout(function () {
                                r.render()
                            }, 300)
                        }($(this))
                    })
                }
            });
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/users/index.blade.php ENDPATH**/ ?>