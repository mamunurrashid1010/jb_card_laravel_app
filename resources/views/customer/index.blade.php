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
                        <h3 class="page-title">Customer Management</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Customer Management</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        {{--  <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add"><i class="fa fa-plus"></i> Add New</a>--}}
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Search Filter -->
            <form action="{{ route('customer.index') }}" method="GET">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <input type="email" class="form-control" name="email" value="{{ Request()->get('email') }}">
                            <label class="focus-label">Email</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <button type="submit" class="btn btn-success btn-block"> Search </button>
                    </div>
                </div>
            </form>
            <!-- /Search Filter -->

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

            <!-- table data -->
            <div class="row pt-3">
                <div class="col-md-12">
                    <h4 style="color: dimgray"><i class="fa fa-list-alt"><strong> Customer List</strong></i></h4>
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable table-border" id="DataTable">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th><strong>Name</strong></th>
                                <th><strong>Email</strong></th>
                                <th><strong>Phone</strong></th>
                                <th><strong>Reg.Date</strong></th>
                                <th><strong>Account Status</strong></th>
                                <th><strong>Package</strong></th>
                                <th><strong>Package Status</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($customers as $key=>$customer)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td hidden class="ids">{{ $customer->id }}</td>
                                    <td class="name"><strong>{{$customer->name}}</strong></td>
                                    <td class="email">{{$customer->email}}</td>
                                    <td class="phone">{{$customer->phone}}</td>
                                    <td class="created_at">{{ date('d M Y',strtotime($customer->created_at)) }}</td>
                                    <td class="sstatus">
                                        @if($customer->status == 'active')
                                            <span class="badge badge-success">{{$customer->status}}</span>
                                            @else
                                            <span class="badge badge-danger">{{$customer->status}}</span>
                                        @endif
                                    </td>
                                    {{--  <td class="address">{{$merchant->address}}</td>--}}
                                    <td class="packageName">
                                        <span class="badge badge-info">{{$customer->customerPackage->packageInfo->name ?? ''}}</span>
                                    </td>
                                    <td class="packageName">
                                        <span class="badge badge-warning">{{$customer->customerPackage->status ?? ''}}</span>
                                    </td>
                                    <td class="text-center">
                                        <a class="statusUpdate" data-toggle="modal" data-id="'.$customer->id.'" data-target="#statusEdit" style="color: #bdad11;cursor: pointer"><button class="btn btn-info btn-sm m-1">Status Update</button></a>
                                        <a class="packageStatusUpdate" data-toggle="modal" data-id="'.$customer->id.'" data-target="#packageStatusEdit" style="color: #bdad11;cursor: pointer"><button class="btn btn-primary btn-sm">P.Status Update</button></a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row pt-3 pl-3">
                    <div class="col-md-12">
                       {{$customers->appends($_GET)->links()}}
                    </div>
                </div>

            </div>
            <!-- /table data -->

        </div>
        <!-- /Page Content -->

        <!-- statusEdit Modal -->
        <div id="statusEdit" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Status Update</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <form action="{{route('customer.accountStatusUpdate')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="e_id" name="id" class="e_id" value="">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Customer account status <span class="text-danger">*</span></label>
                                        <select class="select" name="status" id="status" required>
                                            <option value="">--Select--</option>
                                            <option value="active">active</option>
                                            <option value="inactive">inactive</option>
                                        </select>
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
        <!--/ statusEdit Modal -->

        <!-- package statusEdit Modal -->
        <div id="packageStatusEdit" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Package Status Update</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <form action="{{route('customer.packageStatusUpdate')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="ep_id" name="id" class="e_id" value="">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Package status <span class="text-danger">*</span></label>
                                        <select class="select" name="status" id="" required>
                                            <option value="">--Select--</option>
                                            <option value="pending">pending</option>
                                            <option value="active">active</option>
                                            <option value="inactive">inactive</option>
                                        </select>
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
        <!--/ package statusEdit Modal -->


    </div>
    <!-- /Page Wrapper -->

@endsection

@section('script')

    {{-- status update js --}}
    <script>
        $(document).on('click','.statusUpdate',function()
        {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.ids').text());
        });
    </script>

    {{-- package status update js --}}
    <script>
        $(document).on('click','.packageStatusUpdate',function()
        {
            var _this = $(this).parents('tr');
            $('#ep_id').val(_this.find('.ids').text());
        });
    </script>

@endsection


