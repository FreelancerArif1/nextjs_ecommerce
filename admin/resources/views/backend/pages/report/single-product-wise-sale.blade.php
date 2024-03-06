@extends('backend.layouts.master')

@section('title', 'Single Product Wise Sale - ' . config('concave.cnf_appname'))

@section('content')

    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body d-flex">
                <div class="w-50">
                    <span class="card-title ">Dashboard > Reports > Single Product Wise Sale</span><br>
                </div>
                <p class="w-50 text-right"><button class="btn btn-success">Print statements</button></p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('admin.get.single.product.wise') }}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <select name="product_id" id="filter_option" class="form-control selectpicker"
                                            data-show-subtext="true" data-live-search="true">
                                            <option value="">--Select Option First--</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <label class="col-sm-1"><button class="btn btn-dark" type="submit">Filter</button></label>
                            </div>
                        </form>

                        @if (session()->has('report'))
                            <p> {!! session()->get('report') !!}</p>
                        @endif
                        <div>
                            <h5 class="mb-2 text-dark">{{ $filter->title ?? null }}</h5>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="grid-margin">
        <div class="row">

            <div class="col-md-2">
                <div class="card bg-c-leaf">
                    <div class="card-body pb-0">
                        <div class="text-center">
                            {{-- {{ dd($productsss->title ?? null) }} --}}
                            <h5 class="mb-2 text-light">{{ $filter->qty ?? 0 }}</h5>
                            <h4 class="card-title mb-2">Total Stock</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card bg-c-violate">
                    <div class="card-body pb-0">
                        <div class="text-center">
                            <h5 class="mb-2 text-light">
                                {{ $total_sold ?? 0 }}
                            </h5>
                            <h4 class="card-title mb-2">Total Sold</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card bg-c-yellow">
                    <div class="card-body pb-0">
                        <div class="text-center">
                            <h5 class="mb-2 text-light">
                                {{Helper::getDefaultCurrency()->currency_symbol . ' ' . $total_amount ?? 0 }}
                            </h5>
                            <h4 class="card-title mb-2">Total Amount</h4>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-2">
                <div class="card bg-c-blue">
                    <div class="card-body pb-0">
                        <div class="text-center">
                            <h5 class="mb-2 text-light">
                                {{ $total_refund ?? 0 }}
                            </h5>
                            <h4 class="card-title mb-2">Total Refund</h4>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-md-2">
                <div class="card bg-c-green">
                    <div class="card-body pb-0">
                        <div class="text-center">
                            <h5 class="mb-2 text-light">
                                {{ $filter->viewed ?? 0 }}
                            </h5>
                            <h4 class="card-title mb-2">Total View</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card bg-c-pink">
                    <div class="card-body pb-0">
                        <div class="text-center">
                            <h5 class="mb-2 text-light">{{ $product_return ?? 0 }}</h5>
                            <h4 class="card-title mb-2">Product Return</h4>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    {{-- <div class="grid-margin">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h2 class="card-title" style="font-size: 20px;">Product Wish List</h2>
                        </div>
                        <div class="designed_table table-responsive">
                            <table id="dataTable" class="table">
                                <thead>
                                    <tr>
                                        <th>Order Id</th>
                                        <th>Date</th>
                                        <th>User</th>
                                        <th>Shipping Name</th>
                                        <th>Shipping Phone</th>
                                        <th>Payment Method</th>
                                        <th>Price</th>
                                        <th>Payment Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="grid-margin">
        <div class="row">


            <div class="col-12 col-md-12 col-lg-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h2 class="card-title" style="font-size: 20px;">Product On Wishlist</h2>
                        </div>
                        <div class="designed_table table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Type</th>
                                        <th>Price</th>
                                        <th>Username</th>
                                        <th>Phone</th>
                                        <th data-priority = "1">Action</th>
                                    </tr>
                                </thead>
                                @foreach ($wishlists as $row)
                                    <tr>
                                        <td>
                                            <div class="media">
                                                <img class="list_img mr-3"
                                                    src="/{{ $row->product->default_image ?? 'no_image.png' }}"
                                                    alt="">
                                                <div class="media-body">
                                                    <p class="product_title">
                                                        <a target="_blank"
                                                            href="{{ env('APP_FRONTEND') }}'/product/'{{ $row->product->slug }}"
                                                            class="text-dark text-decoration-none">{{ $row->product->title }}</a>
                                                    </p>
                                                    <a target="_blank"
                                                        href="/admin/seller/edit/{{ $row->product->seller_id }}"
                                                        class="seller_name text-decoration-none">{{ $row->product->seller->shopinfo->name ?? '-' }}</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $row->product->product_type ?? '-' }}</td>
                                        <td>{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->product->price ?? 0 }}</td>
                                        <td>{{ $row->user->name ?? null }}</td>
                                        <td>{{ $row->user->phone ?? null }}</td>
                                        <td>
                                            <a href="{{ route('admin.send.message', $row->user->id ?? 0) }}"><i
                                                    class="mdi mdi-message-processing" style="font-size: 24px;"></i>
                                            </a>
                                            <a href="{{ route('admin.send.notification', $row->user->id ?? 0) }}"><i
                                                    class="mdi mdi-bell" style="font-size: 24px;"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h2 class="card-title" style="font-size: 20px;">Product On Cart</h2>
                        </div>
                        <div class="designed_table table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Type</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Username</th>
                                        <th>Phone</th>
                                        <th data-priority = "1">Action</th>
                                    </tr>
                                </thead>
                                @foreach ($carts as $row)
                                    <tr>
                                        <td>
                                            <div class="media">
                                                <img class="list_img mr-3"
                                                    src="/{{ $row->product->default_image ?? 'no_image.png' }}"
                                                    alt="">
                                                <div class="media-body">
                                                    <p class="product_title">
                                                        <a target="_blank"
                                                            href="{{ env('APP_FRONTEND') }}'/product/'{{ $row->product->slug }}"
                                                            class="text-dark text-decoration-none">{{ $row->product->title }}</a>
                                                    </p>
                                                    <a target="_blank"
                                                        href="/admin/seller/edit/{{ $row->product->seller_id }}"
                                                        class="seller_name text-decoration-none">{{ $row->product->seller->shopinfo->name ?? '-' }}</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $row->product_type ?? '-' }}</td>
                                        <td>{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->price ?? 0 }}</td>
                                        <td>{{ $row->qty ?? 0 }}</td>
                                        <td>{{ $row->user->name ?? null }}</td>
                                        <td>{{ $row->user->phone ?? null }}</td>
                                        <td>
                                            <a href="{{ route('admin.send.message', $row->user->id ?? 0) }}"><i
                                                    class="mdi mdi-message-processing" style="font-size: 24px;"></i>
                                            </a>
                                            <a href="{{ route('admin.send.notification', $row->user->id ?? 0) }}"><i
                                                    class="mdi mdi-bell" style="font-size: 24px;"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    @push('footer')
    @endpush

@endsection
