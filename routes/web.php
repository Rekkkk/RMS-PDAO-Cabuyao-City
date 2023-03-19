<?php

// use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountManagementController;
use App\Http\Controllers\PwdManagementController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Controllers;

Route::group(['middleware' => 'notLogin'], function(){

    Route::group([ 'middleware' => 'backBtn'], function(){

        Route::view('/', 'landingpage/landingpages/landing-page')->name('home');
        Route::view('/about-us', 'landingpage/landingpages/about-us')->name('about.us');

        Route::prefix('appointments')->group(function () {  

            Route::view('/application2', 'landingpage/landingpages/appointments/application-form2');
            Route::view('/pwd-renewal-id', 'landingpage/landingpages/appointments/renewal')->name('appointment.renewal');
            Route::view('/pwd-lost-id', 'landingpage/landingpages/appointments/lost-id')->name('appointment.lost.id');
            Route::view('/pwd-request-cancellation', 'landingpage/landingpages/appointments/cancellation-request-letter')->name('appointment.pwd.cancellation');

            Route::controller(AppointmentController::class)->group(function () {
                Route::get('/new-application', 'appointmentApplicant')->name('appointment.new-applicant');
                Route::post('/new-application', 'newApplicantCreate')->name('new-applicant.create');
                Route::get('/application-success/{appointment}', 'applicationSuccess')->name('applicant.success');
                Route::post('/renewal-submit', 'renewalId')->name('renewal.create');
                Route::get('/renewalID-success/{appointment}', 'renewalIdSuccess')->name('renewalID.success');
                Route::post('/lost-id-submit', 'lostId')->name('lost.id.create');
                Route::get('/lostID-success/{appointment}', 'lostIdSuccess')->name('lostID.success');
                Route::get('/print-appointment/{appointment}', 'printReferenceNumber')->name('print.appointment');
                Route::post('/cancellation-submit', 'cancellation')->name('cancellation.create');
                Route::get('/cancellation-success/{appointment}', 'cancellationSuccess')->name('cancellation.success');
            });   
        });
    
        Route::controller(ProgramController::class)->group(function(){
            Route::get('/programs', 'pwdPrograms')->name('programs');
            Route::get('/view-program/{id}', 'guestViewProgram')->name('guest-view-programs');
        //     Route::get('/program-form/{id}', 'guestProgramForm')->name('guest.programs-form');
        //     Route::post('/form-submit/{program}', 'submitProgramForm')->name('form.submit');
        //     Route::get('/program-form-submit/{claimants}/{program}', 'submitProgramSuccess')->name('success.programs.submit');
        //     Route::get('/print-control-number/{claimants}/{program}', 'printControlNumber')->name('print.control');
            
        });
    
        Route::controller(AuthController::class)->group(function () {
            Route::get('/login', 'loginPage')->name('login');
            Route::post('/checking', 'login')->name('login.check');
        });

    });
});

Route::group(['prefix' => 'logged-in', 'middleware' => 'authCheck', 'middleware' => 'newAccount'], function(){
    Route::controller(AccountManagementController::class)->group(function () {
        Route::get('/downloads', 'downloads')->name('download.test');

    });



    Route::group([ 'middleware' => 'backBtn'], function(){

        Route::get('/pwd-management', [PwdManagementController::class, 'pwdManagementPage'])->name('pwd.management');
        Route::get('/view-pwd/{id}', [PwdManagementController::class, 'viewPwd'])->name('view.pwd');
        Route::get('/print-pwd/{pwd}', [PwdManagementController::class, 'printPWD'])->name('print.pwd');
        Route::post('/upload-signatory-id', [PwdManagementController::class, 'uploadIDSignature'])->name('upload.id.signatory');
        Route::post('/upload-signatory-cancelation', [PwdManagementController::class, 'uploadCancelationSignature'])->name('upload.cancelation.signatory');
        Route::post('/upload-id-picture', [PwdManagementController::class, 'uploadIDPicture'])->name('upload.picture.id');

        Route::controller(AccountManagementController::class)->group(function () {
            Route::get('/myAccount', 'myAccount')->name('my.account');
            Route::post('/change-password', 'changePassword')->name('change.password');

        });

        Route::controller(AuthController::class)->group(function () {
            Route::get('/dashboard', 'dashboard')->name('dashboard');
            Route::get('/change-password', 'forceChangePassword')->name('force.change.password');
            Route::get('/logout', 'logOut')->name('logout');
        });

        // Route::view('/test', 'userpages/report-template/all-barangay-status');

        Route::group(['middleware' => 'isOIC|President'], function () {

            Route::prefix('pwd-management')->group(function () {
                Route::controller(PwdManagementController::class)->group(function () {

                    Route::get('/pwd-docs/{pwd}', 'viewPwdDocs')->name('pwd.docs');
                    Route::get('/autocomplete-search', 'autoCompleteDisease')->name('search');
                    
                });
            });

            Route::prefix('reports')->group(function () {
                Route::controller(ReportsController::class)->group(function(){
                    Route::post('/pwd-report-generate', 'generatePWDStatusReports')->name('pwd.report.generate');
                    Route::post('/upload-graph/{barangay}', 'pwdStatusGraph')->name('upload.graph');

                    Route::post('/initial-report-beneficiaries-oic/{id}', 'generateInitialProgramReports')->name('initial.report.beneficiaries.oic');
                    Route::get('/initial-report-attendee/{id}', 'generateInitialAttendee')->name('initial.report.attendee');
                    Route::get('/initial-report-beneficiaries/{id}', 'generateInitialProgramReportsBarangay')->name('initial.report.beneficiaries');
                    Route::get('/program-report-summary/{program}', 'generateClaimedReports')->name('program.report.summary');
                    Route::post('/program-report-summary-oic/{program}', 'generateClaimedReportsOic')->name('program.report.summary.oic');
                    Route::post('/disability-report-summary', 'generateDisabilityReport')->name('disability.report');
                });
            });

            Route::controller(ProgramController::class)->group(function(){

                    Route::get('/program-management/{sortBy}', 'programManagement')->name('program.management');
                    Route::get('/sort-by/{sortby}', 'sortBy')->name('sort.program'); 
                    Route::get('/view-program/{id}', 'viewProgram')->name('view.program');       
                    Route::get('/view-beneficiaries/{id}', 'viewBeneficiariesList')->name('view.beneficiaries');
                    Route::get('/remove-beneficiary/{pwd}{program}', 'removeBeneficiary')->name('remove.beneficiaries');
                    Route::get('/print-beneficiary/{id}', 'BeneficiariesList')->name('print.beneficiaries');
                    Route::get('/print-unclaimList/{id}', 'printUnclaimList')->name('print.unclaim.list');
                    Route::post('/print-beneficiary-oic/{id}', 'BeneficiariesListPrintOIC')->name('print.beneficiaries.oic');
                    Route::get('/encode-beneficiaries/{id}', 'programBeneficiaryDetails')->name('beneficiaries.details');
                    Route::get('/program-remark/{pwd}/{program}', 'isClaim')->name('is.claim');
                    Route::post('/add-beneficiary/{program}', 'addPwdBeneficiary')->name('add.beneficiary');
                    Route::post('/upload-signatory/{barangay}/{program}', 'uploadSignatory')->name('upload.signatory');
            });

        });
        
        //Staff Routes
        Route::group(['middleware' => 'isStaff'], function () {

            Route::controller(AppointmentController::class)->group(function () {
                Route::post('/limit-appointment', 'limitDateAppointment')->name('limit.appointment');
                Route::post('/edit-limit-appointment', 'editLimitDateAppointment')->name('edit.limit.appointment');
                Route::post('/disable-date', 'disableDate')->name('disable.date');
                Route::get('/remove-disable-date/{date}', 'removedisableDate')->name('remove.disable.date');
            });

            Route::view('/manage-appointment/manage-appointment', 'userpages/appointmentmanage/manage-appointment')->name('manage.appointment');
            Route::view('/manage-appointment/disable-date-appointment', 'userpages/appointmentmanage/disable-date')->name('disable.date.page');

            Route::prefix('walk-in')->group(function () {
                Route::controller(PwdManagementController::class)->group(function () {
                    Route::get('/', 'pwdManagementPage')->name('walk.page');
                    Route::post('/applicant-save', 'walkInPwd')->name('walkin.create');
                    Route::get('/add-pwd/{pwd}', 'addNewPwd')->name('add.pwd');
                    Route::get('/generate-id/{pwd}', 'generateID')->name('generate.id');
                    Route::get('/renewal-pwd/{pwd}', 'walkInRenewal')->name('renewal.pwd');
                    Route::post('/renewal-process/{pwd}', 'acceptRenewal')->name('renewal.accept');
                    Route::get('/lost-id-pwd/{pwd}', 'walkInLostID')->name('lostid.pwd');
                    Route::post('/lostid-process/{pwd}', 'acceptLostID')->name('lostid.accept');
                    Route::get('/cancellation-pwd/{pwd}', 'walkInCancellation')->name('cancellation.pwd');
                    Route::post('/cancellation-process/{pwd}', 'acceptCancellation')->name('cancellation.accept');
                    Route::get('/generateid/{pwd}', 'generateID')->name('print.id');
                    Route::get('/walk-in-history', 'walkInHistory')->name('walkin.history');
                    Route::get('/update/{id}', 'updatePwd')->name('update.pwd');
                    Route::put('/update-save{pwd}', 'updateSave')->name('update.pwd.save');         
                        
                    
                });

                Route::controller(ReportsController::class)->group(function(){
                     Route::post('/transaction-reports-generate', 'transactionGraph')->name('get.transaction.report');
                    Route::post('/appointment-report', 'generateAppointmentReports')->name('appointment.report.generate');  
                });
               

                Route::view('/walk-in-applicant', 'userpages/staff-pages/walkin-transaction/walk-in-applicant')->name('walkin.pwd');

            });

            Route::prefix('appointments')->group(function () {
                Route::controller(AppointmentController::class)->group(function () {
                    Route::get('/', 'appointmentPage')->name('appointment.page');
                    Route::get('/all-appointment', 'viewAllAppointment')->name('all.appointment');
                    Route::get('/view-appointment{id}', 'selectAppointment')->name('select.appointment');
                    Route::get('/generate-id/page{pwd}/{transaction}', 'generateIdPage')->name('generate.id.page');
                    Route::get('/generate-cancelled-letter/page/{pwd}', 'generateCancelledLetter')->name('generate.cancelled.letter');
                    Route::get('/applicant-accept{id}', 'acceptApplicant')->name('accept.applicant');
                    Route::get('/renewal-accept/{pwd}/{appointment}', 'acceptRenewalId')->name('accept.renewal.id');
                    Route::get('/lost-id-accept/{id}/{appointment}', 'acceptLostId')->name('accept.lost.id');
                    Route::get('/cancellation-accept/{pwd}/{appointment}', 'acceptCancellation')->name('accept.cancellation');
                    Route::post('/re-appointment/{appointment}', 'reAppointment')->name('re-appointment');
                    Route::post('/upload-appointment-docs/{appointment}', 'updloadAppointmentDocs')->name('appointment.docs');
                    Route::get('/delete-appointment-docs/{imageId}', 'deleteDocs')->name('delete.docs');

                });
            });

            // Route::post('/appoimtment-report', [ReportsController::class, 'generateAppointmentReports'])->name('appointment.report.generate');
            
            
            
        });
        
        
        //President Routes
        Route::group(['middleware' => 'isPresident'], function () {

          

        });

        //OIC Routes
        Route::group(['middleware' => 'isOIC'], function () {
 
            Route::get('/signatory', [PwdManagementController::class, 'signatoryPage'])->name('signatory');


            
           
            Route::controller(AccountManagementController::class)->group(function () {
                Route::get('/account-management', 'accountManagementPage')->name('account.management');
                Route::get('/account-management/create-account', 'createNewAccountPage')->name('create.account');
                Route::post('/create', 'createNewAccount')->name('account.create');
                Route::get('/dowload-auth/{user}', 'dowloadAuth')->name('dowload.auth');
                Route::get('/account/{id}', 'viewAccount')->name('view.account');
                Route::get('/disable{id}', 'disableAccount')->name('disable.account');
                Route::get('/enable/{user}', 'enableAccount')->name('enable.account');
                Route::post('/suspend{id}', 'suspendAccount')->name('suspend.account');
                Route::post('/reset-password/{id}', 'resetPassword')->name('reset.password');
                Route::get('/edit-account/{user}', 'editAccount')->name('edit.account');
                Route::post('/edit-account-save/{user}', 'editAccountSave')->name('edit.account.save');
            });       

            Route::controller(ProgramController::class)->group(function(){

                Route::get('/create-program', 'createProgram')->name('create.program');
                Route::post('/create-program/save', 'createProgramSave')->name('program.save');
                Route::get('/edit-program/{program}', 'editProgram')->name('edit.program');
                Route::put('/edit-program-save/{program}', 'editProgramSave')->name('edit.program.save');
                Route::get('/delete-image-program/{picture}', 'deletePicture')->name('delete.image.program');
                Route::get('/delete-memo-program/{memorandum}', 'deleteMemo')->name('delete.memo.program');
                Route::get('/set-program-encode/{program}', 'setEncoding')->name('set.encoding');
                Route::get('/set-program-status/{program}', 'markAsDone')->name('set.status');
                Route::get('/email/{program}', 'emailPWD')->name('email.pwd');
                
            });

            // Route::get('/activity-logs', function () {
            //     return view('userpages/activitylog/activity-log');
            // })
            Route::get('/activity-logs-data', [ActivityLogController::class, 'index']);

            Route::get('/activity-logs', [ActivityLogController::class, 'activityLogPage'])->name('activity.log');
        });

        //OIC Account Manager
        Route::group(['middleware' => 'isOICManager'], function () {
            Route::prefix('account-manager')->group(function () {  
                Route::controller(AccountManagementController::class)->group(function () {
                    Route::get('/oic', 'accountManagementPage')->name('account.management.oic');
                    Route::get('/create-account-oic', 'createNewAccountPage')->name('create.account.oic');
                    Route::post('/create-oic', 'createNewAccount')->name('oic.create');
                    Route::get('/oic-account{id}', 'viewAccount')->name('view.oic.account');
                    Route::get('/dowload-auth/{user}', 'dowloadAuth')->name('dowload.oic.auth');
                    Route::get('/disable{id}', 'disableAccount')->name('disable.oic.account');
                    Route::get('/enable/{user}', 'enableAccount')->name('enable.oic.account');
                    Route::post('/suspend{id}', 'suspendAccount')->name('suspend.oic.account');
                    Route::post('/reset-password/{id}', 'resetPassword')->name('reset.oic.password');
                    Route::get('/edit-account-oic/{user}', 'editAccount')->name('edit.oic.account');
                    Route::post('/edit-account-save-oic/{user}', 'editAccountSave')->name('edit.oic.account.save');
                });
            });

        });
    });

});

