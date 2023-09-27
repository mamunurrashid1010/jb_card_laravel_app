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
                        <h3 class="page-title">Offer Management</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Offer Management</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
{{--                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add"><i class="fa fa-plus"></i> Add New</a>--}}
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
            <form action="{{ route('offer.getAllOffer') }}" method="GET">
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

            <!-- table data -->
            <div class="row pt-3">
                <div class="col-md-12">
                    <h4 style="color: dimgray"><i class="fa fa-list-alt"><strong> Offer List</strong></i></h4>
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable table-border" id="DataTable">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th><strong>Merchant</strong></th>
                                <th><strong>Category</strong></th>
                                <th><strong>Image</strong></th>
                                <th><strong>Name</strong></th>
                                <th><strong>Code</strong></th>
                                <th style="width: 300px"><strong>Description</strong></th>
                                <th><strong>Start Date</strong></th>
                                <th><strong>End Date</strong></th>
                                <th><strong>Discount</strong></th>
                                <th><strong>Point</strong></th>
                                <th><strong>Status</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($offers as $key=>$offer)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td hidden class="ids">{{ $offer->id }}</td>
                                    <td class="merchant_name">{{$offer->merchant->name ?? ''}}</td>
                                    <td hidden class="category_id">{{$offer->category_id}}</td>
                                    <td class="category_name">{{$offer->category->name ?? ''}}</td>
                                    <td class="image"><img src="{{ asset('images/offers/'.$offer->image) }}" width="75" alt="offer Image"></td>
                                    <td class="name"><strong>{{$offer->name}}</strong></td>
                                    <td class="offer_code">{{$offer->offer_code}}</td>
                                    <td class="description"><textarea class="w-100">{{$offer->description}}</textarea>}</td>
                                    <td class="start_date">{{$offer->start_date}}</td>
                                    <td class="end_date">{{$offer->end_date}}</td>
                                    <td class="discount">{{$offer->discount}}</td>
                                    <td class="point">{{$offer->point}}</td>
                                    <td class="sstatus">{{$offer->status}}</td>
                                    <td class="text-center">
                                        <a class="statusUpdate" data-toggle="modal" data-id="'.$offer->id.'" data-target="#statusEdit" style="color: #bdad11;cursor: pointer"><button class="btn btn-info btn-sm">Status Update</button></a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row pt-3 pl-3">
                    <div class="col-md-12">
                       {{$offers->appends($_GET)->links()}}
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
                        <form action="{{route('offer.statusUpdate')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="e_id" name="id" class="e_id" value="">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Offer status <span class="text-danger">*</span></label>
                                        <select class="select" name="status" id="status" required>
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
        <!--/ statusEdit Modal -->


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


