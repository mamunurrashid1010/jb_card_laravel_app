@extends('layouts.master')
@section('content')
    <style>
        .select2 {
            width: 100% !important; ;
        }
    </style>
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Invoice Management</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Invoice Management</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add"><i class="fa fa-plus"></i> Add New</a>
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

            <!-- Search Filter -->
            <form action="{{ route('invoice.index') }}" method="GET">
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
                            <label>User Type</label>
                            <select class="select form-control" name="user_type">
                                <option value=""> --Select merchant/customer --</option>
                                <option value="Merchant" @if(Request()->get('user_type') == 'Merchant') selected @endif>Merchant</option>
                                <option value="Customer" @if(Request()->get('user_type') == 'Customer') selected @endif>Customer</option>
                            </select>
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
                    <h4 style="color: dimgray"><i class="fa fa-list-alt"><strong> Invoice List</strong></i></h4>
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable table-border" id="DataTable">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th><strong>Name</strong></th>
                                <th><strong>User Type</strong></th>
                                <th><strong>Invoice No</strong></th>
                                <th><strong>Amount</strong></th>
                                <th><strong>Invoice Date</strong></th>
                                <th><strong>Description</strong></th>
                                <th><strong>Status</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($invoices as $key=>$invoice)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td hidden class="ids">{{ $invoice->id }}</td>
                                    <td class="userInfo">
                                        <strong>{{$invoice->userInfo->name}}</strong> ({{$invoice->userInfo->phone}})<br>
                                        {{$invoice->userInfo->email}}
                                    </td>
                                    <td class="user_type"><strong>{{$invoice->user_type}}</strong></td>
                                    <td class="invoice_no"><strong>{{$invoice->invoice_no}}</strong></td>
                                    <td class="amount"><strong>{{$invoice->amount}}</strong></td>
                                    <td class="invoice_date"><strong>{{$invoice->invoice_date}}</strong></td>
                                    <td class="description"><strong>{{$invoice->description}}</strong></td>
                                    <td class="statuss">
                                        @if($invoice->status == 'paid')
                                            <span class="badge badge-success">{{$invoice->status}}</span>
                                        @else
                                            <span class="badge badge-warning">{{$invoice->status}}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a class="Update" data-toggle="modal" data-id="'.$invoice->id.'" data-target="#edit" style="color: #bdad11;cursor: pointer"><button class="btn btn-warning btn-sm">Edit</button></a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row pt-3 pl-3">
                    <div class="col-md-12">
                       {{$invoices->appends($_GET)->links()}}
                    </div>
                </div>

            </div>
            <!-- /table data -->

        </div>
        <!-- /Page Content -->

        <!-- Add Modal -->
        <div id="add" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Invoice</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('invoice.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group form-select" style="width: 100%">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <select class="form-control" id="user_search" name="user_search" style=""></select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Invoice Date <span class="text-danger">*</span></label>
                                        <input id="" name="invoice_date" class="form-control" type="date" value="{{date('Y-m-d')}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Amount($) <span class="text-danger">*</span></label>
                                        <input id="" name="amount" class="form-control" type="number" style="" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <label>Description</label>
                                    <textarea name="description" rows="2" class="form-control"></textarea>
                                </div>
                                <div class="col-sm-6">
                                    <label>Payment Status  <span class="text-danger">*</span></label>
                                    <select class="select" name="status" id="status" required>
                                        <option selected disabled> --Select --</option>
                                        <option value="paid">paid</option>
                                        <option value="unpaid">unpaid</option>
                                    </select>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Add Now</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Modal -->

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
                        <form action="{{route('invoice.update')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="e_id" name="id" class="e_id" value="">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Invoice No <span class="text-danger">*</span></label>
                                        <input id="e_invoice_no" name="invoice_no" class="form-control" type="text" value="" required readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Invoice Date <span class="text-danger">*</span></label>
                                        <input id="e_invoice_date" name="invoice_date" class="form-control" type="date" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Amount($) <span class="text-danger">*</span></label>
                                        <input id="e_amount" name="amount" class="form-control" type="number" style="" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Payment Status  <span class="text-danger">*</span></label>
                                    <select class="select" name="status" id="e_status" required>
                                        <option value="paid">paid</option>
                                        <option value="unpaid">unpaid</option>
                                    </select>
                                </div>
                                <div class="col-sm-12">
                                    <label>Description</label>
                                    <textarea id="e_description" name="description" rows="2" class="form-control"></textarea>
                                </div><br>
                                <div class="col-sm-12">
                                    <div class="form-group form-select" style="width: 100%">
                                        <label>Change user </label>
                                        <select class="form-control" id="user_search_edit" name="user_search" style=""></select>
                                    </div>
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
{{--        <div class="modal custom-modal fade" id="delete" role="dialog">--}}
{{--            <div class="modal-dialog modal-dialog-centered">--}}
{{--                <div class="modal-content">--}}
{{--                    <div class="modal-body">--}}
{{--                        <div class="form-header">--}}
{{--                            <h3>Delete Product</h3>--}}
{{--                            <p>Are you sure you want to delete?</p>--}}
{{--                        </div>--}}
{{--                        <div class="modal-btn delete-action">--}}
{{--                            <form action="{{route('product.delete')}}" method="POST">--}}
{{--                                @csrf--}}
{{--                                <input type="hidden" name="id" class="e_id" value="">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-6">--}}
{{--                                        <button type="submit" class="btn btn-primary continue-btn submit-btn">Delete</button>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-6">--}}
{{--                                        <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <!-- /Delete Modal -->


    </div>
    <!-- /Page Wrapper -->


@endsection

@section('script')

    <script>
        // $('#DataTable').DataTable();
    </script>

    {{-- getUserList_searchByName /customer/merchant --}}
    <script>
        var path = "{{route('getUserList_searchByName')}}";
        <!-- add model :use from search/filter section  -->
        $('#user_search').select2({
            placeholder: '--- Select Customer/Merchant ---',
            ajax: {
                url: path,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.name + '(ID: ' + item.id + ')'+ '(email: ' + item.email + ')',
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
        <!-- edit model :use from search/filter section  -->
        $('#user_search_edit').select2({
            placeholder: '--- Select Customer/Merchant ---',
            ajax: {
                url: path,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.name + '(ID: ' + item.id + ')'+ '(email: ' + item.email + ')',
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    </script>

    {{-- update js --}}
    <script>
        $(document).on('click','.Update',function()
        {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.ids').text());
            $('#e_userInfo').val(_this.find('.userInfo').text());
            $('#e_invoice_no').val(_this.find('.invoice_no').text());
            $('#e_invoice_date').val(_this.find('.invoice_date').text());
            $('#e_amount').val(_this.find('.amount').text());
            $('#e_description').val(_this.find('.description').text());

            var statuss = (_this.find(".statuss").text());
            var _option = '<option selected value="' +statuss+ '">' + statuss + '</option>'
            $( _option).appendTo("#e_status");

        });
    </script>

    {{-- delete js --}}
{{--    <script>--}}
{{--        $(document).on('click','.Delete',function()--}}
{{--        {--}}
{{--            var _this = $(this).parents('tr');--}}
{{--            $('.e_id').val(_this.find('.ids').text());--}}
{{--        });--}}
{{--    </script>--}}
@endsection


