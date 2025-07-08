<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NinjaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\InstallationController;
use App\Http\Controllers\DatalineController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('show.register');
    Route::get('/login', 'showLogin')->name('show.login');
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
});

Route::middleware('auth')->controller(NinjaController::class)->group(function () {
    Route::get('/ninjas', 'index')->name('ninjas.index');
    Route::get('/ninjas/create', 'create')->name('ninjas.create');
    Route::get('/ninjas/{ninja}', 'show')->name('ninjas.show');
    Route::post('/ninjas', 'store')->name('ninjas.store');
    Route::delete('/ninjas/{ninja}', 'destroy')->name('ninjas.destroy');
});

//dashboard route
Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard')->middleware('auth');

//user routes
Route::middleware('auth')->controller(UserController::class)->group(function () {
    Route::get('/user', 'show')->name('user.show');
});

//customer routes
Route::middleware('auth')->controller(CustomerController::class)->group(function () {
    Route::get('/customer', 'index')->name('customer.index');
    Route::get('/customer/create', 'create')->middleware(['role:employee|admin'])->name('customer.create');
    Route::get('/customer/{customer}', 'show')->name('customer.show');
    Route::post('/customer', 'store')->middleware(['role:employee|admin'])->name('customer.store');
    Route::post('/customer/update/{id}', 'update')->middleware(['role:employee|admin'])->name('customer.update');
});

//site routes
Route::middleware('auth')->controller(SiteController::class)->group(function () {
    Route::get('/site', 'index')->name('site.index');
    Route::get('/site/create/{customer}', 'create')->middleware(['role:employee|admin'])->name('site.create');
    Route::get('/site/{site}', 'show')->name('site.show');
    Route::post('/site', 'store')->middleware(['role:employee|admin'])->name('site.store');
    Route::post('/site/update/{id}', 'update')->middleware(['role:employee|admin'])->name('site.update');
});

//installation routes
Route::middleware('auth')->controller(InstallationController::class)->group(function () {
    Route::get('/installation', 'index')->name('installation.index');
    Route::post('/installation', 'store')->middleware(['role:employee|admin'])->name('installation.store');
    Route::get('/installation/create/{site}', 'create')->middleware(['role:employee|admin'])->name('installation.create');
    Route::get('/installation/{installation}/', 'show')->name('installation.show');
    Route::post('/installation/update/{id}', 'update')->middleware(['role:employee|admin'])->name('installation.update');
    Route::delete('/installation/{installation}', 'destroy')->name('installation.destroy');
    Route::get('/installation/viewdata/{installation}', 'viewdata')->name('installation.viewdata');
    Route::post('/installation/getinfo/{installation}', 'getinfo')->name('installation.getinfo');
    Route::post('/installation/getinfoMonthly/{installation}', 'getinfoMonthly')->name('installation.getinfoMonthly');
    Route::post('/installation/getinfoHH/{installation}', 'getinfoHH')->name('installation.getinfoHH');

});

//dataline routes
Route::middleware('auth')->controller(DatalineController::class)->group(function () {
    Route::get('/dataline/{dataline}', 'show')->name('dataline.show');
    Route::post('/dataline/update/{id}', 'update')->middleware(['role:employee|admin'])->name('dataline.update');
});

