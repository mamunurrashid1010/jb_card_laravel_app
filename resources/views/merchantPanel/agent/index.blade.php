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
                        <h3 class="page-title">Agent Management</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Agent</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_user"><i class="fa fa-plus"></i> Add Agent</a>
                    </div>
                </div>
            </div>
			<!-- /Page Header -->

            <!-- Search Filter -->
{{--            <form action="{{ route('merchant.agent.index') }}" method="POST">--}}
{{--                @csrf--}}
{{--                <div class="row filter-row">--}}
{{--                    <div class="col-sm-6 col-md-3">--}}
{{--                        <div class="form-group form-focus">--}}
{{--                            <input type="text" class="form-control floating" id="name" name="name" value="{{ Request()->get('name') }}">--}}
{{--                            <label class="focus-label">Name</label>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-sm-6 col-md-3">--}}
{{--                        <button type="sumit" class="btn btn-success btn-block"> Search </button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
            <!-- /Search Filter -->

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

            {{-- message --}}
            {!! Toastr::message() !!}
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable" id="DataTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($agents as $key=>$user)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>
                                        <h2 class="table-avatar">
                                            <a href="#" class="avatar">
                                                @if($user->image)
                                                    <img src="{{ asset('images/users/'.$user->image) }}" class="card-img-to" alt="profile_image">
                                                @else
                                                    <img src="{{ asset('/assets/img/user.jpg') }}" class="card-img-top" alt="profile_image">
                                                @endif
                                            </a>
                                            <a href="#" class="name">{{ $user->name }}</a>
                                        </h2>
                                    </td>
                                    <td hidden class="ids">{{ $user->id }}</td>
                                    <td class="email">{{ $user->email }}</td>
                                    <td class="phone_number">{{ $user->phone ?? '' }}</td>
                                    <td class="statuss">{{ $user->status }}</td>
                                    <td class="text-right">
                                        <a class="userUpdate" data-toggle="modal" data-id="'.$user->id.'" data-target="#edit_user"><button class="btn btn-warning"><i class="fa fa-pencil fa-sm"></i>Edit</button></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->

        <!-- Add User Modal -->
        <div id="add_user" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Agent</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('merchant.agent.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" id="" name="name" value="" placeholder="Enter Name" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Email Address <span class="text-danger">*</span></label>
                                    <input class="form-control" type="email" id="" name="email" placeholder="Enter Email" required>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input class="form-control" type="tel" id="" name="phone" placeholder="Enter Phone">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Photo</label>
                                    <input class="form-control" type="file" id="image" name="image">
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Password  <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="password" placeholder="Enter Password" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Repeat Password  <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="password_confirmation" placeholder="Enter Repeat Password" required>
                                </div>
                                <div class="col-sm-6">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select class="select" name="status" id="status" required>
                                        <option selected value=""> --Select --</option>
                                        <option value="active">active</option>
                                        <option value="inactive">inactive</option>
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

        <!-- Edit User Modal -->
        <div id="edit_user" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <form action="{{ route('merchant.agent.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="e_id" value="">
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
                                        <label>Phone</label>
                                        <input class="form-control" type="text" id="e_phone_number" name="phone" placeholder="Enter Phone">
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
        <!-- /Edit Salary Modal -->

    </div>
    <!-- /Page Wrapper -->
@endsection
@section('script')
        <script>
            // $('#DataTable').DataTable();
        </script>
    {{-- update js --}}
    <script>
        $(document).on('click','.userUpdate',function()
        {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.ids').text());
            $('#e_name').val(_this.find('.name').text());
            $('#e_email').val(_this.find('.email').text());
            $('#e_phone_number').val(_this.find('.phone_number').text());
            $('#e_image').val(_this.find('.image').text());

            var statuss = (_this.find(".statuss").text());
            var _option = '<option selected value="' +statuss+ '">' + statuss + '</option>'
            $( _option).appendTo("#e_status");

        });
    </script>
    {{-- delete js --}}
    <script>
        // $(document).on('click','.userDelete',function()
        // {
        //     var _this = $(this).parents('tr');
        //     $('.e_id').val(_this.find('.ids').text());
        //     $('.e_avatar').val(_this.find('.image').text());
        // });
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

@endsection
