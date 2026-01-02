<?php
    $temp_lang = \App::getLocale('lang');
    if($temp_lang == 'ar' || $temp_lang == 'he'){
        $rtl = 'on';
    }
    else {
        $rtl = \App\Models\Utility::getValByName('enable_rtl');
    }
?>
<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>"
    dir=<?php echo e($rtl == 'on' ? 'rtl' : 'ltr'); ?>>
<?php
    $logo = \App\Models\Utility::get_file('logo/');
    $meta_logo = \App\Models\Utility::get_file('uploads/logo/');
    $settings = \App\Models\Utility::settings();
    $user = App\Models\User::where('type' , '=' , 'admin')->first();
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'TaskGo SaaS')); ?> &dash; <?php echo $__env->yieldContent('title'); ?></title>

    <meta name="title" content="<?php echo e($settings['meta_keywords']); ?>">
    <meta name="description" content="<?php echo e($settings['meta_description']); ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(env('APP_URL')); ?>">
    <meta property="og:title" content="<?php echo e($settings['meta_keywords']); ?>">
    <meta property="og:description" content="<?php echo e($settings['meta_description']); ?>">
    <meta property="og:image" content="<?php echo e($meta_logo.$settings['meta_image']); ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo e(env('APP_URL')); ?>">
    <meta property="twitter:title" content="<?php echo e($settings['meta_keywords']); ?>">
    <meta property="twitter:description" content="<?php echo e($settings['meta_description']); ?>">
    <meta property="twitter:image" content="<?php echo e($meta_logo.$settings['meta_image']); ?>">

    <script src="<?php echo e(asset('js/app.js')); ?>" defer></script>
    
    <link rel="icon" href="<?php echo e($logo.'favicon.png'.'?'.time()); ?>" type="image/png">
    
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('assets/libs/@fortawesome/fontawesome-free/css/all.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/site-light.css')); ?>" id="stylesheet">
    <?php if($rtl == 'on'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-rtl.css')); ?>">
    <?php endif; ?>
 
    <?php if($user->mode == 'dark'): ?>
        <style>
        .g-recaptcha {
            filter: invert(1) hue-rotate(180deg) !important;
        }
        </style>
    <?php endif; ?>

    <?php if($user->mode == 'light'): ?>
        <?php echo $__env->make('layouts.lightthemecolor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/site-light.css')); ?>">
    <?php else: ?>
        <?php echo $__env->make('layouts.darkthemecolor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/site-dark.css')); ?>">
    <?php endif; ?>

    <style>
        .auth-lang {
            right: 5px;
            top: 10px;
        }

    </style>
</head>

<body class="application application-offset">
    <?php echo $__env->make('layouts.lightthemecolor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div id="app">
        <div class="container-fluid container-application">
            <div class="main-content position-relative">
                <div class="page-content">
                    <div class="min-vh-100 py-5 d-flex align-items-center">
                        <div class="w-100">
                            <div class="row justify-content-center">
                                <?php echo $__env->yieldContent('language-bar'); ?>
                                <?php echo $__env->yieldContent('content'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if($settings['enable_cookie'] == 'on'): ?>
    <?php echo $__env->make('layouts.cookie_consent', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
</body>

<script src="<?php echo e(asset('assets/js/purpose.core.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/purpose.js')); ?>"></script>
<?php echo $__env->yieldPushContent('custom-scripts'); ?>

</html>
<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/layouts/auth.blade.php ENDPATH**/ ?>