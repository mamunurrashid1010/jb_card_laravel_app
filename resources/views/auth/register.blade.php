@extends('layouts.app')
@section('content')
    <div class="main-wrapper">
        <div class="account-content">
            <div class="container">
                <div class="account-box">
                    <div class="account-wrapper">
                        <h3 class="account-title text-white">Customer Register</h3>
                        <p class="account-subtitle" style="color:#ffc107">Access to our XTRA SAVY App</p>

                        <!-- Registration Form -->
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-group">
                                <label style="color:white">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Enter Your Name" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label style="color:white">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Enter Your Email" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label style="color:white">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="Enter Your Phone" required>
                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            {{-- insert defaults --}}
                            <input type="hidden" class="image" name="image" value="photo_defaults.jpg">

                            <div class="form-group">
                                <label style="color:white">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter Password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label style="color:white"><strong>Repeat Password</strong></label>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Choose Repeat Password" required>
                            </div>
                            <div class="form-group">
                                <label style="color:white"><strong>Package</strong></label>
                                <select class="form-control select" id="package" name="package" required>
                                    <option value="">---Select---</option>
                                    @foreach($packages as $package)
                                        <option value="{{$package->id}}">{{$package->name}}</option>
                                    @endforeach
                                </select>
                            </div>
{{--                            <div class="form-group">--}}
{{--                                <label style="color:white">Organization/Company Name</label>--}}
{{--                                <input type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}" placeholder="Enter Name" required>--}}
{{--                                @error('company_name')--}}
{{--                                <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
                            <div class="form-group text-center">
                                <button class="btn btn-primary account-btn" type="submit">Register</button>
                            </div>
                            <div class="account-footer">
                                <p class="text-white">Already have an account? <a href="{{ route('login') }}">Login</a></p>
                            </div>
                        </form>
                        <!-- /Registration Form -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
