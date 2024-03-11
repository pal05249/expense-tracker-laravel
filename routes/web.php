<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Auth;
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
})->middleware('guest');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::resource('categories',CategoryController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('dashboard-details',DashboardController::class);
    Route::get('/logged-in-user-details',[DashboardController::class,'fetchLoggedInUserDetails']);
    Route::put('/update-profile/{userId}',[UserProfileController::class,'UpdateUserDetails']);
    Route::put('/reset-password/{userId}',[UserProfileController::class,'updatePassword']);
    Route::get('/fetch-doughnut-chart-data',[DashboardController::class,'fetchDoughnutChartDetails']);
    
    Route::get('/fetch-area-chart-data',[DashboardController::class,'fetchAreaChartDetails']);

});


Route::get('/{vue_capture?/}', function () {
    return view('home');
})->where('vue_capture', '[\/\w\.-]*')->middleware('auth');
