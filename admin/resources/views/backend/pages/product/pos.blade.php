@extends('backend.layouts.master')
@section('title','POS - '.config('concave.cnf_appname'))
@section('content')
<style>
    #cart_history table .space{
        white-space: normal !important;
    }
</style>
<section class="section-content padding-y-sm bg-default pos_section">
    <div class="container-fluid mt-50 mb-50">

        <div class="row">

            <div class="col-md-6 col-sm-12">
                
                <div class="row">
                    <div class="col-md-7">
                        <div class="product_search">
                            <i class="mdi mdi-magnify"></i>
                            <input type="text" placeholder="Search Product..." name="serach_product_field" id="serach_product_field">
                        </div> 
                    </div>
                    <div class="col-md-4">
                        <div class="product_search">
                            <select class="form-control selectpicker" data-live-search="true"  name="select_user" id="select_user">
                                <option value="-1">-- Select Customer --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 m-0"> <button style="border-radius: 7px;" class="btn-dark" data-toggle="modal" data-target="#customerAddModal" title="Add Customer"><i style="font-size: 26px;" class="mdi mdi-account-plus"></i></button></div>
                </div>

                <div class="row" id="product_list_area">
                    @foreach($products as $product)
                        <div class="col-sm-6 col-12 col-lg-4 col-xl-4 mb-0 p-1">
                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="card-img-actions">
                                        <img src="{{'/media/thumbnail/'.$product->default_image}}" class="card-img img-fluid" width="96" height="350" alt="">
                                    </div>
                                </div>
                                <div class="card-body bg-light text-center details_card">
                                    <div class="details_section">
                                        <h6 class="font-weight-semibold mb-0">
                                            <a href="#" class="text-primary mb-0 productViewBtn" id="{{ $product->id}}" data-abc="true">{{$product->title}}</a>
                                        </h6>
                                        @if($product->product_type != 'variable')
                                            <p class="mb-0" >SKU: {{$product->sku}}</p>
                                        @endif
                                        <small class="text-danger" >Seller: {{ $product->seller->shopinfo->name ?? '' }}</small>
                                    </div>
                                    <p class="mb-2 font-weight-semibold">{{ 'BDT '.\Helper::price_after_offer($product->id) }}</p>
                                    
                                        @if($product->product_type == 'simple')
                                            <button type="button" style="padding: 3px 5px;" class="btn btn-primary simple_add_to_cart" data-product-id="{{$product->id}}"><i class="mdi mdi-cart mr-1"></i> Add to cart</button>
                                        @elseif($product->product_type == 'digital' || $product->product_type == 'service')
                                            <button type="button" style="padding: 3px 5px;" class="btn btn-primary digital_add_to_cart" data-product-type="{{$product->product_type}}" data-product-id="{{$product->id}}"><i class="mdi mdi-cart mr-1"></i> Add to cart</button>
                                        @elseif($product->product_type == 'variable')
                                            <button type="button" style="padding: 3px 5px;" class="btn btn-primary varient_add_to_cart" data-product-id="{{$product->id}}"><i class="mdi mdi-cart mr-1"></i> Add to cart</button>
                                        @endif
                                    
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="pos_pagination_area" id="">
                    <div class="pos_pagination justify-content-center d-flex mt-4"> {{$products->links()}}</div>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="product_search">
                            <select class="form-control selectpicker" name="payment_method" id="payment_method">
                                <option value="-1">-- Select Payment Method --</option>
                                <option value="cash_on_delivery">Cash On Delivery</option>
                                <option value="online_payment">Online Payment</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="product_search">
                            <select class="form-control selectpicker" name="shipping_address_id" id="shipping_address_id">
                                <option value="-1">-- Select Shipping Address--</option>
                                <optgroup label="Customer Address" id="customer_address_area"></optgroup>
                                <optgroup label="Pick Point Address" id="pickpoint_address">
                                    @php
                                       //print_r($pickpoint_address);exit();
                                    @endphp
                                    @foreach($pickpoint_address as $pickpoint)
                                        <option data-address-type="pickpoint" value="{{$pickpoint==null?'':$pickpoint->id}}">{{$pickpoint==null?'':$pickpoint->title}} - {{$pickpoint->division==null?'':$pickpoint->division->title}} -> {{$pickpoint->district==null?'':$pickpoint->district->title}} -> {{$pickpoint->upazila==null?'':$pickpoint->upazila->title}} -> {{$pickpoint->union==null?'':$pickpoint->union->title}}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 text-right"> 
                        <button  title="Add Address" style="border-radius: 7px;" class="btn-primary" data-toggle="modal" data-target="#customerShippingAddressAddModal"><i style="font-size: 30px;" class="mdi mdi-home-map-marker"></i></button>
                    </div>
                </div>

                <div class="card mt-1" id="cart_history">
                    
                </div>
            </div>
        </div>

    </div>
    
</section>
@push('footer')

<script type="text/javascript">
    // Search Product 
        jQuery(document).on("keyup", "#serach_product_field", function(){
            var search_text = jQuery(this).val();
            var page = 1;
            if(search_text != ''){
                jQuery.ajax({
                    type : "GET",
                    url : "/admin/pos/serach/product?search_text="+search_text+"&page="+page,
                    success: function(response) {
                        jQuery('#product_list_area').empty();
                        jQuery('#product_list_area').html(response);
                        jQuery('.pos_pagination_area').hide();
                    }
                });
            }
        });

    // Pagination 
        jQuery(document).on('click','.dynamic_pagination .page-item .page-link',function(e){
            e.preventDefault();
            var search_text = jQuery('#serach_product_field').val();
            var page = jQuery(this).text();
            if(search_text != ''){
                jQuery.ajax({
                    type : "GET",
                    url : "/admin/pos/serach/product?search_text="+search_text+"&page="+page,
                    success: function(response) {
                        jQuery('#product_list_area').empty();
                        jQuery('#product_list_area').html(response);
                        jQuery('.pos_pagination_area').hide();
                    }
                });
            }
        });

    // Quick View product
        jQuery(document).on('click','.productViewBtn',function(e){
            e.preventDefault();
            var id = jQuery(this).attr('id');
            $.ajax({
                url: "{{  url('/admin/products/view/') }}/"+id,
                type: "GET",
                dataType: "json",
                success: function (data) {
                   var em = jQuery('#vievModelBody').empty();
                   jQuery('#vievModelBody').html(data);
                   jQuery('#productViewModal').modal('show');
                }
            })
        });

    // Calculate grand total 
        function getGrandTotal(){
            var subtotal = Number(jQuery('#subtotal').text());
            var shipping_cost = Number(jQuery('#shipping_cost').text());
            var vat_tax = Number(jQuery('#vat_tax').text());
            var discount = Number(jQuery('#discount').text());
            var grand_total = Number(subtotal + shipping_cost + vat_tax - discount);
            jQuery('#grand_total').text(grand_total);
        }

    //shipping cost calculation 
        function getShippingCost(customer_id, address_type, address){
            $.ajax({
                url: "{{  url('/admin/pos/get/shipping/cost') }}",
                type: "POST",
                data:{
                    customer_id:customer_id,
                    address_type:address_type,
                    address:address
                },
                dataType: "JSON",
                success: function (response) {
                   jQuery('#shipping_cost').empty();
                   jQuery('#shipping_cost').html(response.shipping_cost);

                   jQuery('#grocery_shipping_cost').empty();
                   jQuery('#grocery_shipping_cost').val(response.grocery_shipping_cost);

                   jQuery('#grocery_shipping_cost_text').empty();
                   jQuery('#grocery_shipping_cost_text').text(response.grocery_shipping_cost);
                   getGrandTotal();
                }
            })
        }

    // Get Cart list
        function getCart(customer_id){
            var address = localStorage.getItem("current_user_address_id");
            var address_type = localStorage.getItem("address_type");
            $.ajax({
                url: "{{  url('/admin/pos/get/cart/') }}/"+customer_id,
                type: "GET",
                dataType: "html",
                success: function (response) {
                   jQuery('#cart_history').empty();
                   jQuery('#cart_history').html(response);
                   getShippingCost(customer_id,address_type, address);
                   getGrandTotal();
                }
            })
        }


    //simple add to cart
        jQuery(document).on("click", ".simple_add_to_cart", function(){
            var customer = jQuery('#select_user').val();
            var product_id = jQuery(this).attr("data-product-id");
			var address = jQuery('#shipping_address_id').val();
            var address_type = jQuery('#shipping_address_id').find('option:selected').attr('data-address-type');
            var qty = 1;
            if (customer == -1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Select a customer first!',
                    showConfirmButton: true,
                    timer: 1500
                })
            }else{
                jQuery.ajax({
                    type : "POST",
                    url : "/admin/pos/simple/add-to-cart",
                    data : {user_id: customer, product_id: product_id, qty:qty},
                    success: function(response) {
                        if (response.status == 0) {
                            Swal.fire({
                                icon: 'error',
                                title: response.message,
                                showConfirmButton: true,
                                timer: 1500
                            })
                        }else{
                            getCart(customer);
                            new Audio('/uploads/beep-07a.mp3').play();  
							getCustomerShippingOption(customer,address,address_type);
                            Swal.fire({
                                icon: 'success',
                                title: 'Added to cart!',
                                showConfirmButton: true,
                                timer: 1500
                            })
                        }
                    }
                });
            }
        });

    //digital add to cart
        jQuery(document).on("click", ".digital_add_to_cart", function(){
            var customer = jQuery('#select_user').val();
            var product_id = jQuery(this).attr("data-product-id");
			var address = jQuery('#shipping_address_id').val();
            var address_type = jQuery('#shipping_address_id').find('option:selected').attr('data-address-type');
            var qty = 1;
            let product_type = $(this).attr('data-product-type');
            if (customer == -1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Select a customer first!',
                    showConfirmButton: true,
                    timer: 1500
                })
            }else{
                if (product_type == 'service') {
                    Swal.fire({
                        title: 'Service Informations!',
                        icon: 'info',
                        html:'<div class="form-group">'+
                                '<label>When do you want to take service from us?*</label>'+
                                '<input type="date" name="service_date" id="service_date" class="form-control" min="2022-11-29">'+
                            '</div>'+
                            '<div class="form-group">'+
                                '<label>Select your prefer time, expert will arrive by your selected time *</label>'+
                                '<select name="service_time" id="service_time" class="form-control"><option value="10-11am">10-11 am</option> <option value="11-12pm">11-12 pm</option> <option value="12-1pm">12-1 pm</option> <option value="1-2pm">1-2 pm</option> <option value="2-3pm">2-3 pm</option> <option value="3-4pm">3-4 pm</option> <option value="4-5pm">4-5 pm</option> <option value="5-6pm">5-6 pm</option> <option value="6-7pm">6-7 pm</option> <option value="7-8pm">7-8 pm</option></select>'+
                            '</div>',
                        showCancelButton: true,
                        confirmButtonColor: '#7AB001',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Add To Cart'
                        
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            let service_date = jQuery("#service_date").val();
                            let service_time = jQuery("#service_time").val();

                            jQuery.ajax({
                                type : "POST",
                                url : "/admin/pos/digital/add-to-cart",
                                data : {user_id: customer, product_id: product_id, qty:qty,service_date:service_date,service_time:service_time},
                                success: function(response) {
                                    if (response.status == 0) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: response.message,
                                            showConfirmButton: true,
                                            timer: 1500
                                        })
                                    }else{
                                        getCart(customer);
                                        getCustomerShippingOption(customer,address,address_type);
                                        new Audio('/uploads/beep-07a.mp3').play();  
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Added to cart!',
                                            showConfirmButton: true,
                                            timer: 1500
                                        })
                                    }
                                }
                            });
                        }
                    })
                }else{
                    jQuery.ajax({
                        type : "POST",
                        url : "/admin/pos/digital/add-to-cart",
                        data : {user_id: customer, product_id: product_id, qty:qty},
                        success: function(response) {
                            if (response.status == 0) {
                                Swal.fire({
                                    icon: 'error',
                                    title: response.message,
                                    showConfirmButton: true,
                                    timer: 1500
                                })
                            }else{
                                getCart(customer);
                                getCustomerShippingOption(customer,address,address_type);
                                new Audio('/uploads/beep-07a.mp3').play();  
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Added to cart!',
                                    showConfirmButton: true,
                                    timer: 1500
                                })
                            }
                        }
                    });
                }
                
            }
        });

    //variable add to cart
        jQuery(document).on("submit", "#variable_product_form", function(e){
            e.preventDefault();

            var fromData = jQuery(this).serialize();

            var customer = jQuery('#select_user').val();
            var variable_sku = jQuery('.variable_generate_sku').text();
            var product_id = jQuery(this).find('.variable_final_add_to_cart').attr("data-product-id");
            var qty = jQuery('#variable_qty').val();
			var address = jQuery('#shipping_address_id').val();
            var address_type = jQuery('#shipping_address_id').find('option:selected').attr('data-address-type');

            fromData = fromData+"&user_id="+customer+"&product_id="+product_id+"&qty="+qty+"&variable_sku="+variable_sku;
            if (customer == -1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Select a customer first!',
                    showConfirmButton: true,
                    timer: 1500
                })
            }else{
                jQuery.ajax({
                    type : "POST",
                    url : "/admin/pos/variable/add-to-cart",
                    data : fromData,
                    success: function(response) {
                        if (response.status == 0) {
                            Swal.fire({
                                icon: 'error',
                                title: response.message,
                                showConfirmButton: true,
                                timer: 1500
                            })
                        }else{
                            getCart(customer);
                            new Audio('/uploads/beep-07a.mp3').play();  
                            Swal.fire({
                                icon: 'success',
                                title: 'Added to cart!',
                                showConfirmButton: true,
                                timer: 1500
                            });
                            jQuery('#posVariableProductModal').modal('hide');
							getCustomerShippingOption(customer,address,address_type);
                        }
                    }
                });
            }
        });

    //variable product modal
        jQuery(document).on("click", ".varient_add_to_cart", function(){
            var id = jQuery(this).attr("data-product-id");
			var address = jQuery('#shipping_address_id').val();
            $.ajax({
                url: "{{  url('/admin/pos/variable/product/') }}/"+id,
                type: "GET",
                dataType: "html",
                success: function (response) {
                   jQuery('#posVariableProductModalBody').empty();
                   jQuery('#posVariableProductModalBody').html(response);
                   jQuery('#posVariableProductModal').modal('show');
                }
            })
        });

    //variable sku generate from radio
        jQuery(document).on("change", ".variable_option_radio", function(){
            let generated_sku = $('.variable_generate_sku').text();
            let current_sku = $(this).attr('data-variable-sku');
            if (generated_sku != '') {
                generated_sku = generated_sku+' '+current_sku;
            }else{
                generated_sku = current_sku;
            }
            $('.variable_generate_sku').text(generated_sku);
        })

    //variable sku generate from select
        jQuery(document).on("change", ".variable_option_select", function(){
            let generated_sku = $('.variable_generate_sku').text();
            let current_sku = $(this).find(":selected").attr('data-variable-sku');
            if (generated_sku != '') {
                generated_sku = generated_sku+' '+current_sku;
            }else{
                generated_sku = current_sku;
            }
            $('.variable_generate_sku').text(generated_sku);
        })

    // variable plus 
        jQuery(document).on("click", ".variable_plus", function(){
            // alert('test');
            var qty = Number(jQuery('#variable_qty').val());
            var qty_limit = Number(jQuery('#variable_qty').attr('data-cart-limit'));

            var total_qyt = 0;
            if (qty < qty_limit) {
                total_qyt = Number(qty + 1);
            }else{
                total_qyt = Number(qty);
            }
            jQuery('#variable_qty').val(total_qyt);
        });

     // variable minus 
        jQuery(document).on("click", ".variable_minus", function(){
            // alert('test');
            var qty = Number(jQuery('#variable_qty').val());
            var total_qyt = 1;
            if (qty >= 2) {
                total_qyt = Number(qty - 1);
            }
            jQuery('#variable_qty').val(total_qyt);
        });



    // remove cart 
        jQuery(document).on("click", ".remove_cart_item", function(){
            var id = jQuery(this).attr("id");
            var customer = jQuery('#select_user').val();
			var address = jQuery('#shipping_address_id').val();
            if (customer == -1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Select a customer first!',
                    showConfirmButton: true,
                    timer: 1500
                })
            }else{
                $.ajax({
                    url: "{{  url('/admin/pos/remove/cart/') }}/"+id,
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        if (response.status == 0) {
                            Swal.fire({
                                icon: 'error',
                                title: response.message,
                                showConfirmButton: true,
                                timer: 2000
                            })
                        }else{
                            getCart(customer);
                            new Audio('/uploads/beep-07a.mp3').play();  
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: true,
                                timer: 2000
                            })
                        }
                    }
                })
            }
        });

    // Increment cart 
        jQuery(document).on('click','.increment_cart',function(e){
            e.preventDefault();
            var id = jQuery(this).attr("id");
            var action = 'increment';
            var customer = jQuery('#select_user').val();
            jQuery.ajax({
                type : "POST",
                url : "/admin/pos/update/cart",
                data : {id: id, action: action},
                success: function(response) {
                    if (response.status == 1) {
                        getCart(customer);
                    }else if (response.status == 0) {
                        getCart(customer);
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: true,
                            timer: 1500
                        })
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: response.message,
                            showConfirmButton: true,
                            timer: 1500
                        })
                    }
                }
            });
        });

    // Increment cart 
        jQuery(document).on('click','.decrement_cart',function(e){
            e.preventDefault();
            var id = jQuery(this).attr("id");
            var action = 'decrement';
            var customer = jQuery('#select_user').val();
            jQuery.ajax({
                type : "POST",
                url : "/admin/pos/update/cart",
                data : {id: id, action: action},
                success: function(response) {
                    if (response.status == 1) {
                        getCart(customer);
                    }else{
                        getCart(customer);
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: true,
                            timer: 1500
                        })
                    }
                }
            });
        });

    // Get shipping option for customer address
        function getCustomerShippingOption(customer, address, address_type){
            $.ajax({
                type : "POST",
                url : "/admin/pos/customer/shipping/option",
                data : {customer_id: customer, address: address, address_type:address_type},
                success: function(response) {
                    jQuery('#shippingOptionModalBody').empty();
                    jQuery('#shippingOptionModalBody').html(response);
                }
            });
        }

    // select customer shipping address
        jQuery(document).on('click','#shipping_cost_btn',function(e){
            e.preventDefault();

            var customer = jQuery('#select_user').val();
            var address = jQuery('#shipping_address_id').val();
            var address_type = jQuery('#shipping_address_id').find('option:selected').attr('data-address-type');

            if (customer == -1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Select a customer first!',
                    showConfirmButton: true,
                    timer: 1500
                })
            }else if(address < 1 || address == ''){
                Swal.fire({
                    icon: 'error',
                    title: 'Select a shipping address first!',
                    showConfirmButton: true,
                    timer: 1500
                })
            }else{
                getCustomerShippingOption(customer,address,address_type);
                jQuery('#shippingOptionModal').modal('show');
            }
        });

    // calculate shipping cost 
        function calculateShippingCost(){
            var shipping_cost = 0;
            let grocery_shipping_cost = jQuery('#grocery_shipping_cost').val();
            jQuery('.shipping_option_radio').each(function(key, val) {
                if (jQuery(this).is(":checked")) {
                    let qty = jQuery(this).attr('data-qty');
                    shipping_cost = shipping_cost + (Number(jQuery(this).val()) * Number(qty));
                }
            })
            jQuery('#shipping_cost').text(Number(shipping_cost) + Number(grocery_shipping_cost));
        }


    // select shipping options 
        jQuery(document).on("change", ".shipping_option_radio", function(){
            calculateShippingCost();
            getGrandTotal();
        })

        // jQuery(document).ready(function(){
        //     calculateShippingCost();
        //     getGrandTotal();
        // })
    // open discount modal 
        jQuery(document).on("click", "#discount_modal_btn", function(){
            jQuery('#discountModal').modal('show');
        })

    // select discount type 
        jQuery(document).on("change", "#discount_type", function(){
            var discount_type = jQuery(this).val();
            if (discount_type == 'custom') {
                jQuery('.discount_amount_area').removeClass('d-none');
                jQuery('.discount_amount_area').addClass('d-block');

                jQuery('.coupon_code_area').removeClass('d-block');
                jQuery('.coupon_code_area').addClass('d-none');
            }else{
                jQuery('.discount_amount_area').removeClass('d-block');
                jQuery('.discount_amount_area').addClass('d-none');

                jQuery('.coupon_code_area').removeClass('d-none');
                jQuery('.coupon_code_area').addClass('d-block');
            }
        })

    // discount form submit
        jQuery(document).on("click", "#apply-discount-btn", function(e){
            e.preventDefault();
            var discount_type = jQuery('#discount_type').val();
            if (discount_type == '-1') {
                jQuery('.discount-error').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Ops!</strong> You should select discount type first.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            }else if (discount_type == 'custom') {
                var discount_amount = jQuery('#discount_amount').val();
                if (discount_amount == '') {
                    jQuery('.discount-error').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Ops!</strong> Enter discount amount.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                }else{
                    jQuery('.discount-error').html('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Success!</strong> Coupon successfully added!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    jQuery('#discount').text(Number(jQuery('#discount').text()) + Number(discount_amount));
                    getGrandTotal();
                }
            }else{
                var coupon_code = jQuery('#coupon_code').val();
                if (coupon_code == '') {
                    jQuery('.discount-error').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Ops!</strong> Enter coupon code.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                }else{
                    var customer = jQuery('#select_user').val();
                    jQuery.ajax({
                        type : "POST",
                        url : "/admin/pos/check/coupon/code",
                        data : {coupon_code: coupon_code,customer_id : customer},
                        dataType: "json",
                        success: function(response) {
                            if (response.status == 0) {
                                jQuery('.discount-error').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Ops!</strong> '+response.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                            }else{
                                jQuery('.discount-error').html('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Success!</strong> Coupon successfully added!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                                jQuery('#discount').text(Number(jQuery('#discount').text()) + Number(response.coupon_amount));
                                getGrandTotal();
                            }
                        }
                    });
                }
            }
        })

    

    //Get customer shipping address

        function getShippingAddress(user_id){
            jQuery.ajax({
                type : "POST",
                url : "/admin/pos/customer/shipping/address",
                data : {customer_id: user_id},
                success: function(response) {
                    jQuery('#customer_address_area').html(response);

                        if (localStorage.getItem("address_type") == 'user_address') {
                            jQuery("#customer_address_area option[value='"+localStorage.getItem("current_user_address_id")+"']").prop('selected',true);
                        }

                    $('.selectpicker').selectpicker('refresh');
                }
            });
        }

        jQuery(document).on("change", "#select_user", function(){
            var nonce = jQuery(this).attr("data-nonce");
            var select_user = jQuery(this).find('option:selected').val();
            if(select_user != -1){
                localStorage.setItem("current_user_id", select_user);
                getCart(select_user);
                getShippingAddress(select_user);
            }
        });

        jQuery(document).on("change", "#shipping_address_id", function(){
            var address = jQuery(this).find('option:selected').val();
            var address_type = jQuery(this).find('option:selected').attr('data-address-type');
            
            // alert(address);
            if(address != -1){
                localStorage.setItem("current_user_address_id", address);
                localStorage.setItem("address_type", address_type);
            }
            getCart(localStorage.getItem("current_user_id"));
        });

        jQuery(document).on("change", "#payment_method", function(){
            var payment_method = jQuery(this).find('option:selected').val();
            if(payment_method != -1){
                localStorage.setItem("current_user_payment_method", payment_method);
            }
        });

    // auto select and function 
        $("#shipping_address_id option:selected").prop("selected", false);

        getCart(localStorage.getItem("current_user_id"));

        jQuery('#select_user').val(localStorage.getItem("current_user_id")).trigger('change');

        getShippingAddress(localStorage.getItem("current_user_id"));

        if (localStorage.getItem("address_type") == 'pickpoint') {
            jQuery("#pickpoint_address option[value='"+localStorage.getItem("current_user_address_id")+"']").prop('selected',true);
        }

        jQuery('#payment_method').val(localStorage.getItem("current_user_payment_method")).trigger('change');
        



    //Get Customer district
        jQuery(document).on("change", "#division_id", function(){
            var nonce = jQuery(this).attr("data-nonce");
            var division_id = jQuery(this).find('option:selected').val();
            if(division_id != -1){
                jQuery.ajax({
                    type : "POST",
                    url : "/admin/seller/get-district",
                    data : {division_id: division_id},
                    success: function(response) {
						jQuery('#district_id').empty();
                        jQuery('#district_id').append('<option value="-1">-- Select --</option>'+response);
                    }
                });
            }
        });

    //Get Customer upazila
        jQuery(document).on("change", "#district_id", function(){
            jQuery('.ajax_loader').show();
            var nonce = jQuery(this).attr("data-nonce");
            var district_id = jQuery(this).find('option:selected').val();
            if(division_id != -1){
                    jQuery.ajax({
                    type : "POST",
                    url : "/admin/seller/get-upazila",
                    data : {district_id: district_id},
                    success: function(response) {
						jQuery('#upazila_id').empty(); 
                        jQuery('#upazila_id').append('<option value="-1">-- Select --</option>'+response);
                    }
                });
            }
        });

    //Get customer union
        jQuery(document).on("change", "#upazila_id", function(){
            jQuery('.ajax_loader').show();
            var nonce = jQuery(this).attr("data-nonce");
            var upazila_id = jQuery(this).find('option:selected').val();
            if(division_id != -1){
                    jQuery.ajax({
                    type : "POST",
                    url : "/admin/seller/get-union",
                    data : {upazila_id: upazila_id},
                    success: function(response) {
						jQuery('#union_id').empty(); 
                        jQuery('#union_id').append('<option value="-1">-- Select --</option>'+response); 
                    }
                });
            }
        });

    // Add customer address
        jQuery('#customer-address-add-btn').click(function(event){
            event.preventDefault();
            var customer = jQuery('#select_user').val();
            if(customer == -1){
                jQuery('.address-error').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Ops!</strong> You should select customer first.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            }else{
                // Get form
                var form = jQuery('#address_form')[0];
                var data = new FormData(form);
                data.append('customer', customer);
                jQuery.ajax({
                    method: 'POST',
                    url: '/admin/pos/customer/shipping/address/add',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Successfully added',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        setTimeout(function() {
                            location.reload();
                        }, 800);//
                    },
                    error: function (xhr) {
                        var errorMessage = '<div class="card bg-danger">\n' +
                            '                        <div class="card-body text-center p-5">\n' +
                            '                            <span class="text-white">';
                        $.each(xhr.responseJSON.errors, function(key,value) {
                            errorMessage +=(''+value+'<br>');
                        });
                        errorMessage +='</span>\n' +
                            '                        </div>\n' +
                            '                    </div>';
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            footer: errorMessage
                        })
                    },
               })
            }
        });


        jQuery(document).on("click", "#order_now_btn", function(e){
            e.preventDefault();

            var customer = jQuery('#select_user').val();
            var address  = jQuery('#shipping_address_id').val();
            let address_type = localStorage.getItem("address_type");
            var payment_method  = jQuery('#payment_method').val();
            var order_note  = jQuery('#order_note').val();
			
            var shipping_method = new Array();

            jQuery('.shipping_option_radio').each(function(key, val) {
                if (jQuery(this).is(":checked")) {
                    shipping_method.push([jQuery(this).attr("data-shippingmethod"), jQuery(this).attr("data-id"), jQuery(this).val()]);
                }
            })

            var shipping_cost  = jQuery('#shipping_cost').text();
            var discount  = jQuery('#discount').text();

            if (customer == -1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Select a customer first!',
                    showConfirmButton: true,
                })
            }else if(address < 1 || address == null){
                Swal.fire({
                    icon: 'error',
                    title: 'Select a shipping address first!',
                    showConfirmButton: true,
                })
            }else if (payment_method == -1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Select a payment method!',
                    showConfirmButton: true,
                })
            }else{
                var data = new FormData();
                data.append('customer', customer);
                data.append('address', address);
                data.append('address_type', address_type);
                data.append('payment_method', payment_method);
                data.append('order_note', order_note);
                data.append('shipping_method',  JSON.stringify(shipping_method));
                data.append('discount', discount);

                jQuery.ajax({
                    method: 'POST',
                    url: '/admin/pos/order',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        Swal.fire({
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            // timer: 5000
                        })
                        setTimeout(function() {
                            location.reload();
                        }, 1000);//
                    },
                    error: function (xhr) {
                        var errorMessage = '<div class="card bg-danger">\n' +
                            '                        <div class="card-body text-center p-5">\n' +
                            '                            <span class="text-white">';
                        $.each(xhr.responseJSON.errors, function(key,value) {
                            errorMessage +=(''+value+'<br>');
                        });
                        errorMessage +='</span>\n' +
                            '                        </div>\n' +
                            '                    </div>';
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            footer: errorMessage
                        })
                    },
                })
            }
        })
</script>
@endpush
@endsection