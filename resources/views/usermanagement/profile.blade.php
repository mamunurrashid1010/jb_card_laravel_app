@extends('layouts.master')
@section('content')

    {{-- message --}}
    {!! Toastr::message() !!}
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Content -->
            <div class="content container-fluid">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">Profile</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ul>
                        </div>
                        <div class="col-auto float-right ml-auto">
                        </div>
                    </div><hr>
                </div>
                <!-- /Page Header -->

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

            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="alert text-center" role="alert" style="background-color: #495057; color:white;">PROFILE INFORMATION</div>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    <form action="{{route('user.profile.update')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="card" style="width: 15rem;">
                                    @if($users->image)
                                        <img src="{{ asset('images/users/'.$users->image) }}" class="card-img-to" alt="profile_image">
                                    @else
                                        <img src="{{ asset('images/placeholder.jpg') }}" class="card-img-top" alt="profile_image">
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if(\Illuminate\Support\Facades\Auth::user()->type == 'Admin')
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Change Image</label>
                                    <input name="image" class="form-control" type="file">
                                </div>
                            </div>
                        </div>
                        @endif
                        <h4>Basic Information</h4><hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input name="name" class="form-control" type="text" value="{{$users->name}}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Phone Number <span class="text-danger">*</span></label>
                                    <input name="phone" class="form-control" value="{{$users->phone ?? ''}}" type="number" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea name="address" class="form-control">{{$users->address ?? ''}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input name="emailOld" class="form-control" type="email" value="{{$users->email}}" required readonly>
                                </div>
                            </div>
                            @if(\Illuminate\Support\Facades\Auth::user()->type == 'Admin')
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Change Email</label>
                                    <input name="email" class="form-control" type="email" value="" placeholder="Enter your new email">
                                </div>
                            </div>
                            @endif
                        </div><br>
                        @if(\Illuminate\Support\Facades\Auth::user()->type == 'Admin')
                        <h4>Change Password</h4><hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Current Password</label>
                                    <input name="current_password" class="form-control" type="text" value="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input name="password" class="form-control" type="text" value="">
                                </div>
                            </div>
                        </div>

                        <div class="submit-section">
                            <button type="submit" class="btn submit-btn" style="background-color: #495057; color:white">Save</button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
@endsection
