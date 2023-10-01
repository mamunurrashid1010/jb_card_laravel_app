@extends('layouts.master')
@section('content')

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Wallet</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Wallet</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
{{--                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add"><i class="fa fa-plus"></i> Add New</a>--}}
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- toast message -->
            {!! Toastr::message() !!}

            <!-- error message -->
            @if(count($errors) > 0 )
                <div class="row p-3">
                    <div class="col-sm-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <ul class="p-0 m-0" style="list-style: none;">
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            <!-- /error message -->
            <div class="row">
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget" style="background-color: #138613; color: white">
                        <div class="card-body"> <span class="dash-widget-icon bg-warning"><i class="fa fa-google-wallet"></i></span>
                            <div class="dash-widget-info">
                                <h3>{{$totalWalletPoint}}</h3> <span>Total Wallet Point</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- table data -->
            <div class="row pt-3">
                <div class="col-md-12">
                    <h4 style="color: dimgray"><i class="fa fa-list-alt"><strong> Merchant Wise Wallet Point</strong></i></h4>
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable table-border" id="DataTable">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th><strong>Merchant</strong></th>
                                <th><strong>Transaction</strong></th>
                                <th><strong>Wallet Point</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($merchants as $key=>$merchant)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td hidden class="ids">{{ $merchant->id }}</td>
                                    <td class="merchant">
                                        {{$merchant->name}} {{$merchant->phone}}<br>
                                        {{$merchant->address ?? ''}}
                                    </td>
                                    <td>{{$merchant->merchantTransactions->where('status','confirm')->count()}}</td>
                                    <td>
                                        <span class="badge badge-success font-18">
                                             {{$merchant->merchantTransactions->where('point_status','add')->where('status','confirm')->sum('point') - $merchant->merchantTransactions->where('point_status','deduction')->where('status','confirm')->sum('point')}}
                                        </span>

                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row pt-3 pl-3">
                    <div class="col-md-12">
{{--                       {{$merchants->appends($_GET)->links()}}--}}
                    </div>
                </div>

            </div>
            <!-- /table data -->

        </div>
        <!-- /Page Content -->


    </div>
    <!-- /Page Wrapper -->


@endsection

@section('script')

    <script>
        // $('#DataTable').DataTable();
    </script>

@endsection


