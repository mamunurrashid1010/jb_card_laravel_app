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
                        <h3 class="page-title">User Management</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Users</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_user"><i class="fa fa-plus"></i> Add User</a>
                    </div>
                </div>
            </div>
			<!-- /Page Header -->

            <!-- Search Filter -->
            <form action="{{ route('search/user/list') }}" method="POST">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" id="name" name="name" value="{{ Request()->get('name') }}">
                            <label class="focus-label">Name</label>
                        </div>
                    </div>
{{--                    <div class="col-sm-6 col-md-3">--}}
{{--                        <div class="form-group form-focus">--}}
{{--                            <input type="text" class="form-control floating" id="name" name="role_name">--}}
{{--                            <label class="focus-label">Role Name</label>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-sm-6 col-md-3">--}}
{{--                        <div class="form-group form-focus">--}}
{{--                            <input type="text" class="form-control floating" id="name" name="status">--}}
{{--                            <label class="focus-label">Status</label>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="col-sm-6 col-md-3">
                        <button type="sumit" class="btn btn-success btn-block"> Search </button>
                    </div>
                </div>
            </form>
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
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($result as $key=>$user )
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
                                    {{--  <td class="id">{{ $user->rec_id }}</td>--}}
                                    <td class="email">{{ $user->email }}</td>
                                    <td class="phone_number">@if(!empty($user->phone)){{ $user->phone }}@else N/A @endif</td>
                                    <td>
                                        @if ($user->type=='Admin')
                                            <span class="badge bg-inverse-danger role_name">{{ $user->type }}</span>
                                        @elseif ($user->type=='SuperAdmin')
                                            <span class="badge bg-inverse-success role_name">{{ $user->type }}</span>
                                        @elseif ($user->type=='User')
                                            <span class="badge bg-inverse-primary role_name">{{ $user->type }}</span>
                                        @else
                                            <span class="badge bg-inverse-warning role_name">{{ $user->type }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown action-label">
                                            @if ($user->status=='active')
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-dot-circle-o text-success"></i>
                                                    <span class="statuss">{{ $user->status }}</span>
                                                </a>
                                                @elseif ($user->status=='inactive')
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-dot-circle-o text-info"></i>
                                                    <span class="statuss">{{ $user->status }}</span>
                                                </a>
                                                @elseif ($user->status=='')
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-dot-circle-o text-dark"></i>
                                                    <span class="statuss">N/A</span>
                                                </a>
                                            @endif

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#">
                                                    <i class="fa fa-dot-circle-o text-success"></i> Active
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fa fa-dot-circle-o text-warning"></i> Inactive
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item userUpdate" data-toggle="modal" data-id="'.$user->id.'" data-target="#edit_user"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                {{--    <a class="dropdown-item userDelete" href="#" data-toggle="modal" ata-id="'.$user->id.'" data-target="#delete_user"><i class="fa fa-trash-o m-r-5"></i> Delete</a>--}}
                                            </div>
                                        </div>
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
                        <h5 class="modal-title">Add New User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('user/add/save') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Full Name <span class="text-danger">*</span></label>
                                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="" name="name" value="{{ old('name') }}" placeholder="Enter Name" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Email Address <span class="text-danger">*</span></label>
                                    <input class="form-control" type="email" id="" name="email" placeholder="Enter Email" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input class="form-control" type="tel" id="" name="phone" placeholder="Enter Phone">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Role Name  <span class="text-danger">*</span></label>
                                    <select class="select" name="role_name" id="role_name" required>
                                        <option selected disabled> --Select --</option>
                                        <option value="User">User</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
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
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Status  <span class="text-danger">*</span></label>
                                    <select class="select" name="status" id="status" required>
                                        <option selected disabled> --Select --</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">InActive</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Photo</label>
                                    <input class="form-control" type="file" id="image" name="image">
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
                        <form action="{{ route('update') }}" method="POST" enctype="multipart/form-data">
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
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Role Name <span class="text-danger">*</span></label>
                                    <select class="select" name="role_name" id="e_role_name">
                                        <option value="User">User</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input class="form-control" type="text" id="e_phone_number" name="phone" placeholder="Enter Phone">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
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
                            </div><br>
                            <div class="row">
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

        <!-- Delete User Modal -->
{{--        <div class="modal custom-modal fade" id="delete_user" role="dialog">--}}
{{--            <div class="modal-dialog modal-dialog-centered">--}}
{{--                <div class="modal-content">--}}
{{--                    <div class="modal-body">--}}
{{--                        <div class="form-header">--}}
{{--                            <h3>Delete User</h3>--}}
{{--                            <p>Are you sure want to delete?</p>--}}
{{--                        </div>--}}
{{--                        <div class="modal-btn delete-action">--}}
{{--                            <form action="{{ route('user/delete') }}" method="POST">--}}
{{--                                @csrf--}}
{{--                                <input type="hidden" name="id" class="e_id" value="">--}}
{{--                                <input type="hidden" name="avatar" class="e_avatar" value="">--}}
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
        <!-- /Delete User Modal -->
    </div>
    <!-- /Page Wrapper -->
@endsection
@section('script')
{{--        <script>--}}
{{--            $('#DataTable').DataTable();--}}
{{--        </script>--}}
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

            var name_role = (_this.find(".role_name").text());
            var _option = '<option selected value="' + name_role+ '">' + _this.find('.role_name').text() + '</option>'
            $( _option).appendTo("#e_role_name");

            var position = (_this.find(".position").text());
            var _option = '<option selected value="' +position+ '">' + _this.find('.position').text() + '</option>'
            $( _option).appendTo("#e_position");

            var department = (_this.find(".department").text());
            var _option = '<option selected value="' +department+ '">' + _this.find('.department').text() + '</option>'
            $( _option).appendTo("#e_department");

            var statuss = (_this.find(".statuss").text());
            var _option = '<option selected value="' +statuss+ '">' + _this.find('.statuss').text() + '</option>'
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
