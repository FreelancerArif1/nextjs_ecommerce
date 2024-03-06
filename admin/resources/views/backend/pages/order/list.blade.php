@extends('backend.layouts.master')
@section('title', 'Order List - ' . config('concave.cnf_appname'))
@section('content')

    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Sales > Orders</span>
                <div class="row">
                    <div class="col-md-12">
                        <form action="" method="POST">
                            <div class="form-group row">
                                <div class="col-sm-2 " id="start_date_area">
                                    <label>From Date</label>
                                    <div class="input-group">
                                        <input type="date" name="start_date" id="start_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-2 " id="end_date_area">
                                    <label>To Date</label>
                                    <div class="input-group">
                                        <input type="date" name="end_date" id="end_date" class="form-control">
                                    </div>
                                </div>

                                <label class="col-sm-1 mt-4"><button class="btn btn-dark" type="button"
                                        id="filterBtn">Filter</button></label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-margin">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="designed_table table-responsive">

                        <table id="dataTable" class="table">
                            <thead>
                                <tr>
                                    <th>Order Id</th>
                                    <th>Parent Order</th>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th>Payment Id</th>
                                    <th>Shipping Name</th>
                                    <th>Shipping Address</th>
                                    <th>Payment Method</th>
                                    <th>Quantity</th>
                                    <th>Total Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Payment Status</th>
                                    <th class="text-center" data-priority="1">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure to delete this item? </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Once you delete this item, you can restore this from trash list!</p>
                    <textarea name="reason" id="reason" placeholder="Write reason, why you want to delete this item."
                        class="form-control"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a type="button" href="#" class="btn btn-danger delete_trigger">Delete</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('footer')
    <script type="text/javascript">
        function orderList(start_date, end_date) {
            var table = jQuery('#dataTable').DataTable({
                dom: 'Brftlip',
                buttons: ['csv', 'excel', 'pdf', 'print'],
                searchable: true,
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ url('admin/orders/get-order-list') }}/" + start_date + "/" + end_date,
                    type: 'GET',
                },
                aLengthMenu: [
                    [25, 50, 100, 500, 5000, -1],
                    [25, 50, 100, 500, 5000, "All"]
                ],
                iDisplayLength: 25,
                "order": [
                    [0, 'desc']
                ],
                "language": {
                    "processing": '<span style="color:#4eb9fa;"><i class=" mdi mdi-spin mdi-settings"></i> LOADING...</span>'
                },
                columns: [
                    {
                        data: 'id',
                    },
                    {
                        data: 'parent_order_id'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'user_id',
                        name: 'user_id'
                    },
                    {
                        data: 'payment_id'
                    },
                    {
                        data: 'shipping_name',
                        name: 'shipping_name'
                    },
                    {
                        data: 'shipping_phone',
                        name: 'shipping_phone'
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method'
                    },
                    {
                        data: 'product_qty',
                        name: 'product_qty'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        "className": "text-center"
                    },
                ],
                

            });
        }

        orderList(0, 0);

        $(document).on('click', '#filterBtn', function(e) {
            e.preventDefault();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if (start_date == '') {
                alert('Start Date Required!');
            } else if (end_date == '') {
                alert('End Date Required!');
            } else {
                $('#dataTable').DataTable().destroy();
                orderList(start_date, end_date);
            }
        });
    </script>
@endpush
