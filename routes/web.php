<?php

use App\Http\Controllers\AamarpayController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\BanktransferController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTaskController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\TimesheetController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PlanRequestController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PaystackPaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\FlutterwavePaymentController;
use App\Http\Controllers\RazorpayPaymentController;
use App\Http\Controllers\PaytmPaymentController;
use App\Http\Controllers\MercadoPaymentController;
use App\Http\Controllers\MolliePaymentController;
use App\Http\Controllers\SkrillPaymentController;
use App\Http\Controllers\ZoomMeetingController;
use App\Http\Controllers\CoingatePaymentController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\ProjectReportController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\IyziPayController;
use App\Http\Controllers\PayfastController;
use App\Http\Controllers\TimeTrackerController;
use App\Http\Controllers\PaymentWallPaymentController;
use App\Http\Controllers\ToyyibpayController;
use App\Http\Controllers\LoginDetailController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\NotificationTemplatesController;
use App\Http\Controllers\SspayController;
use App\Http\Controllers\AiTemplateController;
use App\Http\Controllers\BenefitPaymentController;
use App\Http\Controllers\CashfreeController;
use App\Http\Controllers\PaytabController;
use App\Http\Controllers\PaytrController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Auth::routes();

// Route::get('/register/{lang?}', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::get('/login/{lang?}', 'Auth\LoginController@showLoginForm')->name('login');
// Route::get('/reset/password/{lang?}', 'Auth\LoginController@showLinkRequestForm')->name('password.request');

require __DIR__.'/auth.php';


// Route::get('/verify-email', [EmailVerificationPromptController::class,'__invoke',])->name('verification.notice');
// Route::get('/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->name('verification.verify')->middleware('auth');
// Route::get('/email/verification-notification', [EmailVerificationNotificationController::class,'store',])->name('verification.send');

Route::get('/verify-email/{lang?}',[EmailVerificationPromptController::class,'showemailform'])
                ->middleware(['XSS','auth'])
                ->name('verification.notice');

Route::get('/', [HomeController::class, 'landingPage'])->middleware(['XSS']);


Route::get('/invoices/pay/{id}', [InvoiceController::class, 'pay'])->name('pay.invoice')->middleware(['XSS']);

Route::get('/projects/{id}/task/{tid}', [ProjectController::class,'shows'])->name('projects.task.show')->middleware(['XSS']);
// Route::any('/projects/link/{id}',[ProjectController::class,'projectlink'])->name('projects.link')->middleware(['XSS']);
Route::get('/timesheet-table-view',[TimesheetController::class,'filterTimesheetTableView'])->name('filter.timesheet.table.view')->middleware(['XSS']);
Route::any('/projects/link/{id}/{lang?}',[ProjectController::class,'projectlink'])->name('projects.link')->middleware(['XSS']);
Route::get('invoices/pay/pdf/{id}', [InvoiceController::class, 'pdffrominvoice'])->name('invoice.download.pdf');

Route::post('/invoice-pay-with-bank', [BanktransferController::class, 'invoicePayWithbank'])->name('invoice.pay.with.bank')->middleware(['XSS']);
Route::post('/invoice/status/{id}', [BanktransferController::class, 'invoicebankstatus'])->name('invoice.status');

Route::post('/invoice-pay-with-stripe/{id}', [StripePaymentController::class, 'invoicePayWithStripe'])->name('invoice.pay.with.stripe');
Route::any('/invoice-pay-with-stripe/{invoice_id}', [StripePaymentController::class, 'getInvociePaymentStatus'])->name('invoice.stripe');

Route::get('/{id}/{amount}/get-payment-status', [PaypalController::class, 'clientGetPaymentStatus'])->name('client.get.payment.status');
Route::post('/{id}/pay-with-paypal', [PaypalController::class, 'clientPayWithPaypal'])->name('client.pay.with.paypal');

Route::get('/invoice/paystack/{pay_id}/{invoice_id}', [PaystackPaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.paystack')->middleware(['XSS']);
Route::post('/invoice-pay-with-paystack', [PaystackPaymentController::class, 'invoicePayWithPaystack'])->name('invoice.pay.with.paystack')->middleware(['XSS']);

Route::post('/invoice-pay-with-flaterwave', [FlutterwavePaymentController::class, 'invoicePayWithFlutterwave'])->name('invoice.pay.with.flaterwave');
Route::get('/invoice/flaterwave/{txref}/{invoice_id}', [FlutterwavePaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.flaterwave');

Route::post('/invoice-pay-with-razorpay', [RazorpayPaymentController::class, 'invoicePayWithRazorpay'])->middleware('XSS')->name('invoice.pay.with.razorpay');
Route::get('/invoice/razorpay/{txref}/{invoice_id}', [RazorpayPaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.razorpay');

Route::post('/invoice-pay-with-mercado', [MercadoPaymentController::class, 'invoicePayWithMercado'])->middleware('XSS')->name('invoice.pay.with.mercado');
Route::any('/invoice/mercado/{invoice}', [MercadoPaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.mercado.callback');

Route::post('/invoice-pay-with-paytm', [PaytmPaymentController::class, 'invoicePayWithPaytm'])->middleware('XSS')->name('invoice.pay.with.paytm');
Route::post('/invoice/paytm/{invoice}', [PaytmPaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.paytm');

Route::post('/invoice-pay-with-coingate', [CoingatePaymentController::class, 'invoicePayWithCoingate'])->middleware('XSS')->name('invoice.pay.with.coingate');
Route::get('/invoice/coingate/{invoice}', [CoingatePaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.coingate');

Route::post('/invoice-with-payfast', [PayfastController::class,'invoicepaywithpayfast'])->name('invoice.with.payfast');
Route::get('/invoice-payfast-status/{invoice_id}', [PayfastController::class,'invoicepayfaststatus'])->name('invoice.payfast.status');

Route::post('/paymentwall', [PaymentWallPaymentController::class, 'invoicepaymentwall'])->middleware('XSS')->name('invoice.paymentwallpayment');
Route::post('/invoice-pay-with-paymentwall/{plan}', [PaymentWallPaymentController::class, 'invoicePayWithPaymentwall'])->middleware('XSS')->name('invoice.pay.with.paymentwall');
Route::get('/invoice/{flag}/{invoice}', [PaymentWallPaymentController::class, 'invoiceerror'])->name('error.invoice.show');

Route::post('/invoice-pay-with-toyyibpay/{invoice_id}',[ToyyibpayController::class, 'invoicepaywithtoyyibpay'])->name('invoice.pay.with.toyyibpay')->middleware('XSS');
Route::get('/invoice/toyyibpay/{invoice}/{amt}', [ToyyibpayController::class, 'invoicetoyyibpaystatus'])->name('invoice.toyyibpay');

Route::post('/invoice-pay-with-skrill', [SkrillPaymentController::class, 'invoicePayWithSkrill'])->middleware('XSS')->name('invoice.pay.with.skrill');
Route::get('/invoice/skrill/{invoice}', [SkrillPaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.skrill');

Route::post('/invoice-pay-with-mollie', [MolliePaymentController::class, 'invoicePayWithMollie'])->middleware('XSS')->name('invoice.pay.with.mollie');
Route::get('/invoice/mollie/{invoice}', [MolliePaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.mollie');

Route::post('/invoice-pay-with-iyzipay', [IyziPayController::class,'invoicepaywithiyzipay'])->name('invoice.pay.with.iyzipay');
Route::post('iyzipay/callback/{invoice}/{amount}', [IyzipayController::class, 'getInvoiceiyzipayCallback'])->name('iyzipay.invoicepayment.callback');

Route::post('/invoice-pay-with-sspay/{invoice_id}', [SspayController::class,'invoicepaywithsspaypay'])->name('invoice.pay.with.sspay');
Route::get('/invoice/sspay/{invoice}/{amount}', [SspayController::class,'getInvoicePaymentStatus'])->name('invoice.sspay');

Route::post('/invoice-pay-with-paytab', [PaytabController::class, 'invoicePayWithpaytab'])->name('invoice.pay.with.paytab');
Route::any('/invoice-paytab-success/{invoice}', [PaytabController::class, 'getInvoicePaymentStatus'])->name('invoice.paytab.success');

Route::post('/invoice-pay-with-benefit', [BenefitPaymentController::class, 'invoicepayWithbenefit'])->name('invoice.pay.with.benefit');
Route::any('/invoice-benefit-callback/{invoice}',[BenefitPaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.benefit.callback');

Route::post('/invoice-pay-with-cashfree', [CashfreeController::class, 'invoicepayWithCashfree'])->name('invoice.pay.with.cashfree');
Route::any('/invoice-cashfree-callback',[CashfreeController::class, 'getInvoicePaymentStatus'])->name('invoice.cashfree.success');

Route::post('invoice-pay-with-aamarpay', [AamarpayController::class, 'invoicepaywithaamarpay'])->name('invoice.pay.with.aamarpay');
Route::any('aamarpay-invoice/success/{data}', [AamarpayController::class, 'getInvoicePaymentStatus'])->name('invoice.pay.aamarpay.success');

Route::post('invoice-pay-with-paytr', [PaytrController::class, 'invoicepaywithpaytr'])->name('invoice.pay.with.paytr');
Route::any('paytr-invoice/success', [PaytrController::class, 'getInvoicePaymentStatus'])->name('invoice.pay.paytr.success');


Route::resource('invoices', InvoiceController::class)->middleware(['auth','XSS']);

// Route::get('/{id}/{amount}/get-payment-status', [PaypalController::class, 'clientGetPaymentStatus'])->name('client.get.payment.status')->middleware(['XSS']);

Route::group(['middleware' => ['verified']], function (){


Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware(['auth','XSS']);
Route::get('/check', [HomeController::class, 'check'])->middleware(['XSS']);
Route::get('/checks', [HomeController::class, 'homeCheck'])->middleware(['XSS']);
Route::get('dashboard-view', [HomeController::class, 'filterView'])->middleware(['XSS'])->name('dashboard.view');

// User Module

Route::controller(UserController::class)->middleware('auth','XSS')->group(function(){

    Route::get('users/{view?}', 'index')->name('users');
    Route::get('users-view', 'filterUserView')->name('filter.user.view');
    Route::get('checkuserexists', 'checkUserExists')->name('user.exists');
    Route::get('profile', 'profile')->name('profile');
    Route::post('/profile', 'updateProfile')->name('update.profile');
    Route::get('user/info/{id}', 'userInfo')->name('users.info');
    Route::get('user/{id}/info/{type}', 'getProjectTask')->name('user.info.popup');
    Route::delete('users/{id}', 'destroy')->name('user.destroy');

});
// End User Module


// User Last Login
    Route::resource('logindetails', LoginDetailController::class)->middleware(['auth','XSS']);
// End Last Login

//Webhook Route
Route::resource('webhook', WebhookController::class)->middleware('XSS','auth');
// End WebHook Route


// Language Module
Route::controller(UserController::class)->middleware('auth','XSS')->group(function(){

    Route::POST('disable-language','disableLang')->name('disablelanguage');
    Route::GET('/lang/create', 'createLang')->name('lang.create');
    Route::GET('/language/lang/{lang}', 'lang')->name('lang');
    Route::POST('/lang/create', 'storeLang')->name('lang.store');
    Route::POST('/lang/data/{lang}', 'storeLangData')->name('lang.store.data');
    Route::get('/lang/change/{lang}', 'changeLang')->name('lang.change');
    Route::delete('/lang/{id}', 'destroyLang')->name('lang.destroy');
    Route::get('/search', 'search')->name('search.json');

});
// End Language Module


// Milestone Module
Route::controller(ProjectController::class)->middleware('auth','XSS')->group(function(){

    Route::get('projects/{id}/milestone', 'milestone')->name('project.milestone');
    Route::post('projects/{id}/milestone', 'milestoneStore')->name('project.milestone.store');
    Route::get('projects/milestone/{id}/edit', 'milestoneEdit')->name('project.milestone.edit');
    Route::post('projects/milestone/{id}', 'milestoneUpdate')->name('project.milestone.update');
    Route::delete('projects/milestone/{id}', 'milestoneDestroy')->name('project.milestone.destroy');
    Route::get('projects/milestone/{id}/show', 'milestoneShow')->name('project.milestone.show');

});
// End Milestone


// Project Module

Route::controller(ProjectController::class)->middleware('auth','XSS')->group(function(){

    Route::get('invite-project-member/{id}', 'inviteMemberView')->name('invite.project.member.view');
    Route::post('invite-project-user-member', 'inviteProjectUserMember')->name('invite.project.user.member');
    Route::get('project/{view?}', 'index')->name('projects.list');
    Route::get('projects-view', 'filterProjectView')->name('filter.project.view');
    Route::post('projects/{id}/store-stages', 'storeProjectTaskStages')->name('project.stages.store');
    Route::patch('remove-user-from-project/{project_id}/{user_id}', 'removeUserFromProject')->name('remove.user.from.project');
    Route::get('projects-users', 'loadUser')->name('project.user');
    Route::get('projects/{id}/gantt/{duration?}', 'gantt')->name('projects.gantt');
    Route::post('projects/{id}/gantt', 'ganttPost')->name('projects.gantt.post');
    Route::get('projects/{id}/user/{uid}/permission', 'userPermission')->name('projects.user.permission');
    Route::post('projects/{id}/user/{uid}/permission', 'userPermissionStore')->name('projects.user.permission.store');

});
Route::resource('projects', ProjectController::class)->middleware(['XSS','auth']);

Route::get('/projects/copy/{id}',[ProjectController::class,'copyprojects'])->name('projects.copy')->middleware(['auth','XSS']);
Route::post('/projects/copy/store/{id}',[ProjectController::class,'copyprojectsstore'])->name('projects.copy.store')->middleware(['auth','XSS']);
Route::post('/projects/copy/link/{id}',[ProjectController::class,'copylinksetting'])->name('projects.copy.link')->middleware(['auth','XSS']);



Route::get('add-user', [UserController::class, 'addUser'])->name('add.user.view')->middleware('XSS','auth');
Route::get('check-user-exist', [UserController::class, 'addUserExists'])->name('add.user.exists')->middleware('XSS','auth');
Route::post('add-user-member', [UserController::class, 'addUserMember'])->name('add.user.member')->middleware('XSS','auth');
Route::any('user-reset-password/{id}', [UserController::class, 'userPassword'])->name('user.reset');
Route::post('user-reset-password/{id}', [UserController::class, 'userPasswordReset'])->name('user.password.update');

Route::controller(ProjectTaskController::class)->middleware('auth','XSS')->group(function(){
    Route::get('stage/{id}/tasks', 'getStageTasks')->name('stage.tasks');
    Route::get('/projects/{id}/task', 'index')->name('projects.tasks.index');
    Route::get('/projects/{pid}/task-create/{sid}', 'create')->name('projects.tasks.create');
    Route::post('/projects/{pid}/task-store/{sid}', 'store')->name('projects.tasks.store');
    Route::get('/projects/{id}/task/{tid}/show', 'show')->name('projects.tasks.show');
    Route::get('/projects/{id}/task/{tid}/edit', 'edit')->name('projects.tasks.edit');
    Route::post('/projects/{id}/task/update/{tid}', 'update')->name('projects.tasks.update');
    Route::delete('/projects/{id}/task/{tid}', 'destroy')->name('projects.tasks.destroy');
    Route::patch('/projects/{id}/task/order', 'taskOrderUpdate')->name('tasks.update.order');
    Route::patch('update-task-priority-color', 'updateTaskPriorityColor')->name('update.task.priority.color');

});

Route::post('/projects/{id}/comment/{tid}/file', [ProjectTaskController::class, 'commentStoreFile'])->name('comment.store.file');
Route::delete('/projects/{id}/comment/{tid}/file/{fid}', [ProjectTaskController::class, 'commentDestroyFile'])->name('comment.destroy.file');
Route::post('/projects/{id}/comment/{tid}', [ProjectTaskController::class, 'commentStore'])->name('comment.store');
Route::delete('/projects/{id}/comment/{tid}/{cid}', [ProjectTaskController::class, 'commentDestroy'])->name('comment.destroy');
Route::post('/projects/{id}/checklist/{tid}', [ProjectTaskController::class, 'checklistStore'])->name('checklist.store');
Route::post('/projects/{id}/checklist/update/{cid}', [ProjectTaskController::class, 'checklistUpdate'])->name('checklist.update');
Route::delete('/projects/{id}/checklist/{cid}', [ProjectTaskController::class, 'checklistDestroy'])->name('checklist.destroy');
Route::post('/projects/{id}/change/{tid}/fav', [ProjectTaskController::class, 'changeFav'])->name('change.fav');
Route::post('/projects/{id}/change/{tid}/complete', [ProjectTaskController::class, 'changeCom'])->name('change.complete');
Route::post('/projects/{id}/change/{tid}/progress', [ProjectTaskController::class, 'changeProg'])->name('change.progress');

Route::controller(ProjectTaskController::class)->middleware('auth','XSS')->group(function(){

    Route::get('/projects/task/{id}/get', 'taskGet')->name('projects.tasks.get');
    Route::get('taskboard/{view?}', 'taskBoard')->name('taskBoard.view');
    Route::get('taskboard-view', 'taskboardView')->name('project.taskboard.view');
    Route::get('/calendar/{id}/show', 'calendarShow')->name('task.calendar.show');
    Route::post('/calendar/{id}/drag', 'calendarDrag')->name('task.calendar.drag');
    Route::get('calendar/{task}/{pid?}', 'calendarView')->name('task.calendar');
    Route::post('project/task/timer', 'taskStart')->name('project.task.timer');
});

// plan payfast
Route::post('payfast-plan', [PayfastController::class, 'index'])->name('payfast.payment')->middleware(['auth']);
Route::get('payfast-plan/{success}', [PayfastController::class, 'success'])->name('payfast.payment.success')->middleware(['auth']);


// Project Expense Module
Route::controller(ExpenseController::class)->middleware('auth','XSS')->group(function(){

    Route::get('/projects/{id}/expense', 'index')->name('projects.expenses.index');
    Route::get('/projects/{pid}/expense/create', 'create')->name('projects.expenses.create');
    Route::post('/projects/{pid}/expense/store', 'store')->name('projects.expenses.store');
    Route::get('/projects/{id}/expense/{eid}/edit', 'edit')->name('projects.expenses.edit');
    Route::post('/projects/{id}/expense/{eid}', 'update')->name('projects.expenses.update');
    Route::delete('/projects/{eid}/expense/', 'destroy')->name('projects.expenses.destroy');
    Route::get('/expense-list', 'expenseList')->name('expense.list');
});

// Project Timesheet
Route::controller(TimesheetController::class)->middleware('auth','XSS')->group(function(){

    Route::get('append-timesheet-task-html', 'appendTimesheetTaskHTML')->name('append.timesheet.task.html');
    Route::get('timesheet-view', 'filterTimesheetView')->name('filter.timesheet.view');
    Route::get('timesheet-list', 'timesheetList')->name('timesheet.list');
    Route::get('timesheet-list-get', 'timesheetListGet')->name('timesheet.list.get');
    Route::get('/project/{id}/timesheet', 'timesheetView')->name('timesheet.index');
    Route::get('/project/{id}/timesheet/create', 'timesheetCreate')->name('timesheet.create');
    Route::post('/project/timesheet', 'timesheetStore')->name('timesheet.store');
    Route::get('/project/timesheet/{project_id}/edit/{timesheet_id}', 'timesheetEdit')->name('timesheet.edit');
    Route::post('/project/timesheet/update/{timesheet_id}', 'timesheetUpdate')->name('timesheet.update');
    Route::delete('/project/timesheet/{timesheet_id}', 'timesheetDestroy')->name('timesheet.destroy');

});

Route::controller(UserController::class)->middleware('auth','XSS')->group(function(){

    Route::any('/todo/create', 'todo_store')->name('todo.store');
    Route::post('/todo/{id}/update', 'todo_update')->name('todo.update');
    Route::delete('/todo/{id}', 'todo_destroy')->name('todo.destroy');
});
Route::get('/change/mode', [UserController::class, 'changeMode'])->name('change.mode');


// Site Setting

Route::controller(SettingsController::class)->group(function(){

    Route::get('/settings', 'index')->name('settings')->middleware('auth','XSS');
    Route::post('/settings', 'store')->name('settings.store')->middleware('auth');
    Route::post('/emailCheck', 'testEmail')->name('test.email')->middleware('auth','XSS');
    Route::post('/emailCheck/send', 'testEmailSend')->name('test.email.send')->middleware('auth','XSS');

});
Route::post('setting/seo',[SettingsController::class,'seosetting'])->name('seo.settings');
Route::post('cookie-setting', [SettingsController::class, 'saveCookieSettings'])->name('cookie.setting');
Route::any('/cookie-consent', [SettingsController::class,'CookieConsent'])->name('cookie-consent');

Route::get('/config-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return redirect()->back()->with('success', 'Clear Cache successfully.');
});

// Email Templates
Route::controller(EmailTemplateController::class)->middleware('auth','XSS')->group(function(){

    Route::get('mail_template_lang/{id}/{lang?}', 'manageEmailLang')->name('manage.email.language');
    Route::post('email_template_store/{pid}', 'storeEmailLang')->name('store.email.language');
    Route::post('email_template_status', 'updateStatus')->name('status.email.language');
    Route::get('email_template', 'index')->name('email_template.index');
    Route::post('email_template', 'store')->name('email_template.store');
    Route::post('email_template/update/{id}', 'update')->name('email_template.update');
});
// End Email Templates


// Plan Request Module
Route::controller(PlanRequestController::class)->middleware('auth','XSS')->group(function(){

    Route::get('plan_request', 'index')->name('plan_request.index');
    Route::get('request_frequency/{id}', 'requestView')->name('request.view');
    Route::get('request_send/{id}/{duration}', 'userRequest')->name('send.request');
    Route::get('request_response/{id}/{response}', 'acceptRequest')->name('response.request');
    Route::get('request_cancel/{id}', 'cancelRequest')->name('request.cancel');
});
// End Plan Request Module

// Plan Route

Route::get('user/{id}/plan', [UserController::class, 'upgradePlan'])->name('plan.upgrade')->middleware('XSS','auth');
Route::get('user/{id}/plan/{pid}', [UserController::class, 'activePlan'])->name('plan.active')->middleware('XSS','auth');
Route::resource('plans', PlanController::class)->middleware('XSS','auth');
Route::get('order/destory/{id}', [PlanController::class, 'orderdestory'])->name('order.destory')->middleware('XSS','auth');

Route::get('/orders', [PlanController::class, 'orderList'])->name('order_list')->middleware('XSS','auth');

// end Plan route

// Stripe
Route::get('/payment/{code}', [StripePaymentController::class, 'payment'])->name('payment')->middleware('XSS','auth');
// end stripe

// Coupon
Route::get('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('apply.coupon')->middleware('XSS','auth');
Route::resource('coupons', CouponController::class)->middleware('XSS','auth');
// end coupon

// Invoice Module



Route::controller(InvoiceController::class)->middleware('auth','XSS')->group(function(){

    Route::post('/project/invoice', 'jsonClient')->name('project.client.json');
    Route::get('invoices/{id}/products/add', 'productAdd')->name('invoices.products.add');
    Route::post('invoices/{id}/products/store', 'productStore')->name('invoices.products.store');
    Route::delete('invoices/{id}/products/{pid}', 'productDelete')->name('invoices.products.delete');
    Route::get('invoices-payments', 'payments')->name('invoices.payments');
    Route::get('invoices/{id}/payments/create', 'paymentAdd')->name('invoices.payments.create');
    Route::post('invoices/{id}/payments/store', 'paymentStore')->name('invoices.payments.store');
});

//Client Invoice Payment
Route::post('/invoices/client/{id}/payment', [InvoiceController::class, 'addPayment'])->name('client.invoice.payment')->middleware('XSS','auth');


// end invoice module

// Invoice Mail Send
Route::get('invoices/{id}/get_invoice', [InvoiceController::class, 'printInvoice'])->name('get.invoice')->middleware(['XSS']);
Route::get('invoice/{id}/payment/reminder', [InvoiceController::class, 'paymentReminder'])->name('invoice.payment.reminder');
Route::get('invoice/{id}/sent/mail', [InvoiceController::class, 'sent'])->name('invoice.sent');

// Client Side Invoice Mail Send
Route::get('invoice/{id}/custom-send/mail', [InvoiceController::class, 'customMail'])->name('invoice.custom.send')->middleware(['auth','XSS']);
Route::post('invoice/{id}/custom-mail', [InvoiceController::class, 'customMailSend'])->name('invoice.custom.mail')->middleware(['auth','XSS']);

// Invoice Template
Route::get('template/invoice', [InvoiceController::class, 'templateSetting'])->name('invoice.template.setting')->middleware(['auth','XSS']);
Route::post('/invoice-setting', [InvoiceController::class, 'saveTemplateSettings'])->name('invoice.template.store')->middleware(['auth','XSS']);
Route::get('/invoices/preview/{template}/{color}', [InvoiceController::class, 'previewInvoice'])->name('invoice.preview');

// Tax Module
Route::resource('taxes', TaxController::class)->middleware('XSS','auth');

// end tax

// ===================================================================================================================

Route::post('/plan-pay-with-banktransfer', [BanktransferController::class, 'planPayWithBanktransfer'])->name('plan.pay.with.banktransfer')->middleware(['auth','XSS']);
Route::get('bank_transfer_show/{id}', [BanktransferController::class, 'bank_transfer_show'])->name('bank_transfer.show');
Route::get('invoice_bank_transfer_show/{id}', [BanktransferController::class, 'invoice_bank_transfer_show'])->name('invoice_bank_transfer.show');
Route::post('status_edit/{id}', [BanktransferController::class, 'StatusEdit'])->name('status.edit');
Route::get('banktransfer/destory/{id}', [BanktransferController::class, 'banktransferdestory'])->name('banktransfer.destory')->middleware('XSS','auth');
Route::get('invoicebank/destory/{id}', [BanktransferController::class, 'invoicebankPaymentDestroy'])->name('invoicebank.destory');

Route::post('/plan-pay-with-paypal', [PaypalController::class, 'planPayWithPaypal'])->name('plan.pay.with.paypal')->middleware(['auth','XSS']);
Route::get('/{id}/{amount}/{frequency}/{coupon}/payment_settings/plan-get-payment-status', [PaypalController::class, 'planGetPaymentStatus'])->name('plan.get.payment.status')->middleware(['auth','XSS']);

Route::get('/stripe-payment-status', [StripePaymentController::class, 'planGetStripePaymentStatus'])->name('stripe.payment.status');
Route::post('/stripe/payment', [StripePaymentController::class, 'stripePost'])->name('stripe.post');
Route::post('/webhook-stripe', [StripePaymentController::class, 'webhookStripe'])->name('webhook.stripe');

Route::get('/take-a-plan-trial/{plan_id}', [PlanController::class, 'takeAPlanTrial'])->name('take.a.plan.trial')->middleware(['auth','XSS']);
Route::get('/change-user-plan/{plan_id}', [PlanController::class, 'changeUserPlan'])->name('change.user.plan')->middleware(['auth','XSS']);

//================================= Plan Payment Gateways Route ====================================//

Route::post('/plan-pay-with-paystack', [PaystackPaymentController::class, 'planPayWithPaystack'])->name('plan.pay.with.paystack')->middleware(['auth','XSS']);
Route::get('/plan/paystack/{pay_id}/{plan_id}', [PaystackPaymentController::class, 'getPaymentStatus'])->name('plan.paystack');

Route::post('/plan-pay-with-flaterwave', [FlutterwavePaymentController::class, 'planPayWithFlutterwave'])->name('plan.pay.with.flaterwave')->middleware(['auth','XSS']);
Route::get('/plan/flaterwave/{txref}/{plan_id}', [FlutterwavePaymentController::class, 'getPaymentStatus'])->name('plan.flaterwave');

Route::post('/plan-pay-with-razorpay', [RazorpayPaymentController::class, 'planPayWithRazorpay'])->name('plan.pay.with.razorpay')->middleware(['auth','XSS']);
Route::get('/plan/razorpay/{txref}/{plan_id}', [RazorpayPaymentController::class, 'getPaymentStatus'])->name('plan.razorpay');

Route::post('/plan-pay-with-paytm', [PaytmPaymentController::class, 'planPayWithPaytm'])->name('plan.pay.with.paytm')->middleware(['auth','XSS']);
Route::post('/plan/paytm/{plan}', [PaytmPaymentController::class, 'getPaymentStatus'])->name('plan.paytm');

Route::post('/plan-pay-with-mercado', [MercadoPaymentController::class, 'planPayWithMercado'])->name('plan.pay.with.mercado')->middleware(['auth','XSS']);
Route::get('/plan/mercado/{plan}', [MercadoPaymentController::class, 'getPaymentStatus'])->name('plan.mercado');

Route::post('/plan-pay-with-mollie', [MolliePaymentController::class, 'planPayWithMollie'])->name('plan.pay.with.mollie')->middleware(['auth','XSS']);
Route::get('/plan/mollie/{plan}', [MolliePaymentController::class, 'getPaymentStatus'])->name('plan.mollie');

Route::post('/plan-pay-with-skrill', [SkrillPaymentController::class, 'planPayWithSkrill'])->name('plan.pay.with.skrill')->middleware(['auth','XSS']);
Route::get('/plan/skrill/{plan}', [SkrillPaymentController::class, 'getPaymentStatus'])->name('plan.skrill');

Route::post('/plan-pay-with-coingate', [CoingatePaymentController::class, 'planPayWithCoingate'])->name('plan.pay.with.coingate')->middleware(['auth','XSS']);
Route::get('/plan/coingate/{plan}', [CoingatePaymentController::class, 'getPaymentStatus'])->name('plan.coingate');

Route::post('/plan-pay-with-skrill', [SkrillPaymentController::class, 'planPayWithSkrill'])->name('plan.pay.with.skrill')->middleware(['auth','XSS']);

Route::post('/paymentwalls', [PaymentWallPaymentController::class, 'paymentwall'])->name('plan.paymentwallpayment')->middleware(['XSS']);
Route::post('/plan-pay-with-paymentwall/{plan}', [PaymentWallPaymentController::class, 'planPayWithPaymentWall'])->name('plan.pay.with.paymentwall')->middleware(['XSS']);
Route::get('/plan/{flag}', [PaymentWallPaymentController::class, 'planeerror'])->name('error.plan.show');

Route::post('plan/toyyibpay/payment', [ToyyibpayController::class, 'charge'])->name('plan.toyyibpaypayment');
Route::get('plan/status/{planId}/{getAmount}/{couponCode}', [ToyyibpayController::class, 'status'])->name('plan.status');

Route::post('iyzipay/prepare', [IyziPayController::class, 'initiatePayment'])->name('iyzipay.payment.init');
Route::post('iyzipay/callback/plan/{id}/{amount}/{coupan_code?}', [IyzipayController::class, 'iyzipayCallback'])->name('iyzipay.payment.callback');

Route::post('/sspay', [SspayController::class,'SspayPaymentPrepare'])->name('plan.sspaypayment');
Route::get('sspay-payment-plan/{plan_id}/{amount}/{couponCode}', [SspayController::class, 'SspayPlanGetPayment'])->middleware(['auth'])->name('plan.sspay.callback');

//paytab
Route::post('/plan-pay-with-paytab', [PaytabController::class, 'planPayWithpaytab'])->name('plan.pay.with.paytab')->middleware(['auth']);
Route::any('plan-paytab-success/', [PaytabController::class, 'PaytabGetPayment'])->name('plan.paytab.success')->middleware(['auth']);

Route::any('/plan-pay-with-benefit', [BenefitPaymentController::class, 'planPayWithbenefit'])->name('plan.pay.with.benefit');
Route::any('/call_back', [BenefitPaymentController::class, 'call_back'])->name('benefit.call_back');

Route::post('/plan-pay-with-cashfree', [CashfreeController::class, 'planpaywithcashfree'])->name('plan.pay.with.cashfree');
Route::any('cashfree/payments/success', [CashfreeController::class, 'cashfreePaymentSuccess'])->name('cashfreePayment.success');

Route::post('/plan-pay-with-aamarpay', [AamarpayController::class, 'planPayWithaamarpay'])->name('plan.pay.with.aamarpay');
Route::any('/aamarpay/success/{data}', [AamarpayController::class, 'aamarpaysuccess'])->name('pay.aamarpay.success');

Route::post('/plan-pay-with-paytr', [PaytrController::class, 'planPayWithpaytr'])->name('plan.pay.with.paytr');
Route::any('/paytr/success', [PaytrController::class, 'paytrsuccess'])->name('pay.paytr.success');

//================================= End Plan Payment Gateways Route ====================================//

// Plan Request Module

Route::get('plan_request', [PlanRequestController::class, 'index'])->name('plan_request.index')->middleware(['auth','XSS']);
Route::get('request_frequency/{id}', [PlanRequestController::class, 'requestView'])->name('request.view')->middleware(['auth','XSS']);
// Route::get('request_send/{id}/duration', [PlanRequestController::class, 'userRequest'])->name('send.request')->middleware(['auth','XSS']);
Route::get('request_response/{id}/{response}', [PlanRequestController::class, 'acceptRequest'])->name('response.request')->middleware(['auth','XSS']);
Route::get('request_cancel/{id}', [PlanRequestController::class, 'cancelRequest'])->name('request.cancel')->middleware(['auth','XSS']);

// End Plan Request Module




//===========================================Import-Exort=================================================//

Route::get('export/project', [ProjectController::class, 'export'])->name('project.export');
Route::get('import/project/file', [ProjectController::class, 'importFile'])->name('project.file.import');
Route::post('import/project', [ProjectController::class, 'import'])->name('project.import');

Route::get('export/members', [UserController::class, 'export'])->name('members.export');
Route::get('import/members/file', [UserController::class, 'importFile'])->name('members.file.import');
Route::post('import/members', [UserController::class, 'import'])->name('members.import');

Route::get('import/invoice/file', [InvoiceController::class, 'importFile'])->name('invoice.file.import');
Route::post('import/invoice', [InvoiceController::class, 'import'])->name('invoice.import');

//===========================================screenshort=========================================//

Route::delete('tracker/{tid}/destroy', [TimeTrackerController::class, 'Destroy'])->name('tracker.destroy');
Route::get('time-tracker', [TimeTrackerController::class, 'index'])->name('time.tracker')->middleware(['auth','XSS']);
Route::get('projects/{id}/time-tracker', [TimeTrackerController::class, 'projectTracks'])->name('projects.time.tracker')->middleware(['auth','XSS']);
Route::post('tracker/image-view', [TimeTrackerController::class, 'getTrackerImages'])->name('tracker.image.view');
Route::delete('tracker/image-remove', [TimeTrackerController::class, 'removeTrackerImages'])->name('tracker.image.remove');

//===========================================ZoomMeeting=============================================//

Route::any('/setting/saveZoomSettings', [SettingsController::class, 'saveZoomSettings'])->name('setting.ZoomSettings')->middleware(['auth','XSS']);
Route::any('zoommeeting/calendar', [ZoomMeetingController::class, 'calendar'])->name('zoommeeting.calendar')->middleware(['auth','XSS']);
Route::any('calendar/zoommeeting/data', [ZoomMeetingController::class, 'get_event_data'])->name('calendar.zoommeeting')->middleware(['auth','XSS']);

Route::get('/zoom/projects/select/{id}', [ZoomMeetingController::class, 'projectwiseclient'])->name('zoom.projects.select');
Route::resource('zoommeeting', ZoomMeetingController::class)->middleware(['XSS','auth']);

//=======================================Slack Notification============================================//
Route::post('setting/slack', [SettingsController::class, 'slack'])->name('slack.setting');

//=======================================Telegram Notification============================================//
Route::post('setting/Telegram', [SettingsController::class, 'telegram'])->name('telegram.setting');


//=======================================calendar Notification============================================//
Route::post('setting/calendar', [SettingsController::class, 'saveGoogleCalenderSettings'])->name('calendar.setting');


//==================================Recaptcha================================
// Route::post('/recaptcha-settings',['as' => 'recaptcha.settings.store','uses' =>'SettingsController@recaptchaSettingStore'])->middleware(['auth','XSS']);
Route::post('/recaptcha-settings', [SettingsController::class, 'recaptchaSettingStore'])->middleware('XSS','auth')->name('recaptcha.settings.store');


// ==================================================================================================================
// Contracttype Module
Route::resource('contract', ContractTypeController::class)->middleware('XSS','auth');

// End contract

//==================================== Contract ======================================//

Route::resource('contractclient', ContractController::class)->middleware('XSS','auth');

Route::POST('get-projects', [ContractController::class, 'clientByProject'])->middleware('XSS','auth')->name('project.by.user.id');
Route::post('contract/{id}/contract_description', [ContractController::class, 'descriptionStore'])->middleware('auth')->name('contract.contract_description.store');

// contract-attachments/file upload
Route::post('/contract/{id}/file', [ContractController::class, 'fileUpload'])->middleware('XSS','auth')->name('contracts.file.upload');
Route::get('/contract/{id}/file/{fid}', [ContractController::class, 'fileDownload'])->middleware('XSS','auth')->name('contracts.file.download');
Route::get('file/delete/{id}', [ContractController::class, 'fileDelete'])->middleware('XSS','auth')->name('contracts.file.delete');

// contract-comment
Route::post('/contract/{id}/comment', [ContractController::class, 'commentStore'])->middleware('auth')->name('comment_store.store');
Route::get('/contract/{id}/comment', [ContractController::class, 'commentDestroy'])->name('comment_store.destroy');

// contract-notes
Route::post('/contract/{id}/notes', [ContractController::class, 'noteStore'])->middleware('auth')->name('note_store.store');
Route::get('/contract/{id}/notes', [ContractController::class, 'noteDestroy'])->middleware('auth')->name('note_store.destroy');

// contract-copy
Route::get('/contract-copy/{id}', [ContractController::class, 'copycontract'])->middleware('XSS','auth')->name('contracts.copy');
Route::post('/contract/copy/store/{id}', [ContractController::class, 'copycontractstore'])->middleware('XSS','auth')->name('contracts.copy.store');

// contract-download
Route::get('contract/pdf/{id}', [ContractController::class, 'pdffromcontract'])->name('contract.download.pdf');
Route::get('contract/{id}/get_contract', [ContractController::class, 'printContract'])->name('get.contract');

// signature
Route::get('/signature/{id}', [ContractController::class, 'signature'])->middleware('XSS','auth')->name('signature');
Route::post('/signaturestore', [ContractController::class, 'signatureStore'])->middleware('XSS','auth')->name('signaturestore');

// Email
Route::get('/contract/{id}/mail', [ContractController::class, 'sendmailContract'])->middleware('XSS','auth')->name('send.mail.contract');
Route::post('/contract_status_edit/{id}', [ContractController::class, 'contract_status_edit'])->middleware('XSS','auth')->name('contract.status');

Route::resource('notification-templates', NotificationTemplatesController::class)->middleware(['auth','XSS',]);
Route::get('notification-templates/{id?}/{lang?}', [NotificationTemplatesController::class, 'index'])->name('notification-templates.index');

////**===================================== Project Reports ==================================================////

Route::resource('/report_project', ProjectReportController::class)->middleware('XSS','auth');


Route::post('/report_project_data', [ProjectReportController::class, 'ajax_data'])->middleware('XSS','auth')->name('projects.ajax');
Route::post('/report_project/{id}', [ProjectReportController::class, 'show'])->middleware('XSS','auth')->name('project_report.show');
Route::post('/report_project/tasks/{id}', [ProjectReportController::class, 'ajax_tasks_report'])->middleware('XSS','auth')->name('tasks.report.ajaxdata');
Route::get('export/task_report/{id}', [ProjectReportController::class, 'export'])->name('project_report.export');

Route::post('storage-settings', [SettingsController::class, 'storageSettingStore'])->middleware('XSS','auth')->name('storage.setting.store');

//========================================== chat gtp ========================//

Route::post('chatgptkey',[SettingsController::class,'chatgptkey'])->name('settings.chatgptkey');

Route::get('generate/{template_name}',[AiTemplateController::class,'create'])->name('generate');
Route::post('generate/keywords/{id}',[AiTemplateController::class,'getKeywords'])->name('generate.keywords');
Route::post('generate/response',[AiTemplateController::class,'AiGenerate'])->name('generate.response');
Route::get('grammar/{template}',[AiTemplateController::class,'grammar'])->name('grammar')->middleware(['auth','XSS']);
Route::post('grammar/response',[AiTemplateController::class,'grammarProcess'])->name('grammar.response')->middleware(['auth','XSS']);
});



