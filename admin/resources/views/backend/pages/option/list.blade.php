@extends('backend.layouts.master')
@section('content')
<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
      <span class="card-title">Dashboard > Catalog > Options</span>
      <a class="btn btn-success float-right" href="{{ route('admin.option.create')}}">Create New option</a>
		  </div>
	</div>
</div>
<div class="grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <table id="listTable" class="table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Option Title</th>
              <th>Type</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>


        @foreach ($options as $key=>$option)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$option->title}}</td>
				<td>{{$option->type}}</td>
                <td>
                  <label class="badge {{ 'badge_'.strtolower(Helper::getStatusName('default',$option->status))}}">
                  {{ Helper::getStatusName('default',$option->status)}}
                  </label>
                </td>
            <td>  
				@if(Auth::user()->can('customoption.edit'))
                <a class="btn btn-success" href="{{ route('admin.option.edit',$option->id)}}">Edit</a> | 
                @endif
				
				@if(Auth::user()->can('customoption.delete'))
				<a class="btn btn-danger delete_btn" data-url="{{ route('admin.option.delete',$option->id)}}" data-toggle="modal" data-target="#deleteModal" href="#">Delete</a>
				@endif
			</td>
                
            </tr>
        @endforeach
       
          </tbody>
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