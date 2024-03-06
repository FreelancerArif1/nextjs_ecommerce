@extends('backend.layouts.master')
@section('content')
<div class="grid-margin stretch-card">
   <div class="card">
      	<div class="card-body">
         	<span class="card-title">Dashboard > Catalog > Products > Restock Request</span>
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
		                        <th>Serial</th>
		                        <th>Date</th>
		                        <th>Customer</th>
		                        <th>Saller</th>
		                        <th>Product</th>
		                        <th>Status</th>
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

@endsection

@push('footer')
    
	<script type="text/javascript">
		var table = jQuery('#dataTable').DataTable({
			dom: 'Brftlip',
        	buttons: [ 'csv', 'excel', 'pdf', 'print'],
			responsive: true,
			processing: true,
			serverSide: true,
			autoWidth: true,
			ajax: {
				url: "{{ route('admin.product.restock.request.list') }}",
				type: 'GET',
			},
			aLengthMenu: [
				[25, 50, 100, 500, 5000, -1],
				[25, 50, 100, 500, 5000, "All"]
			],
			iDisplayLength: 25,
			"order": [[0, 'desc']],
			columns: [
	         	{data: 'DT_RowIndex',"className" : "text-center",orderable: false, searchable: false,},
	         	{data: 'created_at', name: 'created_at'},
	         	{data: 'user_id', name: 'user_id'},
	         	{data: 'seller_id', name: 'seller_id'},
	         	{data: 'product_id', name: 'product_id'},
	         	{data: 'status', name: 'status'},
	         	{data: 'action', name: 'action', orderable: false, searchable: false, "className" : "text-center"},
			]
		});


		jQuery(document).on('click', '.change_status_btn', function(e) {
            e.preventDefault();
            Swal.fire({
			  	title: 'Do this product resored?',
			  	text: "User will get notification and sms about restored product!",
			  	icon: 'warning',
			  	showCancelButton: true,
			  	confirmButtonColor: '#3085d6',
			  	cancelButtonColor: '#d33',
			  	confirmButtonText: 'Yes, It has been restored!'
			}).then((result) => {
				if (result.isConfirmed) {
					var id = jQuery(this).attr('id');
		            $.ajax({
		                url: "{{ url('/admin/products/restock/request/change/status/') }}/" + id,
		                type: "GET",
		                dataType: "json",
		                success: function(data) {
		                	if (data == 'success') {
		                		Swal.fire({
		                            icon: 'success',
		                            title: 'Request status change successfully',
		                            showConfirmButton: false,
		                            timer: 1500
		                        })
							    setTimeout(function() {
		                            location.reload();
		                        }, 800);//
		                	}else{
		                		Swal.fire({
		                            icon: 'error',
		                            title: data,
		                            showConfirmButton: false,
		                            timer: 1500
		                        })
		                	}
					  	}
		                
		            })
		        }
			})
        });


		jQuery(document).on('click', '.send_notification', function(e) {
            e.preventDefault();
            Swal.fire({
			  	title: 'Are you sure?',
			  	text: "User will get notification about restored product!",
			  	icon: 'warning',
			  	showCancelButton: true,
			  	confirmButtonColor: '#3085d6',
			  	cancelButtonColor: '#d33',
			  	confirmButtonText: 'Yes!'
			}).then((result) => {
				if (result.isConfirmed) {
					var id = jQuery(this).attr('id');
		            $.ajax({
		                url: "{{ url('/admin/products/restock/request/send/pushnotification/') }}/" + id,
		                type: "GET",
		                dataType: "json",
		                success: function(data) {
		                	if (data == 'success') {
		                		Swal.fire({
		                            icon: 'success',
		                            title: 'Notification sended successfully!',
		                            showConfirmButton: false,
		                            timer: 1500
		                        })
		                	}else{
		                		Swal.fire({
		                            icon: 'error',
		                            title: data,
		                            showConfirmButton: false,
		                            timer: 1500
		                        })
		                	}
					  	}
		                
		            })
		        }
			})
        });

        jQuery(document).on('click', '.send_sms', function(e) {
            e.preventDefault();
            Swal.fire({
			  	title: 'Are you sure?',
			  	text: "User will get sms about restored product!",
			  	icon: 'warning',
			  	showCancelButton: true,
			  	confirmButtonColor: '#3085d6',
			  	cancelButtonColor: '#d33',
			  	confirmButtonText: 'Yes!'
			}).then((result) => {
				if (result.isConfirmed) {
					var id = jQuery(this).attr('id');
		            $.ajax({
		                url: "{{ url('/admin/products/restock/request/send/sms/') }}/" + id,
		                type: "GET",
		                dataType: "json",
		                success: function(data) {
		                	if (data == 'success') {
		                		Swal.fire({
		                            icon: 'success',
		                            title: 'SMS sended successfully!',
		                            showConfirmButton: false,
		                            timer: 1500
		                        })
		                	}else{
		                		Swal.fire({
		                            icon: 'error',
		                            title: data,
		                            showConfirmButton: false,
		                            timer: 1500
		                        })
		                	}
					  	}
		                
		            })
		        }
			})
        });
	</script>
@endpush