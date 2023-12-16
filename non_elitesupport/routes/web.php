<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ContactModuleController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\ComplaintTypeController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerMasterController;
use App\Http\Controllers\EscalationController;
use App\Http\Controllers\CronJobController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\CallerController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\VehicleModelController;
use App\Http\Controllers\FollowupController;
use App\Http\Controllers\ImportExcelController;
 
// use Illuminate\Http\Request;
use Illuminate\Http\Request;
use MagicLink\Actions\LoginAction;
/*Followup Cogent Controller */
use  App\Http\Controllers\Followup\AssignFeedbackController;
use  App\Http\Controllers\Followup\DealerFeedbackController;
use  App\Http\Controllers\Followup\CompleteFeedbackController;
/*Followup Cogent Controller */
// use DB;
use App\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('clear', function() { 
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return "Cleared!"; 
});
Route::get('/', function () {
    return view('auth.login');
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
   /*  Route::get('/dashboard', function () {
        return view('dashboard');
        
    }); */
    Route::get('dashboard2', [DashboardController::class, 'dashboard'])->name('dashboard2');
    /* Dashboard */
 
  Route::post('ajax-complaint-search', [DashboardController::class, 'ajaxComplaintSearch'])->name('ajax-complaint-search');
  Route::get('ajax-ticket-type', [DashboardController::class, 'ajaxTicketType'])->name('ajax-ticket-type');
  Route::get('ajax-product-search', [DashboardController::class, 'ajaxProductSearch'])->name('ajax-product-search');
  Route::get('ajax-pie-search', [DashboardController::class, 'ajaxPieSearch'])->name('ajax-pie-search');
  Route::get('ajax-bar-search', [DashboardController::class, 'ajaxBarSearch'])->name('ajax-bar-search');
  Route::get('ajax-bar-table-search', [DashboardController::class, 'ajaxBarTableSearch'])->name('ajax-bar-table-search');
  Route::get('ajax-region-count', [DashboardController::class, 'ajaxRegionCount'])->name('ajax-region-count');
  Route::get('ajax-tat-search', [DashboardController::class, 'ajaxTatSearch'])->name('ajax-tat-search');
  Route::get('ajax-postsurvey-search', [DashboardController::class, 'ajaxPostSurveySearch'])->name('ajax-postsurvey-search');
  Route::get('ajax-topCategory-search', [DashboardController::class, 'ajaxTopCategorySearch'])->name('ajax-topCategory-search');
  Route::get('ajax-topcustomer-search', [DashboardController::class, 'ajaxTopCustomerSearch'])->name('ajax-topcustomer-search');
  Route::post('dashboard-data', [DashboardController::class, 'dashboardData'])->name('dashboard-data');
  Route::get('open-complaint', [DashboardController::class, 'openComplaint'])->name('open-complaint');
  Route::get('closed-complaint', [DashboardController::class, 'closedComplaint'])->name('closed-complaint');
  /* Dashboard */
 // USER MANAGEMENT
 
 Route::get('/users/create', [UserController::class, 'add'])->name('adduser');
 Route::post('/users/store', [UserController::class, 'create'])->name('users.create');
 Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
 Route::post('/users/update', [UserController::class, 'update'])->name('users.update');
 Route::get('/users/delete/{id}', [UserController::class, 'delete'])->name('users.delete');
 Route::get('/get-role', [UserController::class, 'getRole'])->name('get-role');
 Route::get('/users_delete/{id}', [UserController::class, 'usersDelete'])->name('users_delete.usersDelete');

 Route::get('user-type', [UserController::class, 'userType'])->name('user-type');
 Route::post('store-user-type', [UserController::class, 'storeUserType'])->name('store-user-type');
 Route::post('update-user-type', [UserController::class, 'updateUserType'])->name('update-user-type');
 Route::get('userType_delete/{id}', [UserController::class, 'userTypeDelete'])->name('userType_delete.userTypeDelete');

 Route::get('role', [UserController::class, 'role'])->name('role');
 Route::post('store-role', [UserController::class, 'storeRole'])->name('store-role');
 Route::post('update-role', [UserController::class, 'updateRole'])->name('update-role');
 Route::get('role_delete/{id}', [UserController::class, 'roleDelete'])->name('role_delete.roleDelete');

 Route::get('get-role', [UserController::class, 'getRole'])->name('get-role');
 Route::get('reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
 Route::post('store-reset-password', [UserController::class, 'storeResetPassword'])->name('store-reset-password');

/* 
 Route::get('users', [UserController::class, 'users'])->name('users');
 Route::post('store-users', [UserController::class, 'storeUsers'])->name('store-users');
 Route::get('users_delete/{id}', [UserController::class, 'usersDelete'])->name('users_delete.usersDelete');
 Route::get('exportUser', [UserController::class, 'exportUser'])->name('exportUser'); */

/*  Route::get('change-password', [UserController::class, 'changePassword'])->name('change-password');
Route::post('store-change-password', [UserController::class, 'storeChangePassword'])->name('store-change-password'); */
  // USER MANAGEMENT


   /* Brand Controller */
   Route::get('brand', [BrandController::class, 'brand'])->name('brand');
   Route::post('store-brand', [BrandController::class, 'storeBrand'])->name('store-brand');
   Route::post('update-brand', [BrandController::class, 'updateBrand'])->name('update-brand');
   Route::get('brand_delete/{id}', [BrandController::class, 'brandDelete'])->name('brand_delete.brandDelete');
   /* Brand Controller */
   /* ContactModuleController */
   Route::get('contact-module', [ContactModuleController::class, 'contactModule'])->name('contact-module');
   Route::post('store-contact-module', [ContactModuleController::class, 'storeContactModule'])->name('store-contact-module');
   Route::post('update-contact-module', [ContactModuleController::class, 'updateContactModule'])->name('update-contact-module');
   Route::get('contact_module_delete/{id}', [ContactModuleController::class, 'contactModuleDelete'])->name('contact_module_delete.contactModuleDelete');
   /* ContactModuleController */
   /* VehicleController */
   Route::get('vehicle', [VehicleController::class, 'vehicle'])->name('vehicle');
   Route::post('store-vehicle', [VehicleController::class, 'storeVehicle'])->name('store-vehicle');
   Route::post('update-vehicle', [VehicleController::class, 'updateVehicle'])->name('update-vehicle');
   Route::get('vehicle_delete/{id}', [VehicleController::class, 'vehicleDelete'])->name('vehicle_delete.vehicleDelete');
   Route::get('get-product-segment', [VehicleController::class, 'getProductSegment'])->name('get-product-segment');
   Route::get('get-multi-product-segment', [VehicleController::class, 'getMutliProductSegment'])->name('get-multi-product-segment');
   Route::get('get-multi-product', [VehicleController::class, 'getMutliProduct'])->name('get-multi-product');
   Route::get('ajax-vehicle-report-data', [VehicleController::class, 'ajaxVehicleReportData'])->name('ajax-vehicle-report-data');
   Route::get('export', [VehicleController::class, 'export'])->name('export');
   Route::get('check-elite-reg', [VehicleController::class, 'checkEliteReg'])->name('check-elite-reg');
   /* VehicleController */
   /* DealerController */
   Route::get('dealer', [DealerController::class, 'dealer'])->name('dealer');
   Route::post('store-dealer', [DealerController::class, 'storeDealer'])->name('store-dealer');
   Route::get('get-dealername', [DealerController::class, 'getDealerName'])->name('get-dealername');
   Route::get('get-dealercode', [DealerController::class, 'getDealerCode'])->name('get-dealercode');
   Route::get('Dealer_delete/{id}', [DealerController::class, 'dealerDelete'])->name('Dealer_delete.dealerDelete');
   Route::get('GetState', [DealerController::class, 'getState'])->name('GetState');
   Route::get('get-zone', [DealerController::class, 'getZone'])->name('get-zone');
   Route::get('get-brand', [DealerController::class, 'getBrand'])->name('get-brand');
   Route::get('get-product', [DealerController::class, 'getProduct'])->name('get-product');
   Route::get('get-multi-id-state', [DealerController::class, 'getMultiIdState'])->name('get-multi-id-state');
   Route::get('get-multi-id-reg-office', [DealerController::class, 'getMultiIdRegOffice'])->name('get-multi-id-reg-office');
   Route::get('get-multi-id-city', [DealerController::class, 'getMultiIdCity'])->name('get-multi-id-city');
   Route::get('city-change-get-dealer', [DealerController::class, 'cityChangeGetDealer'])->name('city-change-get-dealer');
   Route::get('get-city-by-region', [DealerController::class, 'getCityByRegion'])->name('get-city-by-region');
   Route::get('get-area-office', [DealerController::class, 'getAreaOffice'])->name('get-area-office');
   Route::get('get-multi-id-zone', [DealerController::class, 'getMultiIdZone'])->name('get-multi-id-zone');
   Route::get('get-multi-zone', [DealerController::class, 'getMultiZone'])->name('get-multi-zone');
   Route::get('get-multi-dealer', [DealerController::class, 'getMultiDealer'])->name('get-multi-dealer');
   Route::get('get-city-zone-id', [DealerController::class, 'getCityZoneId'])->name('get-city-zone-id');
   Route::get('get-dealer-by-zone-id', [DealerController::class, 'getDealerByZoneId'])->name('get-dealer-by-zone-id');
   Route::get('get-dealer-by-zone-id-report', [DealerController::class, 'getDealerByZoneIdReport'])->name('get-dealer-by-zone-id-report');
   Route::get('get-city-by-dealer-id', [DealerController::class, 'getCityByDealerId'])->name('get-city-by-dealer-id');
   Route::get('get-state-id-city', [DealerController::class, 'getStateIdCity'])->name('get-state-id-city');
   Route::get('get-multiple-state-id-city', [DealerController::class, 'getMultipleStateIdCity'])->name('get-multiple-state-id-city');
   Route::get('get-city-change-dealer', [DealerController::class, 'getCityChangeDealer'])->name('get-city-change-dealer');
   Route::post('bulk-update', [DealerController::class, 'bulkUpdate'])->name('bulk-update');
   /* DealerController */

   /* AreaController */
   Route::get('area', [AreaController::class, 'area'])->name('area');
   Route::post('store-area', [AreaController::class, 'storeArea'])->name('store-area');
   Route::get('area_delete/{id}', [AreaController::class, 'areaDelete'])->name('area_delete.areaDelete');
   /* AreaController */
   /* CaseController */
   Route::get('new-case', [CaseController::class, 'newCase'])->name('new-case');
   Route::post('store-new-case', [CaseController::class, 'storeNewCase'])->name('store-new-case');
   Route::get('Get-ccm', [CaseController::class, 'getccm'])->name('Get-ccm');
   Route::get('get-complaint-type', [CaseController::class, 'getcomplainttype'])->name('get-complaint-type');
   Route::get('get-dealer', [CaseController::class, 'getDealer'])->name('get-dealer');
   Route::get('get-subproduct', [CaseController::class, 'getSubproduct'])->name('get-subproduct');
  
   Route::get('update-case/{id}', [CaseController::class, 'updateCase'])->name('update-case.updateCase');
   Route::get('case-list', [CaseController::class, 'caseList'])->name('case-list');
   Route::post('store-case-list', [CaseController::class, 'storeCaseList'])->name('store-case-list');
   Route::post('store-update-cases', [CaseController::class, 'storeUpdateCases'])->name('store-update-cases');
   Route::post('case-creation-by-api', [CaseController::class, 'createCaseByApi'])->name('case-creation-by-api');
   Route::get('get-customer-details', [CaseController::class, 'getCustomerDetails'])->name('get-customer-details');
   Route::get('get-customer-details-id', [CaseController::class, 'getCustomerDetailsId'])->name('get-customer-details-id');
   Route::get('search-dealer', [CaseController::class, 'searchDealer'])->name('search-dealer');
   Route::get('get-segment-id', [CaseController::class, 'getSegmentId'])->name('get-segment-id');
   Route::get('get-region-id', [CaseController::class, 'getRegionId'])->name('get-region-id');
   Route::get('get-complaint-cat-id', [CaseController::class, 'getComplaintCatId'])->name('get-complaint-cat-id');
   Route::get('get-product-id', [CaseController::class, 'getProductId'])->name('get-product-id');
   Route::get('get-brand-id', [CaseController::class, 'getBrandId'])->name('get-brand-id');
   Route::get('get-dealercodeasoc-id', [CaseController::class, 'getDealerCodeAsocId'])->name('get-dealercodeasoc-id');
   Route::get('get-assign-user', [CaseController::class, 'getAssignUser'])->name('get-assign-user');
   Route::get('case-deleted/{id}', [CaseController::class, 'caseDeleted'])->name('case-deleted.caseDeleted');
   Route::post('call-back-api', [CaseController::class, 'callBackApi'])->name('call-back-api');
   Route::post('msu-api', [CaseController::class, 'Msuapi'])->name('msu-api');
   Route::get('case-deleted/{id}', [CaseController::class, 'caseDeleted'])->name('case-deleted.caseDeleted');

  
   /* CaseController */

   /* ComplaintTypeController */
   Route::get('complaint-type', [ComplaintTypeController::class, 'complaintType'])->name('complaint-type');
   Route::post('store-complaint', [ComplaintTypeController::class, 'storeComplaint'])->name('store-complaint');
   Route::get('complaint_type_delete/{id}', [ComplaintTypeController::class, 'complaintTypeDelete'])->name('complaint_type_delete.complaintTypeDelete');
   Route::get('complaint-type', [ComplaintTypeController::class, 'complaintType'])->name('complaint-type');
   Route::post('store-complaint', [ComplaintTypeController::class, 'storeComplaint'])->name('store-complaint');
   Route::get('complaint_type_delete/{id}', [ComplaintTypeController::class, 'complaintTypeDelete'])->name('complaint_type_delete.complaintTypeDelete');
   Route::get('get-sub-complaint', [ComplaintTypeController::class, 'getSubComplaint'])->name('get-sub-complaint');
   /* ComplaintTypeController */

   /* AccessController */
   Route::get('access', [AccessController::class, 'access'])->name('access');
   Route::post('store-access', [AccessController::class, 'storeAccess'])->name('store-access');
   Route::get('access_delete/{id}', [AccessController::class, 'accessDelete'])->name('access_delete.accessDelete');
   Route::get('check-msu', [AccessController::class, 'checkMSUFormat'])->name('check-msu');
   /* AccessController */

   /* ReportController */
   Route::get('vahan-api-report', [ReportController::class, 'VahanAPiReport'])->name('vahan-api-report');
   Route::get('report', [ReportController::class, 'report'])->name('report');
   Route::get('consolidated-report', [ReportController::class, 'consolidatedReport'])->name('consolidated-report');
   Route::get('ticket-report', [ReportController::class, 'ticketReport'])->name('ticket-report');
   Route::post('store-ticket-report', [ReportController::class, 'storeTicketReport'])->name('store-ticket-report');
   Route::get('get-ticket-report/{get_complaint_number}', [ReportController::class, 'getTicketReport'])->name('getTicketReport.get-ticket-report');
   Route::get('complaint-number', [ReportController::class, 'complaintNumber'])->name('complaint-number');
   Route::post('report-data', [ReportController::class, 'reportData'])->name('report-data');
   Route::get('open-complaint-report', [ReportController::class, 'openComplaintReport'])->name('open-complaint-report');
   Route::post('store-open-complaint-report', [ReportController::class, 'storeOpenComplaintReport'])->name('store-open-complaint-report');
   Route::post('store-consolidated-report', [ReportController::class, 'storeConsolidatedReport'])->name('store-consolidated-report');
   Route::get('top-focus', [ReportController::class, 'topFocus'])->name('top-focus');
   Route::post('store-top-focus-report', [ReportController::class, 'storeTopFocusReport'])->name('store-top-focus-report');
   Route::get('dealer-summary-report', [ReportController::class, 'dealerSummaryReport'])->name('dealer-summary-report');
   Route::post('store-dealer-summary-report', [ReportController::class, 'storeDealerSummaryReport'])->name('store-dealer-summary-report');
   Route::post('store-kpi-report', [ReportController::class, 'storeKpiReport'])->name('store-kpi-report');
   Route::get('kpi-trend', [ReportController::class, 'kpiTrend'])->name('kpi-trend');
   Route::post('store-pcs-process', [ReportController::class, 'storePcsProcess'])->name('store-pcs-process');
   Route::get('pcs-month-report', [ReportController::class, 'pcsMonthReport'])->name('pcs-month-report');
   Route::get('pcs-process', [ReportController::class, 'pcsProcess'])->name('pcs-process');
   Route::get('preventive-action', [ReportController::class, 'preventiveAction'])->name('preventive-action');
   Route::get('dealer-activity-report', [ReportController::class, 'dealerActivityReport'])->name('dealer-activity-report');
   Route::post('store-dealer-activity-report', [ReportController::class, 'storeDealerActivityReport'])->name('dealer-activity-report');
   Route::get('consolidated-closed-report', [ReportController::class, 'consolidatedClosedReport'])->name('consolidated-closed-report');
   Route::post('store-consolidated-closed-report', [ReportController::class, 'storeConsolidatedClosedReport'])->name('store-consolidated-closed-report');
   /* ReportController */

   /* CustomerMasterController */
   Route::get('customer-master', [CustomerMasterController::class, 'CustomerMaster'])->name('customer-master');
   Route::post('store-customer', [CustomerMasterController::class, 'storeCustomer'])->name('store-customer');
   Route::get('customer_delete/{id}', [CustomerMasterController::class, 'customerDelete'])->name('customer_delete.customerDelete');
   Route::get('customer_contact/{id}', [CustomerMasterController::class, 'customerContact'])->name('customer_contact.customerContact');
   Route::get('customer_contact_delete/{id}', [CustomerMasterController::class, 'customerContactDelete'])->name('customer_contact_delete.customerContactDelete');
   Route::post('store-customer-contact', [CustomerMasterController::class, 'storeCustomerContact'])->name('store-customer-contact');
   Route::get('get-scope-service', [CustomerMasterController::class, 'getScopeService'])->name('get-scope-service');
   Route::get('get-support-type', [CustomerMasterController::class, 'getSupportType'])->name('get-support-type');
   Route::get('get-complaint-cat', [CustomerMasterController::class, 'getComplaintCat'])->name('get-complaint-cat');
   /* CustomerMasterController */
   /* EscalationController */
   Route::get('escalation', [EscalationController::class, 'escalation'])->name('escalation');
   Route::post('store-escalation', [EscalationController::class, 'storeEscalation'])->name('store-escalation');
   Route::get('escalation_delete/{id}', [EscalationController::class, 'escalationDelete'])->name('escalation_delete.escalationDelete');
   Route::get('escalation-individual/{ids}', [EscalationController::class, 'escalationIndividual'])->name('escalation-individual.escalationIndividual');
   Route::get('get-cc-role', [EscalationController::class, 'getCcRole'])->name('get-cc-role');
   Route::get('ajax-role', [EscalationController::class, 'ajaxRole'])->name('ajax-role');
   /* EscalationController */
  

   /* LocationController */
   /* CTI PAge */
   Route::get('cti-ticket', [LocationController::class, 'getTicket'])->name('cti-ticket');
   Route::get('ticket-ticket-report', [LocationController::class, 'ticketsExport'])->name('ticket-ticket-report');
   Route::get('ticket-list', [LocationController::class, 'ticketList'])->name('ticket-list');
   Route::get('update-by-agent/{slug}',[LocationController::class, 'getTicketByAgent'])->name('update-by-agent');
   Route::post('ticket-update-by-agent',[LocationController::class, 'updatedByAgent'])->name('ticket-update-by-agent');
  /* CTI PAge */
   Route::get('ticket-creation', [LocationController::class, 'ticketCreation'])->name('ticket-creation');
   Route::get('ticket-creationCti', [LocationController::class, 'ticketCreationCti'])->name('ticket-creationCti');
   Route::get('get-vehicle-details', [LocationController::class, 'getVehicleDetails'])->name('get-vehicle-details');
   Route::post('store-location', [LocationController::class, 'storeLocation'])->name('store-location');
   Route::get('send-latlong-link', [LocationController::class, 'sendLatlongLink'])->name('send-latlong-link');

   
   Route::get('get-nearest-latlong', [LocationController::class, 'getNearestLatlong'])->name('get-nearest-latlong');
   Route::get('get-assign-details', [LocationController::class, 'getAssignDetails'])->name('get-assign-details');
   Route::get('get-assign-mob', [LocationController::class, 'getAssignMob'])->name('get-assign-mob');
   Route::get('get-stateChange', [LocationController::class, 'getStateChange'])->name('get-stateChange');
   
   Route::get('get-city', [LocationController::class, 'getCity'])->name('get-city');
   Route::get('search-city', [LocationController::class, 'searchCity'])->name('search-city');
   Route::get('caller-update', [LocationController::class, 'callerUpdate'])->name('caller-update');
   Route::get('vehicle-update', [LocationController::class, 'vehicleUpdate'])->name('vehicle-update');
   Route::get('owner-update', [LocationController::class, 'ownerUpdate'])->name('owner-update');
   Route::get('owner-contact-update', [LocationController::class, 'ownerContactUpdate'])->name('owner-contact-update');
   Route::post('ticket-creation-data', [LocationController::class, 'ticketCreationData'])->name('ticket-creation-data');
   Route::get('get-owner-change', [LocationController::class, 'getOwnerChange'])->name('get-owner-change');
   Route::get('get-owner-contact-change', [LocationController::class, 'getOwnerContactChange'])->name('get-owner-contact-change');
   Route::get('get-owner-change-caller', [LocationController::class, 'getOwnerChangeCaller'])->name('get-owner-change-caller');
   Route::get('get-assign-details-manually', [LocationController::class, 'getAssignDetailsManually'])->name('get-assign-details-manually');
   Route::get('mail-function', [LocationController::class, 'mailFunction'])->name('mail-function');
   Route::get('sms-check', [LocationController::class, 'smsCheck'])->name('sms-check');
   Route::get('test-msu', [LocationController::class, 'testMSU'])->name('test-msu');
   Route::get('download-zip', [LocationController::class, 'downloadZip'])->name('download-zip');
   Route::get('copy-paste', [LocationController::class, 'copyPaste'])->name('copy-paste');
   Route::post('store-copy-paste', [LocationController::class, 'storeCopyPaste'])->name('store-copy-paste');



   /* Route::get('upload-file', [LocationController::class, 'uploadFile'])->name('upload-file');
   Route::post('store-upload-file', [LocationController::class, 'storeUploadFile'])->name('store-upload-file'); */

   Route::get('dealer-search-function', [LocationController::class, 'dealerSearchFunction'])->name('dealer-search-function');

   Route::get('check-registration-ticket', [LocationController::class, 'checkRegistrationTicket'])->name('check-registration-ticket');
   Route::get('get-assign-workManager', [LocationController::class, 'getAssignWorkManager'])->name('get-assign-workManager');
   Route::post('get-assign-workManager-mobile', [LocationController::class, 'getAssignWorkManagerMobile'])->name('get-assign-workManager-mobile');
   Route::get('get-night-spoc', [LocationController::class, 'getNightSpoc'])->name('get-night-spoc');
   Route::get('get-vahan', [LocationController::class, 'getVahan'])->name('get-vahan');
   /* LocationController */

   /* OwnerController */
   Route::get('owner-view', [OwnerController::class, 'ownerView'])->name('owner-view');
   Route::post('store-owner', [OwnerController::class, 'storeOwner'])->name('store-owner');
   Route::get('owner_delete/{id}', [OwnerController::class, 'ownerDelete'])->name('owner_delete.ownerDelete');

   Route::get('owner-contact-view', [OwnerController::class, 'ownerContactView'])->name('owner-contact-view');
   Route::post('store-owner-contact', [OwnerController::class, 'storeOwnerContact'])->name('store-owner-contact');
   Route::get('owner_contact_delete/{id}', [OwnerController::class, 'ownerContactDelete'])->name('owner_contact_delete.ownerContactDelete');
   Route::get('get-owner-name', [OwnerController::class, 'getOwnerName'])->name('get-owner-name');
   Route::get('export-owner', [OwnerController::class, 'exportOwner'])->name('export-owner');
   Route::get('ajax-owner-report-data', [OwnerController::class, 'ajaxOwnerReportData'])->name('ajax-owner-report-data');
   /* OwnerController */

   /* CallerController */
   Route::get('get-caller-view', [CallerController::class, 'getCallerView'])->name('get-caller-view');
   Route::post('store-caller', [CallerController::class, 'storeCaller'])->name('store-caller');
   Route::get('caller_delete/{id}', [CallerController::class, 'callerDelete'])->name('caller_delete.callerDelete');

   /* CallerController */

   /* RegionController */ 
  /*  Route::get('region-view', [RegionController::class, 'regionView'])->name('region-view');
   Route::post('store-zone', [RegionController::class, 'storeZone'])->name('store-zone');
   Route::get('zone_delete/{id}', [RegionController::class, 'zoneDelete'])->name('zone_delete.zoneDelete'); */
   
/*    Route::get('state-view', [RegionController::class, 'stateView'])->name('state-view');
   Route::post('store-state', [RegionController::class, 'storeState'])->name('store-state');
   Route::get('state_delete/{id}', [RegionController::class, 'stateDelete'])->name('state_delete.stateDelete'); */
   
/*    Route::get('city-view', [RegionController::class, 'cityView'])->name('city-view');
   Route::post('store-city', [RegionController::class, 'storecity'])->name('store-city');
   Route::get('city_delete/{id}', [RegionController::class, 'cityDelete'])->name('city_delete.cityDelete'); */
   
   Route::get('get-zone-change', [RegionController::class, 'getZoneChange'])->name('get-zone-change');
   Route::get('get-multiple-zone-change', [RegionController::class, 'getMultipleZoneChange'])->name('get-multiple-zone-change');
   Route::get('get-caller-state-change', [RegionController::class, 'getCallerStateChange'])->name('get-caller-state-change');
   Route::get('get-assign-dealer-state-change', [RegionController::class, 'getAssignDealerStateChange'])->name('get-assign-dealer-state-change');
   /* RegionController */

   /* VehicleModelController */
   Route::get('vehicle-models', [VehicleModelController::class, 'vehicleModel'])->name('vehicle-models');
   Route::post('store-vehicle-models', [VehicleModelController::class, 'storeVehicleModels'])->name('store-vehicle-models');
   Route::get('get-vehicle-models', [VehicleModelController::class, 'getVehicleModels'])->name('get-vehicle-models');
   Route::get('vehicle-model-delete/{id}', [VehicleModelController::class, 'vehicleModelDelete'])->name('vehicle-model-delete.vehicleModelDelete');
   /* VehicleModelController */

   /* FollowupController */
   Route::get('followups', [FollowupController::class, 'index'])->name('followups');
   Route::post('store-folloup-info', [FollowupController::class, 'storeFolloupInfo'])->name('followups.store');
   Route::post('getFollupinfo', [FollowupController::class, 'getFollupinfo'])->name('getFollupinfo');
   Route::post('new-call-list', [FollowupController::class, 'newCallList'])->name('newcalllist');
   Route::post('store-followups-form', [FollowupController::class, 'storeFollowupsForm'])->name('store-followups-form');

   Route::get('general-ticket', [FollowupController::class, 'generalTicket'])->name('general-ticket'); 
   Route::post('general-ticket-store', [FollowupController::class, 'generalTicketStore'])->name('general-ticket-store'); 
   Route::get('general-ticket-list', [FollowupController::class, 'generalTicketList'])->name('general-ticket-list');
   Route::post('store-general-ticket', [FollowupController::class, 'storeGeneralTicket'])->name('store-general-ticket');
   Route::get('prim-disposition', [FollowupController::class, 'primDisposition'])->name('prim-disposition');
   /* FollowupController */

   /* ImportExcelController */
    Route::get('import-excel', [ImportExcelController::class, 'importExcel'])->name('import-excel');
    Route::post('store-import-excel', [ImportExcelController::class, 'storeImportExcel'])->name('store-import-excel');
    Route::get('import-user-data', [ImportExcelController::class, 'importUserData'])->name('import-user-data');
   /* ImportExcelController */
   /* FOLLOWUP COGENT CRM*/
 
   Route::get('cogent-assign', [AssignFeedbackController::class, 'index'])->name('cogent-assign');
   Route::post('cogent-assign-day-fresh', [AssignFeedbackController::class, 'dayFresh'])->name('cogent-assign-day-fresh');
   Route::post('cogent-assign-lang-ajax', [AssignFeedbackController::class, 'langAjax'])->name('cogent-assign-lang-ajax');
   Route::post('cogent-assign-update', [AssignFeedbackController::class, 'update'])->name('cogent-assign-update');
   Route::post('cogent-complaint-id', [AssignFeedbackController::class, 'cogentComplaintId'])->name('cogent-complaint-id');

   Route::get('cogent-dealer', [DealerFeedbackController::class, 'index'])->name('cogent-dealer');
   Route::post('cogent-dealer-day-fresh', [DealerFeedbackController::class, 'dayFresh'])->name('cogent-dealer-day-fresh');
   Route::post('cogent-dealer-lang-ajax', [DealerFeedbackController::class, 'langAjax'])->name('cogent-dealer-lang-ajax');
   Route::post('cogent-dealer-update', [DealerFeedbackController::class, 'update'])->name('cogent-dealer-update');


   Route::get('cogent-complete', [CompleteFeedbackController::class, 'index'])->name('cogent-complete');
   Route::post('cogent-complete-day-fresh', [CompleteFeedbackController::class, 'dayFresh'])->name('cogent-complete-day-fresh');
   Route::post('cogent-complete-lang-ajax', [CompleteFeedbackController::class, 'langAjax'])->name('cogent-complete-lang-ajax');
   Route::post('cogent-complete-update', [CompleteFeedbackController::class, 'update'])->name('cogent-complete-update');

   /* FOLLOWUP COGENT CRM*/
    
});

  /* Auto Login */
  Route::get('/autologin', function (Request $request) {
    Session::flush(); 
    Auth::logout();
   /*  Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear'); */
    $uId = base64_decode($request->id);
    
    Auth::loginUsingId($uId);
    // dd($uId);
    return redirect('dashboard2');
});
/* Auto Login */
Route::get('insert-latlong', [LocationController::class, 'insertLatlong'])->name('insert-latlong');
Route::get('get-latlong-map', [LocationController::class, 'getLatlongMap'])->name('get-latlong-map');
Route::get('get-location', [CaseController::class, 'getlocation'])->name('get-location');
Route::get('search-location-mob/{phone}/{sessionId}', [LocationController::class, 'searchLocationMob'])->name('search-location-mob.searchLocationMob');
 /* CronJobController */
 Route::get('case-creation-mail', [CronJobController::class, 'caseCreationMail'])->name('case-creation-mail');
 Route::get('case-updation-mail', [CronJobController::class, 'caseUpdationMail'])->name('case-updation-mail');
 Route::get('case-closed-mail', [CronJobController::class, 'caseClosedMail'])->name('case-closed-mail');
 Route::get('case-re-open', [CronJobController::class, 'caseReOpen'])->name('case-re-open');

 Route::get('ticket-hold-mail', [CronJobController::class, 'ticketHoldMail'])->name('ticket-hold-mail');
 /* CronJobController */