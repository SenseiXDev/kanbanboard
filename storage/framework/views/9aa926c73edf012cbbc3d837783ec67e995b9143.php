<?php $__env->startSection('title'); ?>
    <?php echo e(__('Project Detail')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-button'); ?>
    <div class="d-flex align-items-center  justify-content-between">
        <div class="dropdown btn btn-sm btn-white btn-icon-only rounded-circle ml-2 m-0">
            <a href="#" onclick="saveAsPDF()" class="action-item text-dark line_height_auto" data-toggle="tooltip"
                title="<?php echo e(__('Project Report Download')); ?>">
                <i class="fas fa-print mt-2"></i>
            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="row" id="printableArea">
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header">
                                <h6><?php echo e(__('Overview')); ?></h6>
                            </div>
                            <div class="card-body" style="height: 295px !important;">
                                <div class="row align-items-center">
                                    <div class="col-7">

                                        <table class="table" id="pc-dt-simple">
                                            <tbody>
                                                <tr class="table_border">
                                                    <th class="table_border"><?php echo e(__('Project Name')); ?>:</th>
                                                    <td class="table_border"><?php echo e($project->title); ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="table_border"><?php echo e(__('Project Status')); ?>:</th>
                                                    <td class="table_border">

                                                        <span
                                                            class="badge badge-pill badge-<?php echo e(\App\Models\Project::$status_color[$project->status]); ?>"><?php echo e(__(\App\Models\Project::$status[$project->status])); ?></span>
                                                    </td>
                                                </tr>
                                                <tr role="row">
                                                    <th class="table_border"><?php echo e(__('Start Date')); ?>:</th>
                                                    <td class="table_border"><?php echo e($project->start_date); ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="table_border"><?php echo e(__('Due Date')); ?>:</th>
                                                    <td class="table_border"><?php echo e($project->end_date); ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="table_border"><?php echo e(__('Total Members')); ?>:</th>
                                                    <td class="table_border"><?php echo e(count($users)); ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-5 ">
                                        <!--  <div id="projects-chart"></div> -->

                                        <?php
                                            $task_percentage = $project->project_progress()['percentage'];
                                            $data = trim($task_percentage, '%');
                                            $status = $data > 0 && $data <= 25 ? 'red' : ($data > 25 && $data <= 50 ? 'orange' : ($data > 50 && $data <= 75 ? 'blue' : ($data > 75 && $data <= 100 ? 'green' : '')));
                                        ?>

                                        <div class="circular-progressbar p-0">
                                            <div class="flex-wrapper">
                                                <div class="single-chart">
                                                    <svg viewBox="0 0 36 36"
                                                        class="circular-chart orange <?php echo e($status); ?>">
                                                        <path class="circle-bg"
                                                            d="M18 2.0845
                                                                                  a 15.9155 15.9155 0 0 1 0 31.831
                                                                                  a 15.9155 15.9155 0 0 1 0 -31.831" />
                                                        <path class="circle" stroke-dasharray="<?php echo e($data); ?>, 100"
                                                            d="M18 2.0845
                                                                                  a 15.9155 15.9155 0 0 1 0 31.831
                                                                                  a 15.9155 15.9155 0 0 1 0 -31.831" />
                                                        <text x="18" y="20.35"
                                                            class="percentage"><?php echo e($data); ?>%</text>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header">
                                <h6><?php echo e(__('Milestone Progress')); ?></h6>
                            </div>

                            <?php
                                $mile_percentage = $project->project_milestone_progress()['percentage'];
                                $mile_percentage = trim($mile_percentage, '%');
                            ?>

                            <div class="card-body" style="height: 295px !important;">

                                <!-- <div class="d-flex justify-content-center"> -->
                                <div id="milestone-chart" class="d-flex justify-content-center"></div>
                                <!--  </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">
                                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Refferals"><i
                                            class=""></i></a>
                                </div>
                                <h6><?php echo e(__('Task Priority')); ?></h6>
                            </div>
                            <div class="card-body" style="height: 285px !important;">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <!--  <div id="projects-chart"></div> -->
                                        <div id='chart_priority'></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">
                                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Refferals"><i
                                            class=""></i></a>
                                </div>
                                <h6><?php echo e(__('Task Status')); ?></h6>
                            </div>
                            <div class="card-body" style="height: 285px !important;">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <div id="chart"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">
                                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Refferals"><i class=""></i></a>
                                </div>
                                <h6><?php echo e(__('Hours Estimation')); ?></h6>
                            </div>
                            <div class="card-body" style="height: 285px !important;">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <div id="chart-hours"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6><?php echo e(__('Users')); ?></h6>
                            </div>
                            <div class="card-body table-border-style ">
                                <div class="table-responsive"
                                    style="height: 320px !important; overflow: auto !important;">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th><?php echo e(__('Name')); ?></th>
                                                <th><?php echo e(__('Assigned Tasks')); ?></th>
                                                <th><?php echo e(__('Done Tasks')); ?></th>
                                                <th><?php echo e(__('Logged Hour')); ?></th>


                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php $__currentLoopData = $project->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $hours_format_number = 0;
                                                    $total_hours = 0;
                                                    $hourdiff_late = 0;
                                                    $esti_late_hour = 0;
                                                    $esti_late_hour_chart = 0;

                                                    $total_user_task = App\Models\ProjectTask::where('project_id', $project->id)
                                                        ->whereRaw('FIND_IN_SET(?,  assign_to) > 0', [$user->id])
                                                        ->get()
                                                        ->count();

                                                    $all_task = App\Models\ProjectTask::where('project_id', $project->id)
                                                        ->whereRaw('FIND_IN_SET(?,  assign_to) > 0', [$user->id])
                                                        ->get();

                                                    $total_complete_task = App\Models\ProjectTask::join('task_stages', 'task_stages.id', '=', 'project_tasks.stage_id')
                                                        ->where('project_tasks.project_id', '=', $project->id)
                                                        ->where('assign_to', '=', $user->id)
                                                        ->where('task_stages.complete', '=', '1')
                                                        ->get()
                                                        ->count();
                                                    $logged_hours = 0;
                                                    $timesheets = App\Models\Timesheet::where('project_id', $project->id)
                                                        ->where('created_by', $user->id)
                                                        ->get();
                                                ?>
                                                <?php $__currentLoopData = $timesheets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timesheet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $date_time = $timesheet->time;
                                                        $hours = date('H', strtotime($date_time));
                                                        $minutes = date('i', strtotime($date_time));
                                                        $total_hours = $hours + $minutes / 60;
                                                        $logged_hours += $total_hours;
                                                        $hours_format_number = number_format($logged_hours, 2, '.', '');
                                                    ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                <tr>
                                                    <td><?php echo e($user->name); ?></td>
                                                    <td><?php echo e($total_user_task); ?></td>
                                                    <td><?php echo e($total_complete_task); ?></td>
                                                    <td><?php echo e($hours_format_number); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6><?php echo e(__('Milestones')); ?></h6>
                            </div>
                            <div class=" table-border-style">
                                <div class="card-body">
                                    <div class="table-responsive"
                                        style="height: 320px !important; overflow: auto !important;">
                                        <table class=" table ">
                                            <thead>
                                                <tr>
                                                    <th> <?php echo e(__('Name')); ?></th>
                                                    <th> <?php echo e(__('Progress')); ?></th>
                                                    <th> <?php echo e(__('Status')); ?></th>
                                                    <th> <?php echo e(__('Start Date')); ?></th>
                                                    <th> <?php echo e(__('End Date')); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $project->milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $milestone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($milestone->title); ?></td>
                                                        <td>
                                                            <div class="progress_wrapper">
                                                                <div class="progress">
                                                                    <div class="progress-bar" role="progressbar"
                                                                        style="width: <?php echo e($milestone->progress); ?>px;"
                                                                        aria-valuenow="55" aria-valuemin="0"
                                                                        aria-valuemax="100"></div>
                                                                </div>
                                                                <div class="progress_labels">
                                                                    <div class="total_progress">

                                                                        <strong> <?php echo e($milestone->progress); ?>%</strong>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td> <span
                                                                class="badge badge-pill badge-<?php echo e(\App\Models\Project::$status_color[$milestone->status]); ?>"><?php echo e(__(\App\Models\Project::$status[$milestone->status])); ?></span>
                                                        </td>
                                                        <td><?php echo e($milestone->start_date); ?></td>
                                                        <td><?php echo e($milestone->end_date); ?></td>


                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 mb-3 row d-flex align-items-center justify-content-end ml-2 col-12" id="show_filter">
                    <div class="col-auto ">
                        <select class="form-control form-control-sm w-auto" name="all_users" id="all_users">
                            <option value="" class="px-4"><?php echo e(__('All Users')); ?></option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <select class="form-control form-control-sm w-auto" name="milestone_id" id="milestone_id">
                            <option value="" class="px-4"><?php echo e(__('All Milestones')); ?></option>
                            <?php $__currentLoopData = $milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $milestone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($milestone->id); ?>"><?php echo e($milestone->title); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <select class="form-control form-control-sm w-auto" name="status" id="status">
                            <option value="" class="px-4"><?php echo e(__('All Status')); ?></option>
                            <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($stage->id); ?>"><?php echo e(__($stage->name)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <select class="form-control form-control-sm w-auto" name="priority" id="priority">
                            <option value="" class="px-4"><?php echo e(__('All Priority')); ?></option>
                            <option value="low"><?php echo e(__('Low')); ?></option>
                            <option value="medium"><?php echo e(__('Medium')); ?></option>
                            <option value="critical"><?php echo e(__('Critical')); ?></option>
                            <option value="high"><?php echo e(__('High')); ?></option>
                        </select>
                    </div>

                    <button class="btn btn-white btn-sm  col-auto btn-filter apply" style="border: 1px solid #E0E6ED;"
                        id="filter"><i class="mdi mdi-check"></i><?php echo e(__('Apply')); ?></button>

                    <button class=" btn btn-white btn-sm  col-auto " style="border: 1px solid #E0E6ED;"> <a
                            href="<?php echo e(route('project_report.export', $project->id)); ?>" class="btn-white">
                            <i class="mdi mdi-check"></i>
                            <?php echo e(__('Export')); ?>

                        </a></button>
                </div>


                <div class="col-md-12">
                    <div class="card">

                        <div class="card-header">
                            <h6><?php echo e(__('Tasks')); ?></h6>
                        </div>
                        <div class="card-body mx-2">
                            <div class="row">

                                <div class="table-responsive"
                                    style="height: 320px !important; overflow: auto !important;">
                                    <table class="table" id="tasks-selection-datatable">
                                        <thead>
                                            <th><?php echo e(__('Task Name')); ?></th>
                                            <th><?php echo e(__('Milestone')); ?></th>
                                            <th><?php echo e(__('Start Date')); ?></th>
                                            <th><?php echo e(__('Due Date')); ?></th>

                                            <th><?php echo e(__('Assigned to')); ?></th>

                                            <th> <?php echo e(__('Total Logged Hours')); ?></th>
                                            <th><?php echo e(__('Priority')); ?></th>
                                            <th><?php echo e(__('Status')); ?></th>

                                        </thead>
                                        <tbody class="task">

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>



            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('css-page'); ?>

<?php $__env->stopPush(); ?>
<style type="text/css">
    .apexcharts-menu-icon {
        display: none;
    }
      table.dataTable.no-footer {
    border-bottom: none !important;
}
    .table_border{
    border: none !important
    }
</style>


<?php $__env->startPush('script'); ?>
<script type="text/javascript" src="<?php echo e(asset('js/html2pdf.bundle.min.js')); ?>"></script>
<script>
          var filename = $('#chart-hours').val();

        function saveAsPDF() {

            var element = document.getElementById('printableArea');


            var opt = {
                margin: 0.3,

                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    dpi: 72,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A2'
                }
            };
            html2pdf().set(opt).from(element).save();
        }

    </script>

<script src="<?php echo e(asset('assets/js/apexcharts.min.js')); ?>"></script>
<script>
        $(document).ready(function() {


            $(document).on("click", ".btn-filter", function() {

                getData();
            });

            function getData() {
               // table.clear().draw();
                 $("#tasks-selection-datatable tbody tr").html(
                    '<td colspan="11" class="text-center"> <?php echo e(__('Loading ...')); ?></td>');

                var data = {
                    assign_to: $("#all_users").val(),
                    priority: $("#priority").val(),
                    due_date_order: $("#due_date_order").val(),
                    milestone_id:  $("#milestone_id").val(),
                    start_date: $("#start_date").val(),
                    due_date:  $("#due_date").val(),
                    status: $("#status").val(),
                };

                $.ajax({
                    url: '<?php echo e(route('tasks.report.ajaxdata',$project->id)); ?>',
                    type: 'POST',
                    data: data,
                    success: function(data){

                        // $('#formdata')[0].reset();
                        $(".task").html("");

                        $.each(data.data, function(index, value)
                        {
                            var row =  $("<tr><td>"
                            + value.title + "</td><td>"
                            + value.milestone_id + "</td><td>"
                            + value.start_date + "</td><td>"
                            + value.end_date + "</td><td>"
                            + value.user_name + "</td><td>"
                            + value.logged_hours + "</td><td>"
                            + value.priority + "</td><td>"
                            + value.status + "</td></tr>");
                            $(".task").append(row);
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

   <script>
           (function () {
        var options = {
            series: [<?php echo json_encode($mile_percentage); ?>],
            chart: {
                height: 475,
                type: 'radialBar',
                offsetY: -20,
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {
                radialBar: {
                    startAngle: -90,
                    endAngle: 90,
                    track: {
                        background: "#e7e7e7",
                        strokeWidth: '97%',
                        margin: 5, // margin is in pixels
                    },
                    dataLabels: {
                        name: {
                            show: true
                        },
                        value: {
                            offsetY: -50,
                            fontSize: '20px'
                        }
                    }
                }
            },
            grid: {
                padding: {
                    top: -10
                }
            },
            colors: ["#51459d"],
            labels: ['Progress'],
        };
        var chart = new ApexCharts(document.querySelector("#milestone-chart"), options);
        chart.render();
    })();


var options = {
          series:  <?php echo json_encode($arrProcessPer_status_task); ?>,
          chart: {
          width: 380,
          type: 'pie',
        },
         colors: ["#23d180","#719fea","#edf104","#e92f2f"],
        labels:<?php echo json_encode($arrProcess_Label_status_tasks); ?>,
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 100
            },
            legend: {
              position: 'bottom'

            }
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();



     var options = {
          series: [{
          data: <?php echo json_encode($arrProcessPer_priority); ?>

        }],
          chart: {
          height: 210,
          width: 200,
          type: 'bar',
        },
        colors: ['#6fd943','#EA8ADD','#3ec9d6','#F9152C'],
        plotOptions: {
          bar: {

            columnWidth: '50%',
            distributed: true,
          }
        },
        dataLabels: {
          enabled: false
        },
        legend: {
          show: true
        },
        xaxis: {
          categories: <?php echo json_encode($arrProcess_Label_priority); ?>,
          labels: {

          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart_priority"), options);
        chart.render();



        ///=====================Hour Chart =============================================================///


        var options = {
          series: [{
           data: [<?php echo json_encode($esti_logged_hour_chart); ?>,<?php echo json_encode($logged_hour_chart); ?>],

        }],
          chart: {
          height: 210,
          width:300,
          type: 'bar',
        },
        colors: ['#963aff','#ffa21d'],
        plotOptions: {
          bar: {
               horizontal: true,
            columnWidth: '30%',
            distributed: true,
          }
        },
        dataLabels: {
          enabled: false
        },
        legend: {
          show: true
        },
        xaxis: {
          categories: ["Estimated Hours","Logged Hours "],

        }
        };

        var chart = new ApexCharts(document.querySelector("#chart-hours"), options);
        chart.render();

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/project_report/show.blade.php ENDPATH**/ ?>