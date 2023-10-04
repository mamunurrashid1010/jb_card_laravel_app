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
                        <h3 class="page-title">Transaction Details</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Merchant / Transaction details </li>
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

            <!-- merchant info, today sales, total sales -->
            <div class="row">
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        <div class="card-body" style="background-color: #eee">
                            <p>
                                Merchant Info <hr>
                                Name  : {{ $merchantInfo->name}} <br>
                                Email : {{ $merchantInfo->email}} <br>
                                Phone : {{ $merchantInfo->phone ?? ''}}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget" style="background-color: #355773; color: white">
                        <div class="card-body"> <span class="dash-widget-icon bg-warning"><i class="fa fa-bar-chart"></i></span>
                            <div class="dash-widget-info">
                                <h3>${{$today_transaction_amount}}</h3> <span>Today Sales</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget" style="background-color: #31a978; color: white">
                        <div class="card-body"> <span class="dash-widget-icon bg-warning"><i class="fa fa-bar-chart"></i></span>
                            <div class="dash-widget-info">
                                <h3>${{$total_transaction_amount}}</h3> <span>Total Sales</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Filter -->
            <form action="{{ route('merchant.transactionDetails',$merchant_id) }}" method="GET">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-2">
                        <div class="form-group form-focus">
                            <label>Invoice No</label>
                            <input id="" name="invoice_no" class="form-control" type="text" value="{{Request()->get('invoice_no')}}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-2">
                        <div class="form-group form-focus">
                            <label>From Date</label>
                            <input id="datepicker" name="fromDate" class="form-control" type="date" value="{{Request()->get('fromDate')}}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-2">
                        <div class="form-group form-focus">
                            <label>To Date</label>
                            <input id="datepicker" name="toDate" class="form-control" type="date" value="{{Request()->get('toDate')}}">
                        </div>
                    </div>
                    <div class="col-sm-2 col-md-2 col-lg-2">
                        <button type="submit" class="btn btn-success btn-block" style="margin-top: 28px"> Search </button>
                    </div>
                </div>
            </form>
            <!-- /Search Filter -->

            <!-- table data -->
            <div class="row pt-3">
                <div class="col-md-12">
                    <h4 style="color: dimgray"><i class="fa fa-list-alt"><strong> Transaction List</strong></i></h4>
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable table-border" id="DataTable">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th><strong>Customer</strong></th>
                                <th><strong>Offer</strong></th>
                                <th><strong>Invoice No</strong></th>
                                <th><strong>Amount($)</strong></th>
                                <th><strong>Discount</strong></th>
                                <th><strong>Point</strong></th>
                                <th><strong>Point Status</strong></th>
                                <th style="width: 200px"><strong>Description</strong></th>
                                <th><strong>Date</strong></th>
                                <th><strong>Created By</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($transactionDetails as $key=>$transaction)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td hidden class="ids">{{ $transaction->id }}</td>
                                    <td class="customer">
                                        {{$transaction->customerInfo->name}}
                                        <span style="color: darkgrey">(ID: {{$transaction->customerInfo->id}}) </span>
                                    </td>
                                    <td class="customer">
                                        {{$transaction->offerInfo->name}}
                                        <span style="color: darkgrey">(Code: {{$transaction->offerInfo->offer_code}}) </span>
                                    </td>
                                    <td class="invoice_no">{{$transaction->invoice_no}}</td>
                                    <td class="amount">{{$transaction->amount}}</td>
                                    <td class="discount">{{$transaction->discount}}</td>
                                    <td class="point">{{$transaction->point}}</td>
                                    <td class="point_status">{{$transaction->point_status}}</td>
                                    <td class="description"><textarea class="w-100">{{$transaction->details}}</textarea></td>
                                    <td class="created-at">{{ date('d M Y',strtotime($transaction->created_at)) }}</td>
                                    <td class="user">{{$transaction->userInfo->name}}</td>
                                    <td class="text-center">
                                        <a class="Update" data-toggle="modal" data-id="'.$transaction->id.'" data-target="#edit" style="color: #bdad11;cursor: pointer"><button class="btn btn-warning btn-sm">Edit</button></a>
                                        <a class="Delete" data-toggle="modal" data-id="'.$transaction->id.'" data-target="#delete" style="color: #bdad11;cursor: pointer"><button class="btn btn-danger btn-sm">Delete</button></a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row pt-3 pl-3">
                    <div class="col-md-12">
                       {{$transactionDetails->appends($_GET)->links()}}
                    </div>
                </div>

            </div>
            <!-- /table data -->

            <!-- Edit Modal -->
            <div id="edit" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <br>
                        <div class="modal-body">
                            <form action="{{route('merchant.transaction.update')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="edit_id" name="id" class="edit_id" value="">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Invoice No <span class="text-danger">*</span></label>
                                            <input type="text" id="e_invoice_no" name="invoice_no" class="form-control" value="" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Amount <span class="text-danger">*</span></label>
                                            <input type="number" step="any" id="e_amount" name="amount" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Discount <span class="text-danger">*</span></label>
                                            <input type="text" id="e_discount" name="discount" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Point <span class="text-danger">*</span></label>
                                            <input type="number" step="any" id="e_point" name="point" class="form-control" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Point status(add/deduction/none) <span class="text-danger">*</span></label>
                                        <select class="select" name="point_status" id="e_point_status" required>
                                            <option selected value="none">none</option>
                                            <option value="add">add</option>
                                            <option value="deduction">deduction</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="submit-section">
                                    <button type="submit" class="btn btn-primary submit-btn">Update Now</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Edit Modal -->

            <!-- Delete Modal -->
            <div class="modal custom-modal fade" id="delete" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete Transaction</h3>
                                <p>Are you sure you want to delete?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <form action="{{route('merchant.transaction.delete')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" class="e_id" value="">
                                    <div class="row">
                                        <div class="col-6">
                                            <button type="submit" class="btn btn-primary continue-btn submit-btn">Delete</button>
                                        </div>
                                        <div class="col-6">
                                            <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Delete Modal -->


        </div>
        <!-- /Page Content -->


    </div>
    <!-- /Page Wrapper -->


@endsection

@section('script')

    <script>
        // $('#DataTable').DataTable();
    </script>

    {{-- update js --}}
    <script>
        $(document).on('click','.Update',function()
        {
            var _this = $(this).parents('tr');
            $('#edit_id').val(_this.find('.ids').text());
            $('#e_invoice_no').val(_this.find('.invoice_no').text());
            $('#e_amount').val(_this.find('.amount').text());
            $('#e_discount').val(_this.find('.discount').text());
            $('#e_point').val(_this.find('.point').text());
            $('#e_offer_code').val(_this.find('.offer_code').text());

            // point_status
            var point_status = (_this.find(".point_status").text());
            var _option = '<option selected value="'+point_status+'">' + point_status + '</option>'
            $( _option).appendTo("#e_point_status");
        });
    </script>

    {{-- delete js --}}
    <script>
        $(document).on('click','.Delete',function()
        {
            var _this = $(this).parents('tr');
            $('.e_id').val(_this.find('.ids').text());
        });
    </script>
@endsection


