<?php

namespace App\Http\Controllers;

use App\Models\Attendances;
use App\Models\departments;
use App\Models\Employees;
use App\Models\Expenses;
use App\Models\Products;
use App\Models\ProductStatus;
use App\Models\Purchases;
use App\Models\Sales;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Models\User;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // main dashboard
    public function index()
    {
        //$company_id=Auth::user()->company_id;

        # access permission
        $obj=new UserPermissionsController();
        $returnAccessStatus=$obj->moduleAccessPermission('Dashboard Manage');

        if( Auth::user()->type=='Admin' || $returnAccessStatus=='allow')
        {
            return view('dashboard.dashboard');
        }
        elseif (Auth::user()->type=='Merchant' || Auth::user()->type=='Agent'){
            return view('merchantPanel.dashboard.dashboard');
        }
        elseif (Auth::user()->type=='Customer'){
            return view('customerPanel.dashboard.dashboard');
        }
        else
        {
            return view('dashboard.userDashboard');
        }

    }

    /**
     * home_frontend
     * website home page
     */
    function home_frontend(Request $request){
        return view('frontend.pages.home');
    }

}
