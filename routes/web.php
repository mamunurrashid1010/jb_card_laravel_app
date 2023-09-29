<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\OffersController;
use App\Http\Controllers\OfferTransactionsController;
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


# ----------------------------- Multi Auth ----------------------------------------#
# login
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

# main dashboard
Route::get('/home', [HomeController::class, 'index'])->name('home');


# ----------------------------------------------- Admin Panel ---------------------------------------------------------#
# customer
Route::get('customer/index',[CustomerController::class,'index'])->middleware('auth')->name('customer.index');
Route::post('customer/store',[CustomerController::class,'store'])->middleware('auth')->name('customer.store');
Route::post('customer/update',[CustomerController::class,'update'])->middleware('auth')->name('customer.update');
Route::post('customer/accountStatusUpdate',[CustomerController::class,'accountStatusUpdate'])->middleware('auth')->name('customer.accountStatusUpdate');
Route::post('customer/packageStatusUpdate',[CustomerController::class,'packageStatusUpdate'])->middleware('auth')->name('customer.packageStatusUpdate');

# offer
Route::get('offer/getAllOffer',[OffersController::class,'getAllOffer'])->middleware('auth')->name('offer.getAllOffer');
Route::post('offer/statusUpdate',[OffersController::class,'statusUpdate'])->middleware('auth')->name('offer.statusUpdate');

#  package
Route::get('package/index',[PackagesController::class,'index'])->middleware('auth')->name('package.index');
Route::post('package/store',[PackagesController::class,'store'])->middleware('auth')->name('package.store');
Route::post('package/update',[PackagesController::class,'update'])->middleware('auth')->name('package.update');

# merchant
Route::get('merchant/index',[MerchantController::class,'index'])->middleware('auth')->name('merchant.index');
Route::post('merchant/store',[MerchantController::class,'store'])->middleware('auth')->name('merchant.store');
Route::post('merchant/update',[MerchantController::class,'update'])->middleware('auth')->name('merchant.update');

# category
Route::get('category/index',[CategoriesController::class,'index'])->middleware('auth')->name('category.index');
Route::post('category/store',[CategoriesController::class,'store'])->middleware('auth')->name('category.store');
Route::post('category/update',[CategoriesController::class,'update'])->middleware('auth')->name('category.update');


# product
Route::get('product/index',[ProductsController::class,'index'])->middleware('auth')->name('product.index');
Route::post('product/store',[ProductsController::class,'store'])->middleware('auth')->name('product.store');
Route::post('product/update',[ProductsController::class,'update'])->middleware('auth')->name('product.update');
Route::post('product/delete',[ProductsController::class,'delete'])->middleware('auth')->name('product.delete');
Route::post('product/statusUpdate',[ProductsController::class,'statusUpdate'])->middleware('auth')->name('product.statusUpdate');
Route::get('product/invoice/{product_id}',[ProductsController::class,'invoice'])->middleware('auth')->name('product.invoice');
Route::get('product/label/{product_id}',[ProductsController::class,'label'])->middleware('auth')->name('product.label');

# user userManagement
Route::get('userManagement', [UserManagementController::class, 'index'])->middleware('auth')->name('userManagement');
Route::post('user/add/save', [UserManagementController::class, 'addNewUserSave'])->name('user/add/save');
Route::post('search/user/list', [UserManagementController::class, 'searchUser'])->name('search/user/list');
Route::post('update', [UserManagementController::class, 'update'])->name('update');
//Route::post('user/delete', [UserManagementController::class, 'delete'])->middleware('auth')->name('user/delete');
# users role
Route::get('user/role/{userID?}',[UserManagementController::class,'roleUser'])->middleware('auth')->name('roleUser');
Route::post('user/role/update',[UserManagementController::class,'userRoleUpdate'])->middleware('auth')->name('user/role/update');

# profile
Route::get('user/profile', [UserManagementController::class, 'profile'])->middleware('auth')->name('user.profile');
Route::post('user/profile/update', [UserManagementController::class, 'profileUpdate'])->middleware('auth')->name('user.profile.update');

# companies
Route::get('company/view',[CompaniesController::class,'view'])->middleware('auth')->name('company/view');
Route::post('company/update',[CompaniesController::class,'update'])->middleware('auth')->name('company/update');

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



# ----------------------------------------------- Merchant Panel ------------------------------------------------------#
# offer
Route::get('merchant/offer/index',[OffersController::class,'index'])->middleware('auth')->name('merchant.offer.index');
Route::post('merchant/offer/store',[OffersController::class,'store'])->middleware('auth')->name('merchant.offer.store');
Route::post('merchant/offer/update',[OffersController::class,'update'])->middleware('auth')->name('merchant.offer.update');

# offer-transaction (reward)
Route::get('merchant/offer/transaction/create',[OfferTransactionsController::class,'create'])->middleware('auth')->name('merchant.offer.transaction.create');

# agent
Route::get('merchant/agent/index',[AgentController::class,'index'])->middleware('auth')->name('merchant.agent.index');
Route::post('merchant/agent/store',[AgentController::class,'store'])->middleware('auth')->name('merchant.agent.store');
Route::post('merchant/agent/update',[AgentController::class,'update'])->middleware('auth')->name('merchant.agent.update');

# profile
Route::get('merchant/profile', [MerchantController::class, 'profile'])->middleware('auth')->name('merchant.profile');


# ----------------------------------------------- Customer Panel ------------------------------------------------------#
# Customer register
//Route::get('/register', function (){ return redirect()->route('login'); })->name('register');
Route::get('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/register', [RegisterController::class, 'storeUser'])->name('register');

# offer
Route::get('customer/offer/index',[OffersController::class,'getCustomerOfferList'])->middleware('auth')->name('customer.offer.index');

# profile
Route::get('customer/profile', [CustomerController::class, 'profile'])->middleware('auth')->name('customer.profile');
Route::post('customer/profile/update', [CustomerController::class, 'update'])->middleware('auth')->name('customer.profile.update');
Route::get('getCustomerList_searchByName', [CustomerController::class, 'getCustomerList_searchByName'])->middleware('auth')->name('getCustomerList_searchByName');
