@extends('backend.layouts.master')
@section('title', 'Product Create - ' . config('concave.cnf_appname'))
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet" />
    <style type="text/css">
        .bootstrap-tagsinput .tag {
            margin-right: 2px;
            color: white !important;
            background-color: #5daf21;
            padding: 0.2rem;
            line-height: 28px;
        }
    </style>
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Catalog > Products >
                    <span class="create_product_title">Create New
                        @if (isset($_GET['product_type']))
                            {{ $_GET['product_type'] }}
                        @endif
                        Product
                    </span></span>
                <a class="btn btn-primary float-right" href="{{ route('admin.product') }}">View Product List</a>
            </div>
        </div>
    </div>

    @if (!isset($_GET['product_type']))
        <div class="product_type_selector mb-3">
            <div class="card">
                <div class="card-body">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Product Type <span
                                            class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <select name="product_type" id="product_type" class="form-control">
                                                <option value="simple">Simple</option>
                                                <option value="variable">Variable</option>
                                                <option value="digital">Digital</option>
                                                <option value="service">Service</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3"> <button type="submit" id="submit_product_type"
                                    class="btn btn-primary btn-input">Continue</button></div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endif

    <div class="product_form_section">
        @if (isset($_GET['product_type']) && $_GET['product_type'] == 'simple')
            @include('backend.pages.product.parts.create.simple')
        @elseif(isset($_GET['product_type']) && $_GET['product_type'] == 'variable')
            @include('backend.pages.product.parts.create.variable')
        @elseif(isset($_GET['product_type']) && $_GET['product_type'] == 'digital')
            @include('backend.pages.product.parts.create.digital')
        @elseif(isset($_GET['product_type']) && $_GET['product_type'] == 'service')
            @include('backend.pages.product.parts.create.service')
        @endif
    </div>

    @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>

        <script type="text/javascript">
            jQuery(document).ready(function() {
                $(".tag_field").tagsinput('items');
                // jQuery("#is_grocery").trigger("click");

                function checkShippingCost() {
                    var type_for_shipping = '';
                    if ($('#is_grocery').is(":checked")) {
                        type_for_shipping = "grocery";
                    } else {
                        type_for_shipping = "other";
                    }
                    // alert(type_for_shipping);
                    var weight = $('#weight').val();
                    var weight_unit = $('#weight_unit').val();
                    if (weight == '') {
                        weight = 0;
                    } else {
                        weight = weight;
                    }
                    // alert(weight);
                    $.ajax({
                        url: "{{ url('/admin/products/check/shipping/cost/') }}/" + type_for_shipping + "/" +
                            weight + "/" + weight_unit,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            jQuery("#inside_standard_shipping").val(data.inside_origin_standard);
                            jQuery("#inside_express_shipping").val(data.inside_origin_express);
                            jQuery("#outside_standard_shipping").val(data.outside_origin_standard);
                            jQuery("#outside_express_shipping").val(data.outside_origin_express);
                            // console.log(data);
                        }
                    })
                }

                checkShippingCost();


                jQuery(document).on("change", "#is_grocery", function() {

                    checkShippingCost();

                });

                jQuery(document).on("change", "#weight_unit", function() {

                    checkShippingCost();

                });

                jQuery(document).on("keyup", "#weight", function() {

                    checkShippingCost();

                });

            });
        </script>
    @endpush

@endsection
