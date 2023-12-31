@extends('layouts.app')
@section('content')
    <div class="main-wrapper">
        <div class="account-content">
            <div class="container">
                <!-- Account Logo -->

{{--                <div class="account-logo">--}}
                <div class="m-3">
                    <img src="{{ URL::to('assets/img/logo.png') }}" width="450px" alt="">
                </div>
                {{-- message --}}
                {!! Toastr::message() !!}
                <!-- /Account Logo -->
                <div class="account-box">
                    <div class="account-wrapper">
                        <h3 class="account-title text-white">Login</h3>
                        <p class="account-subtitle" style="color:#ffc107">XTRA SAVY App </p>
                        <!-- Account Form -->
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label style="color:white">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Enter email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label style="color:white">Password</label>
                                    </div>
                                </div>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter Password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
{{--                            <div class="form-group">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col">--}}
{{--                                        <label></label>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-auto">--}}
{{--                                        <a class="text-muted" href="{{ route('forget-password') }}">--}}
{{--                                            Forgot password?--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="form-group text-center">
                                <button class="btn btn-primary account-btn" type="submit">Login</button>
                            </div>
{{--                            <div class="account-footer">--}}
{{--                                <p>Don't have an account yet? <a href="{{ route('register') }}">Register</a></p>--}}
{{--                            </div>--}}
                        </form>
                        <!-- /Account Form -->
                        <div class="form-group text-center">
                            <a href="{{ route('register') }}"><button class="btn btn-primary account-btn">Create customer account</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
