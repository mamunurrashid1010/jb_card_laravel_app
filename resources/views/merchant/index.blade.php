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
                        <h3 class="page-title">Merchant Management</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Merchant Management</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add"><i class="fa fa-plus"></i> Add New Merchant</a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Search Filter -->
            <form action="{{ route('merchant.index') }}" method="GET">
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
                    <h4 style="color: dimgray"><i class="fa fa-list-alt"><strong> Merchant List</strong></i></h4>
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable table-border" id="DataTable">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th class="text-center"><strong>Action</strong></th>
                                <th><strong>Photo</strong></th>
                                <th><strong>Business Name</strong></th>
                                <th><strong>Owner Name</strong></th>
                                <th><strong>Email</strong></th>
                                <th><strong>Phone</strong></th>
                                <th><strong>Transactions</strong></th>
                                <th><strong>Offers</strong></th>
                                <th><strong>Address</strong></th>
                                <th><strong>C.P. Name</strong></th>
                                <th><strong>C.P. Phone</strong></th>
                                <th><strong>Status</strong></th>
                                <th><strong>Package</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($merchants as $key=>$merchant)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td hidden class="ids">{{ $merchant->id }}</td>
                                    <td class="text-center">
                                        <a class="" href="{{route('merchant.transactionDetails',$merchant->id)}}"  style="color: #bdad11;cursor: pointer"><button class="btn btn-info btn-sm">Transaction Details</button></a>
                                        <a class="Update" data-toggle="modal" data-id="'.$merchant->id.'" data-target="#edit" style="color: #bdad11;cursor: pointer"><button class="btn btn-warning btn-sm">Edit</button></a>
                                    </td>
                                    <td class="photo">
                                        @if($merchant->image)
                                            <img src="{{ asset('images/users/'.$merchant->image) }}" class="card-img-to avatar" alt="profile_image">
                                        @else
                                            <img src="{{ asset('/assets/img/user.jpg') }}" class="card-img-top avatar" alt="profile_image">
                                        @endif
                                    </td>
                                    <td class="business_name"><strong>{{$merchant->MerchantInfo->business_name}}</strong></td>
                                    <td class="owner_name">{{$merchant->MerchantInfo->owner_name}}</td>
                                    <td class="email">{{$merchant->email}}</td>
                                    <td class="phone">{{$merchant->phone}}</td>
                                    <td class="total_transaction"><span class="badge badge-purple text-white font-18">{{ $merchant->merchantTransactions->count() }}</span></td>
                                    <td class="total_offer"><span class="badge badge-purple text-white font-18">{{ $merchant->merchantOffers->count() }}</span></td>
                                    <td class="address">{{$merchant->address}}</td>
                                    <td class="contact_person_name">{{$merchant->MerchantInfo->contact_person_name}}</td>
                                    <td class="contact_person_phone">{{$merchant->MerchantInfo->contact_person_phone}}</td>
                                    <td class="statuss">{{ $merchant->status }}</td>
                                    <td class="packageList">
                                        @foreach($merchant->merchantPackage as $mp)
                                            <span class="badge badge-info">{{$mp->packageName->name ?? ''}}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row pt-3 pl-3">
                    <div class="col-md-12">
                       {{$merchants->appends($_GET)->links()}}
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
                        <h5 class="modal-title">Add New Merchant</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('merchant.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Business Name <span class="text-danger">*</span></label>
                                        <input type="text" id="" name="business_name" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Owner Name <span class="text-danger">*</span></label>
                                        <input type="text" id="" name="owner_name" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" id="" name="email" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone <span class="text-danger">*</span></label>
                                        <input type="text" id="" name="phone" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Address <span class="text-danger">*</span></label>
                                        <input type="text" id="" name="address" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Contact Person Name</label>
                                        <input type="text" id="" name="contact_person_name" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Contact Person Phone</label>
                                        <input type="text" id="" name="contact_person_phone" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Password <span class="text-danger">*</span></label>
                                        <input type="text" id="" name="password" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Photo</label>
                                    <input class="form-control" type="file" id="image" name="image">
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Package <span class="text-danger">*</span></label>
                                        <select class="select" name="package_id" id="package_id" required>
                                            <option selected disabled> --Select --</option>
                                            @foreach($packages as $package)
                                                <option value="{{$package->id}}">{{$package->name}} ({{$package->amount}}$)</option>
                                            @endforeach
                                        </select>
                                    </div>
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
                        <form action="{{route('merchant.update')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="e_id" name="id" class="e_id" value="">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Business Name <span class="text-danger">*</span></label>
                                        <input type="text" id="e_business_name" name="business_name" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Owner Name <span class="text-danger">*</span></label>
                                        <input type="text" id="e_owner_name" name="owner_name" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone <span class="text-danger">*</span></label>
                                        <input type="text" id="e_phone" name="phone" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="email" id="e_email" value="" readonly/>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Address <span class="text-danger">*</span></label>
                                        <input type="text" id="e_address" name="address" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select class="select" name="status" id="e_status">
                                        <option selected disabled> --Select --</option>
                                        <option value="active">active</option>
                                        <option value="inactive">inactive</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Contact Person Name</label>
                                        <input type="text" id="e_contact_person_name" name="contact_person_name" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Contact Person Phone</label>
                                        <input type="text" id="e_contact_person_phone" name="contact_person_phone" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Change Photo</label>
                                    <input class="form-control" type="file" id="image" name="image">
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Change Email</label>
                                        <input name="new_email" class="form-control" type="email" value="" placeholder="Enter your new email">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>New Password </label>
                                        <input type="password" class="form-control" id="newPassword" name="password" placeholder="Enter new password">
                                        <input type="checkbox" onclick="newPasswordShow()">Show Password
                                    </div>
                                </div>
{{--                                <div class="col-sm-6">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label>Password <span class="text-danger">*</span></label>--}}
{{--                                        <input type="text" id="" name="password" class="form-control" value="" required>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-6">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label>Package <span class="text-danger">*</span></label>--}}
{{--                                        <select class="select" name="package_id" id="package_id" required>--}}
{{--                                            <option selected disabled> --Select --</option>--}}
{{--                                            @foreach($packages as $package)--}}
{{--                                                <option value="{{$package->id}}">{{$package->name}} ({{$package->amount}}$)</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
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

    {{-- update js --}}
    <script>
        $(document).on('click','.Update',function()
        {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.ids').text());
            $('#e_business_name').val(_this.find('.business_name').text());
            $('#e_owner_name').val(_this.find('.owner_name').text());
            $('#e_phone').val(_this.find('.phone').text());
            $('#e_email').val(_this.find('.email').text());
            $('#e_address').val(_this.find('.address').text());
            $('#e_contact_person_name').val(_this.find('.contact_person_name').text());
            $('#e_contact_person_phone').val(_this.find('.contact_person_phone').text());

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


