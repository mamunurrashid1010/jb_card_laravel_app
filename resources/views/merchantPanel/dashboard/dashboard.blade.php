@extends('layouts.master')
@section('content')

    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Welcome <span class="badge badge-info">Merchant</span></h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <div class="row">
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget" style="background-color: #dc3545; color: white">
                        <div class="card-body"> <span class="dash-widget-icon bg-warning"><i class="fa fa-bar-chart"></i></span>
                            <div class="dash-widget-info">
                                <h3>15</h3> <span>Demo</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget" style="background-color: #10ff10; color: white">
                        <div class="card-body"> <span class="dash-widget-icon bg-warning"><i class="fa fa-bar-chart"></i></span>
                            <div class="dash-widget-info">
                                <h3>50</h3> <span>Offer</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget" style="background-color: #355773; color: white">
                        <div class="card-body"> <span class="dash-widget-icon bg-warning"><i class="fa fa-bar-chart"></i></span>
                            <div class="dash-widget-info">
                                <h3>500</h3> <span>Product</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget" style="background-color: #31a978; color: white">
                        <div class="card-body"> <span class="dash-widget-icon bg-warning"><i class="fa fa-bar-chart"></i></span>
                            <div class="dash-widget-info">
                                <h3>0</h3> <span>Demo</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
        <!-- /Page Content -->
    </div>
@endsection
