@extends('backend.layouts.master')
@section('title','Blog Category - '.config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
   <div class="card">
      <div class="card-body">
         <span class="card-title">Dashboard > Blog Category</span>
         <a class="btn btn-success float-right" href="{{ route('admin.blog.category.create') }}">Create New Blog Category</a>
      </div>
   </div>
</div>
<div class="grid-margin stretch-card">
   <div class="card">
      <div class="designed_table">
         <table id="dataTable" class="table">
            <thead>
               <tr>
                  <th>Serial</th>
                  <th>Category Title</th>
                  <th>Icon</th>
                  <th>Meta Title</th>
                  <th>Meta KeyWord</th>
                  <th>Status</th>
                  <th data-priority="1" class="text-center">Action</th>
               </tr>
            </thead>
            <tbody></tbody>
         </table>
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
          url: "{{ route('admin.blog.category.list') }}",
          type: 'GET',
        },
        "order": [[0, 'desc']],
        columns: [
            {data: 'DT_RowIndex',"className" : "text-center",orderable: false, searchable: false,},
            {data: 'category', name: 'category'},
            {data: 'icon',name: 'icon'},
            {data: 'meta_title'},
            {data: 'meta_keyword'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false, "className" : "text-center"},
        ]
      });
    </script>
@endpush