@extends('backend.layouts.master')
@section('title', 'Corporate Quatation - ' . config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <span class="card-title">Dashboard > Corporate > Corporate Quatation > View</span>
        </div>
    </div>
</div>

<div class="">
    <form method="POST" class="row" action="{{ route('admin.corporate.request.update') }}">
        @csrf
        <div class="col-12 col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body formated_para row">
                    <div class="col-12">
                        <h4 class="card-title text-uppercase mb-4">Quatation Information</h4>
                        <hr>
                    </div>
                    <div class="col-12 col-lg-3 col-md-3">
                        <p><strong>Request ID: </strong> KBC{{ date('y', strtotime($request->created_at)) }}{{ $request->id }}</p>
                        <p><strong>User: </strong> {{ $request->username->name ?? '' }}</p>
                        <p><strong>Request Date: </strong> {{ $request->created_at->format('d M, Y, g:i a') }}</p>
                        <p><strong>Total Quantity: </strong> {{ $request->qty }}</p>
                        <p><strong>Total Price: </strong> {{ \Helper::getDefaultCurrency()->currency_symbol .' ' .$request->amount }}</p>
                        <p><strong>Total Discount: </strong> {{ \Helper::getDefaultCurrency()->currency_symbol .' ' .$request->discount }}</p>
                        @if($request->work_order != '')
                            <p><strong>Work Order: </strong> <a href="/{{$request->work_order}}" target="_blank"><i class="mdi mdi-file-document"></i> Download Here</a></p>
                        @endif
                        @if($request->delivery_address != '')
                            <p><strong>Delivery Address: </strong> {{ $request->delivery_address }}</p>
                        @endif

                        <p><strong>Preferable Date For Delivery: </strong> 
                            <input type="datetime-local" name="preferable_date_for_delivery" class="form-control" value="{{ $request->preferable_date_for_delivery }}">
                        </p>
                        <p><strong>Delivery Date: </strong> 
                            <input type="datetime-local" name="delivery_date" class="form-control" value="{{ $request->delivery_date }}">
                        </p>

                        <p><strong>Shipping Cost: </strong> 
                            <input type="text" name="shipping_cost" class="form-control" value="{{ $request->shipping_cost }}">
                        </p>

                        <p><strong>Payment Details: </strong> 
                            <textarea class="form-control " name="payment_details" placeholder="Write something..">{{ $request->payment_details }}</textarea>
                        </p>

                        <p><strong>Deal Status: </strong> 
                            <select class="form-control" name="status">
                                 
                                    <option value="1">Pending</option>
                                 
                                    <option value="2">Accepted</option>
                                 
                                    <option value="3">On Hold</option>
                                 
                                    <option value="9">Out for Delivery</option>
                                 
                                    <option value="10" selected="">Delivered</option>
                                    <option value="6">Completed</option>
                            </select>
                            <input type="hidden" name="quotation_id" value="{{ $request->id }}">
                        </p>

                        <p><strong>Payment Status: </strong> 
                            <select class="form-control" name="payment_status">
                                <option @if ($request->payment_status == 1) selected @endif value="1">Pending </option>
                                <option @if ($request->payment_status == 2) selected @endif value="2">Accepted </option>
                                <option @if ($request->payment_status == 3) selected @endif value="3">On Hold </option>
                                <option @if ($request->payment_status == 4) selected @endif value="4">Failed </option>
                                <option @if ($request->payment_status == 5) selected @endif value="5">Canceled </option>
                                <option @if ($request->payment_status == 6) selected @endif value="6">Completed </option>
                                <option @if ($request->payment_status == 15) selected @endif value="15">Partial </option>
                                <option @if ($request->payment_status == 7) selected @endif value="7">Refund Requested
                                </option>
                                <option @if ($request->payment_status == 8) selected @endif value="8">Refunded </option>
                            </select>
                        </p>

                        <p>
                            <strong>Note: </strong> 
                            <textarea class="form-control" name="note" placeholder="Write something.."></textarea>
                        </p>
                    </div>

                    <div class="col-12 col-lg-9 col-md-9">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="product-name">Product</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-subtotal">Total</th>
                                    <th class="product-subtotal">Discount</th>
                                </tr>
                            </thead>
                    
                            <tbody style="background-color: #fff;background-color: #fff;border-top: 1px solid #f2f2f2;">
                                @foreach ($products as $item)

                                    <tr class="woocommerce-cart-form__cart-item cart_item">
                                        <td>
                                            {{ $loop->index + 1 }}
                                            <input type="hidden" name="request_id[]" value="{{ $item->id }}">
                                        </td>
                                        <td class="product-name" data-title="Product">
                                            <div class="d-flex align-items-center">
                                                <a href="#">
                                                    <img width="80" class="thumb-image"
                                                        src="{{ '/' . $item->product->default_image }}"
                                                        class="attachment-shop_thumbnail size-shop_thumbnail wp-post-image"
                                                        alt="">
                                                </a>
                                                <div class="ml-3 m-w-200-lg-down">
                                                    <a target="_blank"
                                                        href="{{ env('APP_FRONTEND') . '/product/bluewow-js27-gamepad-joystick' }}">{{ $item->product->title }}</a><br>
                                                    @if ($item->product->product_type == 'variable')
                                                        <small><b>SKU:</b> {{ $item->product_sku }}</small><br>
                                                        @php
                                                            $variable_option = json_decode($item->variable_option);
                                                        @endphp
                                                        @foreach ($variable_option as $key => $val)
                                                            <span class="badge badge-success text-white">{{ $key }}:
                                                                {{ $val }}</span>
                                                        @endforeach
                                                        <br>
                                                    @else
                                                        <small><b>SKU:</b> {{ $item->product->sku }}</small><br>
                                                    @endif

                                                    <small><b>Seller:</b> {{ $item->seller->name ?? '' }}</small><br>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="product-quantity" data-title="Quantity">
                                            <span>{{ $item->qty }}</span>
                                        </td>

                                        <td class="product-price" data-title="Price">
                                            <span
                                                class="woocommerce-Price-amount amount">{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $item->price }}</span>
                                        </td>

                                        <td class="product-subtotal" data-title="Total">
                                            <span
                                                class="woocommerce-Price-amount amount">{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $item->qty * $item->price }}</span>
                                        </td>
                                        <td class="product-subtotal" data-title="Discount">
                                            <input type="number" min="0" name="discount[]" value="{{ $item->discount }}" class="form-control">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12">
                        <div class="text-right p-3">
                            <button type="submit" class="btn btn-success">Review Quatation</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if($negotiations)
        <div class="row mt-3">
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-body formated_para ticket_replay">
                        <h4 class="card-title text-uppercase mb-4">Quatation Negotiations</h4>
                        <hr>
                        <div class="replay-body" style="overflow: hidden; background: #EFF0F5; padding: 5px;">
                            @foreach($negotiations as $replay)
                                @if(!$replay->admin_id)
                                    <div class="message new text-justify">
                                        
                                        @if($replay->username->avatar)
                                            <figure class="avatar">
                                                <img src="/{{$replay->username->avatar}}">
                                            </figure>
                                        @else
                                            <figure class="avatar">
                                                <img src="{{ asset('default-user.png') }}">
                                            </figure>
                                        @endif
                                        {!! $replay->note !!}<br>
                                        <div class="timestamp">{{ date('d M, Y h:ia', strtotime($replay->created_at)) }}</div>
                                        @if($replay->status == 0)
                                            <div class="checkmark-sent-delivered">✓</div>
                                        @else
                                            <div class="checkmark-sent-delivered">✓</div>
                                            <div class="checkmark-read">✓</div>
                                        @endif
                                        <div class="sender">
                                            {{$replay->username->name}}
                                        </div>
                                    </div>
                                @else
                                    <div class="message message-personal new text-justify">
                                        {!! $replay->note !!}<br>
                                        <div class="reciever-timestamp">{{ date('d M, Y h:ia', strtotime($replay->created_at)) }}</div>
                                        @if($replay->status == 0)
                                            <div class="reciever-checkmark-sent-delivered">✓</div>
                                        @else
                                            <div class="reciever-checkmark-sent-delivered">✓</div>
                                            <div class="reciever-checkmark-read">✓</div>
                                        @endif
                                        <div class="reciever">{{ $replay->adminname->name }}</div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('footer')

@endpush
@endsection
