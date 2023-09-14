<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserManagementController;




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

# ------------------------------ website home page ---------------------------------#
Route::get('/', function (){ return redirect()->route('login');})->name('frontend.home');


Route::group(['middleware'=>'auth'],function()
{
    Route::get('home',function()
    {
        return view('home');
    });
    Route::get('home',function()
    {
        return view('home');
    });
});

Auth::routes();

# ------------------------------ Customer register ---------------------------------#
//Route::get('/register', function (){ return redirect()->route('login'); })->name('register');
Route::get('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/register', [RegisterController::class, 'storeUser'])->name('register');

// -----------------------------login----------------------------------------//
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// ----------------------------- main dashboard ------------------------------//
Route::get('/home', [HomeController::class, 'index'])->name('home');

# ----------------------------- package ----------------------------------------#
Route::get('package/index',[PackagesController::class,'index'])->middleware('auth')->name('package.index');
Route::post('package/store',[PackagesController::class,'store'])->middleware('auth')->name('package.store');
Route::post('package/update',[PackagesController::class,'update'])->middleware('auth')->name('package.update');

# ----------------------------- merchant ----------------------------------------#
Route::get('merchant/index',[MerchantController::class,'index'])->middleware('auth')->name('merchant.index');
Route::post('merchant/store',[MerchantController::class,'store'])->middleware('auth')->name('merchant.store');
Route::post('merchant/update',[MerchantController::class,'update'])->middleware('auth')->name('merchant.update');

# ----------------------------- category ----------------------------------------#
Route::get('category/index',[CategoriesController::class,'index'])->middleware('auth')->name('category.index');
Route::post('category/store',[CategoriesController::class,'store'])->middleware('auth')->name('category.store');
Route::post('category/update',[CategoriesController::class,'update'])->middleware('auth')->name('category.update');


// ----------------------------- product ----------------------------------------//
Route::get('product/index',[ProductsController::class,'index'])->middleware('auth')->name('product.index');
Route::post('product/store',[ProductsController::class,'store'])->middleware('auth')->name('product.store');
Route::post('product/update',[ProductsController::class,'update'])->middleware('auth')->name('product.update');
Route::post('product/delete',[ProductsController::class,'delete'])->middleware('auth')->name('product.delete');
Route::post('product/statusUpdate',[ProductsController::class,'statusUpdate'])->middleware('auth')->name('product.statusUpdate');
Route::get('product/invoice/{product_id}',[ProductsController::class,'invoice'])->middleware('auth')->name('product.invoice');
Route::get('product/label/{product_id}',[ProductsController::class,'label'])->middleware('auth')->name('product.label');

// ----------------------------- user userManagement -----------------------//
Route::get('userManagement', [UserManagementController::class, 'index'])->middleware('auth')->name('userManagement');
Route::post('user/add/save', [UserManagementController::class, 'addNewUserSave'])->name('user/add/save');
Route::post('search/user/list', [UserManagementController::class, 'searchUser'])->name('search/user/list');
Route::post('update', [UserManagementController::class, 'update'])->name('update');
//Route::post('user/delete', [UserManagementController::class, 'delete'])->middleware('auth')->name('user/delete');
# users role
Route::get('user/role/{userID?}',[UserManagementController::class,'roleUser'])->middleware('auth')->name('roleUser');
Route::post('user/role/update',[UserManagementController::class,'userRoleUpdate'])->middleware('auth')->name('user/role/update');

// -----------------------------companies----------------------------------------//
Route::get('company/view',[CompaniesController::class,'view'])->middleware('auth')->name('company/view');
Route::post('company/update',[CompaniesController::class,'update'])->middleware('auth')->name('company/update');



// ----------------------------- user profile ------------------------------//
Route::get('profile_user', [App\Http\Controllers\UserManagementController::class, 'profile'])->middleware('auth')->name('profile_user');
Route::post('profile/information/save', [App\Http\Controllers\UserManagementController::class, 'profileInformation'])->name('profile/information/save');


// ----------------------------- forget password ----------------------------//
Route::get('forget-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'getEmail'])->name('forget-password');
Route::post('forget-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'postEmail'])->name('forget-password');

// ----------------------------- reset password -----------------------------//
Route::get('reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'getPassword']);
Route::post('reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'updatePassword']);


// ----------------------------- search user management ------------------------------//
Route::post('search/user/list', [App\Http\Controllers\UserManagementController::class, 'searchUser'])->name('search/user/list');

// ----------------------------- form change password ------------------------------//
Route::get('change/password', [App\Http\Controllers\UserManagementController::class, 'changePasswordView'])->middleware('auth')->name('change/password');
Route::post('change/password/db', [App\Http\Controllers\UserManagementController::class, 'changePasswordDB'])->name('change/password/db');

