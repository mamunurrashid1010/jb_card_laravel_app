@extends('layouts.master')
@section('content')

    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Welcome</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <div class="row">
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        <div class="card-body"> <span class="dash-widget-icon"><i class="fa fa-bar-chart"></i></span>
                            <div class="dash-widget-info">
                                <h3>00</h3> <span>Total Product</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        <div class="card-body"> <span class="dash-widget-icon"><i class="fa fa-bar-chart"></i></span>
                            <div class="dash-widget-info">
                                <h3>00</h3> <span>Booking Office</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        <div class="card-body"> <span class="dash-widget-icon"><i class="fa fa-bar-chart"></i></span>
                            <div class="dash-widget-info">
                                <h3>00</h3> <span>On The Airlines</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        <div class="card-body"> <span class="dash-widget-icon"><i class="fa fa-bar-chart"></i></span>
                            <div class="dash-widget-info">
                                <h3>00</h3> <span>Arrived in Airport</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
        <!-- /Page Content -->
    </div>
@endsection
