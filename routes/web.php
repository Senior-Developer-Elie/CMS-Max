<?php

use App\Http\Controllers\SocialMediaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/sign-proposal/{proposal_id}', 'IndexController@signProposal');
Route::post('/client-signed', 'IndexController@clientSignedProposal');

Route::group(['middleware' => 'web'], function () {

    Auth::routes();

    Route::get('/', 'AdminController@index');

    //Admin
    Route::get('/webadmin', 'AdminController@index');
    Route::get('/change-password', 'AdminController@changePassword');
    Route::post('/change-password', 'AdminController@changePassword');
    //Route::get('/laraform-demo', 'AdminController@laraform');

    //Manage Proposal
    Route::get('/add-proposal', 'ProposalController@addProposal')->middleware('auth');
    Route::get('/process', 'ProposalController@process')->middleware('auth');

    Route::get('/proposal-list', 'ProposalController@index');
    Route::post('/admin-download', 'ProposalController@download');

    Route::get('/delete-proposal', 'ProposalController@deleteProposal');
    Route::post('/delete-proposal', 'ProposalController@deleteProposal');

    Route::get('/send-email', 'ProposalController@sendEmail');
    Route::post('/send-email', 'ProposalController@sendEmail');

    Route::get('/edit-proposal', 'ProposalController@editProposal')->middleware('auth');
    Route::post('/edit-proposal', 'ProposalController@editProposal')->middleware('auth');

    Route::get('/manual-sign', 'ProposalController@manualSign');
    Route::post('/manual-sign', 'ProposalController@manualSign');

    Route::get('/change-proposal-sold-status/{proposalId}/{sold}', 'ProposalController@changeProposalSoldStatus');
    Route::post('/change-proposal-sold-status/{proposalId}/{sold}', 'ProposalController@changeProposalSoldStatus');

    //Edit Service
    Route::get('/manage-default-text', 'ServiceController@manageDefaultText');

    Route::get('/add-service', 'ServiceController@addService');
    Route::post('/add-service', 'ServiceController@addService');

    Route::get('/edit-service/{service_id}', 'ServiceController@editService');
    Route::post('/edit-service/{service_id}', 'ServiceController@editService');

    Route::get('/delete-service/{service_id}', 'ServiceController@deleteService');
    Route::post('/delete-service/{service_id}', 'ServiceController@deleteService');

    //Edit Credit Card Default Rate
    Route::get('/calculate-card-rate', 'CreditRateController@index');
    // Route::get('/manage-default-rate', 'CreditRateController@manageDefaultRate');
    // Route::post('/manage-default-rate', 'CreditRateController@manageDefaultRate');
    Route::get('/generate-pdf', 'CreditRateController@generatePDF');
    Route::post('/generate-pdf', 'CreditRateController@generatePDF');

    //Manage Users
    Route::group(['middleware' => 'admin:Manage Admins'], function() {
        Route::resource('users', 'UserController', [
            'except' => ['show']
        ]); 
        Route::get('users/{user}/delete', ['as' => 'users.confirm-delete', 'uses' => 'UserController@confirmDelete']);

        Route::post('/update-user-page-permissions', 'UserPagePermissionController@updatePagePermissions');
        Route::get('/get-permission', 'UserPagePermissionController@getPermission');
        Route::post('/update-permission', 'UserPagePermissionController@updatePermission');
    });

    //Manage Blogs
    Route::get('/blog-dashboard', 'BlogController@index');

    Route::get('/add-client', 'ClientController@addClient');
    Route::post('/add-client', 'ClientController@addClient');


    //Route::get('/edit-blog-client/{client_id}', 'WebsiteController@editClient');

    Route::post('/change-blog-name', 'BlogController@changeBlogName');


    Route::get('/blog-list', 'BlogController@blogList');

    Route::get('/mark-complete/{blog_id}', 'BlogController@markComplete');
    Route::post('/mark-complete/{blog_id}', 'BlogController@markComplete');

    Route::get('/undo-complete/{blog_id}', 'BlogController@undoComplete');
    Route::post('/undo-complete/{blog_id}', 'BlogController@undoComplete');

    Route::get('/upload-blog/{blog_id}', 'BlogController@uploadBlog');
    Route::post('/upload-blog', 'BlogController@uploadBlog');

    Route::get('/upload-blog-image/{blog_id}', 'BlogController@uploadBlogImage');
    Route::post('/upload-blog-image', 'BlogController@uploadBlogImage');

    Route::get('/clear-upload/{blog_id}', 'BlogController@clearUpload');
    Route::post('/clear-upload/{blog_id}', 'BlogController@clearUpload');

    Route::post('/clear-blog-image', 'BlogController@clearBlogImage');

    Route::get('/change-to-not-available/{blog_id}', 'BlogController@changeToNotAvailable');
    Route::post('/change-to-not-available/{blog_id}', 'BlogController@changeToNotAvailable');

    Route::get('/download-blog/{blog_id}/{type}', 'BlogController@downloadBlog');

    Route::get('/quickbooks-import', 'QuickBookController@index');
    Route::post('/quickbooks-import', 'QuickBookController@showTable');

    Route::get('/admin-history', 'AdminHistoryController@index');

    //Inner Pages Routes
    Route::get('/jobs', 'InnerBlogController@index');

    Route::post('/add-inner-page', 'InnerBlogController@addInnerPage');
    Route::get('/get-inner-page-data', 'InnerBlogController@getInnerPageData');
    Route::post('/delete-inner-page', 'InnerBlogController@deleteInnerPage');

    Route::post('/update-inner-page-priority', 'InnerBlogController@updateInnerPagePriority');


    Route::post('/complete-inner-page', 'InnerBlogController@completeInnerPage');

    Route::get('/inner-page-download-files', 'InnerBlogController@downloadAllFiles');

    Route::post('/inner-page-clear-blog', 'InnerBlogController@clearBlog');

    Route::post('/inner-page-clear-image', 'InnerBlogController@clearImage');

    Route::post('/inner-page-undo-complete', 'InnerBlogController@undoComplete');

    Route::post('/inner-page-upload-files', 'InnerBlogController@uploadFiles');

    Route::post('/inner-page-clear-file-upload', 'InnerBlogController@clearUploadedFile');
    Route::post('/inner-page-complete-file', 'InnerBlogController@markFileAsFinal');
    Route::post('/inner-page-revert-file', 'InnerBlogController@markFileAsPending');

    //DB SEED
    // Route::get('/db-seed', 'AdminController@dbSeed');
    Route::get('/remove-temp-uploaded-blog-files', 'AdminController@removeUploadedFiles');

    //Blog Industries
    Route::get('/blog-industries', 'BlogIndustryController@index');

    Route::post('/add-blog-industry', 'BlogIndustryController@addBlogIndustry');
    Route::post('/delete-blog-industry', 'BlogIndustryController@deleteBlogIndustry');

    Route::get('/blog-industry-client-list', 'BlogIndustryController@clientList');

    /**User Profile */
    Route::get('/profile', 'ProfileController@index');
    Route::post('/profile', 'ProfileController@index');
    Route::post('/upload-photo', 'ProfileController@uploadPhoto');

    /**Notification */
    Route::get('/archive-notification', 'NotificationController@archiveNotification');
    Route::post('/archive-all-notifications', 'NotificationController@archiveAllNotifications');

    /**Clients */
    Route::get('/client-list', ['as' => 'clients.index', 'uses' => 'ClientController@index']);
    Route::get('/client-notes-versions', 'ClientNotesVersionsController@show');
    Route::get('/client-history', 'ClientController@clientHistory');
    Route::post('/edit-blog-client/{client_id}', 'ClientController@editClient');
    Route::get('/delete-client/{client_id}', 'ClientController@deleteClient');
    Route::post('/delete-client/{client_id}', 'ClientController@deleteClient');
    Route::get('/archive-client/{client_id}', 'ClientController@archiveClient');
    Route::post('/archive-client/{client_id}', 'ClientController@archiveClient');
    Route::get('/un-archive-client/{client_id}', 'ClientController@unArchiveClient');
    Route::post('/un-archive-client/{client_id}', 'ClientController@unArchiveClient');
    Route::post('/client-add-api-clients', 'ClientController@addApiClients');
    Route::post('/client-select-api-client', 'ClientController@selectApiClient');
    Route::post('/client-all-sync', 'ClientController@syncAllClientInfo');
    Route::post('/client-single-sync', 'ClientController@syncClientInfo');
    Route::post('/update-client-attribute', 'ClientController@updateAttribute');

    /**Api Clients */
    Route::get('/api-client-list', 'ApiClientController@index');
    Route::post('/archive-api-client', 'ApiClientController@archiveApiClient');
    Route::post('/unarchive-api-client', 'ApiClientController@unArchiveApiClient');

    /**Website */
    Route::resource('websites', 'Website\WebsiteController', [
        'except' => ['show']
    ]);
    //Route::delete('websites/{website}', 'Website\WebsiteController@destroy');
    Route::post('websites/export-budget', ['as' => 'websites.export-budget', 'uses' => 'Website\WebsiteBudgetExportController@index']);
    Route::get('websites/{website}/delete', ['as' => 'websites.confirm-delete', 'uses' => 'Website\WebsiteController@confirmDelete']);
    Route::post('websites/{website}/archive', ['as' => 'websites.archive', 'uses' => 'Website\WebsiteController@archive']);
    Route::post('websites/{website}/restore', ['as' => 'websites.restore', 'uses' => 'Website\WebsiteController@restore']);
    Route::post('/update-website-attribute', 'Website\WebsiteController@updateAttribute');
    Route::post('/update-website-product-value', 'Website\WebsiteController@updateProductValue');

    // Route::get('/get-website-info', 'WebsiteController@getWebsiteInfo');
    // Route::post('/add-website', 'WebsiteController@addWebsite');
    // Route::post('/delete-website', 'WebsiteController@deleteWebsite');
    // Route::post('/archive-website', 'WebsiteController@archiveWebsite');
    // Route::post('/un-archive-website', 'WebsiteController@unarchiveWebsite');
    
    
    // Website Manage Sender
    Route::get('/manage-website-sender', 'Website\WebsiteManageSenderController@index');
    
    // Website marketing
    Route::get('/marketing', 'Website\WebsiteMarketingController@index');
    
    // Website Budgeting
    Route::get('/budgeting', 'Website\WebsiteBudgetingController@index');

    // Website post live checklist
    Route::get('/post-live-checklist', 'Website\WebsitePostLiveCheckListController@index');
    Route::post('/post-live-checklist/archive', 'Website\WebsitePostLiveCheckListController@archive');
    Route::post('/update-website-post-live-attribute', 'Website\WebsitePostLiveCheckListController@updatePostLive');

    // Website payroll
    // Route::get('/payroll', 'Website\WebsitePayrollController@index');
    // Route::post('/payroll-archive-website', 'Website\WebsitePayrollController@archiveWebsite');
    // Route::post('/payroll-un-archive-website', 'Website\WebsitePayrollController@unarchiveWebsite');

    //Credit Card Processing
    Route::get('/credit-card-processing', 'Website\WebsiteCreditCardProcessingController@index');
    Route::post('/credit-card-processing/archive-website', 'Website\WebsiteCreditCardProcessingController@archiveWebsite');
    Route::post('/credit-card-processing/un-archive-website', 'Website\WebsiteCreditCardProcessingController@unarchiveWebsite');
    Route::post('/credit-card-processing/store', 'Website\WebsiteCreditCardProcessingController@store');
    Route::post('/credit-card-processing/destroy', 'Website\WebsiteCreditCardProcessingController@destroy');
    Route::post('/credit-card-processing/update-attribute', 'Website\WebsiteCreditCardProcessingController@updateAttribute');

    /**Website in Progress */
    Route::get('/website-progress', 'Website\WebsiteProgressController@index');
    Route::post('/update-stage-priorities', 'Website\WebsiteProgressController@updateStagePriorities');

    //Website Statistics
    Route::get('/website-completed', 'Website\WebsitesStatisticsController@index');
    Route::get('/website-completed-statistics', 'Website\WebsitesStatisticsController@getWebsiteCompletionStatusForBarChart');

    // Website Billing
    Route::get('/billing', 'Website\WebsiteBillingController@index');

    //Task Route
    Route::post('/update-task-priorities', 'TaskController@updateTaskPriorities');
    Route::post('/add-task', 'TaskController@addTask');
    Route::post('/update-task-attribute', 'TaskController@updateTaskAttribute');
    Route::get('/get-task-details', 'TaskController@getTaskDetails');
    Route::post('/task-upload-file', 'TaskController@uploadFiles');
    Route::post('/remove-task-file', 'TaskController@removeFile');
    Route::get('/task-download-file', 'TaskController@downloadFile');
    Route::post('/delete-task', 'TaskController@deleteTask');
    Route::post('/complete-task', 'TaskController@completeTask');
    Route::post('/task-update-pre-post-options', 'TaskController@updatePrePostOptions');
    Route::post('/task-create-comment', 'TaskController@createComment');
    Route::get('/sync-task-comments', 'TaskController@syncTaskComments');
    Route::get('/task-get-comment', 'TaskController@getComment');
    Route::post('/task-remove-comment', 'TaskController@removeComment');
    Route::get('/task-get-comments-count', 'TaskController@getCommentsCount');
    Route::post('/task-pin-comment', 'TaskController@pinComment');
    Route::post('/task-upload-comment-files', 'TaskController@uploadCommentFiles');
    Route::get('/task-download-comment-file', 'TaskController@downloadCommentFile');
    Route::post('/task-pre-upload-image', 'TaskController@uploadPreImage');
    Route::get('/task-download-pre-image', 'TaskController@downloadPreImage');

    /**Profit & Loss */
    Route::resource('financial-reports', 'FinancialReportController', [
        'except' => ['show']
    ]);
    Route::post('/track-profit-loss-history', 'ProfitLossController@trackMonthProfitLoss');

    //Manage Expense
    Route::get('/manage-expense', 'ExpenseController@index');
    Route::get('/get-expense-data', 'ExpenseController@getExpense');
    Route::post('/add-edit-expense', 'ExpenseController@addEditExpense');
    Route::post('/delete-expense', 'ExpenseController@deleteExpense');

    //Manage Profit
    Route::get('/manage-profit', 'ProfitController@index');
    Route::get('/get-profit-data', 'ProfitController@getProfit');
    Route::post('/add-edit-profit', 'ProfitController@addEditProfit');
    Route::post('/delete-profit', 'ProfitController@deleteProfit');


    //Manage PaymentGateway
    Route::get('/manage-paymentGateway', 'PaymentGatewayController@index');
    Route::get('/get-paymentGateway-data', 'PaymentGatewayController@getPaymentGateway');
    Route::post('/add-edit-paymentGateway', 'PaymentGatewayController@addEditPaymentGateway');
    Route::post('/delete-paymentGateway', 'PaymentGatewayController@deletePaymentGateway');

    //Manage Affiliate
    Route::get('/manage-affiliate', 'AffiliateController@index');
    Route::get('/get-affiliate-data', 'AffiliateController@getAffiliate');
    Route::post('/add-edit-affiliate', 'AffiliateController@addEditAffiliate');
    Route::post('/delete-affiliate', 'AffiliateController@deleteAffiliate');

    //Manage Dns
    Route::get('/manage-dns', 'DnsController@index');
    Route::get('/get-dns-data', 'DnsController@getDns');
    Route::post('/add-edit-dns', 'DnsController@addEditDns');
    Route::post('/delete-dns', 'DnsController@deleteDns');

    //Api Route for Chrome Extension
    Route::get('/get-notifications', 'AdminController@getNotifications');

    //MailGun
    Route::get('/mailgun-api-keys', 'MailgunController@index');
    Route::get('/failed-mails', 'MailgunController@failedEmails');
    Route::get('/get-mailgun-events-datatable', 'MailgunController@getMailgunEventsDatatable');
    Route::get('/get-mailgun-suppressions-datatable', 'MailgunController@getMailgunSuppressionsDatatable');
    Route::post('/archive-failed-mail', 'MailgunController@archiveFailedMail');
    Route::post('/archive-all-failed-mail', 'MailgunController@archiveAllFailedMails');
    Route::post('/add-mailgun-api-key', 'MailgunController@addApiKey');
    Route::post('/delete-mailgun-api-key', 'MailgunController@deleteApiKey');
    Route::post('/delete-mailgun-suppression', 'MailgunController@deleteSuppression');
    Route::post('/archive-mailgun-suppression', 'MailgunController@archiveSuppression');
    Route::post('/archive-all-mailgun-suppression', 'MailgunController@archiveAllSuppressions');
    Route::post('/webhooks/new-event', 'MailgunController@newEvent');

    //PDF Convert
    Route::get('/convert', 'PdfConvertController@index');
    Route::post('/process-pdf-convert', 'PdfConvertController@process');

    //Mockups
    Route::get('/mockups/create', 'MockupController@index');
    Route::post('/mockups/create', 'MockupController@create');
    Route::get('/mockups/{mockup_url}', 'MockupController@show');

    //Social Media
    Route::prefix('social-media')->group(function () {
        Route::get('/', 'SocialMediaController@index');
        Route::get('/website-details/{website_id}', 'SocialMediaController@show');
        Route::post('/update-social-media-checklist/{website_id}', 'SocialMediaController@updateSocialMediaChecklist');
        Route::post('/update-social-media-archived/{website_id}', 'SocialMediaController@updateSocialMediaArchived');
    });

    // Social Media Checklist
    Route::resource('social_media_check_lists', 'SocialMediaCheckListsController', [
        'except' => ['show']
    ]);
    Route::get('social_media_check_lists/{social_media_check_list}/delete', ['as' => 'social_media_check_lists.confirm-delete', 'uses' => 'SocialMediaCheckListsController@confirmDelete']);
});
