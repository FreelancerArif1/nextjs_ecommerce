
@extends('backend.layouts.master')

@section('title', 'Dashboard - ' . config('concave.cnf_appname'))

@section('content')
    @php
        $revenue = $data['revenue'];
        $order = $data['order'];
        $product = $data['product'];
        $users = $data['users'];
        $orders = $data['orders'];
        $top_seller = $data['top_seller'];
        $top_sale_product = $data['top_sale_product'];
        $top_customer = $data['top_customer'];
        $completed_orders = $data['completed_orders'];
        $status = $data['status'];
        $all_orders = $data['all_orders'];
        $top_category_sale = $data['top_category_sale'];
        
    @endphp
<style>
    .dashboard_table .table th, .dashboard_table .table td{
        white-space: normal !important;
    }
</style>
    <div class="row">
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Total Revenue</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h3 class="font-weight-medium mb-4"><?php
                    if (!empty($revenue)) {
                        if ($revenue > 999) {
                            echo '৳' . $revenue / 1000 . 'K';
                        } else {
                            echo '৳' . $revenue;
                        }
                    } else {
                        echo '৳0';
                    } ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Total Orders</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h3 class="font-weight-medium"> <?php if (!empty($order)) {
                        if ($order > 999) {
                            echo $order / 1000 . 'K';
                        } else {
                            echo $order;
                        }
                    } else {
                        echo 0;
                    } ?> </h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Total Products</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h3 class="font-weight-medium"> <?php if (!empty($product)) {
                        if ($product > 999) {
                            echo $product / 1000 . 'K';
                        } else {
                            echo $product;
                        }
                    } else {
                        echo 0;
                    } ?> </h3>
                </div>
            </div>
        </div>

        @if ($users)
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body pb-0">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mb-0">Total Customers</h4>
                            <p class="font-weight-semibold mb-0"></p>
                        </div>
                        <h3 class="font-weight-medium"><?php if (!empty($users)) {
                            if ($users > 999) {
                                echo $users / 1000 . 'K';
                            } else {
                                echo $users;
                            }
                        } ?></h3>
                    </div>
                </div>
            </div>
        @endif


    </div>

    <div class="row">
        <div class="col-md-6 grid-margin">
            <div class="card">
                <div class="card-body">
                    <canvas id="sales_figure"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div>
                        <canvas id="statistics"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-6 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div>
                        <canvas id="category_wise_product_sale"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div>
                        <canvas id="order_summary"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h2 class="card-title mb-0" style="font-size: 20px; margin-bottom: 18px !important;">Latest Orders
                        </h2>
                        <a href="{{ route('admin.order') }}"><small>Show All</small></a>
                    </div>
                    <div class="dashboard_table">
                        <table class="table table-striped table-hover" style="padding:10px 5px !important">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Shipping Name</th>
                                    <th>Shipping Phone</th>
                                    <th>Payment Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                       
                                       
                                        <td>DR{{ date('y',strtotime($order->created_at)) }}{{ $order->id }}</td>
                                        @if($order->is_pickpoint == 1)
                                        <td >
                                            {{ optional(App\Models\Pickpoints::find($order->address_id))->title }}
                                        </td>
                                        <td>{{ optional(App\Models\Pickpoints::find($order->address_id))->phone }}</td>
                                        @else
                                            @if($address = optional(App\Models\Addresses::find($order->address_id)))
                                                <td>
                                                    {{ $address->shipping_first_name . ' ' . $address->shipping_last_name }}
                                                </td>
                                                <td >{{ $address->shipping_phone }}</td>
                                            @else
                                                <td>-</td>
                                                <td>-</td>
                                            @endif
                                        @endif



                                        <td>
                                            <span class="badge text-light {{ 'color_' . $order->status }}">{{ Helper::getStatusName('order', $order->status) }} </span>
                                        </td>
                                        <td >
                                            <a class="icon_btn text-success"
                                                href="{{ route('admin.order.show', $order->id) }}"><i class="mdi mdi-eye"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        @if ($top_seller)
            <div class="col-md-6 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h2 class="card-title mb-0" style="font-size: 20px; margin-bottom: 18px !important;">Top
                                Earnning Sellers</h2>
                            <a href="{{ route('admin.order') }}"><small>Show All</small></a>
                        </div>
                        <div class="dashboard_table">
                            <table class="table table-striped table-hover" width="100%">
                                <thead>
                                    <tr>
                                        <th width="33%">Seller Id</th>
                                        <th width="33%">Total Earning</th>
                                        <th width="33%">Seller Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($top_seller as $seller)
                                        <tr>
                                            <td>{{ $seller->id }}</td>
                                            <td>{{ $seller->total_earn }}</td>
                                            <td>{{ $seller->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        <div class="col-md-6 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h2 class="card-title mb-0" style="font-size: 20px; margin-bottom: 18px !important;">Top Selling
                            Products</h2>
                        <a href="{{ route('admin.order') }}"><small>Show All</small></a>
                    </div>
                    <div class=" dashboard_table">
                        <table class="table table-striped table-hover" width="100%">
                            <thead>
                                <tr>
                                    <th width="20%">Product Id</th>
                                    <th width="20%">Product Name</th>
                                    <th width="20%">Shop Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($top_sale_product as $products)
                                    <tr>
                                        <td>{{ $products->id }}</td>
                                        <td>{{ $products->title }}</td>
                                        <td>{{ $products->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if ($top_customer)
            <div class="col-md-6 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h2 class="card-title mb-0" style="font-size: 20px; margin-bottom: 18px !important;">Top
                                Customer</h2>
                            <a href="{{ route('admin.order') }}"><small>Show All</small></a>
                        </div>
                        <div class=" dashboard_table">
                            <table class="table table-striped table-hover" width="100%">
                                <thead>
                                    <tr>
                                        <th width="15%">Customer ID</th>
                                        <th width="15%">Purchase Number</th>
                                        <th width="15%">Total Spend</th>
                                        <th width="55%">Customer Name</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($top_customer as $customer)
                                        <tr>
                                            <td>{{ $customer->user_id }}</td>
                                            <td>{{ $customer->total_buy }}</td>
                                            <td>{{ $customer->total_buy_amount }}</td>
                                            <td>{{ $customer->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!--<div class="col-md-6 grid-margin">
                                                                   <div class="card">
                                                                    <div class="card-body">
                                                                     <div>
                                                                      <canvas id="complain"></canvas>
                                                                     </div>
                                                                    </div>
                                                                   </div>
                                                                  </div>-->

    </div>


    @push('footer')
        <script>
            //SETUP
            const labels = [
                'January',
                'February',
                'March',
                'April',
                'May',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December',
            ];
            const data = {
                labels: labels,
                datasets: [{
                    label: 'Revenue Analysis',
                    backgroundColor: 'rgb(255, 99, 132)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: [
                        @for ($i = 1; $i <= 12; $i++)
                            @php
                                $total_revenue = 0;
                                foreach ($completed_orders as $row) {
                                    $date = DateTime::createFromFormat('Y-m-d', $row->created_at);
                                    if ($i == date('m', $date)) {
                                        $total_revenue += $row->price * $row->product_qty + $row->shipping_cost;
                                    }
                                }
                                
                            @endphp
                            {{ $total_revenue }},
                        @endfor
                    ],
                }]
            };
            //Config
            const config = {
                type: 'line',
                data: data,
                options: {}
            };


            const salesFigureChart = new Chart(
                document.getElementById('sales_figure'),
                config
            );
        </script>

        <script>
            //SETUP
            const statisticsData = {
                labels: [
                    @foreach ($status as $row)
                        '{{ $row->title }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Sale Statistics',
                    data: [
                        @foreach ($status as $row)
                            @php
                                $status_order = 0;
                                foreach ($all_orders as $order) {
                                    if ($row->id == $order->status) {
                                        $status_order++;
                                    }
                                }
                            @endphp
                            {{ $status_order }},
                        @endforeach
                    ],
                    backgroundColor: [
                        @foreach ($status as $row)
                            '{{ $row->color_code }}',
                        @endforeach
                    ],
                    hoverOffset: 4
                }]
            };
            //Config
            const statisticsDataConfig = {
                type: 'doughnut',
                data: statisticsData,
                options: {
                    responsive: true,
                    aspectRatio: 2,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Order Status Statistics'
                        }
                    }
                },
            };

            const statisticsChart = new Chart(
                document.getElementById('statistics'),
                statisticsDataConfig
            );
        </script>


        <script>
            //SETUP
            const complainData = {
                labels: [
                    'Recieved',
                    'Resolved',
                    'Pending',
                ],
                datasets: [{
                    label: 'Complain Statistics',
                    data: [300, 50, 100],
                    backgroundColor: [
                        'rgb(49, 163, 223)',
                        'rgb(11, 157, 56)',
                        'rgb(255, 205, 86)',
                    ],
                    hoverOffset: 4
                }]
            };
            //Config
            const complainDataConfig = {
                type: 'bar',
                data: complainData,
                options: {
                    responsive: true,
                    indexAxis: 'y',
                    aspectRatio: 3,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: false,
                            text: 'Complain Statistics'
                        }
                    }
                },
            };

            const complainChart = new Chart(
                document.getElementById('complain'),
                complainDataConfig
            );
        </script>



        <script>
            //SETUP
            const category_wise_product_saleData = {
                labels: [
                    @foreach ($top_category_sale as $row)
                        '{{ $row->category }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Category wise Sale',
                    data: [
                        @foreach ($top_category_sale as $row)
                            {{ $row->total_sale }},
                        @endforeach
                    ],
                    backgroundColor: [
                        'rgb(49, 163, 223)',
                        'rgb(147, 22, 225)',
                        'rgb(255, 205, 86)',
                        'rgb(11, 157, 56)',
                        'rgb(25, 55, 86)',
                        'rgb(95, 105, 105)',
                        'rgb(95, 105, 200)',
                        'rgb(95, 105, 95)',
                        'rgb(95, 105, 100)',
                        'rgb(95, 105, 600)',
                    ],
                    hoverOffset: 4
                }]
            };
            //Config
            const category_wise_product_saleDataConfig = {
                type: 'pie',
                data: category_wise_product_saleData,
                options: {
                    responsive: true,
                    aspectRatio: 2,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Category wise Sale Statistics'
                        }
                    }
                },
            };

            const category_wise_product_saleChart = new Chart(
                document.getElementById('category_wise_product_sale'),
                category_wise_product_saleDataConfig
            );
        </script>






        <script>
            //SETUP
            const ordersummaryLabels = [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December',
            ];
            const ordersummaryData = {
                labels: ordersummaryLabels,
                datasets: [{
                    label: 'Number of Sales',
                    backgroundColor: 'rgb(34, 245, 200)',
                    data: [
                        @for ($i = 1; $i <= 12; $i++)
                            @php
                                $total_sale = 0;
                                foreach ($completed_orders as $row) {
                                    $date = DateTime::createFromFormat('Y-m-d', $row->created_at);
                                    if ($i == date('m', $date)) {
                                        $total_sale++;
                                    }
                                }
                            @endphp
                            {{ $total_sale }},
                        @endfor
                    ],
                }]
            };
            //Config
            const ordersummaryConfig = {
                type: 'bar',
                data: ordersummaryData,
                options: {}
            };


            const ordersummaryChart = new Chart(
                document.getElementById('order_summary'),
                ordersummaryConfig
            );
        </script>
    @endpush
@endsection
