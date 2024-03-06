@extends('backend.layouts.master')
@section('title', 'Product Sales Report - ' . config('concave.cnf_appname'))
@section('content')
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Reports > Product Sales Report</span>
                @if (Auth::user()->getRoleNames() != '["seller"]')
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" method="POST">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <select name="saller" id="seller_id" class="selectpicker form-control"
                                                data-show-subtext="true" data-live-search="true" required="">
                                                <option value="0">--Select Shop First--</option>
                                                @foreach (App\Models\ShopInfo::all() as $row)
                                                    <option value="{{ $row->seller_id }}">{{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <label class="col-sm-1"><button class="btn btn-dark" type="submit" id="filter_data">Filter</button></label>
                                </div>
                            </form>
                        </div>
                    </div>
                
                @endif
            </div>
        </div>
    </div>
    <div class="grid-margin ">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="designed_table">
                        <table id="dataTable" class="table">
                            <thead>
                                <tr>
                                    <th class="text-center">Serial</th>
                                    <th class="text-center">Product</th>
                                    <th class="text-center">Category</th>
                                    <th class="text-center">Brand</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-center">No of Sale</th>
                                    <th class="text-center" data-priority = "1">Sale Amount</th>
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

    @push('footer')
        <script type="text/javascript">
            function fetchData(id) {
                var datatable = jQuery('#dataTable').DataTable({
                    dom: 'Brftlip',
                    buttons: ['csv', 'excel', 'pdf', 'print'],
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: true,
                    ajax: {
                        url: "{{ url('/admin/reports/get-product-sale/') }}/" + id,
                        type: 'GET',
                    },
                    aLengthMenu: [
                        [25, 50, 100, 500],
                        [25, 50, 100, 500]
                    ],
                    iDisplayLength: 25,
                    "order": [
                        [6, 'desc']
                    ],
                    "language": {
                        "processing": '<span style="color:#4eb9fa;"><i class=" mdi mdi-spin mdi-settings"></i> LOADING...</span>'
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            "className": "text-center",
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: 'title',
                            name: 'title',
                            "className": "text-left"
                        },
                        {
                            data: 'category',
                            'width': '10%',
                            "className": "white-space-break",
                            name: 'category',
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: 'brand_id',
                            "className": "text-center",
                            name: 'brand_id'
                        },
                        {
                            data: 'qty',
                            "className": "text-center",
                            name: 'qty'
                        },
                        {
                            data: 'no_of_sale',
                            name: 'no_of_sale',
                            orderable: false,
                            searchable: false,
                            "className": "text-center"
                        },
                        {
                            data: 'sale_amount',
                            "className": "text-center",
                            name: 'sale_amount',
                        },
                    ]
                });
            }

            jQuery('#dataTable').DataTable().destroy();
            fetchData(0);

            jQuery(document).on('click', '#filter_data', function(e) {
                e.preventDefault();
                // alert('aa');
                let val = jQuery('#seller_id').val();

                jQuery('#dataTable').DataTable().destroy();
                fetchData(val);
            });
        </script>
    @endpush

@endsection
