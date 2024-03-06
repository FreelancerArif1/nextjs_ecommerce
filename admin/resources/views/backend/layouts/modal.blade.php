@if (Route::currentRouteName() == 'admin.pos')
    {{-- Customer add modal start --}}
    <div class="modal fade" id="customerAddModal" tabindex="-1" role="dialog" aria-labelledby="customerAddModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerAddModalLabel">Add New Customer </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-sample" method="post" action="{{ route('admin.pos.customer.create') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Customer Name <span
                                            class="required">*</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" name="name" class="form-control" required=""
                                            placeholder="Customer Name" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Customer Phone <span
                                            class="required">*</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" name="phone" class="form-control" required=""
                                            placeholder="Customer Phone" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Customer Email </label>
                                    <div class="col-sm-7">
                                        <input type="email" name="email" class="form-control"
                                            placeholder="Customer Email" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p class="text-right">
                                        <button class="btn btn-primary" name="save" type="submit">Add
                                            Customer</button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Customer add modal end --}}

    {{-- Customer address add modal start --}}
    <div class="modal fade" id="customerShippingAddressAddModal" tabindex="-1" role="dialog"
        aria-labelledby="customerShippingAddressAddModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerShippingAddressAddModalLabel">Add New Shipping Address </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="address-error">

                    </div>
                    <form class="form-sample" method="post" action="#" enctype="multipart/form-data"
                        id="address_form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Full Name <span
                                            style="color: #f00">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" class="form-control"
                                            @error('name') is-invalid @enderror value="{{ old('name') }}" required
                                            autocomplete="name" autofocus placeholder="Full Name" id="address-name">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label"> Email</label>
                                    <div class="col-sm-8">
                                        <input id="email" type="text"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" autocomplete="email" autofocus
                                            placeholder="Email Address" id="address-email">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Phone <span
                                            style="color: #f00">*</span></label>
                                    <div class="col-sm-8">
                                        <input id="phone" type="text"
                                            class="form-control @error('phone') is-invalid @enderror" name="phone"
                                            value="{{ old('phone') }}" required autocomplete="name" autofocus
                                            placeholder="Phone Number" id="address-phone">
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Division<span
                                            style="color: #f00">*</span></label>
                                    <div class="col-sm-8">
                                        <select class=" form-control" @error('division_id') is-invalid @enderror
                                            name="division_id" value="{{ old('division_id') }}"
                                            data-show-subtext="true" data-live-search="true" name="division_id"
                                            id="division_id" required="">
                                            <option>Select Division</option>
                                            @foreach (App\Models\Division::orderBy('title', 'asc')->get() as $division)
                                                <option value="{{ $division->id }}">{{ $division->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('division_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">District<span
                                            style="color: #f00">*</span></label>
                                    <div class="col-sm-8">
                                        <select class=" form-control" id="district_id"
                                            @error('district_id') is-invalid @enderror name="district_id"
                                            value="{{ old('district_id') }}" data-show-subtext="true"
                                            data-live-search="true" required="">
                                            <option value="" selected disabled>--Select Division First--</option>
                                        </select>

                                        @error('district_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Upazila / Thana<span
                                            style="color: #f00">*</span></label>
                                    <div class="col-sm-8">
                                        <select class=" form-control" @error('upazila_id') is-invalid @enderror
                                            name="upazila_id" value="{{ old('upazila_id') }}"
                                            data-show-subtext="true" data-live-search="true" id="upazila_id"
                                            required="">
                                            <option value="" selected disabled>--Select District First--</option>
                                        </select>
                                        @error('upazila_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Union / Area<span
                                            style="color: #f00">*</span></label>
                                    <div class="col-sm-8">
                                        <select class=" form-control" @error('union_id') is-invalid @enderror
                                            name="union_id" value="{{ old('union_id') }}" data-show-subtext="true"
                                            data-live-search="true" id="union_id" required="">
                                            <option value="" selected disabled>--Select Upazila First--</option>
                                        </select>
                                        @error('union_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div> --}}


                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Address<span
                                            style="color: #f00">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="street_address" class="form-control" required
                                            autocomplete="name" autofocus placeholder="Address">
                                        @error('street_address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p class="text-right">
                                        <button class="btn btn-primary" name="save" id="customer-address-add-btn"
                                            type="submit">Add Address</button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Customer address add modal end --}}



    <style type="text/css">
        /* HIDE RADIO */
        .img-radio {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* IMAGE STYLES */
        .img-radio+img {
            cursor: pointer;
        }

        /* CHECKED STYLES */
        .img-radio:checked+img {
            outline: 2px solid #f00;
        }

        .fs-14 {
            font-size: 14px !important;
        }
    </style>

    {{-- product quick view modal --}}
    <div class="modal fade" id="posVariableProductModal" tabindex="-1" role="dialog"
        aria-labelledby="posVariableProductModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="posVariableProductModalCenterTitle">Product Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="posVariableProductModalBody">

                </div>
            </div>
        </div>
    </div>
    <style type="text/css">
        .labl {
            display: block;
            /*width: 400px;*/
        }

        .labl>input {
            /* HIDE RADIO */
            visibility: hidden;
            /* Makes input not-clickable */
            position: absolute;
            /* Remove input from document flow */
        }

        .labl>input+div {
            /* DIV STYLES */
            cursor: pointer;
            border: 2px solid transparent;
            border: 1px solid #67b120;
        }

        .labl>input:checked+div {
            /* (RADIO CHECKED) DIV STYLES */
            background-color: #67b120;
            border: 1px solid #67b120;
            color: #ffff;
            font-weight: 400;
        }
    </style>
    {{-- product quick view modal --}}
    <div class="modal fade" id="shippingOptionModal" tabindex="-1" role="dialog"
        aria-labelledby="shippingOptionModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shippingOptionModalCenterTitle">Shipping Option</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="shippingOptionModalBody">

                </div>
            </div>
        </div>
    </div>

    {{-- discount modal  --}}
    <div class="modal fade" id="discountModal" tabindex="-1" role="dialog"
        aria-labelledby="discountModalModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountModalCenterTitle">Discount Option</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="">
                    <div class="discount-error">

                    </div>
                    <form class="" method="post" action="#" enctype="multipart/form-data"
                        id="discount_form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Discount Type <span
                                            style="color: #f00">*</span></label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="discount_type" id="discount_type"
                                            required="">
                                            <option value="-1">-- Select Discount Type --</option>
                                            <option value="custom">Custom</option>
                                            <option value="coupon">Coupon </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 discount_amount_area">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Discount Amount <span
                                            style="color: #f00">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="discount_amount" id="discount_amount"
                                            class="form-control" required="" placeholder="Discount Amount">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 coupon_code_area d-none">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Coupon Code <span
                                            style="color: #f00">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="coupon_code" id="coupon_code"
                                            class="form-control" placeholder="Coupon Code">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p class="text-right">
                                        <button class="btn btn-primary" id="apply-discount-btn" type="submit">Apply
                                            Discount</button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endif

<!--View Modal -->
<div class="modal fade bd-example-modal-lg" id="productViewModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Product Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="vievModelBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!--Seller quick View Modal -->
<div class="modal fade bd-example-modal-lg" id="sellerQuickViewModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Seller Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="sellerQuickViewModalBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-lg" id="customerQuickViewModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Customer Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="customerQuickViewModalBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade activity_log" id="activityLogViewModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Manipulated Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="activityLogViewModalBody">
            </div>
        </div>
    </div>
</div>


<div id="live_notofication"></div>



