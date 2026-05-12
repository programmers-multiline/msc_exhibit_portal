<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\UserDropdownController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\AuthController;

use Illuminate\Support\Facades\Mail;
use App\Mail\ParticipantBrochureMail;
use App\Http\Controllers\BrochureController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\CustomerList;

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

Route::get('/participants', [ParticipantController::class, 'index'])->name('participants.index');
Route::get('/participants/create', [ParticipantController::class, 'create'])->name('participants.create');
Route::post('/participants/attendee', [ParticipantController::class, 'attendee']);
Route::get('/participants/images/{id}', [ParticipantController::class,'getImages']);
Route::post('/participants/update-status/{id}',[ParticipantController::class, 'updateStatus']);
Route::post('/participants/bulk-assign',[ParticipantController::class, 'bulkAssign']);
Route::get('/form', [UserDropdownController::class, 'create']);
Route::get('/import-sheet', [ParticipantController::class, 'importFromGoogleSheet']);
Route::post('/participants/import', [ParticipantController::class, 'importExcel']);
Route::get('/contacts', [ParticipantController::class, 'contacts']);
Route::get('/contacts/search', [ParticipantController::class, 'search']);
Route::get('/companies', [CompanyController::class, 'index']);

Route::get('/client', [CustomerList::class, 'index']);
Route::get('/client/list', [CustomerList::class, 'ClientList']);

Route::get('/client_card', [CustomerList::class, 'client_card']);
Route::get('/client/Cardlist', [CustomerList::class, 'ClientCardList']);

Route::post('/companies/update-address', [CompanyController::class, 'updateAddress']);

Route::get('/external-login', [UserLoginController::class, 'showLogin']);
Route::post('/login', [UserLoginController::class, 'login']);
//Route::post('/', [UserLoginController::class, 'logout']);

Route::post('/', [UserLoginController::class, 'logout'])->name('logout');



Route::get('/company_card', [CompanyController::class, 'companyCard'])->middleware('auth');
/* Route::get('/company_card', [CompanyController::class, 'index'])
    ->middleware('auth'); */
Route::get('/company_card/list', [CompanyController::class, 'companyCardList']);
Route::get('/get-company/{id}', [CompanyController::class, 'getCompany']);
Route::get('/get-contacts/{id}', [CompanyController::class, 'getContacts']);
Route::get('/get-contacts_details/{id}', [CompanyController::class, 'Contacts_details']);



Route::get('/reports', [ReportsController::class, 'index']);
Route::get('/participant/create',[ParticipantController::class,'create']);
Route::post('/participant/store',[ParticipantController::class,'store']);
Route::get('/participant/check-duplicate',[ParticipantController::class,'checkDuplicate']);



Route::post('/participant/store-ajax',[ParticipantController::class,'storeAjax']);
Route::get('/participant/check-duplicate',[ParticipantController::class,'checkDuplicate']);


//Route::post('/company/save', [CompanyController::class,'saveCompany']);
Route::post('/company/save', [CompanyController::class,'saveCompany'])->name('company.save');


Route::get('/download-brochure', [BrochureController::class, 'download'])->name('brochure.download');





Route::middleware(['web'])->group(function(){
    Route::post('/participant/store',[ParticipantController::class,'store']);
});



Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [UserLoginController::class, 'login'])->name('login.custom');
Route::get('/login-via-oms', [UserLoginController::class, 'login_via_oms']);

Route::get('/partcipant', function () {
    if (!session()->has('user')) {
        return redirect('/login');
    }
    return view('partcipant');
});

Route::get('/partcipant', function () {
     return redirect('/login');
})->middleware('auth:login');

Route::get('/', function () {
    return view('auth.login');
});


Route::get('/test-mail', function() {
    $participant = (object)[
        'participant_name' => 'Juan Dela Cruz',
        'email'            => 'erwincaloingprograms@gmail.com'  // <-- ilagay ang email mo dito
    ];

    try {
        Mail::to($participant->email)
            ->send(new ParticipantBrochureMail($participant));

        return 'Email sent successfully';
    } catch (\Exception $e) {
        return 'Email failed: ' . $e->getMessage();
    }
});
