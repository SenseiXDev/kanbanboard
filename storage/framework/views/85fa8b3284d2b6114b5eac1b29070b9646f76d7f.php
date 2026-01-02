<?php $__env->startSection('title'); ?>
<?php echo e(__('Manage Trackers')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('theme-script'); ?>
    <link rel="stylesheet" href="<?php echo e(url('assets/libs/swiper/dist/css/swiper.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('assets/libs/swiper/dist/css/swiper.min.css')); ?>">
    
    <style>
        .product-thumbs .swiper-slide img {
        border:2px solid transparent;
        object-fit: cover;
        cursor: pointer;
        }
        .product-thumbs .swiper-slide-active img {
        border-color: #bc4f38;
        }

        .product-slider .swiper-button-next:after,
        .product-slider .swiper-button-prev:after {
            font-size: 20px;
            color: #000;
            font-weight: bold;
        }
        .no-image{
            min-height: 300px;
            align-items: center;
            display: flex;
            justify-content: center;
        }
        
    </style>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="">
                    <div class="table-responsive">
                        <table class="table table-striped data-table">
                            <thead>
                            <tr>
                               
                                <th> <?php echo e(__('Description')); ?></th>
                                <th> <?php echo e(__('Task')); ?></th>
                                <th> <?php echo e(__('Project')); ?></th>
                                <th> <?php echo e(__('Start Time')); ?></th>
                                <th> <?php echo e(__('End Time')); ?></th>
                                <th><?php echo e(__('Total Time')); ?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $treckers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trecker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                                <?php
                                    $total_name = Utility::second_to_time($trecker->total_time);

                                ?>
                                <tr>
                                    <td><?php echo e(!empty($trecker->name)?$trecker->name:' - '); ?></td>
                                    <td><?php echo e(isset($trecker->tasks->name)?$trecker->tasks->name:' - '); ?></td>
                                    <td><?php echo e(isset($trecker->project->title)?$trecker->project->title:' - '); ?></td>
                                    <td><?php echo e(date("H:i:s",strtotime($trecker->start_time))); ?></td>
                                    <td><?php echo e(date("H:i:s",strtotime($trecker->end_time))); ?></td>
                                    <td><?php echo e($total_name); ?></td>
                                    <td>
                                        <img alt="Image placeholder" src="<?php echo e(asset('assets/img/gallery.png')); ?>" class="avatar view-images rounded-circle avatar-sm" data-toggle="tooltip" data-original-title="<?php echo e(__('View Screenshot images')); ?>" style="height: 25px;width:24px;margin-right:10px;cursor: pointer;" data-id="<?php echo e($trecker->id); ?>" id="track-images-<?php echo e($trecker->id); ?>" >
                                       <a href="#" class="action-item " data-toggle="tooltip" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($trecker->id); ?>').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['tracker.destroy', $trecker->id],'id'=>'delete-form-'.$trecker->id]); ?>

                                    <?php echo Form::close(); ?>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center"> <?php echo e(__("No Record")); ?></td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg ss_modale " role="document">
          <div class="modal-content image_sider_div">
            
          </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>

<script src="<?php echo e(url('assets/libs/swiper/dist/js/swiper.min.js')); ?>"></script>

<script type="text/javascript">

    function init_slider(){
            if($(".product-left").length){
                    var productSlider = new Swiper('.product-slider', {
                        spaceBetween: 0,
                        centeredSlides: false,
                        loop:false,
                        direction: 'horizontal',
                        loopedSlides: 5,
                        navigation: {
                            nextEl: ".swiper-button-next",
                            prevEl: ".swiper-button-prev",
                        },
                        resizeObserver:true,
                    });
                var productThumbs = new Swiper('.product-thumbs', {
                    spaceBetween: 0,
                    centeredSlides: true,
                    loop: false,
                    slideToClickedSlide: true,
                    direction: 'horizontal',
                    slidesPerView: 7,
                    loopedSlides: 5,
                });
                productSlider.controller.control = productThumbs;
                productThumbs.controller.control = productSlider;
            }
        }


    $(document).on('click', '.view-images', function () {
         
            var p_url = "<?php echo e(route('tracker.image.view')); ?>";
            var data = {
                'id': $(this).attr('data-id')
            };
            console.log(data);
            postAjax(p_url, data, function (res) {
                $('.image_sider_div').html(res);
                $('#exampleModalCenter').modal('show');   
                setTimeout(function(){
                    var total = $('.product-left').find('.product-slider').length
                    if(total > 0){
                        init_slider(); 
                    }
                
                },200);

            });
            });


            // ============================ Remove Track Image ===============================//
            $(document).on("click", '.track-image-remove', function () {
            var rid = $(this).attr('data-pid');
            $('.confirm_yes').addClass('image_remove');
            $('.confirm_yes').attr('image_id', rid);
            $('#cModal').modal('show');
            var total = $('.product-left').find('.swiper-slide').length
            });

            function removeImage(id){
                var p_url = "<?php echo e(route('tracker.image.remove')); ?>";
                var data = {id: id};
                deleteAjax(p_url, data, function (res) {

                    if(res.flag){
                        $('#slide-thum-'+id).remove();
                        $('#slide-'+id).remove();
                        setTimeout(function(){
                            var total = $('.product-left').find('.swiper-slide').length
                            if(total > 0){
                                init_slider();
                            }else{
                                $('.product-left').html('<div class="no-image"><h5 class="text-muted">Images Not Available .</h5></div>');
                            }
                        },200);

                      
                        show_toastr('Sucess', res.msg, 'success');
                        $('#fire-modal-'+id).remove();
                    }else{
                        show_toastr('Error', res.msg, 'error');
                    }
                });
            }

            $(document).on("click", '.remove-track', function () {
            var rid = $(this).attr('data-id');
            $('.confirm_yes').addClass('t_remove');
            $('.confirm_yes').attr('uid', rid);
            $('#cModal').modal('show');
        });

      
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/time_trackers/index.blade.php ENDPATH**/ ?>