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
                        <h3 class="page-title">Offer</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Offer</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        {{--  <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add"><i class="fa fa-plus"></i> Add New</a>--}}
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
            <form action="{{ route('customer.offer.index') }}" method="GET">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <select class="select form-control" name="category_id">
                                <option value=""> --Select category --</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}" @if(Request()->get('category_id') == $category->id) selected @endif>{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2 col-md-2 col-lg-2">
                        <button type="submit" class="btn btn-success btn-block"> Search </button>
                    </div>
                </div>
            </form>
            <!-- /Search Filter -->

            <!-- data -->
            <div class="row pt-3">
                <div class="col-md-12">
                    <h4 style="color: dimgray"><i class="fa fa-list-alt"><strong> Offer List</strong></i></h4>
                    <div class="row">
                        @foreach ($offers as $key=>$offer)
                        <div class="col-md-12 border m-2">
                            <div class="row" style="background-color: #e9ecef;">
                                <div class="col-sm-12 col-md-2 col-lg-2">
                                    <div style="width: 100%;height: 150px">
                                        <img src="{{ asset('images/offers/'.$offer->image) }}" style="width: 100%;height: 150px" alt="offer Image">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6 p-2" style="background-color: #ffffff;">
                                    <span class="font-weight-bold badge badge-success">Code : {{$offer->name}}</span>
                                    <span class="float-right"><p class="badge badge-info ml-1">Start Date : {{$offer->start_date}}</p> <p class="badge badge-info">End Date : {{$offer->end_date}}</p></span>
                                    <br>
                                    <span class="font-weight-bold">Title :</span> {{$offer->name}} <br>
                                    <span class="font-weight-bold">Category :</span> {{$offer->category->name ?? ''}} <br>
                                    <span class="font-weight-bold overflow-hidden">Description :</span> {{$offer->description}}
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-4">
                                    <div class="row p-2">
                                        <div class="col-sm-12 col-md-6 col-lg-6 text-center" style="background-color: #e9ecef">
                                            <h4 style="font-size: 25px;color: #b90b17;">
                                                <span >{{$offer->discount}}</span><br>
                                                Discount
                                            </h4>
                                            <h4 class="pt-3" style="font-size: 25px;color: #007bff">
                                                <span>{{$offer->point}}</span><br>
                                                Point
                                            </h4>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6 p-2" style="background-color: #ee9b09;">
                                            <p><u>Merchant Info</u></p>
                                            <span>
                                                {{$offer->merchant->name ?? ''}} <br>
                                                {{$offer->merchant->phone ?? ''}} <br>
                                                {{$offer->merchant->address ?? ''}} <br>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="row pt-3 pl-3">
                        <div class="col-md-12">
                            {{$offers->appends($_GET)->links()}}
                        </div>
                    </div>

            </div>
            <!-- / data -->

        </div>
        <!-- /Page Content -->


    </div>
    <!-- /Page Wrapper -->


@endsection

@section('script')

    <script>
        // $('#DataTable').DataTable();
    </script>

    {{-- status update js --}}
    <script>
        $(document).on('click','.statusUpdate',function()
        {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.ids').text());
        });
    </script>

@endsection


