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
                        <h3 class="page-title">Product Label</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Product Label</li>
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
                <!-- sender and receiver info -->
                <div class="row pb-3 pt-2">
                    <div class="col-md-12 col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-borderless" id="">
                                <tbody >
                                <tr>
                                    <td>
                                        <img src="{{ asset('assets/images/barcode.png') }}" style="width: 500px; height: 250px;"   alt="">
                                        <h3 class="pl-5">Reference Number : {{$product->invoice_no ?? ""}} </h3>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
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
