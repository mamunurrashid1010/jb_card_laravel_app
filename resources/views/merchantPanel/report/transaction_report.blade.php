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
                        <h3 class="page-title">Transaction Report</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Report / Transaction report </li>
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

            <!-- Search Filter -->
            <form action="{{ route('merchant.offer.transaction.report.index') }}" method="GET">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-2">
                        <div class="form-group form-focus">
                            <label>Agent</label>
                            <select class="select form-control" name="agent_id">
                                <option value=""> --Select agent --</option>
                                @foreach($agents as $agent)
                                    <option value="{{$agent->id}}" @if(Request()->get('agent_id') == $agent->id) selected @endif>{{$agent->name}}</option>
                                @endforeach
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
                    <h4 style="color: dimgray"><i class="fa fa-list-alt"><strong> Transaction List</strong></i></h4>
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable table-border" id="DataTable">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th><strong>Customer</strong></th>
                                <th><strong>Offer</strong></th>
                                <th><strong>Invoice No</strong></th>
                                <th><strong>Amount</strong></th>
                                <th><strong>Discount</strong></th>
                                <th><strong>Point</strong></th>
                                <th><strong>Point Status</strong></th>
                                <th style="width: 200px"><strong>Description</strong></th>
                                <th><strong>Date</strong></th>
                                <th><strong>Created By</strong></th>
                                {{--    <th class="text-center"><strong>Action</strong></th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($transactionReport as $key=>$transaction)
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
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row pt-3 pl-3">
                    <div class="col-md-12">
                       {{$transactionReport->appends($_GET)->links()}}
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

    {{-- update js --}}
    <script>
        $(document).on('click','.Update',function()
        {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.ids').text());
            $('#e_name').val(_this.find('.name').text());
            $('#e_description').val(_this.find('.description').text());
            $('#e_start_date').val(_this.find('.start_date').text());
            $('#e_end_date').val(_this.find('.end_date').text());
            $('#e_discount').val(_this.find('.discount').text());
            $('#e_point').val(_this.find('.point').text());
            $('#e_offer_code').val(_this.find('.offer_code').text());

            // category
            var category_id = (_this.find(".category_id").text());
            var delivery_name = (_this.find(".category_name").text());
            var _option = '<option selected value="'+category_id+'">' + delivery_name + '</option>'
            $( _option).appendTo("#e_category_id");
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


