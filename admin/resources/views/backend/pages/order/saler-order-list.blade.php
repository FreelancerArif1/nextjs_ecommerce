@extends('backend.layouts.master')
@section('content')
<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
        <span class="card-title">Dashboard > Sales > Orders</span>
		  </div>
	</div>
</div>
<div class="grid-margin">
   	<div class="row">
      	<div class="col-md-12">
         	<div class="card">
            	<div class="designed_table">
              
	               	<table id="dataTable" class="table" >
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
	                 	<tbody></tbody>
	                </table>
            	</div>
         	</div>
      	</div>
	</div>
</div>

  <!--Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Are you sure to delete this item? </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Once you delete this item. You can not restore this item again!</p>
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
	var table = jQuery('#dataTable').DataTable({
		responsive: true,
		processing: true,
		serverSide: true,
		autoWidth: true,
		ajax: {
			url: "{{ route('admin.order.sale.list') }}",
			type: 'GET',
		},
		"order": [[0, 'desc']],
		"language": {"processing": '<span style="color:#4eb9fa;"><i class=" mdi mdi-spin mdi-settings"></i> LOADING...</span>'},
		columns: [
         	{data: 'DT_RowIndex',"className" : "text-center",orderable: false, searchable: false,},
         	{data: 'order_date', name: 'order_date'},
         	{data: 'user_name', name: 'user_name'},
         	{data: 'payment_id', name: 'payment_id'},
         	{data: 'shipping_name', name: 'shipping_name'},
         	{data: 'shipping_phone', name: 'shipping_phone'},
         	{data: 'payment_method', name: 'payment_method'},
         	{data: 'product_qty', name: 'product_qty'},
         	{data: 'paid_amount', name: 'paid_amount'},
         	{data: 'status', name: 'status'},
         	{data: 'action', name: 'action', orderable: false, searchable: false, "className" : "text-center"},
		]
	});
</script>
@endpush