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
                            <h3 class="page-title">Offer Transaction (Reward)</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Offer Transaction (Reward)</li>
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
                <div class="col-md-12">
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="alert text-center" role="alert" style="background-color: #495057; color:white;">Offer Transaction Form</div>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    <form action="{{route('merchant.offer.transaction.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-4 pt-3" style="background-color: #ddd">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Customer <span class="text-danger">*</span></label>
                                            <select class="form-control" id="customer_search" name="customer_id" style="" onchange="getCustomerDetails(this.value)"></select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 pt-2">
                                        <h5>Customer Info</h5><hr>
                                        <p id="customerName"></p>
                                        <p id="customerPhone"></p>
                                        <p id="customerEmail"></p>
                                        <h3 id="customer_MerchantWiseWalletPoint" class="text-success"></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 pt-3" style="background-color: #e9ecef">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Offer <span class="text-danger">*</span></label>
                                            <select class="form-control" id="offer_search" name="offer_id" style="" onchange="getOfferDetails(this.value)"></select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 pt-2">
                                        <h5>Offer Info</h5><hr>
                                        <span id="offerName"></span><br>
                                        <span id="offerCode"></span><br>
                                        <span id="offerStart"></span><br>
                                        <span id="offerEnd"></span><br><br>
                                        <span id="offerDiscount" class="font-18 text-success"></span><br>
                                        <span id="offerPoint" class="font-18 text-success"></span><br><br>
                                        <span id="offerDescription"></span><br>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 pt-3">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Date <span class="text-danger">*</span></label>
                                            <input name="date" class="form-control" value="{{ date('d M Y') }}" type="text" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Created By (user) <span class="text-danger">*</span></label>
                                            <input name="created_by" class="form-control" value="{{Auth::user()->name ?? ''}}" type="text" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Invoice No <span class="text-danger">*</span></label>
                                            <input name="invoice_no" class="form-control" value="" type="text" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Amount <span class="text-danger">*</span></label>
                                            <input name="amount" class="form-control" value="0" type="number" step="any" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Point (add/deduction/none) <span class="text-danger">*</span></label>
                                        <select class="select" name="point_status" id="point_status" required>
                                            <option selected value="none">none</option>
                                            <option value="add">add</option>
                                            <option value="deduction">deduction</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Details</label>
                                            <textarea name="details" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="submit-section">
                            <button type="submit" class="btn submit-btn btn-success" style="">Submit Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
@endsection

@section('script')
        {{-- getCustomerList_searchByName --}}
            <script>
                var path = "{{route('getCustomerList_searchByName')}}";
                <!-- use from search/filter section  -->
                $('#customer_search').select2({
                    placeholder: '--- Select Customer ---',
                    ajax: {
                        url: path,
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results:  $.map(data, function (item) {
                                    return {
                                        text: item.name + '(ID: ' + item.id + ')',
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            </script>
            {{-- getCustomerDetails --}}
            <script>
                function getCustomerDetails(customerId){
                    const url = "{{ route('merchant.getCustomerDetails', ['customerId' => ':customerId']) }}".replace(':customerId', customerId);
                    axios.get(url)
                        .then(res=>{
                            $('#customerName').text("Name: "+res.data.name);
                            $('#customerPhone').text("Phone: "+res.data.phone);
                            $('#customerEmail').text("Email: "+res.data.email);
                            $('#customer_MerchantWiseWalletPoint').text("Wallet Point: "+res.data.merchantWiseWalletPoint);
                        })
                        .catch(error=>{
                            $('#customerName').text();
                            $('#customerPhone').text();
                            $('#customerEmail').text();
                        })
                }
            </script>

            {{-- getOfferList_searchByName --}}
            <script>
                var path = "{{route('getOfferList_searchByName')}}";
                <!-- use from search/filter section  -->
                $('#offer_search').select2({
                    placeholder: '--- Select Offer ---',
                    ajax: {
                        url: path,
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results:  $.map(data, function (item) {
                                    return {
                                        text: item.name + '(CODE: ' + item.offer_code + ')',
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            </script>
            {{-- getOfferDetails --}}
            <script>
                function getOfferDetails(offerId){
                    const url = "{{ route('getOfferDetails', ['offerId' => ':offerId']) }}".replace(':offerId', offerId);
                    axios.get(url)
                        .then(res=>{
                            $('#offerName').text("Name: "+res.data.name);
                            $('#offerCode').text("Code: "+res.data.offer_code);
                            $('#offerStart').text("Start Date: "+res.data.start_date);
                            $('#offerEnd').text("End Date: "+res.data.end_date);
                            $('#offerDiscount').text("Discount: "+res.data.discount);
                            $('#offerPoint').text("Point: "+res.data.point);
                            $('#offerDescription').text("Description: "+res.data.description);
                        })
                        .catch(error=>{
                            $('#offerName').text();
                            $('#offerCode').text();
                            $('#offerStart').text();
                            $('#offerEnd').text();
                            $('#offerDiscount').text();
                            $('#offerPoint').text();
                            $('#offerDescription').text();
                        })
                }
            </script>
@endsection
