<?php
// $color = App\Models\Utility::color();

if (isset($usr))
{
    $setting = \App\Models\Utility::settingsById($usr->id);
}
else {
    $setting = App\Models\Utility::colorset();
}

$color = (!empty($setting['color'])) ? $setting['color'] : '#6fd943';

?>
<style>
    .application-offset .container-application:before {
        background-color: <?php echo e($color); ?> !important;
    }

    .nav-application>.btn:hover:not(.active) {
        color: <?php echo e($color); ?> !important;
    }

    .nav-application>.btn.active {
        background-color: <?php echo e($color); ?> !important;
    }

    .custom-control-input:checked~.custom-control-label::before {
        border-color: <?php echo e($color); ?> !important;
        background-color: <?php echo e($color); ?> !important;
    }

    .btn-primary {

        background-color: <?php echo e($color); ?> !important;
        border-color: <?php echo e($color); ?> !important;
    }

    .btn-primary:hover {
        /* color: #FFF; */
        background-color: <?php echo e($color); ?> !important;
        border-color: <?php echo e($color); ?> !important;
    }

    .bg-primary {
        background-color: <?php echo e($color); ?> !important;
    }

    .text-primary {
        color: <?php echo e($color); ?> !important;
    }

    span.mb-0.text-sm.font-weight-bold.hover:hover {
        color: <?php echo e($color); ?> !important;
    }


    .dropdown-item:hover,
    .dropdown-item:focus {
        color: <?php echo e($color); ?> !important;

    }

    .dropdown-item.active,
    .dropdown-item:active {
        color: <?php echo e($color); ?> !important;

    }


    .btn-outline-primary {

    color:<?php echo e($color); ?> !important;
    border-color: <?php echo e($color); ?> !important;
}


    .btn-outline-primary:hover{

    color: #fff !important;
    background-color:<?php echo e($color); ?> !important;
    border-color:<?php echo e($color); ?> !important;

}


   .badge-primary {
    color: #fff;
    background-color:<?php echo e($color); ?> !important;
}


    .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
    color: #FFF !important;
    background-color: <?php echo e($color); ?> !important;
}


.btn-check:checked + .btn-outline-primary, .btn-check:active + .btn-outline-primary, .btn-outline-primary:active, .btn-outline-primary.active, .btn-outline-primary.dropdown-toggle.show {
    color: #ffffff !important;
    background-color: <?php echo e($color); ?> !important;
    border-color: <?php echo e($color); ?> !important;
}
</style>
<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/layouts/lightthemecolor.blade.php ENDPATH**/ ?>