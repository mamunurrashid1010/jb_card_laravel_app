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
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add"><i class="fa fa-plus"></i> Add New</a>
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

            <!-- table data -->
            <div class="row pt-3">
                <div class="col-md-12">
                    <h4 style="color: dimgray"><i class="fa fa-list-alt"><strong> Offer List</strong></i></h4>
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable table-border" id="DataTable">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th><strong>Category</strong></th>
                                <th><strong>Name</strong></th>
                                <th><strong>Description</strong></th>
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
                                    <td hidden class="category_id">{{$offer->category_id}}</td>
                                    <td class="category_name">{{$offer->category->name ?? ''}}</td>
                                    <td class="name"><strong>{{$offer->name}}</strong></td>
                                    <td class="description">{{$offer->description}}</td>
                                    <td class="start_date">{{$offer->start_date}}</td>
                                    <td class="end_date">{{$offer->end_date}}</td>
                                    <td class="discount">{{$offer->discount}}</td>
                                    <td class="point">{{$offer->point}}</td>
                                    <td class="sstatus">{{$offer->status}}</td>
                                    <td class="text-center">
                                        <a class="Update" data-toggle="modal" data-id="'.$offer->id.'" data-target="#edit" style="color: #bdad11;cursor: pointer"><button class="btn btn-warning btn-sm">Edit</button></a>
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

        <!-- Add Modal -->
        <div id="add" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Offer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('merchant.offer.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Category <span class="text-danger">*</span></label>
                                        <select class="select" name="category_id" id="category_id" required>
                                            <option value=""> --Select --</option>
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input id="" name="name" class="form-control" type="text" value="" placeholder="name" required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Description <span class="text-danger">*</span></label>
                                        <input id="" name="description" class="form-control" type="text" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input id="" name="start_date" class="form-control" type="date" value="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input id="" name="end_date" class="form-control" type="date" value="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Discount(%)</label>
                                        <input id="" name="discount" class="form-control" type="text" value="0">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Point</label>
                                        <input id="" name="point" class="form-control" type="number" value="0">
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
                        <form action="{{route('merchant.offer.update')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="e_id" name="id" class="e_id" value="">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Category <span class="text-danger">*</span></label>
                                        <select class="select" name="category_id" id="e_category_id" required>
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input id="e_name" name="name" class="form-control" type="text" value="" placeholder="name" required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Description <span class="text-danger">*</span></label>
                                        <input id="e_description" name="description" class="form-control" type="text" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input id="e_start_date" name="start_date" class="form-control" type="date" value="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input id="e_end_date" name="end_date" class="form-control" type="date" value="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Discount(%)</label>
                                        <input id="e_discount" name="discount" class="form-control" type="text" value="0">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Point</label>
                                        <input id="e_point" name="point" class="form-control" type="number" value="0">
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
            $('#e_name').val(_this.find('.name').text());
            $('#e_description').val(_this.find('.description').text());
            $('#e_start_date').val(_this.find('.start_date').text());
            $('#e_end_date').val(_this.find('.end_date').text());
            $('#e_discount').val(_this.find('.discount').text());
            $('#e_point').val(_this.find('.point').text());

            // category
            var category_id = (_this.find(".category_id").text());
            var delivery_name = (_this.find(".category_name").text());
            var _option = '<option selected value="'+category_id+'">' + delivery_name + '</option>'
            $( _option).appendTo("#e_category_id");
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


