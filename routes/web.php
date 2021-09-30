<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('auth.login');
});

// FOR ADMIN


//GROUP ROUTE WITH PREFIX "ADMIN"
Route::prefix('admin')->group(function() {

    //GROUP ROUTE WITH MIDDLEWARE "AUTH"
    Route::group(['middleware' => 'auth'], function() {

        //ROUTE DASHBOARD
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');
        Route::resource('/category', CategoryController::class,['as' => 'admin']);
        Route::resource('/campaign', CampaignController::class,['as' => 'admin']);
        Route::get('/donatur', [DonaturController::class, 'index'])->name('admin.donatur.index');
        Route::get('/donation', [DonationController::class, 'index'])->name('admin.donation.index');
        Route::get('/donation/filter', [DonationController::class, 'filter'])->name('admin.donation.filter');
        Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile.index');
        Route::resource('/slider', SliderController::class, ['except' => ['show', 'create', 'edit', 'update'], 'as' => 'admin']);
    });
});


//SEDIH AKU SI COKRO DIAREE T.T