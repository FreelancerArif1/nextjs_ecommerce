@extends('backend.layouts.master')
@section('content')
<div class="grid-margin stretch-card">
   <div class="card">
      	<div class="card-body">
         	<span class="card-title">Dashboard > Catalog > Products > Return Request</span>
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
								<th>REQ. ID</th>
		                        <th>Product</th>
		                        <th>Date</th>
		                        <th>Customer</th>
		                        <th>Saller</th>
		                        <th>Order Id</th>
		                        <th>Return Type</th>
		                        <th>Price (BDT)</th>
		                        <th>Refunded Amount (BDT)</th>
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
<!--Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   	<div class="modal-dialog" role="document">
      	<div class="modal-content">
         	<div class="modal-header">
            	<h5 class="modal-title" id="exampleModalLabel">Are you sure to approve this request? </h5>
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
            		<span aria-hidden="true">&times;</span>
            	</button>
         	</div>
         	<div class="modal-body">
            	<p>Once you approve this request. You can not restore this item again!</p>
         	</div>
         	<div class="modal-footer">
            	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            	<a type="button" href="#" class="btn btn-success delete_trigger">Approve</a>
         	</div>
      	</div>
   	</div>
</div>

	<!--Details Modal -->
	<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	   	<div class="modal-dialog" role="document">
	      	<div class="modal-content">
	         	<div class="modal-header">
	            	<h5 class="modal-title" id="exampleModalLabel">Return Details </h5>
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            		<span aria-hidden="true">&times;</span>
	            	</button>
	         	</div>
	         	<div class="modal-body">
	         		<b class="return-title"></b></br>
	            	<p class="return-description"></p>
	         	</div>
	         	<div class="modal-footer">
	            	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
				url: "{{ route('admin.product.return.request.list') }}",
				type: 'GET',
			},
			aLengthMenu: [
				[25, 50, 100, 500, 5000, -1],
				[25, 50, 100, 500, 5000, "All"]
			],
			iDisplayLength: 25,
			"order": [[1, 'desc']],
			columns: [
	         	{data: 'DT_RowIndex',"className" : "text-center",orderable: false, searchable: false,},
				{data: 'id'},
	         	{data: 'product', name: 'product'},
	         	{data: 'date', name: 'date'},
	         	{data: 'customer', name: 'customer'},
	         	{data: 'saller', name: 'saller'},
	         	{data: 'order_id', name: 'order_id'},
	         	{data: 'return_type', name: 'return_type'},
	         	{data: 'price', name: 'price', "className" : "text-center product_price"},
	         	{data: 'refund_amount', name: 'refund_amount', "className" : "text-center"},
	         	{data: 'status', name: 'status'},
	         	{data: 'action', name: 'action', orderable: false, searchable: false, "className" : "text-center"},
			]
		});

		jQuery(document).on('click','.detailsBtn',function(e){

			var title = $(this).data("return-title");
			var description = $(this).data("return-details");

			// alert(title);

			$('.return-title').html(title);
			$('.return-description').html(description);

			$('#detailsModal').modal('show');
		});

		jQuery(document).on('click','.refund_btn',function(e){
			e.preventDefault();

			let that = $(this);

			let url = jQuery(this).attr('data-url');
			let id = jQuery(this).attr('data-id');

			let price = Number(that.closest('tr').find('.product_price').text());
			console.log(price);

	        Swal.fire({
	            title: 'Are you sure?',
	            icon: 'warning',
	            html:'Please provide the Refund Amount.<br><br> <textarea id="refund_amount" name="refund_amount" class="form-control" placeholder="Please enter the refund amount"></textarea>',
	            showCancelButton: true,
	            confirmButtonColor: '#3085d6',
	            cancelButtonColor: '#d33',
	            confirmButtonText: 'Yes, do it!'
	            
	        })
	        .then((result) => {
	            if (result.isConfirmed) {
	                let refund_amount = Number(jQuery("#refund_amount").val());

	                if (price >= refund_amount) {
	                	jQuery.ajax({
		                    method: 'GET',
		                    url: url,
		                   	data: {refund_amount: refund_amount},
		                    success: function(data) {
		                        Swal.fire({
		                            icon: 'success',
		                            title: 'Refund completed!',
		                            showConfirmButton: false,
		                            timer: 2000,
		                        });

		                        $(that).remove();
		                        setTimeout(function() {
		                            location.reload();
		                        }, 800);//

		                    },
		                    error: function(xhr) {
		                        var errorMessage = '<div class="card bg-danger">\n' +
		                            '                        <div class="card-body text-center p-5">\n' +
		                            '                            <span class="text-white">';
		                        jQuery.each(xhr.responseJSON.errors, function(key, value) {
		                            errorMessage += ('' + value + '<br>');
		                        });
		                        errorMessage += '</span>\n' +
		                            '                        </div>\n' +
		                            '                    </div>';
		                        Swal.fire({
		                            icon: 'error',
		                            title: 'Oops...',
		                            footer: errorMessage
		                        })
		                    },
		                    
		                })
	                } else{
		                Swal.fire({
                            icon: 'error',
                            title: 'Refund amount can not be greater than price!',
                            showConfirmButton: false,
                            timer: 2000,
                        });
	            	}
	            }
	        })
		});
		
	</script>
@endpush