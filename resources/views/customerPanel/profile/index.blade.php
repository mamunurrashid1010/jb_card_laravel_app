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
                    <form action="" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input name="companyName" class="form-control" type="text" value="{{$customer->name}}">
                                </div>
                            </div>
                            <div class="col-sm-6">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input name="phone" class="form-control " value="{{$customer->phone ?? ''}}" type="number">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input name="email" class="form-control" type="email" value="{{$customer->email}}" required>
                                </div>
                            </div>
                        </div>
{{--                        <div class="row">--}}
{{--                            <div class="col-sm-12">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label>Address</label>--}}
{{--                                    <input name="address" class="form-control " value="@if(!empty($companyInfo->address)){{$companyInfo->address}}@endif" type="text">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="submit-section">
                            <button type="submit" class="btn submit-btn" style="background-color: #495057; color:white">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
@endsection
