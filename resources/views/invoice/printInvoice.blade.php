@extends('layouts.master')
@section('content')

    <!-- sub total calculation -->
    @php $subTotal=0; @endphp

        <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Invoice Details</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Invoice Manage / Print Invoice</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <span class="btn btn-info"  onclick="printDiv('content')" ><i class="fa fa-print"></i> Print</span>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            {{-- message --}}
            {!! Toastr::message() !!}

            <div id="content">
                <!-- company info -->
                <div class="row pb-3 pt-3 m-2">
                    <div class="col-md-12 col-sm-12">
                        <img src="{{ asset('images/logo.png') }}" style="width: auto; height: 100px;border-radius: 10px"   alt="">
                    </div>
                    <div class="col-md-6 col-sm-6">
{{--                        <h2>XTRA SAVY</h2>--}}
{{--                        <h4>District 315 B4, Bangladesh</h4>--}}
                    </div>
                    <div class="col-md-6 col-sm-6 text-center">
                        <h2>MONEY RECEIPT</h2>
                    </div>
                </div>
                <!-- customer billing info -->
                <div class="row pb-3 pt-3 m-2">
                    <div class="col-md-8 col-sm-6">
                        <div class="table-responsive pl-2">
                            <table width="100%" cellpadding="0" cellspacing="0" >
                                <tbody>
                                <h4>Billing Info</h4>
                                <tr>
                                    <td><b>Name</b></td>
                                    <td>:</td>
                                    <td>{{$invoice->userInfo->name ?? ""}}</td>
                                </tr>
                                <tr>
                                    <td><b>Phone</b></td>
                                    <td>:</td>
                                    <td>{{$invoice->userInfo->phone ?? ""}}</td>
                                </tr>
                                <tr>
                                    <td><b>Email</b></td>
                                    <td>:</td>
                                    <td>{{$invoice->userInfo->email ?? ""}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="table-responsive pl-2">
                            <table width="100%" cellpadding="0" cellspacing="0" >
                                <tbody>
                                <br>
                                <tr>
                                    <td><b>Invoice Number</b></td>
                                    <td>:</td>
                                    <td >{{$invoice->invoice_no}}</td>
                                </tr>
                                <tr>
                                    <td><b>Date</b></td>
                                    <td>:</td>
                                    <td>@php echo date('d M Y',strtotime($invoice->date)) @endphp</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- product info -->
                <div class="row m-2">
                    <div class="col-md-12 col-sm-12">
                        <div class="table-responsive">
                            <table class="table custom-table table-bordered" id="DataTable myTable">
                                <thead>
                                <tr class="thead-dark" style="background-color: #e7e7e7">
                                    <th style="max-width: 10px">Serial No</th>
                                    <th style="max-width: 120px">Description</th>
                                    <th class="text-right" style="min-width: 100px">Amount</th>
                                </tr>
                                </thead>
                                <tbody class="tbody">
                                    <tr class="">
                                        <td>1</td>
                                        <td>{{$invoice->description}}</td>
                                        <td class="text-right">${{$invoice->amount}}</td>
                                    </tr>
                                    <tr class="">
                                        <td>2</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr class="">
                                        <td>3</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr class="">
                                        <td>4</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr class="">
                                        <td>5</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                <tr>
                                    <td colspan="2">
                                        <span class="float-right"><b>Total</b></span>
                                    </td>
{{--                                    <td class="text-right"><b>Total</b></td>--}}
                                    <td class="text-right">${{$invoice->amount}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row pt-5 m-2">
                    <div class="col-md-12 col-sm-12 text-center">
                        <h4><b>Thank You</b></h4>
                    </div>
                    <div class="col-md-12 col-sm-12 pt-4 text-right">
                        <h5>Print Data : @php echo date('d M Y') @endphp </h5>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
@endsection
@section('script')
    <script>
        <!-- print -->
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>
@endsection
