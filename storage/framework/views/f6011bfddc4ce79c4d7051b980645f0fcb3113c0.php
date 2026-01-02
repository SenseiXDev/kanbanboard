<?php echo $__env->make('Chatify::layouts.headLinks', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    <?php echo e(__('Messenger')); ?>

<?php $__env->stopSection(); ?>


<?php
//  $color = Cookie::get('theme_color');

$profile=\App\Models\Utility::get_file('/'.config('chatify.user_avatar.folder'));  
$setting = App\Models\Utility::colorset();
$color = (!empty($setting['color'])) ? $setting['color'] : '#6fd943';
if (!isset($color)) {
    $color = '#6fd943';
}
?>

<?php $__env->startSection('content'); ?>
<div class="messenger rounded min-h-750 overflow-hidden mt-4">
    
    <div class="messenger-listView">
        
        <div class="m-header">
            <nav>
                
                
                <nav class="m-header-right">
                    
                    <a href="#" class="listView-x" ><i class="fas fa-times"></i></a>
                </nav>
            </nav>
            
            <input type="text" class="messenger-search" placeholder="Search" />
            
            <div class="messenger-listView-tabs">
                <a href="#" <?php if($route == 'user'): ?> class="active-tab" <?php endif; ?> data-view="users">
                    <span class="fa fa-clock"></span></a>
                <a href="#" <?php if($route == 'group'): ?> class="active-tab" <?php endif; ?> data-view="groups">
                    <span class="fas fa-users"></span></a>
            </div>
        </div>
        
        <div class="m-body contacts-container">
           
           
           <div class="<?php if($route == 'user'): ?> show <?php endif; ?> messenger-tab users-tab app-scroll" data-view="users">

               
               <div class="favorites-section">
                <p class="messenger-title">Favorites</p>
                <div class="messenger-favorites app-scroll-thin"></div>
               </div>

               
               <?php echo view('Chatify::layouts.listItem', ['get' => 'saved']); ?>


               
               <div class="listOfContacts" style="width: 100%;height: calc(100% - 200px);position: relative;"></div>

           </div>

           
           
             <div class="all_members <?php if($route == 'group'): ?> show <?php endif; ?> messenger-tab app-scroll" data-view="groups">
                <p style="text-align: center;color:grey;"><?php echo e(__('Soon will be available')); ?></p>
            </div>

             
           <div class="messenger-tab search-tab app-scroll" data-view="search">
                
                <p class="messenger-title">Search</p>
                <div class="search-records">
                    <p class="message-hint center-el"><span>Type to search..</span></p>
                </div>
             </div>
        </div>
    </div>

    
    <div class="messenger-messagingView">
        
        <div class="m-header m-header-messaging">
            <nav class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                
                <div class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                    <a href="#" class="show-listView"><i class="fas fa-arrow-left"></i></a>
                    <div class="avatar av-s header-avatar" style="margin: 0px 10px; margin-top: -5px; margin-bottom: -5px; background-image: url('<?php echo e($profile.'/avatars/avatar.png'); ?>');">
                    </div>
                    <a href="#" class="user-name"><?php echo e(config('chatify.name')); ?></a>
                </div>
                
                <nav class="m-header-right">
                    <a href="#" class="add-to-favorite"><i class="fas fa-star"></i></a>
                    
                    <a href="#" class="show-infoSide"><i class="fas fa-info-circle"></i></a>
                </nav>
            </nav>
        </div>
        
        <div class="internet-connection">
            <span class="ic-connected"><?php echo e(__('Connected')); ?></span>
                <span class="ic-connecting"><?php echo e(__('Connecting...')); ?></span>
                <span class="ic-noInternet"><?php echo e(__('Please add pusher settings for using messenger.')); ?></span>
        </div>
        
        <div class="m-body messages-container app-scroll">
            <div class="messages">
                <p class="message-hint center-el"><span>Please select a chat to start messaging</span></p>
            </div>
            
            <div class="typing-indicator">
                <div class="message-card typing">
                    <p>
                        <span class="typing-dots">
                            <span class="dot dot-1"></span>
                            <span class="dot dot-2"></span>
                            <span class="dot dot-3"></span>
                        </span>
                    </p>
                </div>
            </div>
            
            <?php echo $__env->make('Chatify::layouts.sendForm', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
    
    <div class="messenger-infoView app-scroll">
        
        <nav style="margin-left: 90px;">
            <a href="#"><i class="fas fa-times"></i></a>
        </nav>
        <?php echo view('Chatify::layouts.info')->render(); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script'); ?>

<?php echo $__env->make('Chatify::layouts.modals', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('Chatify::layouts.footerLinks', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<script>
    $("#drop_a").click(function(){
        $("drop_a").attr('aria-expanded');
        $("#drop_li").addClass("show");
        $("#drop_div").addClass("show");
    });
</script>

<?php $__env->stopPush(); ?>

<style type="text/css">
    .m-list-active, .m-list-active:hover, .m-list-active:focus {
    background: <?php echo e($color); ?> !important;
}
.mc-sender p {
    background: <?php echo e($color); ?> !important;
}

.messenger-favorites div.avatar {
    box-shadow: 0px 0px 0px 2px <?php echo e($color); ?> !important;
}
.messenger-listView-tabs a, .messenger-listView-tabs a:hover, .messenger-listView-tabs a:focus {
    color: <?php echo e($color); ?> !important;
}
.m-header svg {
    color: <?php echo e($color); ?> !important;
}
.active-tab {
    border-bottom: 2px solid <?php echo e($color); ?> !important;
}
.messenger-infoView nav a {
  
    color: <?php echo e($color); ?> !important;
}


.messenger-list-item td span .lastMessageIndicator {

    color: <?php echo e($color); ?> !important;
    font-weight: bold;
}
.messenger-sendCard button svg {
     color: <?php echo e($color); ?> !important;
}


.mc-sender p sub {
    color: #fff !important;
}

.mc-sender p {
    direction: ltr;
    color: #fff !important;
}

.m-list-active  td span .lastMessageIndicator{
     color: #fff !important;
}


.messenger-list-item td b {
    
    background-color:  <?php echo e($color); ?> !important;
}

</style>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/vendor/Chatify/pages/app.blade.php ENDPATH**/ ?>