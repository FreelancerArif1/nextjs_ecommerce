@extends('backend.layouts.master')
@section('title','Review List - '.config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
   <div class="card">
      <div class="card-body">
         <span class="card-title">Dashboard > Product > Reviews</span>
      </div>
   </div>
</div>
<div class="grid-margin">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="designed_table table-responsive">
               <table id="dataTable" class="table" >
                  <thead>
                     <tr>
                        <th>Serial</th>
                        <th>Product</th>
                        <th>User</th>
                        <th>Comment</th>
                        <th>Seller</th>
                        <th>Status</th>
                        <th class="text-center" data-priority="1">Action</th>
                     </tr>
                  </thead>
                  <tbody class="action_btn_review"> </tbody>
               </table>
            </div>
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
<!--Replay Modal start-->
<div class="modal fade" id="replayModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Replay To: <b class="user_name"></b> </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            
            <div class="row">
               <div class="col-md-6">
                  <div class="comment_history" style="border-right:2px dashed rgb(156, 156, 156);"></div>
               </div>
               <div class="col-md-6">
                  <form action="{{ route('admin.review.replay') }}" method="post">
                     @csrf
                     <div class="form-group">
                        <label>Replay</label><br>
                        <input type="hidden" value="" name="comments_id" class="comments_id">
                        <textarea class="form-control" name="replay" col="3" placeholder="Write your message here.."></textarea>
                     </div>
                     <div class="form-group">
                        <label>Image</label><br>
                        <button  type="button" 
                           data-image-width="800" 
                           data-image-height="800"  
                           data-input-name="images" 
                           data-input-type="multiple"
                           data-file-location="uploads/comments"
                           class="btn btn-success initConcaveMedia" >Select File
                        </button>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" value="Replay" class="btn btn-danger">
                     </div>
                  </form>
               </div>
            </div>

         </div>
      </div>
   </div>
</div>
<!--Replay Modal end-->


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
   		url: "{{ route('admin.get.review.list') }}",
   		type: 'GET',
   	},
      aLengthMenu: [
				[25, 50, 100, 500, 5000, -1],
				[25, 50, 100, 500, 5000, "All"]
			],
		iDisplayLength: 25,
   	"order": [[0, 'desc']],
      "language": {"processing": '<span style="color:#4eb9fa;"><i class=" mdi mdi-spin mdi-settings"></i> LOADING...</span>'},
   	columns: [
           {data: 'DT_RowIndex',"className" : "text-center",orderable: false, searchable: false,},
           {data: 'product', name: 'product'},
           {data: 'user', name: 'user'},
           {data: 'comment', name: 'comment'},
           {data: 'seller', name: 'seller'},
           {data: 'status', name: 'status'},
           {data: 'action', name: 'action', orderable: false, searchable: false, "className" : "text-center"},
   	]
   });
   
   
   $(document).on('click', '.reply_btn', function(){
      $('.comments_id').val($(this).attr('data-comments_id'));
      $('.user_name').text($(this).attr('data-user'));
     
      $.ajax({
         url: "/admin/reviews/get-single-review-list/"+$(this).attr('data-comments_id'), success: function(result){
            $(".comment_history").html(result);
         }
      });

   });
</script>
@endpush