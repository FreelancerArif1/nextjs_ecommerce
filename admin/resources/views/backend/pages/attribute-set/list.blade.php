@extends('backend.layouts.master')
@section('title','Attribute Set List - '.config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
      <span class="card-title">Dashboard > Attribute Sets > List</span>
      <a class="btn btn-success float-right" href="{{ route('admin.attribute-set.create')}}">Create New attribute set</a>
		  </div>
	</div>
</div>
<div class="grid-margin">
      
  <div class="row">
    <div class="col-md-12">
    <div class="card">
      <div class="designed_table table-responsive">
        <table id="listTable" class="table">
          <thead>
            <tr>
              <th>Attribute Set Title</th>
              <th>Status</th>
              <th class="text-center" data-priority="1">Action</th>
            </tr>
          </thead>
          <tbody>


        @foreach ($attributeset as $as)
            <tr>
                <td class="pl-3" >{{$as->title}}</td>
                <td>
                  <label class="badge {{ 'badge_'.strtolower(Helper::getStatusName('default',$as->is_active))}}">
                  {{ Helper::getStatusName('default',$as->is_active)}}
                  </label>
                </td>

                <td class="text-center">  
                  @if(Auth::user()->can('attributeset.edit'))
                  <a class="text-success" href="{{ route('admin.attribute-set.edit',$as->id)}}"><i class="mdi mdi-pencil-box-outline"></i></a>
                  @endif
                  
                  @if(Auth::user()->can('attributeset.delete'))
                  <a class="text-danger delete_btn" data-url="{{ route('admin.attribute-set.delete',$as->id)}}" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>
                  @endif
                </td>
                
            </tr>
        @endforeach
       
          </tbody>
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