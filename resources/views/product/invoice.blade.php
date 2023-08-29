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
                        <h3 class="page-title">Invoice</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Invoice</li>
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

                <div class="row pb-3 pt-3">
                    <div class="col-md-12 col-sm-12 text-center">
                        <h3><u>GENERAL CARGO PROOF OF DELIVERY</u></h3>
                    </div>
                </div>

                <!-- customer billing info -->
                <div class="row pb-3 pt-3">
                    <div class="col-md-8 col-sm-12">
                        <div class="table-responsive">
                            <table class="table custom-table table-bordered" id="DataTable myTable">
                                <tbody class="tbody">
                                <tr>
                                    <td>
                                        Date:  {{$product->date ?? ""}} <br>
                                        Invoice No:  {{$product->invoice_no ?? ""}} <br>
                                        Booking Branch:  {{$product->booking_branch ?? ""}} <br>
                                        Destination Branch:  {{$product->destination_branch ?? ""}}
                                    </td>
                                    <td>
                                        Service Type: {{$product->service_type ?? ""}} <br>
                                        Delivery Mode: {{$product->delivery_mode ?? ""}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <img src="{{ asset('images/ilogo.png') }}" style="width: 200px; height: 130px;"   alt="">
                    </div>
                </div>

                <!-- sender and receiver info -->
                <div class="row pb-3 pt-2">
                    <div class="col-md-12 col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-borderless" id="">
                                <tbody >
                                <tr>
                                    <td>
                                        SENDER INFORMATION: <br><br>
                                        Name: {{$product->sender_name ?? ""}}<br>
                                        Contact No: {{$product->sender_phone ?? ""}}<br>
                                        Address: {{$product->sender_address ?? ""}}
                                    </td>
                                    <td>
                                        SENDER INFORMATION: <br><br>
                                        Name: {{$product->receiver_name ?? ""}}<br>
                                        Contact No: {{$product->receiver_phone ?? ""}}<br>
                                        Address: {{$product->receiver_address ?? ""}}
                                    </td>
                                    <td class="text-center">
                                        <img src="{{ asset('assets/images/barcode.png') }}" style="width: 200px; height: 70px;"   alt="">
                                        <br><br>
                                        General ....................
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- product info -->
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="table-responsive">
                            <table class="table custom-table table-bordered" id="DataTable myTable">
                                <thead>
                                <tr class="text-center">
                                    <th>PRODUCT DESCRIPTION</th>
                                    <th>QUANTITY (PCS)</th>
                                    <th>UNIT (KG)</th>
                                </tr>
                                </thead>
                                <tbody class="tbody">
                                <tr class="text-center">
                                    <td> {{$product->product_description ?? ""}} </td>
                                    <td> {{$product->quantity ?? ""}} </td>
                                    <td> {{$product->unit ?? ""}} </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 pt-2">
                        <P>I confirming that all item have been received in well condition</P>
                    </div>
                </div>

                <!-- operation manager, datetime, sign info -->
                <div class="row pb-3 pt-2">
                    <div class="col-md-12 col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-borderless" id="">
                                <tbody>
                                <tr class="text-center">
                                    <td>OPERATION MANAGER:</td>
                                    <td>DATE & TIME: .........................</td>
                                    <td>RECEIVER SIGN: .........................</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- product info -->
                <div class="row p-2 m-2">
                    <div class="col-md-5 col-sm-12" style="border: 1px solid black">
                        <p class="text-center pt-2">GENERAL & CARGO LTD SYLHET OFFICE:</p>
                        <img src="{{ asset('images/BD.png') }}" style="display: block;margin-left: auto;margin-right: auto;width: 20%;"   alt="">
                        <p>NIRVANA INN /UK Visa Application Centre MIRZA JANGALROAD, SYLHET 3100, BANGLADESH 01308354327/01964-737553, +442035007493</p>

                    </div>
                    <div class="col-md-2 col-sm-12">
                    </div>
                    <div class="col-md-5 col-sm-12 p-1" style="border: 1px solid black">
                        <p class="text-center pt-2">GENERAL & CARGO LTD LONDON OFFICE:</p>
                        <img src="{{ asset('images/UK.png') }}" style="display: block;margin-left: auto;margin-right: auto;width: 20%;"   alt="">
                        <br>
                        <p class="text-center">
                            1B Fourth Avenue, LONDON <br>
                            E12 6DB <br>
                            Tel.:020 7993 6755
                        </p>
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
