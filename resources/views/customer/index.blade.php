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
                          <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_customer"><i class="fa fa-plus"></i> Add New Customer</a>
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
                                <th><strong>Photo</strong></th>
                                <th><strong>Name</strong></th>
                                <th><strong>Email</strong></th>
                                <th><strong>Phone</strong></th>
                                <th><strong>Transactions</strong></th>
                                <th><strong>Wallet Point</strong></th>
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
                                    <td class="photo">
                                        @if($customer->image)
                                            <img src="{{ asset('images/users/'.$customer->image) }}" class="card-img-to avatar" alt="profile_image">
                                        @else
                                            <img src="{{ asset('/assets/img/user.jpg') }}" class="card-img-top avatar" alt="profile_image">
                                        @endif
                                    </td>
                                    <td class="name"><strong>{{$customer->name}}</strong></td>
                                    <td class="email">{{$customer->email}}</td>
                                    <td class="phone">{{$customer->phone}}</td>
                                    <td class="total_transaction"><span class="badge badge-purple text-white font-18">{{ $customer->merchantWiseWallet->count() }}</span></td>
                                    <td class="wallet_point">
                                        <span class="badge badge-purple text-white font-18">
                                            {{ $customer->merchantWiseWallet->where('point_status','add')->where('status','confirm')->sum('point') - $customer->merchantWiseWallet->where('point_status','deduction')->where('status','confirm')->sum('point') }}
                                        </span>
                                    </td>
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
                                        <a class="customerUpdate" data-toggle="modal" data-id="'.$customer->id.'" data-target="#edit_customer" style="color: #bdad11;cursor: pointer"><button class="btn btn-warning btn-sm">Edit</button></a>
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

        <!-- Add User Modal -->
        <div id="add_customer" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Full Name <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" id="" name="name" value="" placeholder="Enter Name" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input class="form-control" type="email" id="" name="email" placeholder="Enter Email" required>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input class="form-control" type="tel" id="" name="phone" placeholder="Enter Phone">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Password  <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password" placeholder="Enter Password" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Repeat Password  <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="Choose Repeat Password" required>
                                </div>
                                <div class="col-sm-6">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select class="select" name="status" id="status" required>
                                        <option selected disabled> --Select --</option>
                                        <option value="pending">pending</option>
                                        <option value="active">active</option>
                                        <option value="inactive">inactive</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Photo</label>
                                    <input class="form-control" type="file" id="image" name="image">
                                </div>
                                <div class="col-sm-6">
                                    <label>Package <span class="text-danger">*</span></label>
                                    <select class="form-control select" id="package" name="package" required>
                                        <option value="">---Select---</option>
                                        @foreach($packages as $package)
                                            <option value="{{$package->id}}">{{$package->name}} (${{$package->amount}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add User Modal -->

        <!-- Edit Modal -->
        <div id="edit_customer" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <form action="{{ route('customer.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="edit_id" value="">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="name" id="e_name" value="" required />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="email" id="e_email" value="" readonly/>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" id="e_phone_number" name="phone" placeholder="Enter Phone" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Change Photo</label>
                                    <input class="form-control" type="file" id="image" name="image">
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>New Password </label>
                                        <input type="password" class="form-control" id="newPassword" name="password" placeholder="Enter new password">
                                        <input type="checkbox" onclick="newPasswordShow()">Show Password
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Change Email</label>
                                        <input name="new_email" class="form-control" type="email" value="" placeholder="Enter your new email">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Modal -->


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

    <script>
        //newPasswordShow
        function newPasswordShow() {
            var x = document.getElementById("newPassword");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>

    {{-- update js --}}
    <script>
        $(document).on('click','.customerUpdate',function()
        {
            var _this = $(this).parents('tr');
            $('#edit_id').val(_this.find('.ids').text());
            $('#e_name').val(_this.find('.name').text());
            $('#e_email').val(_this.find('.email').text());
            $('#e_phone_number').val(_this.find('.phone').text());
        });
    </script>

@endsection


