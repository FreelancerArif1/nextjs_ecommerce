@extends('backend.layouts.master')
@section('title', 'Low Stock Item - ' . config('concave.cnf_appname'))
@section('content')
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Report > Low Stock Item</span>
                {{-- @if (Auth::user()->can('product.create'))
                    <a class="btn btn-success float-right" href="{{ route('admin.product.create') }}">Create New Product</a>
                @endif --}}
            </div>
        </div>
    </div>
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="table-responsive designed_table" id="productTableLoader">
                <table id="dataTable" class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Seller</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Status</th>
                            <th data-priority="1">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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



    @push('footer')
        <script type="text/javascript">
            var table = jQuery('#dataTable').DataTable({
                dom: 'Brftlip',
                buttons: ['csv', 'excel', 'pdf', 'print'],
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ url('admin/reports/get-low-stock-item') }}",
                    type: 'GET',
                },
                aLengthMenu: [
                    [25, 50, 100, 500, 5000, -1],
                    [25, 50, 100, 500, 5000, "All"]
                ],
                iDisplayLength: 25,
                "language": {
                    "processing": '<span style="color:#4eb9fa;"><i class=" mdi mdi-spin mdi-settings"></i> LOADING...</span>'
                },
                "order": [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'id',
                        "className": "text-center",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'title',
                        'width': '20%',
                        name: 'products.title'
                    },
                    {
                        data: 'seller_id',
                        name: 'seller_id'
                    },
                    {
                        data: 'product_type'
                    },
                    {
                        data: 'price'
                    },
                    {
                        data: 'qty',
                        "className": "text-center"
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
                ]
            });

            jQuery(document).on('change', '#bulkOption', function(e) {
                e.preventDefault();
                var val = $(this).val();
                // alert(val);
                if (val == 'change_special_price') {
                    $('#special_price_area').removeClass('d-none');
                    $('#special_price_area').addClass('d-block');

                    $('#special_price_start_area').removeClass('d-none');
                    $('#special_price_start_area').addClass('d-block');

                    $('#special_price_end_area').removeClass('d-none');
                    $('#special_price_end_area').addClass('d-block');
                } else {
                    $('#special_price_area').removeClass('d-block');
                    $('#special_price_area').addClass('d-none');

                    $('#special_price_start_area').removeClass('d-block');
                    $('#special_price_start_area').addClass('d-none');

                    $('#special_price_end_area').removeClass('d-block');
                    $('#special_price_end_area').addClass('d-none');
                }
            });



            jQuery(document).on('click', '.productViewBtn', function(e) {
                e.preventDefault();
                var id = jQuery(this).attr('id');
                $.ajax({
                    url: "{{ url('/admin/products/view/') }}/" + id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        var em = jQuery('#vievModelBody').empty();
                        jQuery('#vievModelBody').html(data);
                        jQuery('#productViewModal').modal('show');
                    }
                })
            });
        </script>
    @endpush

@endsection
