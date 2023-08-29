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
                        <h3 class="page-title">Product Management</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Product Management</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        @if(!(Auth::user()->type=='DeliveryAgent'))
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add"><i class="fa fa-plus"></i> Add New</a>
                        @endif
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Search Filter -->
            <form action="{{ route('product.index') }}" method="GET">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <input type="text" class="form-control" name="invoice_no" value="{{ Request()->get('invoice_no') }}">
                            <label class="focus-label">Invoice No</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <select class="select form-control" name="status">
                                <option value=""> --Select Status --</option>
                                <option value="Booking Office" @if(Request()->get('status')=='Booking Office') selected @endif>Booking Office</option>
                                <option value="On The Airlines"  @if(Request()->get('status')=='On The Airlines') selected @endif>On The Airlines</option>
                                <option value="Arrived in Airport"  @if(Request()->get('status')=='Arrived in Airport') selected @endif>Arrived in Airport</option>
                                <option value="Waiting for Custom Clearing"  @if(Request()->get('status')=='Waiting for Custom Clearing') selected @endif>Waiting for Custom Clearing</option>
                                <option value="In Delivery Hub" @if(Request()->get('status')=='In Delivery Hub') selected @endif>In Delivery Hub</option>
                                <option value="In Delivery Office"  @if(Request()->get('status')=='In Delivery Office') selected @endif>In Delivery Office</option>
                                <option value="Delivered"  @if(Request()->get('status')=='Delivered') selected @endif>Delivered</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <button type="submit" class="btn btn-success btn-block"> Search </button>
                    </div>
                </div>
            </form>
            <!-- /Search Filter -->

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
                    <h4 style="color: dimgray"><i class="fa fa-list-alt"><strong> Product List</strong></i></h4>
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable table-border" id="DataTable">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th class="text-center"><strong>Action</strong></th>
                                <th><strong>Date</strong></th>
                                <th><strong>Invoice No</strong></th>
                                <th><strong>Status</strong></th>
                                <th><strong>Booking Branch</strong></th>
                                <th><strong>Destination Branch</strong></th>
                                <th><strong>Service Type</strong></th>
                                <th><strong>Delivery Mode</strong></th>
                                <th><strong>Sender Name</strong></th>
                                <th><strong>Sender Phone</strong></th>
                                <th><strong>Sender Address</strong></th>
                                <th><strong>Receiver Name</strong></th>
                                <th><strong>Receiver Phone</strong></th>
                                <th><strong>Receiver Address</strong></th>
                                <th><strong>Product Description</strong></th>
                                <th><strong>Quantity</strong></th>
                                <th><strong>Unit</strong></th>
{{--                                <th><strong>Word</strong></th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($products as $key=>$product)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td class="text-center">
                                        @if(!(Auth::user()->type=='DeliveryAgent'))
                                        <a class="Update" data-toggle="modal" data-id="'.$product->id.'" data-target="#edit" style="color: #bdad11;cursor: pointer"><button class="btn btn-warning btn-sm">Edit</button></a>
                                        @endif
                                        <a class="StatusUpdate" data-toggle="modal" data-id="'.$product->id.'" data-target="#status_update" style="color: #0b5fb7;cursor: pointer"><button class="btn btn-info btn-sm">Status Update</button></a>
                                        <a href="{{url('product/invoice')}}/{{$product->id}}" target="_blank" style="cursor: pointer"><button class="btn btn-purple btn-sm">Proof of Delivery</button></a>
                                        <a href="{{url('product/label')}}/{{$product->id}}" target="_blank" style="cursor: pointer"><button class="btn btn-info btn-sm">Label Print</button></a>
                                            <a class="Delete" href="#" data-toggle="modal" data-id="'.$product->id.'" data-target="#delete" style="color: #bd2f2f;cursor: pointer"><button class="btn btn-danger btn-sm"><i class="fa fa-trash-o fa-lg"></i> Delete</button></a>
                                            {{--   <a class="Delete" href="#" data-toggle="modal" data-id="'.$club->id.'" data-target="#delete_device" style="color: #bd2f2f;cursor: pointer"><i class="fa fa-trash-o fa-lg"></i></a>--}}
                                    </td>
                                    <td hidden class="ids">{{ $product->id }}</td>
                                    <td class="date"><strong>{{$product->date}}</strong></td>
                                    <td class="invoice_no">{{$product->invoice_no}}</td>
                                    <td class="statuss"><span class="badge badge-warning">{{$product->status}}</span></td>
                                    <td class="booking_branch">{{$product->booking_branch}}</td>
                                    <td class="destination_branch">{{$product->destination_branch}}</td>
                                    <td class="service_type">{{$product->service_type}}</td>
                                    <td class="delivery_mode">{{$product->delivery_mode}}</td>
                                    <td class="sender_name">{{$product->sender_name}}</td>
                                    <td class="sender_phone">{{$product->sender_phone}}</td>
                                    <td class="sender_address">{{$product->sender_address}}</td>
                                    <td class="receiver_name">{{$product->receiver_name}}</td>
                                    <td class="receiver_phone">{{$product->receiver_phone}}</td>
                                    <td class="receiver_address">{{$product->receiver_address}}</td>
                                    <td class="product_description">{{$product->product_description}}</td>
                                    <td class="quantity">{{$product->quantity}}</td>
                                    <td class="unit">{{$product->unit}}</td>
{{--                                    <td class="word">{{$product->word}}</td>--}}
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row pt-3 pl-3">
                    <div class="col-md-12">
                          {{$products->appends($_GET)->links()}}
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
                        <h5 class="modal-title">Add New Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('product.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Date <span class="text-danger">*</span></label>
                                        <input id="" name="date" class="form-control" type="date" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Invoice No <span class="text-danger">*</span></label>
                                        <input id="" name="invoice_no" class="form-control" type="text" value="" placeholder="Enter invoice no" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Booking Branch <span class="text-danger">*</span></label>
                                        <input id="" name="booking_branch" class="form-control" type="text" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Destination Branch <span class="text-danger">*</span></label>
                                        <input id="" name="destination_branch" class="form-control" type="text" value="" required >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Service Type </label>
                                        <input id="" name="service_type" class="form-control" type="text" value="" >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>delivery_mode <span class="text-danger">*</span></label>
                                        <select class="select" name="delivery_mode" id="delivery_mode" required>
                                            <option selected disabled> --Select --</option>
                                            <option value="Office-Delivery">Office-Delivery</option>
                                            <option value="Home-Delivery">Home-Delivery</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="alert bg-dark"><h5 class=" text-white text-center">Sender Details </h5></div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Sender Name <span class="text-danger">*</span></label>
                                        <input id="" name="sender_name" class="form-control" type="text" value="" required >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Sender Phone <span class="text-danger">*</span></label>
                                        <input id="" name="sender_phone" class="form-control" type="text" value="" required >
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Sender Address <span class="text-danger">*</span></label>
                                        <textarea name="sender_address" class="form-control" rows="2" required></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="alert bg-dark"><h5 class=" text-white text-center">Receiver Details </h5></div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Receiver Name <span class="text-danger">*</span></label>
                                        <input id="" name="receiver_name" class="form-control" type="text" value="" required >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Receiver Phone <span class="text-danger">*</span></label>
                                        <input id="" name="receiver_phone" class="form-control" type="text" value="" required >
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Receiver Address <span class="text-danger">*</span></label>
                                        <textarea name="receiver_address" class="form-control" rows="2" required></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="alert bg-dark"><h5 class=" text-white text-center">Product Details </h5></div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Product Description <span class="text-danger">*</span></label>
                                        <textarea name="product_description" class="form-control" rows="2" required></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Quantity </label>
                                        <input id="" name="quantity" class="form-control" type="text" value="" >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Unit </label>
                                        <input id="" name="unit" class="form-control" type="text" value="" >
                                    </div>
                                </div>
{{--                                <div class="col-sm-6">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label>Word </label>--}}
{{--                                        <input id="" name="word" class="form-control" type="text" value="" >--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Image </label>
                                        <input id="" name="image" class="form-control" type="file" value="" >
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select class="select" name="status" id="status" required>
                                            <option selected value=""> --Select --</option>
                                            <option value="Booking Office">Booking Office</option>
                                            <option value="On The Airlines">On The Airlines</option>
                                            <option value="Arrived in Airport">Arrived in Airport</option>
                                            <option value="Waiting for Custom Clearing">Waiting for Custom Clearing</option>
                                            <option value="In Delivery Hub">In Delivery Hub</option>
                                            <option value="In Delivery Office">In Delivery Office</option>
                                            <option value="Delivered">Delivered</option>
                                        </select>
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
                        <form action="{{route('product.update')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="e_id" name="id" class="e_id" value="">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Date <span class="text-danger">*</span></label>
                                        <input id="e_date" name="date" class="form-control" type="date" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Invoice No <span class="text-danger">*</span></label>
                                        <input id="e_invoice_no" name="invoice_no" class="form-control" type="text" value="" placeholder="Enter invoice no" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Booking Branch <span class="text-danger">*</span></label>
                                        <input id="e_booking_branch" name="booking_branch" class="form-control" type="text" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Destination Branch <span class="text-danger">*</span></label>
                                        <input id="e_destination_branch" name="destination_branch" class="form-control" type="text" value="" required >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Service Type </label>
                                        <input id="e_service_type" name="service_type" class="form-control" type="text" value="" >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>delivery_mode <span class="text-danger">*</span></label>
                                        <select class="select" name="delivery_mode" id="e_delivery_mode" required>
                                            <option selected disabled> --Select --</option>
                                            <option value="Office-Delivery">Office-Delivery</option>
                                            <option value="Home-Delivery">Home-Delivery</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="alert bg-dark"><h5 class=" text-white text-center">Sender Details </h5></div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Sender Name <span class="text-danger">*</span></label>
                                        <input id="e_sender_name" name="sender_name" class="form-control" type="text" value="" required >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Sender Phone <span class="text-danger">*</span></label>
                                        <input id="e_sender_phone" name="sender_phone" class="form-control" type="text" value="" required >
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Sender Address <span class="text-danger">*</span></label>
                                        <textarea id="e_sender_address" name="sender_address" class="form-control" rows="2" required></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="alert bg-dark"><h5 class=" text-white text-center">Receiver Details </h5></div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Receiver Name <span class="text-danger">*</span></label>
                                        <input id="e_receiver_name" name="receiver_name" class="form-control" type="text" value="" required >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Receiver Phone <span class="text-danger">*</span></label>
                                        <input id="e_receiver_phone" name="receiver_phone" class="form-control" type="text" value="" required >
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Receiver Address <span class="text-danger">*</span></label>
                                        <textarea id="e_receiver_address" name="receiver_address" class="form-control" rows="2" required></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="alert bg-dark"><h5 class=" text-white text-center">Product Details </h5></div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Product Description <span class="text-danger">*</span></label>
                                        <textarea id="e_product_description" name="product_description" class="form-control" rows="2" required></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Quantity </label>
                                        <input id="e_quantity" name="quantity" class="form-control" type="text" value="" >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Unit </label>
                                        <input id="e_unit" name="unit" class="form-control" type="text" value="" >
                                    </div>
                                </div>
{{--                                <div class="col-sm-6">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label>Word </label>--}}
{{--                                        <input id="e_word" name="word" class="form-control" type="text" value="" >--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Received Image </label>
                                        <input id="" name="image" class="form-control" type="file" value="" >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Delivered Image </label>
                                        <input id="" name="delivered_image" class="form-control" type="file" value="" >
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

        <!-- status update Modal -->
        <div id="status_update" class="modal custom-modal fade" role="dialog">
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
                        <form action="{{route('product.statusUpdate')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="e1_id" name="id" class="e_id" value="">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Invoice No</label>
                                        <input id="e1_invoice_no" name="invoice_no" class="form-control" type="text" value="" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select class="select" name="status" id="e1_status" required>
                                            <option value=""> --Select --</option>
                                            <option value="Booking Office" @if(Auth::user()->type=='DeliveryAgent') disabled @endif >Booking Office</option>
                                            <option value="On The Airlines" @if(Auth::user()->type=='DeliveryAgent') disabled @endif >On The Airlines</option>
                                            <option value="Arrived in Airport" @if(Auth::user()->type=='DeliveryAgent') disabled @endif >Arrived in Airport</option>
                                            <option value="Waiting for Custom Clearing" @if(Auth::user()->type=='DeliveryAgent') disabled @endif >Waiting for Custom Clearing</option>
                                            <option value="In Delivery Hub" @if(Auth::user()->type=='DeliveryAgent') disabled @endif >In Delivery Hub</option>
                                            <option value="In Delivery Office" @if(Auth::user()->type=='DeliveryAgent') disabled @endif >In Delivery Office</option>
                                            <option value="Delivered">Delivered</option>
                                        </select>
                                    </div>
                                </div>
                                @if(Auth::user()->type=='DeliveryAgent')
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Image </label>
                                        <input id="" name="image" class="form-control" type="file" value="" >
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-success">Update Now</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /status update Modal -->

        <!-- Delete Modal -->
        <div class="modal custom-modal fade" id="delete" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Product</h3>
                            <p>Are you sure you want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{route('product.delete')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" class="e_id" value="">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn submit-btn">Delete</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
            $('#e_date').val(_this.find('.date').text());
            $('#e_invoice_no').val(_this.find('.invoice_no').text());
            $('#e_booking_branch').val(_this.find('.booking_branch').text());
            $('#e_destination_branch').val(_this.find('.destination_branch').text());
            $('#e_service_type').val(_this.find('.service_type').text());

            $('#e_sender_name').val(_this.find('.sender_name').text());
            $('#e_sender_phone').val(_this.find('.sender_phone').text());
            $('#e_sender_address').val(_this.find('.sender_address').text());
            $('#e_receiver_name').val(_this.find('.receiver_name').text());
            $('#e_receiver_phone').val(_this.find('.receiver_phone').text());
            $('#e_receiver_address').val(_this.find('.receiver_address').text());

            $('#e_product_description').val(_this.find('.product_description').text());
            $('#e_quantity').val(_this.find('.quantity').text());
            $('#e_unit').val(_this.find('.unit').text());
            //$('#e_word').val(_this.find('.word').text());

            // delivery mode
            var delivery_mode = (_this.find(".delivery_mode").text());
            var _option = '<option selected value="'+delivery_mode+'">' + delivery_mode + '</option>'
            $( _option).appendTo("#e_delivery_mode");

        });
    </script>

    {{-- status update --}}
    <script>
        $(document).on('click','.StatusUpdate',function()
        {
            var _this = $(this).parents('tr');
            $('#e1_id').val(_this.find('.ids').text());
            $('#e1_invoice_no').val(_this.find('.invoice_no').text());

            // status
            var status = (_this.find(".statuss").text());
            var _option = '<option selected value="'+status+'">' + status + '</option>'
            $( _option).appendTo("#e1_status");

        });
    </script>

    {{-- delete js --}}
    <script>
        $(document).on('click','.Delete',function()
        {
            var _this = $(this).parents('tr');
            $('.e_id').val(_this.find('.ids').text());
        });
    </script>
@endsection


