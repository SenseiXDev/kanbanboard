<div class="row">
    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($plan->status): ?>
            <div class="col-xs-12 col-sm-12 col-md-<?php echo e($size); ?> col-lg-<?php echo e($size); ?> mt-4">
                <div class="card card-pricing popular text-center px-3 mb-5 mb-lg-0">
                    <span
                        class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-primary text-white"><?php echo e($plan->name); ?>

                    </span>
                    <?php if(\Auth::user()->type == 'owner' && \Auth::user()->plan == $plan->id): ?>
                        <span class="align-items-center ms-2 text-right">
                            <i class="f-10 lh-1 fas fa-circle text-primary"></i>
                            <span class="ms-2"><?php echo e(__('Active')); ?></span>
                        </span>
                    <?php endif; ?>
                    <div class="card-body delimiter-top">
                        <ul class="list-unstyled mb-4">
                            <?php if($plan->id != 1): ?>
                                <?php if($plan->trial_days == 0): ?>
                                    <li><?php echo e(__('Trial')); ?> : <?php echo e($plan->trial_days); ?> <?php echo e(__('Days')); ?></li>
                                <?php endif; ?>
                                <li><?php echo e(__('Monthly Price')); ?>:
                                    <?php echo e(env('CURRENCY') ? env('CURRENCY') : '$'); ?><?php echo e($plan->monthly_price); ?></li>
                                <li><?php echo e(__('Annual Price')); ?>:
                                    <?php echo e(env('CURRENCY') ? env('CURRENCY') : '$'); ?><?php echo e($plan->annual_price); ?></li>
                            <?php endif; ?>
                            <?php if($plan->max_users != 0): ?>
                                <li><?php echo e($plan->max_users < 0 ? __('Unlimited') : $plan->max_users); ?> <?php echo e(__('Users')); ?>

                                </li>
                            <?php endif; ?>
                            <?php if($plan->max_projects != 0): ?>
                                <li><?php echo e($plan->max_projects < 0 ? __('Unlimited') : $plan->max_projects); ?>

                                    <?php echo e(__('Projects')); ?></li>
                            <?php endif; ?>
                            <?php if($plan->storage_limit != 0): ?>
                                <li><?php echo e($plan->storage_limit < 0 ? __('Unlimited') : $plan->storage_limit); ?>

                                    <?php echo e(__('Storage Limit')); ?></li>
                            <?php endif; ?>
                            <li>
                                <?php if($plan->enable_chatgpt == 'on'): ?>
                                    <span class="theme-avtar">
                                        <i class="text-primary fas fa-plus-circle"></i></span>
                                    <?php echo e(__('Chat GPT')); ?>

                                <?php else: ?>
                                    <span class="theme-avtar">
                                        <i class="text-danger fas fa-plus-circle"></i></span>
                                    <?php echo e(__('Chat GPT')); ?>

                                <?php endif; ?>
                            </li>
                            <?php if($plan->description): ?>
                                <li>
                                    <small><?php echo e($plan->description); ?></small>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="card-footer delimiter-top">
                        <div class="row justify-content-center">
                            <?php $planStatus = true; ?>
                            <?php if(\Auth::user()->type != 'client' && \Auth::user()->plan != '' && \Auth::user()->plan == $plan->id &&
                                    date('Y-m-d') < \Auth::user()->plan_expire_date &&
                                    (\Auth::user()->is_plan_purchased == 0 || \Auth::user()->plan_expire_date < date('Y-m-d'))): ?>
                                <button class="btn btn-sm btn-neutral mb-3">
                                    <a>
                                        <?php if(Auth::user()->is_trial_done && Auth::user()->is_plan_purchased == 0): ?>
                                            <?php if(Auth::user()->plan_expire_date < date('Y-m-d')): ?>
                                                <?php echo e(__('Trial Expired : ')); ?>

                                            <?php else: ?>
                                                <?php echo e(__('Trial Expire on : ')); ?>

                                            <?php endif; ?>
                                            <?php $planStatus = false; ?>
                                        <?php elseif(Auth::user()->plan != '' && Auth::user()->plan_expire_date < date('Y-m-d')): ?>
                                            <?php echo e(__('Plan Expired : ')); ?>

                                            <?php $planStatus = false; ?>
                                        <?php endif; ?>
                                        <?php if($planStatus): ?>
                                            <?php echo e(__('Plan Expire on : ')); ?>

                                        <?php endif; ?>
                                        <?php echo e(date('d M Y', strtotime(\Auth::user()->plan_expire_date))); ?>

                                    </a>
                                </button>
                            <?php elseif(\Auth::user()->plan == $plan->id && date('Y-m-d') < \Auth::user()->plan_expire_date): ?>
                                <button class="btn btn-sm btn-neutral mb-3">
                                    <a><?php echo e(__('Expire on ')); ?>

                                        <?php echo e(date('d M Y', strtotime(\Auth::user()->plan_expire_date))); ?></a>
                                </button>
                            <?php elseif($paymentSetting['enable_stripe'] == 'on' ||
                                    $paymentSetting['enable_paypal'] == 'on' ||
                                    $paymentSetting['is_paystack_enabled'] == 'on' ||
                                    $paymentSetting['is_flutterwave_enabled'] == 'on' ||
                                    $paymentSetting['is_razorpay_enabled'] == 'on' ||
                                    $paymentSetting['is_mercado_enabled'] == 'on' ||
                                    $paymentSetting['is_paytm_enabled'] == 'on' ||
                                    $paymentSetting['is_mollie_enabled'] == 'on' ||
                                    $paymentSetting['is_skrill_enabled'] == 'on' ||
                                    $paymentSetting['is_coingate_enabled'] == 'on' ||
                                    $paymentSetting['is_paymentwall_enabled'] == 'on' ||
                                    $paymentSetting['is_toyyibpay_enabled'] == 'on' ||
                                    $paymentSetting['is_payfast_enabled'] == 'on'|| $paymentSetting['is_iyzipay_enabled'] == 'on'): ?>
                                <?php if(\Auth::user()->is_trial_done == 0 && $plan->id != 1): ?>
                                    <div class="col-auto mb-2">
                                        <a href="<?php echo e(route('take.a.plan.trial', $plan->id)); ?>"
                                            id="trial_<?php echo e($plan->id); ?>"
                                            class="btn btn-xs btn-primary btn-icon rounded-pill">
                                            <span class="btn-inner--icon"><i class="fas fa-cart-plus"></i></span>
                                            <span class="btn-inner--text"><?php echo e(__('Take a Trial')); ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <?php if(($plan->id == 1) && $plan->price <= 0): ?>
                                    <button class="btn btn-sm btn-neutral mb-3">
                                        <a><?php echo e(__('Lifetime')); ?></a>
                                    </button>
                                <?php else: ?>
                                    <?php if($plan->id != \Auth::user()->plan && $plan->price >= 0): ?>
                                        <div class="col-auto mb-2">
                                            <a href="<?php echo e(route('payment', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))); ?>"
                                                id="interested_plan_<?php echo e($plan->id); ?>"
                                                class="btn btn-xs btn-primary btn-icon rounded-pill">
                                                <span class="btn-inner--icon"><i class="fas fa-cart-plus"></i></span>
                                                <span class="btn-inner--text"><?php echo e(__('Subscribe')); ?></span>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($plan->id != 1): ?>
                                        <div class="col-auto mb-2">
                                            <?php if(\Auth::user()->requested_plan != $plan->id): ?>
                                                <a href="#" class="btn btn-xs btn-primary btn-icon rounded-pill"
                                                    data-url="<?php echo e(route('request.view', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))); ?>"
                                                    data-ajax-popup="true" data-title="<?php echo e(__('Manually Request')); ?>">
                                                    <span class="btn-inner--icon"><i class="fas fa-share"></i></span>
                                                    <span class="btn-inner--text"><?php echo e(__('Manually Request')); ?></span>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php echo e(route('request.cancel', \Auth::user()->id)); ?>"
                                                    class="btn btn-xs btn-primary btn-icon rounded-pill">
                                                    <span class="btn-inner--icon"><i class="fas fa-times"></i></span>
                                                    <span class="btn-inner--text"><?php echo e(__('Cancel Request')); ?></span>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH /home/u71832/domains/taskmanager.tenyne.com/public_html/resources/views/plans/planlist.blade.php ENDPATH**/ ?>