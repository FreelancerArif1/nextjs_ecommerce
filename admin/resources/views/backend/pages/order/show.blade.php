@extends('backend.layouts.master')
@section('title', 'Order Information - ' . config('concave.cnf_appname'))
@section('content')
<style>
    .order_items table th,.order_items table .space{
        white-space: normal !important;
    }
</style>
<div class="grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <span class="card-title">Dashboard > Sales > Orders > Order Information</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body formated_para">
                <h4 class="card-title text-uppercase mb-4">Order Information</h4>
                <hr>
                <p><strong>Order ID: </strong> DR{{ date('y', strtotime($order->created_at)) }}{{ $order->id }}</p>
                @if ($order->parent_order_id)
                    <p><strong>Parent Order ID: </strong> <a
                            href="/admin/orders/view/{{ $order->parent_order_id }}">DR{{ date('y', strtotime($order->created_at)) }}{{ $order->parent_order_id }}</a>
                    </p>
                @endif
                <p><strong>Order Date: </strong> {{ $order->created_at->format('d M, Y, g:i a') }}</p>
                <p><strong>Payment Method: </strong> {{ $order->payment_method }}</p>
                <p>
                    <strong>Payment Status: </strong>
                    @if (Auth::user()->getRoleNames() != '["seller"]')
                        <select name="payment_status" id="payment_status">
                            <option @if ($order->status == 1) selected @endif value="#9931cc"
                                data-transactionid="{{ $order->payment_id }}" data-statusid="1"> Pending </option>
                            <option @if ($order->status == 2) selected @endif value="#1f01fe"
                                data-transactionid="{{ $order->payment_id }}" data-statusid="2"> Accepted </option>
                            <option @if ($order->status == 3) selected @endif value="#ec8b23"
                                data-transactionid="{{ $order->payment_id }}" data-statusid="3"> On Hold </option>
                            <option @if ($order->status == 4) selected @endif value="#91310c"
                                data-transactionid="{{ $order->payment_id }}" data-statusid="4"> Failed </option>
                            <option @if ($order->status == 5) selected @endif value="#e62e2d"
                                data-transactionid="{{ $order->payment_id }}" data-statusid="5"> Canceled </option>
                            <option @if ($order->status == 6) selected @endif value="#3b8104"
                                data-transactionid="{{ $order->payment_id }}" data-statusid="6"> Completed </option>
                            <option @if ($order->status == 15) selected @endif value="#0f0"
                                data-transactionid="{{ $order->payment_id }}" data-statusid="15"> Partial </option>
                            <option @if ($order->status == 7) selected @endif value="#ff9c45"
                                data-transactionid="{{ $order->payment_id }}" data-statusid="7"> Refund Requested
                            </option>
                            <option @if ($order->status == 8) selected @endif value="#0c7d89"
                                data-transactionid="{{ $order->payment_id }}" data-statusid="8"> Refunded </option>
                        </select>

                        <textarea name="reason" id="reason" class="form-control mt-2" placeholder="Please describe the reason.."
                            rows="5"></textarea>
                        <p class="text-right"> <button type="button" id="payment_status_submit"
                                class="btn btn-primary ">Change Status</button></p>

                        <p class="status_message" style="color:green; padding-bottom:10px; font-size: 15px;"></p>
                        @else
                        <span style="color: {{ $order->statuses->color_code }};">{{ $order->statuses->title }}</span>
                    @endif
                </p>
                @php
                    $subtotal = 0;
                    foreach ($order->order_details as $row) {
                        if (\Auth::user()->getRoleNames()[0] == 'seller') {
                            if (\Auth::id() != $row->seller_id) {
                                continue;
                            }
                        }
                        $subtotal = $subtotal + $row->product_qty * $row->price;
                    }
                    
                @endphp
                <p><strong>Subtotal:
                    </strong>{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $subtotal }}</strong>
                    {{-- </strong>{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . (($order->paid_amount + $order->grocery_shipping_cost ?? 0)  - $order->shipping_cost) ?? '' }} --}}
                </p>

                @if ($order->vat && $order->vat > 0)
                    <p><strong>VAT (+):
                        </strong>{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $order->vat ?? 0 }}
                    </p>
                @endif

                @if ($order->grocery_shipping_cost && $order->grocery_shipping_cost > 0)
                    <p><strong>Grocery Shipping Charge:
                        </strong>{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $order->grocery_shipping_cost ?? '' }}
                    </p>
                @endif

                @if ($order->shipping_cost && $order->shipping_cost > 0)
                    <p><strong>Total Shipping Charge (+):
                        </strong>{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $order->shipping_cost ?? '' }}
                    </p>
                @endif

                @if ($order->coupon_amount && $order->coupon_amount > 0)
                    <p><strong>Coupon Discount (-):
                        </strong>{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $order->coupon_amount ?? 0 }}
                    </p>
                @endif

                @if ($order->voucher_amount && $order->voucher_amount > 0)
                    <p><strong>Voucher Discount (-):
                        </strong>{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $order->voucher_amount ?? 0 }}
                    </p>
                @endif


                {{-- {{ dd($order->paid_amount) }} --}}
                <p><strong>Total Amount:
                    </strong>{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $order->total_amount ?? 0}}
                </p>

                <p><strong>Paid Amount:
                    </strong>{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $order->paid_amount ?? 0}}
                </p>

                @if($order->refunded > 0)
                    <p><strong>Refunded Amount:
                        </strong>{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $order->refunded ?? 0}}
                    </p>
                @endif

                @if($order->paid_amount < $order->total_amount) 
                    <p class="text-danger"><strong>Due Amount:
                        </strong>{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . ($order->total_amount - $order->paid_amount)}}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card min-h-330">
            <div class="card-body formated_para">
                <div class="row">
                    <div class="col-12">
                        <h4 class="card-title text-uppercase mb-2">Shipping Information</h4>
                        <hr>

                        @if($order->is_pickpoint == 1)

                            <p><strong>Title: </strong> {{ $order->pickpoint_address->title ?? '' }}</p>
                            <p><strong>Address: </strong> {{ $order->pickpoint_address->address ?? '' }},
                                {{ $order->pickpoint_address->union->title ?? '' }}, {{ $order->pickpoint_address->upazila->title ?? '' }},
                                {{ $order->pickpoint_address->district->title ?? '' }}, {{ $order->pickpoint_address->division->title ?? '' }}
                            </p>
                            <p><strong>Postcode: </strong> {{ $order->pickpoint_address->postcode }}</p>
                            <p><strong>Phone Number: </strong> {{ $order->pickpoint_address->phone }}</p>
                            <p><strong>Email: </strong> {{ $order->pickpoint_address->email }}</p>

                        @else
                            <p><strong>Full Name: </strong> {{ $order->address->shipping_first_name }}
                                {{ $order->address->shipping_last_name ?? '' }}</p>
                            <p><strong>Address: </strong> {{ $order->address->shipping_address }},
                                {{ $order->address->union->title ?? '' }}, {{ $order->address->upazila->title ?? '' }},
                                {{ $order->address->district->title ?? '' }}, {{ $order->address->division->title ?? '' }}
                            </p>
                            <p><strong>Postcode: </strong> {{ $order->address->shipping_postcode }}</p>
                            <p><strong>Phone Number: </strong> {{ $order->address->shipping_phone }}</p>
                            <p><strong>Email: </strong> {{ $order->address->shipping_email }}</p>
                        @endif




                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (Auth::user()->getRoleNames() != '["seller"]')
        <div class="col-md-4">
            <div class="card min-h-330">
                <div class="card-body formated_para">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="card-title text-uppercase mb-2">Additional Information</h4>
                            <hr>
                            @if (!$order->parent_order_id)

                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Auto Renewal Status: </strong>
                                    </div>
                                    <div class="col-md-6">
                                        <select
                                            style="padding: 4.5px 10px;background: #000;color: #fff;border: none;border-radius: 3px; width: 100%; margin-bottom: 8px;"
                                            name="status" id="auto_r_status">
                                            <option disabled selected>--Select Renewal Status-- </option>
                                            <option @if (isset($order->auto_renewal->status) && $order->auto_renewal->status == 1) selected @endif value="1">Active
                                            </option>
                                            <option @if (isset($order->auto_renewal->status) && $order->auto_renewal->status == 0) selected @endif value="0">Inactive
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                @if ($order->auto_renewal)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Next Order Date:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            {{ date('d M, Y', strtotime($order->auto_renewal->next_order_date)) }}
                                        </div>
                                    </div>
                                @endif


                              
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Auto Renewal Cycle: </strong>
                                        </div>
                                        <div class="col-md-6">
                                            <select
                                                style="padding: 4.5px 10px;background: #7ab000;color: #fff;border: none;border-radius: 3px;  width: 100%;"
                                                name="renewal_cycle" id="renewal_cycle">
                                                <option disabled selected>--Select Renewal Cycle-- </option>
                                                <option @if (isset($order->auto_renewal->renewal_cycle) && $order->auto_renewal->renewal_cycle == 1) selected @endif value="1">Daily
                                                </option>
                                                <option @if (isset($order->auto_renewal->renewal_cycle) && $order->auto_renewal->renewal_cycle == 7) selected @endif value="7">Weekly
                                                </option>
                                                <option @if (isset($order->auto_renewal->renewal_cycle) && $order->auto_renewal->renewal_cycle == 15) selected @endif value="15">Bi-Weekly
                                                </option>
                                                <option @if (isset($order->auto_renewal->renewal_cycle) && $order->auto_renewal->renewal_cycle == 30) selected @endif value="30">Monthly
                                                </option>
                                            </select>
                                            <span class="cycle_trigger_span"></span>
                                        </div>
                                    </div>

                                    <hr class="p-0 m-0 mt-1">

                                    <div class="row mt-1">
                                        <div class="col-md-6"></div>
                                        <div class="col-md-6">
                                            <p> <button type="button" class="btn btn-danger cycle_trigger w-100">Update Auto Renewal</button></p>
                                        </div>
                                    </div>

                                

                            @endif

                            <p><strong>Transaction ID: </strong> {{ $order->payment_id ?? '' }}</p>
                            <p><strong>Order From: </strong> {{ $order->order_from }}</p>
                            <p><strong>Order Note: </strong> {{ $order->note ?? '' }}</p>
                            @if ($order->payment_method == 'online_payment' &&
                                ($order->status == 1 || $order->status == 4 || $order->status == 5))
                                <p><strong>Digital Payment Link:</strong></p>
                                <p>
                                    <code class="digital_payment_link">
                                        {{ env('APP_API_URL') . '/api/v1/digital-payment-link/' . base64_encode($order->id) }}
                                    </code>
                                    <button
                                        onclick='copyToClipboard("{{ env('APP_API_URL') . '/api/v1/digital-payment-link/' . base64_encode($order->id) }}")'
                                        class="copy_link btn btn-xs btn-primary ml-2 p-1">COPY</button>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <div @if (Auth::user()->getRoleNames() != '["seller"]') class=" col-md-9 mt-3" @else class="col-12 col-md-12 mt-3" @endif>
        <div class="card min-h-330 ">
            <h4 class="card-body text-uppercase mb-3">Order Item(s)</h4>
           
        <div class="order_items ">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="product-name">Product</th>
                        <th class="product-quantity">Quantity</th>
                        <th class="product-price">Price</th>
                        <th class="product-subtotal">Total</th>
                        <th class="product-subtotal">Action</th>
                    </tr>
                </thead>


                @php
                    $groupWiseData = [];
                    foreach ($order->order_details as $item) {
                        if (Auth::user()->getRoleNames() == '["seller"]') {
                            if ($item->seller_id == Auth::user()->id) {
                                $groupWiseData[$item->seller_id][] = $item;
                            }
                        } else {
                            $groupWiseData[$item->seller_id][] = $item;
                        }
                    }
                @endphp

                @foreach ($groupWiseData as $items)

                    <tbody
                        style="background-color: #fff;background-color: #fff;border-bottom: 20px solid #f2f2f2;border-top: 20px solid #f2f2f2;">
                        <tr>
                            <td colspan="5">Shipped by:
                                <b>{{ App\Models\ShopInfo::where('seller_id', $items[0]->seller_id)->first()->name }}</b>
                            </td>
                            <td class="text-right">
                                <a href="#" onclick="printInvoice()" class="btn btn-info ml-auto"><i
                                        class="mdi mdi-printer"></i> Print Invoice</a>
                            </td>
                        </tr>
                        @foreach ($items as $item)

                            <tr class="woocommerce-cart-form__cart-item cart_item">
                                <td>{{ $loop->index + 1 }}</td>
                                <td class="product-name space" data-title="Product">
                                    <div class="d-flex align-items-center">
                                        <a href="#">
                                            <img width="80" class="thumb-image"
                                                src="{{ '/' . $item->product->default_image }}"
                                                class="attachment-shop_thumbnail size-shop_thumbnail wp-post-image"
                                                alt="">
                                        </a>
                                        <div class="ml-3 m-w-200-lg-down">
                                            <a target="_blank"
                                                href="{{ env('APP_FRONTEND') . '/product/'. $item->product->slug }}">{{ $item->product->title }}</a><br>
                                            @if ($item->product->product_type == 'variable' || $item->product->product_type == 'service')
                                                <small><b>SKU:</b> {{ $item->product_sku }}</small><br>
                                                @php
                                                    $variable_option = json_decode($item->product_options);
                                                    // var_dump($variable_option);
                                                    // exit();
                                                @endphp
                                                @if($variable_option)
                                                @foreach ($variable_option as $key => $val)
                                                    <span class="badge badge-success text-white">{{ $key }}:
                                                        {{ $val }}</span>
                                                @endforeach
                                                @endif
                                                <br>
                                                @else
                                                <small><b>SKU:</b> {{ $item->product_sku }}</small><br>
                                            @endif

                                            @if ($item->product->product_type != 'service')
                                                <small><b>Shipping Method:</b> {{ $item->shipping_method }}</small><br>
                                                @if( $item->shipping_method != 'pick_point')
                                                    <small> <b>Expected Shipping Date:</b> {{ Helper::expectedShipping($item->id) }} </small>
                                                @endif
                                            @endif

                                            @if($refundedAmount = optional($item->product_return_by_order_details_id)->refund_amount )
                                            <br> <small class="text-danger"><b>Refunded Amount:</b> BDT {{$refundedAmount}}</small><br>
                                            @endif
                                           
                                            
                                        </div>
                                    </div>
                                </td>

                                <td class="product-quantity" data-title="Quantity">
                                    <span>{{ $item->product_qty }}</span>
                                </td>

                                <td class="product-price" data-title="Price">
                                    <span
                                        class="woocommerce-Price-amount amount">{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $item->price}}</span>
                                </td>

                                <td class="product-subtotal" data-title="Total">
                                    <span
                                        class="woocommerce-Price-amount amount">{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $item->product_qty * $item->price }}</span>
                                </td>
                                <td class="product-subtotal" data-title="Total">


                                    @if (Auth::user()->getRoleNames() == '["seller"]')
                                        @if ($item->status != 6)
                                            @if ($order->status == 2 || $order->status == 6)
                                                <form method="post" class="form-inline mt-3"
                                                    action="{{ route('admin.order.update') }}">
                                                    @csrf
                                                    <select name="status" class="form-control status" required=""
                                                        id="{{ $item->id }}">
                                                        <option>--Select--</option>
                                                        <option @if ($item->status == 1) selected @endif
                                                            value="1">Pending</option>
                                                        <option @if ($item->status == 2) selected @endif
                                                            value="2"> Accepted </option>
                                                        <option @if ($item->status == 3) selected @endif
                                                            value="3"> On Hold </option>
                                                        <option @if ($item->status == 5) selected @endif
                                                            value="5"> Canceled </option>
                                                        <option @if ($item->status == 9) selected @endif
                                                            value="9"> Out for Delivery </option>
                                                        <option @if ($item->status == 10) selected @endif
                                                            value="10"> Delivered </option>
                                                        <option @if ($item->status == 11) selected @endif
                                                            value="11"> Return Requested </option>
                                                        <option @if ($item->status == 12) selected @endif
                                                            value="12"> Return Accepted</option>
                                                        <option @if ($item->status == 16) selected @endif
                                                            value="16"> Damage</option>
                                                        <option @if ($item->status == 17) selected @endif
                                                            value="17"> Loss</option>
                                                        <option @if ($item->status == 8) selected @endif
                                                            value="8" > Refunded</option>
                                                    </select>
                                                    <select
                                                        class="form-control shipping_company{{ $item->id }} ml-1 d-none"
                                                        name="shipping_company_id">
                                                        <option value="-1">-- Select --</option>
                                                        @foreach (App\Models\CourierCompany::all() as $row)
                                                            <option value="{{ $row->id }}"
                                                                @if ($item->shipping_company_id == $row->id) selected="" @endif>
                                                                {{ $row->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" name="tracking_id"
                                                        class="form-control tracking_number{{ $item->id }} ml-1 d-none"
                                                        placeholder="Tracking Number"
                                                        value="{{ $item->tracking_id }}">
                                                    <input type="hidden" name="id"
                                                        value="{{ $item->id }}">
                                                    <button type="submit"
                                                        class="btn btn-success form-control ml-2">Submit</button>
                                                </form>
                                                @else
                                                <span class="text-danger text-uppercase w-speace-ini fs-12">Waiting for
                                                    payment Confirmation</span>
                                            @endif
                                            @else
                                            <span class="bg-success text-white text-uppercase p-1 pl-2 pr-2">Delivery
                                                Confirmed</span>
                                        @endif
                                        @else
                                        @if ($order->status == 2 || $order->status == 6)
                                            <form method="post" class="form-inline mt-3"
                                                action="{{ route('admin.order.update') }}">
                                                @csrf
                                                <select name="status" class="form-control status mb-2"
                                                    required="" id="{{ $item->id }}">
                                                    <option>--Select--</option>
                                                    <option @if ($item->status == 1) selected @endif
                                                        value="1">Pending</option>
                                                    <option @if ($item->status == 2) selected @endif
                                                        value="2"> Accepted </option>
                                                    <option @if ($item->status == 3) selected @endif
                                                        value="3"> On Hold </option>
                                                    <option @if ($item->status == 5) selected @endif
                                                        value="5"> Canceled </option>
                                                    <option @if ($item->status == 9) selected @endif
                                                        value="9"> Out for Delivery </option>
                                                    <option @if ($item->status == 10) selected @endif
                                                        value="10"> Delivered </option>
                                                    <option @if ($item->status == 11) selected @endif
                                                        value="11"> Return Requested </option>
                                                    <option @if ($item->status == 12) selected @endif
                                                        value="12"> Return Accepted</option>
                                                    <option @if ($item->status == 6) selected @endif
                                                        value="6"> Completed </option>
                                                        
                                                    <option @if ($item->status == 16) selected @endif
                                                            value="16"> Damage</option>
                                                    <option @if ($item->status == 17) selected @endif
                                                            value="17"> Loss</option>
                                                    <option @if ($item->status == 8) selected @endif
                                                            value="8"  > Refunded</option>
                                                </select>
                                                <select
                                                    class="form-control ml-1 mb-2 shipping_company{{ $item->id }} d-none"
                                                    name="shipping_company_id">
                                                    <option value="-1">-- Select --</option>
                                                    @foreach (App\Models\CourierCompany::all() as $row)
                                                        <option value="{{ $row->id }}"
                                                            @if ($item->shipping_company_id == $row->id) selected="" @endif>
                                                            {{ $row->title }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="text" name="tracking_id"
                                                    class="form-control tracking_number{{ $item->id }} ml-1 d-none"
                                                    placeholder="Tracking Number" value="{{ $item->tracking_id }}">
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <button type="submit"
                                                    class="btn btn-success form-control ml-2">Submit</button>
                                            </form>
                                            @else
                                            <span class="text-danger text-uppercase w-speace-ini fs-12">Waiting for
                                                payment Confirmation</span>
                                        @endif
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @endforeach
            </table>
                    
        </div>


        </div>
    </div>

    @if (Auth::user()->getRoleNames() != '["seller"]')
        <div class="col-md-3 mt-3">
            <div class="timeline card">
                <div class="row">
                    <div class="col-12">
                        <div class="timeline">
                            <div class="wrapper">
                                <h1 class="text-uppercase">Order Log</h1>
                                @if (count($order_log) > 0)
                                    <ul class="sessions">
                                        @foreach ($order_log as $oLog)
                                            <li>
                                                <div class="time">
                                                    {{ date('d M, Y h:ia', strtotime($oLog->created_at)) }}</div>
                                                <p>{{ $oLog->generated_text }} <br>
                                                    @if ($oLog->reason)
                                                        <small class="text-danger">Reason: {{ $oLog->reason }}</small>
                                                    @endif
                                                </p>
                                            </li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <p class="text-danger"> No order log generated yet!</p>
                                @endif

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        @if($order->is_partial_payment == 1)
            <div class="col-md-12 mt-3">
                <div class="card min-h-330">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h4 class="card-title text-uppercase mb-2">Partial Payment History</h4>
                                <hr>

                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <td>SL.</td>
                                            <td>Transaction ID</td>
                                            <td>Amount</td>
                                            <td>Status</td>
                                            <td>Date</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(\App\Models\PartialOrderTransaction::where('order_id',$order->id)->get() as $paymentHistory )
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{$paymentHistory->transaction_id}}</td>
                                                <td>BDT {{$paymentHistory->amount}}</td>
                                                <td>{{ optional($paymentHistory->statuses)->title}}</td>
                                                <td>{{ date('d M, Y h:ia',strtotime($paymentHistory->updated_at)) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    @endif






</div>


@include('backend.pages.order.invoice')

@push('footer')
<script type="text/javascript">
    jQuery(document).ready(function() {

        function statusChange() {
            $(".status").each(function(index) {
                var id = jQuery(this).attr('id');
                var status = jQuery(this).val();
                if (status == 9) {
                    jQuery('.shipping_company' + id).removeClass('d-none');
                    jQuery('.tracking_number' + id).removeClass('d-none');

                    jQuery('.shipping_company' + id).addClass('d-block');
                    jQuery('.tracking_number' + id).addClass('d-block');
                } else {
                    jQuery('.shipping_company' + id).removeClass('d-block');
                    jQuery('.tracking_number' + id).removeClass('d-block');

                    jQuery('.shipping_company' + id).addClass('d-none');
                    jQuery('.tracking_number' + id).addClass('d-none');
                }
            })
        }

        statusChange();

        jQuery(document).on("change", ".status", function() {
            var id = jQuery(this).attr('id');
            var status = jQuery(this).val();
            if (status == 9) {
                jQuery('.shipping_company' + id).removeClass('d-none');
                jQuery('.tracking_number' + id).removeClass('d-none');

                jQuery('.shipping_company' + id).addClass('d-block');
                jQuery('.tracking_number' + id).addClass('d-block');
            } else {
                jQuery('.shipping_company' + id).removeClass('d-block');
                jQuery('.tracking_number' + id).removeClass('d-block');

                jQuery('.shipping_company' + id).addClass('d-none');
                jQuery('.tracking_number' + id).addClass('d-none');
            }
        });
    })

    function copyToClipboard(element) {
        $('.copy_link').html('Copied!');
        $('.copy_link').removeClass('btn-primary');
        $('.copy_link').addClass('btn-danger');
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(element).select();
        document.execCommand("copy");
        $temp.remove();
    }
</script>
<script>
    jQuery('.cycle_trigger').on('click', function() {
        jQuery.ajax({
            type: "POST",
            url: "{{ route('admin.orderautorenewal.create') }}",
            data: {
                order_id: {{ $order->id }},
                renewal_cycle: jQuery('#renewal_cycle').val(),
                status: jQuery('#auto_r_status').val()
            },
            cache: false,
            success: function(response) {
                jQuery('.cycle_trigger_span').html('<p class="text-primary">' + response.message +
                    '</p>');
            }
        });

    });
</script>
@endpush
@endsection
