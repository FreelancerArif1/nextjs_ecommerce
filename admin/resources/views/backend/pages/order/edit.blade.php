@extends('backend.layouts.master')
@section('title','Order Edit - '.config('concave.cnf_appname'))
@section('content')

<div class="grid-margin stretch-card">
  	<div class="card">
      	<div class="card-body">
        	<span class="card-title">Dashboard > Sales > Orders > Edit</span>
      	</div>
  	</div>
</div>

<div class="grid-margin stretch-card">
  	<div class="card">
      	<div class="card-body">
      		<form class="" action="{{ route('admin.order.update.order')}}" method="post">
      			@csrf
      			<input type="hidden" name="order_id" value="{{ $order->id }}">
				<div class="row">
					<div class="col-lg-3">
						<label>Customer</label>
						<select name="customer_id" class="form-control customer_id" >
							<option value="{{$order->user->id ?? 0 }}">{{$order->user->name ?? '' }}</option>
						</select>
					</div>
					<div class="col-lg-3">
						<label>Address</label>
						<select name="address_id" id="address_id" class="form-control address_id">
							<optgroup label="Customer Address" id="customer_address_area" >
								@foreach($user_address as $address)
									<option value="{{ $address->id }}" @if($order->is_pickpoint == 0 && $order->address_id == $address->id ) selected="" @endif data-address-type="user_address">
										{{ $address->division->title ?? '' }} > {{ $address->district->title ?? ''}} > {{ $address->upazila->title ?? '' }} > {{ $address->union->title ?? '' }} > {{ $address->shipping_address ?? '' }}
									</option>
								@endforeach
							</optgroup>
							<optgroup label="Pickpoint Address" id="pickpoint_address" >
								@foreach($pickpoint_address as $paddress)
									<option value="{{ $paddress->id }}" @if($order->is_pickpoint == 1 && $order->address_id == $paddress->id ) selected="" @endif data-address-type="pickpoint">
										{{ $paddress->division->title ?? '' }} > {{ $paddress->district->title ?? ''}} > {{ $paddress->upazila->title ?? '' }} > {{ $paddress->union->title ?? '' }} > {{ $paddress->address ?? '' }}
									</option>
								@endforeach
							</optgroup>
						</select>
						<input type="hidden" name="is_pickpoint" id="is_pickpoint" value="{{ $order->is_pickpoint}}">
					</div>
					<div class="col-lg-3">
						<label>Payment Method</label>
                        <select class="form-control" name="payment_method" id="payment_method">
                            <option value="-1">-- Select Payment Method --</option>
                            <option value="cash_on_delivery" @if($order->payment_method == 'cash_on_delivery') selected @endif>Cash On Delivery</option>
                            <option value="online_payment" @if($order->payment_method == 'online_payment') selected @endif>Online Payment</option>
                            {{-- <option value="2">Bkash</option>
                            <option value="3">Nagad</option>
                            <option value="4">Rocket</option>
                            <option value="5">Bank Card</option> --}}
                        </select>
                    </div>
                    <div class="col-lg-3">
                    	<label>Payment Status</label>
                    	<select name="payment_status" id="" class="form-control">
                          	<option @if ($order->status == 1) selected @endif value="1"> Pending </option>
                            <option @if ($order->status == 2) selected @endif value="2"> Accepted </option>
                            <option @if ($order->status == 3) selected @endif value="3"> On Hold </option>
                            <option @if ($order->status == 4) selected @endif value="4"> Failed </option>
                            <option @if ($order->status == 5) selected @endif value="5"> Canceled </option>
                            <option @if ($order->status == 6) selected @endif value="6"> Completed </option>
                            <option @if ($order->status == 15) selected @endif value="15"> Partial </option>
                            <option @if ($order->status == 7) selected @endif value="7"> Refund Requested
                            </option>
                            <option @if ($order->status == 8) selected @endif value="8"> Refunded </option>
                      	</select>
                    </div>
				</div>  

				<div class="row mt-4"> 
					<div class="col-lg-12">
						<table class="table table-bordered">  
	                        <thead>
	                            <tr>
	                                <th scope="col" width="30%">Product</th>
	                                <th scope="col" width="5%">Quantity</th>
	                                <th scope="col" width="10%">Price</th>
	                                <th scope="col" width="15%">Shipping Cost</th>
	                                <th scope="col" width="10%">Packaging Cost</th>
	                                <th scope="col" width="10%">Security Charge</th>
	                                <th scope="col" width="10%">Sub Total</th>
	                                <th scope="col">
	                                	<a class="btn btn-info text-light" id="add"><i class="mdi mdi-plus-circle"></i></a>
	                                </th>
	                            </tr>
	                        </thead>
	                        <tbody id="order_details">
	                        	@php $count = 1; $availableShippingOptions = ''; $subtotal =0; @endphp
	                        	@foreach($order->order_details as $row)
	                        		@php 
                                		$availableShippingOptions = \Helper::get_shipping_cost($order->user->id,$row->seller_id,$row->product_id, $order->address->district->id ?? 0);
                                		$subtotal = $subtotal + ($row->price * $row->product_qty);
                                	@endphp
		                        	<tr id="row{{$count}}">
	                                    <td class="product_row" data-row="{{$count}}">
	                                    	<input type="hidden" name="order_details_id[]" value="{{$row->id}}">
	                                        <select class="form-control selectpicker  product_id_list product_id{{ $count }}" data-row="{{ $count }}"  name="product_select_id[]" disabled="">
	                                            <option value="{{$row->product_id}}" > {{$row->product->title}}</option>
	                                        </select>
	                                        <input type="hidden" name="product_id[]" value="{{$row->product_id}}">
	                                        <input type="hidden" class="product_vat_list product_vat{{ $count }}" name="product_vat[]" value="{{$row->product->vat}}" >

	                                        <input type="hidden"  class="product_aditional_cost_list product_aditional_cost{{ $count }}" name="product_aditional_cost[]" value="{{ \Helper::calculateAditionalCost($row->product_id,$row->product_options) }}" >

	                                        <input type="hidden" data-is-grocery="{{$row->product->is_grocery}}" name="grocery_subtotal" class="grocery_subtotal_list grocery_subtotal{{$count}}" value="@if($row->product->is_grocery == 'grocery') {{ $subtotal }} @else 0 @endif">

	                                        <div class="variable_option_area mt-1 variable_option{{$count}}">
	                                        	@if ($row->product->product_type == 'variable')
	                                        		@php
		                                        		$variableOptions = json_decode($row->product_options);

											            $product_meta = \App\Models\ProductMeta::where('product_id', $row->product->id)->where('meta_key','custom_options')->first();
											            $meta_value = unserialize($product_meta->meta_value);
										            @endphp
										            @foreach($meta_value as $key => $value)
										                @if($value['type'] == 'radio')
									                        <div class="">
									                            <h6 class=" mb-1">{{ $value['title'] }}: </h6>
									                            @php 
									                            	$key = '';
									                            	$key_val = '';
									                            @endphp
									                            @foreach($value['value'] as $val)
									                            	@foreach ($variableOptions as $opk => $option)
									                            		@if($value['title'] == $opk && $val['title'] == $option) 
										                            		@php 
										                            			$key = $opk;
									                            				$key_val = $option;
										                            		@endphp
										                            	@endif
									                            	@endforeach
								                                    <label>
								                                        <input type="radio" name="variable_option_{{ $row->product_id}}[]" value="{{$value['title']}}:{{$val['title'] }}" class="variable_option" @if($key == $value['title'] && $key_val == $val['title']) checked="" @endif>
								                                        <span>{{ $val['title'] }}</span>
								                                    </label>
									                            @endforeach
									                        </div>
										                @endif

										                @if($value['type'] == 'dropdown')
									                        <div class="">
									                            <h6 class="mb-1">{{ $value['title'] }}: </h6>
									                            <select class="form-control variable_option" name="variable_option_{{ $row->product_id}}[]" id="">
									                                <option value="0">Select</option>
									                                @php 
										                            	$key = '';
										                            	$key_val = '';
										                            @endphp
									                                @foreach($value['value'] as $val)
									                                	@foreach ($variableOptions as $opk => $option)
										                            		@if($value['title'] == $opk && $val['title'] == $option) 
											                            		@php 
											                            			$key = $opk;
										                            				$key_val = $option;
											                            		@endphp
											                            	@endif
										                            	@endforeach
									                                    <option value="{{$value['title']}}:{{ $val['title'] }}" @if($key == $value['title'] && $key_val == $val['title']) selected="" @endif>{{ $val['title'] }}</option>
									                                @endforeach
									                            </select>
									                        </div>
										                @endif
										            @endforeach
										        @endif
	                                        </div>
	                                    </td>
	                                    <td>
	                                        <input type="number" value="{{ $row->product_qty }}" name="qty[]" class="form-control qty_list qty{{$count}}" min="1" max="{{ $row->product->max_cart_qty ?? 100 }}" />
	                                    </td>
	                                    <td>
	                                        <input type="text" name="price[]" readonly="" class="form-control price_list price{{$count}}" value="{{ $row->price ?? number_format(0, 2,'.','') }}" />
	                                    </td>
	                                    <td class="shipping_cost_info " id="shipping_cost_{{$count}}" data-orderdetails-id="{{ $row->id }}" data-product-id="{{$row->product_id}}">
	                                    	@if($order->is_pickpoint == 1)
	                                    		<span class="badge badge-danger">Pickup Point</span>
	                                    		<input type="hidden" name="shipping_method[]" value="" class="shipping_method_list">
	                                    	@else
		                                    	@if ($row->product->product_type != 'digital' && $row->product->product_type != 'service' && $row->product->is_grocery != 'grocery') 
			                                        <input type="text" name="shipping_cost[]" required="" class="form-control shipping_cost_list shipping_cost{{$count}}" value="{{ $row->shipping_cost ?? 0 }}" />
			                                        <input type="hidden" name="shipping_method[]" 
			                                        	@if($row->shipping_method == 'free_shipping') 
			                                        		value="free_shipping" 
			                                        	@elseif($row->shipping_method == 'standard_shipping')
			                                        		value="standard_shipping" 
			                                        	@elseif($row->shipping_method == 'express_shipping')
			                                        		value="express_shipping" 
			                                        	@endif 
			                                        	class="shipping_method_list">
			                                        <div class="shipping_cost_area mt-1 shipping_cost_area{{$count}}">
			                                        	@if($availableShippingOptions['free_shipping'] == 'on')
			                                        		<label class="labl">
							                                    <input type="radio" name="shipping_option{{$row->product_id}}" @if($row->shipping_method == 'free_shipping') checked="checked"  @endif value="0" data-previous-value="0" class="shipping_option_list shipping_option{{$count}}" />
						                                        <small>BDT 0  </small>
						                                        <small>Free Shipping</small>
							                                </label><br>
			                                        	@endif
			                                        	<label class="labl">
		                                                    <input type="radio" name="shipping_option{{$row->product_id}}"  value="{{ $availableShippingOptions['standard_shipping'] }}" data-previous-value="{{ $availableShippingOptions['standard_shipping'] }}" data-shippingmethod="standard_shipping" @if($row->shipping_method == 'standard_shipping') checked="checked"  @endif  class="shipping_option_list shipping_option{{$count}}" />
		                                                    <small>BDT {{ $availableShippingOptions['standard_shipping']}}</small>
		                                                    <small>Standerd Shipping</small>
		                                                </label><br>
		                                                <label class="labl">
		                                                    <input type="radio" name="shipping_option{{$row->product_id}}" value="{{$availableShippingOptions['express_shipping']}}" data-previous-value="{{ $availableShippingOptions['express_shipping'] }}" data-shippingmethod="express_shipping" @if($row->shipping_method == 'express_shipping') checked="checked"  @endif class="shipping_option_list shipping_option{{$count}}"  />
		                                                    <small>BDT {{ $availableShippingOptions['express_shipping']}}</small>
		                                                    <small>Express Shipping</small>
		                                                </label>
			                                        </div>
			                                    @else
			                                    	<input type="hidden" name="shipping_cost[]" required="" class="form-control shipping_cost_list shipping_cost{{$count}}" value="0" />
			                                    @endif
			                                @endif
	                                    </td>
	                                    <td>
	                                        <input type="text" name="packaging_cost[]" readonly="" class="form-control packaging_cost_list packaging_cost{{$count}}" value="{{ $row->packaging_cost ?? number_format(0, 2,'.','') }}" />
	                                    </td>
	                                    <td>
	                                        <input type="text" name="security_charge[]" readonly="" class="form-control security_charge_list security_charge{{$count}}" value="{{ $row->security_charge ?? number_format(0, 2,'.','') }}" />
	                                    </td>
	                                    <td>

	                                        <input type="text" name="subtotal[]" readonly="" class="form-control subtotal_list subtotal{{$count}}" value="{{ number_format($row->price + $row->shipping_cost + $row->packaging_cost + $row->security_charge, 2,'.', '') }}" />
	                                    </td>
	                                    <td>
	                                        <a id="{{$count}}" class="btn btn-danger text-light btn_remove"><i class="mdi mdi-delete m-0"></i></a>
	                                    </td>
	                                </tr>
	                                @php $count++; @endphp
                                @endforeach
	                        </tbody>
	                    </table>  
					</div>
				</div>   

				<div class="row mt-4">
					<div class="col-lg-6">
						<label>Order Note:</label>
						<textarea class="form-control" name="order_note" id="order_note">{{ $order->note}}</textarea>
					</div>
					<div class="col-lg-6">
						<div class="form-group row">
							<label class="col-lg-5">Total Subtotal:</label>
							<input type="text" name="order_subtotal" id="order_subtotal" class="form-control col-lg-7" value="{{ $subtotal }}" readonly="">
						</div>
						<div class="form-group row">
							<label class="col-lg-5">Grocery Shipping Cost:</label>
							<input type="text" name="order_grocery_shipping_cost" id="order_grocery_shipping_cost" class="form-control col-lg-7" value="{{ $order->grocery_shipping_cost }}" readonly="">
						</div>
						<div class="form-group row">
							<label class="col-lg-5">Shipping Cost:</label>
							<input type="text" name="order_shipping_cost" id="order_shipping_cost" class="form-control col-lg-7" value="{{ $order->shipping_cost }}" >
						</div>
						<div class="form-group row">
							<label class="col-lg-5">Packaging Cost:</label>
							<input type="text" name="order_packaging_cost" id="order_packaging_cost" class="form-control col-lg-7" value="{{$order->total_packaging_cost}}" readonly="">
						</div>
						<div class="form-group row">
							<label class="col-lg-5">Security Charge:</label>
							<input type="text" name="order_security_charge" id="order_security_charge" class="form-control col-lg-7" value="{{$order->total_security_charge}}" readonly="">
						</div>
						<div class="form-group row">
							<label class="col-lg-5">Vat / Tax:</label>
							<input type="text" name="order_vat" id="order_vat" class="form-control col-lg-7" value="{{ $order->vat }}" readonly="">
						</div>
						<div class="form-group row">
							<label class="col-lg-5">Discount:</label>
							<input type="text" name="order_discount" id="order_discount" class="form-control col-lg-7" value="{{$order->discount_amount }}" >
						</div>
						<div class="form-group row">
							<label class="col-lg-5">Grand Total:</label>
							<input type="text" name="order_grand_total" id="order_grand_total" class="form-control col-lg-7" value="{{$order->total_amount}}" readonly="">
						</div>
						<div class="form-group text-right">
							<button class="btn btn-success" type="submit" id="update_order_btn">Update</button>
						</div>
					</div>
				</div>	
      		</form>
      	</div>
  	</div>
</div>

@push('footer')

	<script type="text/javascript">
		jQuery(document).ready(function () {
			var i = {{ $count }};
	        jQuery('#add').click(function(){   
	            
	            jQuery('#order_details').append(''+
	            '<tr id="row'+i+'">'+
		            '<td class="product_row" data-row="'+i+'">'+
		            	'<input type="hidden" name="order_details_id[]" value="0">'+
		            	'<input type="hidden" name="product_id[]" value="" class="product_select_id'+i+'">'+
		                '<select class="form-control selectpicker product_id_list product_id'+i+'" data-size="10"  name="product_select_id[]" data-row="'+i+'" data-show-subtext="true" data-live-search="true" >'+
		                    '<option value="-1"> -- Select --</option>'+
		                    	@foreach($products as $row)
		                    		'<option value="{{ $row->id }}" >{{ $row->title }}</option>'+
		                    	@endforeach
		                '</select>'+
		                '<input type="hidden" class="product_vat_list product_vat'+i+'" name="product_vat"  value="" >'+
		                '<input type="hidden" class="product_aditional_cost_list product_aditional_cost'+i+'"  name="product_aditional_cost" value="" >'+
		                '<input type="hidden" data-is-grocery="" name="grocery_subtotal" class="grocery_subtotal_list grocery_subtotal'+i+'" value="0">'+
		                '<div class="variable_option_area mt-1 variable_option'+i+'"></div>'+
		            '</td>'+
		            '<td>'+
		                '<input type="number" min="1" value="1" name="qty[]"  class="form-control qty_list qty'+i+'" />'+
		            '</td>'+
		            '<td>'+
		                '<input type="text" name="price[]" readonly="" class="form-control price_list price'+i+'" />'+
		            '</td>'+
		            '<td>'+
	                    '<input type="text" name="shipping_cost[]" class="form-control shipping_cost_list shipping_cost'+i+'" value="0" />'+
	                    '<input type="hidden" name="shipping_method[]" value="" class="shipping_method_list">'+
	                    '<div class="shipping_cost_area mt-1 shipping_cost_area'+i+'">'+
	                    	
	                    '</div>'+
	                '</td>'+
	                '<td>'+
	                    '<input type="text" name="packaging_cost[]" readonly="" class="form-control packaging_cost_list packaging_cost'+i+'" value="0" />'+
	                '</td>'+
	                '<td>'+
	                    '<input type="text" name="security_charge[]" readonly="" class="form-control security_charge_list security_charge'+i+'" value="0" />'+
	                '</td>'+
		            '<td>'+
		                '<input type="text" name="subtotal[]" readonly="" class="form-control subtotal_list subtotal'+i+'" />'+
		            '</td>'+
		            '<td>'+
		                '<a id="'+i+'" class="btn btn-danger text-light btn_remove"><i class="mdi mdi-delete m-0"></i></a>'+
		            '</td>'+
		        '</tr>'); 
		        jQuery('.selectpicker').selectpicker('refresh');
		        i++;
	        }); 

	        
	        jQuery(document).on('click', '.btn_remove', function(){  
	           var button_id = jQuery(this).attr("id");   
	           jQuery('#row'+button_id+'').remove();  
	           i--;
	           calculation();
	           calculateGrandTotal();
	        });  

	        function shipping_cost_for_pickpoint(address_type,address_id, subtotal){
	        	$.ajax({
	                url: "{{  url('/admin/orders/get/shipping/cost') }}/"+address_type+"/"+address_id+"/"+subtotal,
	                type: "GET",
	                dataType: "JSON",
	                success: function (response) {
	                	if (response.status == 1) {
	                   		jQuery('#order_shipping_cost').val(response.shipping_cost);
	                	}
	                   	calculateGrandTotal();
	                }
	            })
	        }

	        $(document).on("change", "#address_id", function(e) {
	        	e.stopImmediatePropagation();

	        	let address_type = jQuery(this).find('option:selected').attr('data-address-type');
	        	let address = $(this).val();
	        	let subtotal = $('#order_subtotal').val();

	        	if (address_type == 'pickpoint') {
	        		jQuery('#is_pickpoint').val(1)
	        	}else{
	        		jQuery('#is_pickpoint').val(0)
	        	}

	        	shipping_cost_for_pickpoint(address_type,address,subtotal);
	        	generateShippingHtml(address_type);
	        })

	        function generateShippingHtml(address_type){
	        	jQuery('.shipping_cost_info').each(function(key, val) {
	                let order_details_id = jQuery(this).attr('data-orderdetails-id');
	                let product_id = jQuery(this).attr('data-product-id');
	                let id = jQuery(this).attr('id');
	                if (address_type == 'pickpoint') {
	                	jQuery(this).append('<span class="badge badge-danger pickpoint_text">Pickpoint</span>');
	                	jQuery('.shipping_cost_list').attr('readonly','readonly');
	                	jQuery('.shipping_option_list').attr('checked',false);
	                	jQuery('.shipping_option_list').attr('disabled',true);
	                	jQuery('.shipping_option_list').val(0);
	                	jQuery('.shipping_cost_list').val(0);
	                	calculation();
	                	calculateGrandTotal();
	                }else{
	                	jQuery('.shipping_cost_list').attr('readonly',false);
	                	jQuery('.shipping_option_list').attr('checked',false);
	                	jQuery('.shipping_option_list').attr('disabled',false);
	                	jQuery('.shipping_option_list').val(0);
	                	jQuery('.shipping_cost_list').val(0);
	                	jQuery('#order_shipping_cost').val(0);
	                	jQuery('.pickpoint_text').remove();
	                	calculation();
	                	calculateGrandTotal();
	                }
	            })
	        }

	        function calculation(){
	        	var total_subtotal = 0;
	        	var total_shipping_cost = 0;
	        	var total_packaging_cost = 0;
	        	var total_security_charge = 0;
	        	var total_vat = 0;
	        	var grand_total = 0;
	        	let grocery_total = 0;
	        	var total_discount = jQuery('#order_discount').val();

	        	jQuery(".product_row").each(function() {
	        		var row = jQuery(this).attr('data-row');

	        		var qty = jQuery('.qty'+row).val();
	        		var price = jQuery('.price'+row).val();
	        		var shipping_cost = jQuery('.shipping_cost'+row).val();


	        		var packaging_cost = jQuery('.packaging_cost'+row).val();
	        		var security_charge = jQuery('.security_charge'+row).val();
	        		var aditional_cost = jQuery('.product_aditional_cost'+row).val();

	        		var vat = jQuery('.product_vat'+row).val();

	        		vat = Number(vat) * Number(price) / 100;

	        		var subtotal = (Number(qty) * Number(price));

	        		total_subtotal = Number(total_subtotal) + Number(subtotal);
		        	total_shipping_cost = Number(total_shipping_cost) + Number(shipping_cost);
		        	total_packaging_cost = Number(total_packaging_cost) + Number(packaging_cost);
		        	total_security_charge = Number(total_security_charge) + Number(security_charge);
		        	total_vat = Number(total_vat) + Number(vat);

		        	jQuery(".subtotal"+row).val(subtotal);

		        	grocery_total = Number(grocery_total) + Number(jQuery('.grocery_subtotal'+row).val());

	        	})


	        	grand_total = Number(grand_total) + Number(total_subtotal) + Number(total_shipping_cost) + Number(total_packaging_cost) + Number(total_security_charge) + Number(total_vat);

	        	// Grocery shipping cost calculation 
	        	let address_type = jQuery('#address_id').find('option:selected').attr('data-address-type')
	        	let order_grocery_shipping_cost = 0;
	        	if (address_type == 'pickpoint' && grocery_total > 0) {
	        		let settingAmountForGrocery = {{ \Helper::getsettings('shipping_validation_amount') ?? 500 }};
	                let minimumShippingAmount = {{ \Helper::getsettings('shipping_minimum_amount') ?? 30 }};
	                let maximumShippingAmount = {{ \Helper::getsettings('shipping_maximum_amount') ?? 50 }};

	                if (grocery_total >= settingAmountForGrocery) {
	                    order_grocery_shipping_cost = Number(minimumShippingAmount);
	                } else {
	                    order_grocery_shipping_cost = Number(maximumShippingAmount);
	                }
	        	}

	        	jQuery("#order_grocery_shipping_cost").val(order_grocery_shipping_cost.toFixed(2));

	        	jQuery("#order_subtotal").val(total_subtotal.toFixed(2));
	        	jQuery("#order_shipping_cost").val(total_shipping_cost);
	        	jQuery("#order_packaging_cost").val(total_packaging_cost);
	        	jQuery("#order_security_charge").val(total_security_charge);
	        	jQuery("#order_vat").val(total_vat);

	        }

	        function calculateGrandTotal(){
	        	let subtotal = jQuery("#order_subtotal").val();
	        	let order_grocery_shipping_cost = jQuery("#order_grocery_shipping_cost").val();
	        	let order_shipping_cost = jQuery("#order_shipping_cost").val();
	        	let order_packaging_cost = jQuery("#order_packaging_cost").val();
	        	let order_security_charge = jQuery("#order_security_charge").val();
	        	let order_vat = jQuery("#order_vat").val();
	        	let order_discount = jQuery("#order_discount").val();

	        	jQuery("#order_grand_total").val((Number(subtotal) + Number(order_shipping_cost) + Number(order_grocery_shipping_cost) + Number(order_packaging_cost) + Number(order_security_charge) + Number(order_vat) - Number(order_discount)).toFixed(2));
	        }

	        $(document).on("change", ".product_id_list", function(e) {
	        	e.stopImmediatePropagation();

	            var row = jQuery(this).attr('data-row');
	            var product_id = jQuery(this).val();
	            var user_id = jQuery('.customer_id').val();
	            var address_id = jQuery('.address_id').val();

	            // alert(product_id);
	            // return;
	            if(product_id != -1) {
	                jQuery.ajax({
	                    url: "{{  url('/admin/orders/product/details/') }}/"+product_id+"/"+user_id+"/"+address_id,
	                    type:"GET",
	                    dataType:"json",
	                    success:function(data) {
	                        jQuery('.price'+row).val(data.product_price);
	                        jQuery('.packaging_cost'+row).val(data.product.packaging_cost);
	                        jQuery('.security_charge'+row).val(data.product.security_charge);
	                        jQuery('.product_vat'+row).val(data.product.vat);
	                        jQuery('.shipping_cost_area'+row).html(data.shipping_option);
	                        jQuery('.variable_option'+row).html(data.variable_option);
	                        jQuery('.product_select_id'+row).val(data.product.id);

	                        if (data.product.is_grocery == 'grocery') {
	                        	jQuery('.grocery_subtotal'+row).val(Number(data.product_price) * Number(jQuery('.qty'+row).val()));
	                        }

	                        calculation();
	                        calculateGrandTotal();
	                    },
	                });
	            }else{
	                Swal.fire({
	                    icon: 'error',
	                    title: 'Select Products!',
	                    showConfirmButton: true,
	                    timer: 1500
	                })
	            }
	        });

	        

	        $(document).on("change", ".shipping_option_list", function(e) {
	        	e.stopImmediatePropagation();
	        	var shipping_cost = jQuery(this).attr('data-previous-value');
	        	var row_number =  $(this).closest('td').find('.shipping_cost_list').val(Number(shipping_cost));
	        	$(this).closest('td').find('.shipping_method_list').val(jQuery(this).attr('data-shippingmethod'));
	        	calculation();
	        	calculateGrandTotal();
	        })

	        $(document).on("change", ".variable_option", function(e) {
	        	e.stopImmediatePropagation();


	        	var aditional_cost_for_radio = jQuery(this).closest('td').find('.variable_option').attr('data-aditionalprice');
	        	if (aditional_cost_for_radio == undefined) {
	        		aditional_cost_for_radio = 0;
	        	}
	        	var aditional_cost_for_select = jQuery(this).closest('td').find('.variable_option :selected').attr('data-aditionalprice');
	        	if (aditional_cost_for_select == undefined) {
	        		aditional_cost_for_select = 0;
	        	}

	        	// alert(aditional_cost_for_select);

	        	

	        	var row_number =  $(this).closest('td').find('.product_aditional_cost_list ').val(Number(aditional_cost_for_radio) + Number(aditional_cost_for_select));

	        	calculation();
	        	calculateGrandTotal();
	        })

	        $(document).on("change", ".qty_list", function(e) {
	        	calculation();
	        	calculateGrandTotal();
	        })

	        $(document).on("keyup", ".qty_list", function(e) {
	        	calculation();
	        	calculateGrandTotal();
	        })

	        $(document).on("keyup", "#order_discount", function(e) {
	        	calculation();
	        	calculateGrandTotal();
	        })

	        $(document).on("keyup", ".shipping_cost_list", function(e) {
	        	calculation();
	        	calculateGrandTotal();
	        })

	    });
	</script>
@endpush

@endsection

