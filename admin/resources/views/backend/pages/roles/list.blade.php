@extends('backend.layouts.master')
@section('title','Role List - '.config('concave.cnf_appname'))
@section('content')

<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
      <span class="card-title">Dashboard > User Roles > Roles</span>
      <a class="btn btn-success float-right" href="{{ route('roles.create')}}">Create New Role</a>
		  </div>
	</div>
</div>


<div class="grid-margin stretch-card">
    <div class="card">
      <div class="table-responsive">
        <table id="listTable">
          <thead>
            <tr>
              <th width="10%">Serial No.</th>
              <th width="75%" >Name</th>
              <th width="15%" class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>


        @foreach ($roles as $role)
        @php  if($role->name == 'superadmin') continue @endphp
            <tr>
                <td class="pl-3">{{$loop->iteration}}</td>
                <td>
                  <strong style="text-transform:capitalize">{{$role->name}}</strong><br>
                  @foreach($role->permissions as $permission)
                    <span class=" badge badge-primary mr-2 mb-1 text-small">{{$permission->name}}</span>
                  @endforeach
                
                </td>
					
				
            <td class="text-center">  
                <a class="text-success" href="{{ route('roles.edit',$role->id)}}"><i class="mdi mdi-pencil-box-outline"></i></a> 
                <a class="text-danger delete_btn_resource"  data-url="{{route('roles.destroy',$role->id)}}" data-toggle="modal" data-target="#deleteModal" href="javascript:void(0)"><i class="mdi mdi-delete"></i></a>
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
        <form class="delete_form" method="post" action="">
          @csrf
          @method('DELETE')
          <button type="submit"  class="btn btn-danger delete_trigger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
      
@endsection