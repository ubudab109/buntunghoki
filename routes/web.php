<?php

use App\Http\Controllers\AdminBankController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\RolePermissionController;
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

Route::get('/login-page', [AuthController::class, 'loginPage'])->name('login.page');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function () {

    // DATASET
    Route::group(['prefix' => 'dataset'], function () {
        Route::get('bank-payment', [AdminBankController::class, 'getBankPayments'])->name('bank-payment.dataset');
    });

    // DATATABLES API
    Route::group(['prefix' => 'datatable'], function () {
        Route::get('roles', [RolePermissionController::class, 'data'])->name('role.data');
        Route::get('admins', [AdminController::class, 'data'])->name('admin.data');
        Route::get('payments', [PaymentTypeController::class, 'data'])->name('payment.data');
        Route::get('banks', [BankController::class, 'data'])->name('bank.data');
        Route::get('admin-banks', [AdminBankController::class, 'data'])->name('admin-bank.data');
    });

    // ROLE
    Route::group(['prefix' => 'role'], function () {
        Route::get('create-page', [RolePermissionController::class, 'create'])->name('role.create');
        Route::post('', [RolePermissionController::class, 'store'])->name('role.store');
        Route::get('detail/{id}', [RolePermissionController::class, 'detail'])->name('role.detail');
        Route::post('update/{id}', [RolePermissionController::class, 'update'])->name('role.update');
    });

    // ADMIN
    Route::group(['prefix' => 'admin'], function () {
        Route::post('', [AdminController::class, 'store'])->name('admin.store');
        Route::post('update/{id}', [AdminController::class, 'update'])->name('admin.update');
        Route::get('create-page', [AdminController::class, 'create'])->name('admin.create');
        Route::get('detail/{id}', [AdminController::class, 'detail'])->name('admin.detail');
        Route::delete('delete/{id}', [AdminController::class, 'delete'])->name('admin.delete');
    });

    // PAYMENT TYPE
    Route::group(['prefix' => 'payment-type'], function () {
        Route::post('', [PaymentTypeController::class, 'store'])->name('payment.store');
        Route::post('update/{id}', [PaymentTypeController::class, 'update'])->name('payment.update');
        Route::get('detail/{id}', [PaymentTypeController::class, 'detail'])->name('payment.detail');
        Route::delete('delete/{id}', [PaymentTypeController::class, 'delete'])->name('payment.delete');
    });

    // BANK
    Route::group(['prefix' => 'bank'], function () {
        Route::post('', [BankController::class, 'store'])->name('bank.store');
        Route::post('update/{id}', [BankController::class, 'update'])->name('bank.update');
        Route::get('detail/{id}', [BankController::class, 'detail'])->name('bank.detail');
        Route::delete('delete/{id}', [BankController::class, 'delete'])->name('bank.delete');    
    });

    // ADMIN BANK
    Route::group(['prefix' => 'admin-bank'], function () {
        Route::post('', [AdminBankController::class, 'store'])->name('admin-bank.store');
        Route::get('create', [AdminBankController::class, 'create'])->name('admin-bank.create');
        Route::get('detail/{id}', [AdminBankController::class, 'detail'])->name('admin-bank.detail');
        Route::delete('delete/{id}', [AdminBankController::class, 'delete'])->name('admin-bank.delete');
        Route::post('update/{id}', [AdminBankController::class, 'update'])->name('admin-bank.update');
    });

    Route::post('company-setting', [CompanySettingController::class, 'update'])->name('company-setting.update');

    Route::get('/', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('role', [PageController::class, 'roles'])->name('role.index');
    Route::get('admin', [PageController::class, 'admin'])->name('admin.index');
    Route::get('payment-type', [PageController::class, 'paymentType'])->name('payment.index');
    Route::get('bank', [PageController::class, 'bank'])->name('bank.index');
    Route::get('admin-bank', [PageController::class, 'adminBank'])->name('admin-bank.index');
    Route::get('company-setting', [PageController::class, 'setting'])->name('company-setting.list');
});
