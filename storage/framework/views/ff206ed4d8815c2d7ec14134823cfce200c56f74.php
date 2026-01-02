<?php $__env->startSection('title'); ?>
    <?php echo e(__('Invoices')); ?>

<?php $__env->stopSection(); ?>

<?php
$logo_path = \App\Models\Utility::get_file('/');
?>

<?php $__env->startSection('action-button'); ?>
    <a href="#" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-2" data-url="<?php echo e(route('invoices.create')); ?>" data-ajax-popup="true" data-size="md" data-title="<?php echo e(__('Create Invoice')); ?>">
        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
    </a>
    <a href="#" class="btn btn-sm btn-white btn-icon-only rounded-circle" data-url="<?php echo e(route('invoice.file.import')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Import Members CSV file')); ?>" data-toggle="tooltip">
        <i class="fa fa-file-csv"></i>
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<style>
    .ahref:hover,
    .ahref.active {
        color: #fff !important;
    }

</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <ul class="nav nav-dark nav-tabs nav-overflow" role="tablist">
        <li class="nav-item">
            <a href="#send" id="send-tab" class="ahref nav-link active" data-toggle="tab" role="tab" aria-selected="true">
                <?php echo e(__('Send')); ?>

            </a>
        </li>
        <li class="nav-item">
            <a href="#receive" id="receive-tab" class="ahref nav-link" data-toggle="tab" role="tab" aria-selected="true">
                <?php echo e(__('Received')); ?>

            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="send" role="tabpanel" aria-labelledby="send-tab">
            <div class="row">
                <div class="col-12 pb-4">
                    <span class="text-white font-weight-700"><?php echo e(__('Note :')); ?></span>
                    <span class="text-white"><?php echo e(__('You have sent this invoice to your client.')); ?></span>
                </div>
                <?php if($send_invoice->count() > 0): ?>
                    <?php $__currentLoopData = $send_invoice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4">
                            <div class="card hover-shadow-lg">
                                <div class="card-header border-0">
                                    <div class="row align-items-center">
                                        <div class="col-10">
                                            <h6 class="mb-0"><a href="<?php echo e(route('invoices.show',$invoice->id)); ?>"><?php echo e($invoice->project->title); ?></a></h6>
                                        </div>
                                        <div class="col-2 text-right">
                                            <div class="actions">
                                                <div class="dropdown">
                                                    <a href="#" class="action-item" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="<?php echo e(route('invoices.show',$invoice->id)); ?>" class="dropdown-item">
                                                            <?php echo e(__('Show')); ?>

                                                        </a>
                                                        <a href="#" class="dropdown-item" data-url="<?php echo e(route('invoices.edit',[$invoice->id])); ?>" data-ajax-popup="true" data-size="md" data-title="<?php echo e(__('Edit ').\App\Models\Utility::invoiceNumberFormat($invoice->invoice_id)); ?>">
                                                            <?php echo e(__('Edit')); ?>

                                                        </a>
                                                        <a href="#" class="dropdown-item" data-toggle="tooltip" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-<?php echo e($invoice->id); ?>').submit();">
                                                            <?php echo e(__('Delete')); ?>

                                                        </a>
                                                        <?php echo Form::open(['method' => 'DELETE', 'route' => ['invoices.destroy', $invoice->id],'id'=>'delete-form-'.$invoice->id]); ?>

                                                        <?php echo Form::close(); ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="p-3 border border-dashed">
                                        <span class="text-sm text-muted font-weight-600"><?php echo e(\App\Models\Utility::invoiceNumberFormat($invoice->invoice_id)); ?></span>
                                        <div class="row align-items-center mt-3">
                                            <div class="col-6">
                                                <h6 class="mb-0"><?php echo e(\App\Models\Utility::projectCurrencyFormat($invoice->project_id,($invoice->getSubTotal()+$invoice->getTax()),true)); ?></h6>
                                                <span class="text-sm text-muted"><?php echo e(__('Amount')); ?></span>
                                            </div>
                                            <div class="col-6">
                                                <h6 class="mb-0"><?php echo e(\App\Models\Utility::getDateFormated($invoice->due_date)); ?></h6>
                                                <span class="text-sm text-muted"><?php echo e(__('Due Date')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="media mt-4 align-items-center">
                                        <?php if(!empty($invoice->client) ? $invoice->client->avatar : ""): ?>
                                            <img  src="<?php echo e($logo_path.$invoice->client->avatar); ?>" class="avatar rounded-circle avatar-sm"  title="<?php echo e($invoice->client->name); ?>"  >
                                        <?php else: ?>
                                            <img <?php echo e(!empty($invoice->client)? $invoice->client->img_avatar : ""); ?>  class="avatar rounded-circle avatar-sm" title="<?php echo e(!empty($invoice->client) ? $invoice->client->name : ""); ?>" >
                                        <?php endif; ?>
                                        <div class="media-body pl-3">
                                            <div class="text-sm my-0"><?php echo e(!empty($invoice->client) ? $invoice->client->name : ""); ?> <span class="text-primary font-weight-600"><?php echo e('@'.__('client')); ?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-center my-3"><?php echo e(__('No Invoice Found.')); ?></h6>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="tab-pane fade show" id="receive" role="tabpanel" aria-labelledby="receive-tab">
            <div class="row">
                <div class="col-12 pb-4">
                    <span class="text-white font-weight-700"><?php echo e(__('Note :')); ?></span>
                    <span class="text-white"><?php echo e(__('You have got this invoice from user.')); ?></span>
                </div>
                <?php if($receive_invoice->count() > 0): ?>
                    <?php $__currentLoopData = $receive_invoice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4">
                            <div class="card hover-shadow-lg">
                                <div class="card-header border-0">
                                    <div class="row align-items-center">
                                        <div class="col-10">
                                            <h6 class="mb-0"><a href="<?php echo e(route('invoices.show',$invoice->id)); ?>"><?php echo e($invoice->project->title); ?></a></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="p-3 border border-dashed">
                                        <span class="text-sm text-muted font-weight-600"><?php echo e(\App\Models\Utility::invoiceNumberFormat($invoice->invoice_id)); ?></span>
                                        <div class="row align-items-center mt-3">
                                            <div class="col-6">
                                                <h6 class="mb-0"><?php echo e(\App\Models\Utility::projectCurrencyFormat($invoice->project_id,($invoice->getSubTotal()+$invoice->getTax()),true)); ?></h6>
                                                <span class="text-sm text-muted"><?php echo e(__('Amount')); ?></span>
                                            </div>
                                            <div class="col-6">
                                                <h6 class="mb-0"><?php echo e(\App\Models\Utility::getDateFormated($invoice->due_date)); ?></h6>
                                                <span class="text-sm text-muted"><?php echo e(__('Due Date')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="media mt-4 align-items-center">


                                              <?php if($invoice->user->avatar): ?>
                                                <img  src="<?php echo e($logo_path.$invoice->user->avatar); ?>" class="avatar rounded-circle avatar-sm"  title="<?php echo e($invoice->user->name); ?>"  >

                                              <?php else: ?>
                                               <img <?php echo e($invoice->user->img_avatar); ?>  class="avatar rounded-circle avatar-sm" title="<?php echo e($invoice->user->name); ?>" >
                                              <?php endif; ?>
                                        <div class="media-body pl-3">
                                            <div class="text-sm my-0"><?php echo e($invoice->user->name); ?> <span class="text-primary font-weight-600"><?php echo e('@'.__('user')); ?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-center my-3"><?php echo e(__('No Invoice Found.')); ?></h6>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        function fillClient(project_id, selected = 0) {
            $.ajax({
                url: '<?php echo e(route('project.client.json')); ?>',
                data: {project_id: project_id},
                type: 'POST',
                success: function (data) {
                    $('#client_id').html('');

                    if (data != '') {
                        $('#no_client').addClass('d-none');
                        $.each(data, function (key, data) {
                            var selected = '';
                            if (key == selected) {
                                selected = 'selected';
                            }
                            $("#client_id").append('<option value="' + key + '" ' + selected + '>' + data + '</option>');
                        });
                    } else {
                        $('#no_client').removeClass('d-none');
                    }
                }
            })
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/invoices/index.blade.php ENDPATH**/ ?>