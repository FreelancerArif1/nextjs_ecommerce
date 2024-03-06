<table id="" class="table">
    <thead>
        <tr>
            <th>Order Id</th>
            <th>Date</th>
            <th>User</th>
            <th>Payment Id</th>
            <th>Billing Name</th>
            <th>Billing Phone</th>
            <th>Payment Method</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Payment Status</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
            @if (Auth::user()->getRoleNames() == '["seller"]')
                @php
                    $is_seller = false;
                    $product_qty = 0;
                @endphp
                @foreach (App\Models\OrderDetails::where('order_id', $order->id)->get() as $row)
                    @if ($row->seller_id == Auth::user()->id)
                        @php
                            $is_seller = true;
                            $product_qty += $row->product_qty;
                        @endphp
                    @endif
                @endforeach
                @if ($is_seller == true)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->created_at->toDateString() }}</td>
                        <td>{{ $order->user->firstname . ' ' . $order->user->lastname }}</td>
                        <td>{{ $order->payment_id ?? '-' }}</td>
                        <td>{{ $order->address->shipping_first_name . ' ' . $order->address->shipping_last_name }}</td>
                        <td>{{ $order->address->shipping_phone }}</td>
                        <td>{{ $order->payment_method ?? '-' }}</td>

                        <td>{{ $product_qty }}</td>

                        <td>{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $order->paid_amount }}</td>


                        @php
                            $statuses = DB::table('statuses')->get();
                            $payment = DB::table('payments')
                                ->where('transaction_id', $order->payment_id)
                                ->first();
                            $payment_status = $payment->status ?? 1;
                        @endphp
                        @foreach ($statuses as $status)
                            @if ($payment_status == $status->id)
                                <td><label class="badge text-light"
                                        style="background-color: {{ $status->color_code }};">{{ $status->title }}</label>
                                </td>
                            @endif
                        @endforeach


                        <td class="text-center">
                            @if (Auth::user()->can('order.view'))
                                <a class="text-success" href="{{ route('admin.order.show', $order->id) }}"><i
                                        class="mdi mdi-eye"></i></a>
                            @endif
                            @if (Auth::user()->can('order.delete'))
                                <a class="text-danger delete_btn"
                                    data-url="{{ route('admin.order.delete', $order->id) }}" data-toggle="modal"
                                    data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>
                            @endif
                        </td>
                    </tr>
                @endif
            @else
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->created_at->toDateString() }}</td>
                    <td>{{ $order->user->firstname . ' ' . $order->user->lastname }}</td>
                    <td>{{ $order->payment_id ?? '-' }}</td>
                    <td>{{ $order->address->shipping_first_name . ' ' . $order->address->shipping_last_name }}</td>
                    <td>{{ $order->address->shipping_phone }}</td>
                    <td>{{ $order->payment_method ?? '-' }}</td>
                    <td>{{ $order->order_details->sum('product_qty') }}</td>
                    <td>{{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $order->paid_amount }}</td>
                    @php
                        $statuses = DB::table('statuses')->get();
                    @endphp
                    @foreach ($statuses as $status)
                        @if ($order->status == $status->id)
                            <td><label class="badge text-light"
                                    style="background-color: {{ $status->color_code }};">{{ $status->title }}</label>
                            </td>
                        @endif
                    @endforeach

                    <td class="text-center">
                        @if (Auth::user()->can('order.view'))
                            <a class="text-success" href="{{ route('admin.order.show', $order->id) }}"><i
                                    class="mdi mdi-eye"></i></a>
                        @endif
                        @if (Auth::user()->can('order.delete'))
                            <a class="text-danger delete_btn" data-url="{{ route('admin.order.delete', $order->id) }}"
                                data-toggle="modal" data-target="#deleteModal" href="#"><i
                                    class="mdi mdi-delete"></i></a>
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>

<div class="pagination_center" style="display: table;">{!! $orders->links() !!}</div>
