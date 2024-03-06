@extends('backend.layouts.master')
@section('title', 'Product List - ' . config('concave.cnf_appname'))
@section('content')
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Catalog > Products</span>
                @if (Auth::user()->can('product.create'))
                    <a class="btn btn-success float-right" href="{{ route('admin.product.create') }}">Create New Product</a>
                @endif
            </div>
        </div>
    </div>
    <div class="grid-margin stretch-card">
        <form action="{{ route('admin.product.action') }}" method="POST" style="width: 100%;">
            <div class="card">
                <div class="toolbar">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Bulk Action</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <select name="action" id="bulkOption" class="form-control">
                                            <option value="">--Select Option First--</option>
                                            <option value="active">Change Status to Active</option>
                                            <option value="inactive">Change Status to Inactive</option>
                                            <option value="promotional">Change Regular to Promotional</option>
                                            <option value="regular">Change Promotional to Regular</option>
                                            <option value="change_special_price">Change Special Price</option>
                                            <option value="exportItems">Export Selected Items</option>
                                            <option value="exportAll">Feed Generate</option>
                                            <option value="syncShippingCost">Sync Shipping Cost</option>
                                            <option value="delete" onClick="confirm('Are you sure to delete ?')">Delete
                                                Selected Items</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-4 d-none" id="special_price_area">
                                    <div class="">
                                        <p class="mb-0">Special Price (%)</p>
                                        <input type="text" name="special_price" id="special_price" class="form-control">
                                    </div>
                                </div>

                                <div class="col-sm-4 d-none" id="special_price_start_area">
                                    <div class="">
                                        <p class="mb-0">Special Price Start</p>
                                        <input type="datetime-local" name="special_price_start" value="2022-01-01T06:00:00"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="col-sm-4 mb-2 d-none" id="special_price_end_area">
                                    <div class="">
                                        <p class="mb-0">Special Price End</p>
                                        <input type="datetime-local" name="special_price_end" class="form-control "
                                            value="2022-01-01T06:00:00">
                                    </div>
                                </div>

                                <label class="col-sm-3"><button class="btn btn-dark" type="submit">Apply</button></label>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            {{-- <label class="">
                    <a href="{{ asset('files') }}/product_import_sample.csv" class="btn btn-info" title="Download Sample CSV">Sample CSV</a>
                    <button class="btn btn-success" data-toggle="modal" data-target="#productImportModal" title="Product Import CSV" type="button">Import Products</button>
                  </label> --}}
                        </div>
                    </div>
                </div>
                <div class="table-responsive designed_table" id="productTableLoader">
                    <table id="dataTable" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>
                                    <div class="form-check form-check-flat">
                                        <label class="form-check-label">
                                            <input id="select_all" type="checkbox" class="form-check-input"><i
                                                class="input-helper"></i>
                                        </label>
                                    </div>
                                </th>

                                <th>Product</th>
                                <th>Seller</th>
                                <th>SKU</th>
                                <th>Brand</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Weight</th>
                                <th>Qty</th>
                                <th>Delivery Location</th>
                                <th>Race</th>
                                <th>Viewed</th>
                                <th>Uploaded</th>
                                <th>Status</th>
                                <th>QC Approved</th>
                                <th data-priority="1">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
            </div>
        </form>
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
                    url: "{{ url('admin/products/get/product/list') }}",
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
                    [ 0, 'desc' ]
                ],
                columns: [{
                        data: 'id',
                        "className": "text-center",
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data: 'checkbox',
                        name: 'checkbox',
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
                        name: 'shop_info.name'
                    },
                    {
                        data: 'sku',
                        "className": "text-uppercase"
                    },
                    {
                        data: 'brand_title',
                        name: 'brands.title'
                    },
                    {
                        data: 'category_id',
                        'width': '10%',
                        "className": "white-space-break",
                        name: 'categories.title'
                    },
                    {
                        data: 'product_type'
                    },
                    {
                        data: 'price'
                    },
                    {
                        data: 'weight'
                    },
                    {
                        data: 'qty',
                        "className": "text-center"
                    },
                    {
                        data: 'delivery_location',
                    },
                    {
                        data: 'is_grocery',
                        "className": "text-center text-uppercase"
                    },
                    {
                        data: 'viewed',
                        "className": "text-center"
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'qc_approved',
                        name: 'qc_approved'
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
