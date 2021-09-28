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
inde
        //ROUTE DASHBOARD
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');
        Route::resource('/category', CategoryController::class,['as' => 'admin']);
        Route::resource('/campaign', CampaignController::class,['as' => 'admin']);

    });
});
