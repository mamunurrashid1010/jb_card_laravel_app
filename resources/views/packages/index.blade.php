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
                        <h3 class="page-title">Package Management</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Package Management</li>
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
                    <h4 style="color: dimgray"><i class="fa fa-list-alt"><strong> Package List</strong></i></h4>
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable table-border" id="DataTable">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th><strong>Package Name</strong></th>
                                <th><strong>Amount</strong></th>
                                <th class="text-center"><strong>Action</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($packages as $key=>$package)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td hidden class="ids">{{ $package->id }}</td>
                                    <td class="name"><strong>{{$package->name}}</strong></td>
                                    <td class="amount"><strong>{{$package->amount}}</strong></td>
                                    <td class="text-center">
                                        <a class="Update" data-toggle="modal" data-id="'.$package->id.'" data-target="#edit" style="color: #bdad11;cursor: pointer"><button class="btn btn-warning btn-sm">Edit</button></a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row pt-3 pl-3">
                    <div class="col-md-12">
                    {{--   {{$products->appends($_GET)->links()}}--}}
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
                        <h5 class="modal-title">Add New Package</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('package.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input id="" name="name" class="form-control" type="text" value="" placeholder="name" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Amount($) <span class="text-danger">*</span></label>
                                        <input id="" name="amount" class="form-control" type="text" value="" required>
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
                        <form action="{{route('package.update')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="e_id" name="id" class="e_id" value="">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input id="e_name" name="name" class="form-control" type="text" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Amount($) <span class="text-danger">*</span></label>
                                        <input id="e_amount" name="amount" class="form-control" type="text" value="" required>
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
            $('#e_amount').val(_this.find('.amount').text());
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


