@extends('backend.layouts.master')
@section('title','navbar List - '.config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
	<div class="card">
		<div class="card-body">
      <span class="card-title">Dashboard > Settings > navbars</span>
      <a class="btn btn-success float-right" href="{{ route('admin.navbar.create')}}">Create New navbar</a>
		</div>
	</div>
</div>
<div class="grid-margin stretch-card" style="width: 100%;">
  <div class="card">
    <form action="{{ route('admin.navbar.reorder') }}" method="POST">
      @csrf
      <div class="">
        <table id="" class="table">
          <thead>
            <tr>
              <th></th>
              <th>Title</th>
              <th>Link</th>
              <th>Link Type</th>
              <th>Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody class="dynamic_attributes sortable">
            @foreach ($navbars as $key=>$navbar)
              <tr class="attribute_list">  
                <td><i class="mdi mdi-format-list-bulleted"></i></td> 

                <td>
                  <div class="media-body">
                    <input type="hidden" name="navbar_ids[]" value="{{$navbar->id}}">
                    <p class="product_title">{{$navbar->title}}</p>
                  </div>
                </td>

                <td>{{$navbar->link}}</td>
                <td>{{$navbar->link_type}}</td>

                <td>
                  <label class="badge {{ 'badge_'.strtolower(Helper::getStatusName('default',$navbar->status))}}">
                    {{ Helper::getStatusName('default',$navbar->status)}}
                  </label>
                </td>

                <td class="text-center">  
                  @if(Auth::user()->can('navbar.edit'))
                    <a class="text-success" href="{{ route('admin.navbar.edit',$navbar->id)}}"><i class="mdi mdi-pencil-box-outline"></i></a>
                  @endif

                  @if(Auth::user()->can('navbar.delete'))
                    <a class="text-danger delete_btn" data-url="{{ route('admin.navbar.delete',$navbar->id)}}" data-toggle="modal" data-target="#deleteModal" href="#"><i class="text-danger mdi mdi-delete"></i></a>
                  @endif
                </td> 
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="form-group p-2">
        <p class="text-right">
          <button class="btn btn-primary" type="submit">Re-Order</button>
        </p>
      </div>
    </form>
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