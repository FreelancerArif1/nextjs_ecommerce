@extends('backend.layouts.master')
@section('title','Administrator List - '.config('concave.cnf_appname'))

@section('content')
<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
			  <span class="card-title">Dashboard > Accounts > Administrator</span>
			  <a class="btn btn-success float-right" href="{{ route('admin.administrator.create')}}">Create New Account</a>
		  </div>
	</div>
</div>
<div class="grid-margin stretch-card">
    <div class="card">
      <div class="designed_table table-responsive">
        <table id="listTable" class="table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Role</th>
              <th>Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>


        @foreach ($administrators as $key=>$administrator)
            <tr>
              <td class="pl-3">
                  <div class="media">

                    @if($administrator->avatar)
                     <img class="list_img mr-3" src="{{ '/'.$administrator->avatar }}">
                     @else
                      <img class="list_img mr-3" src="{{ asset('uploads/images/default/no-image.png')}}">
                     @endif

                     <div class="media-body">
                        <p class="product_title">{{$administrator->name}}</p>
                    </div>
                  </div>
               </td>

                <td>{{$administrator->email}}</td>
                <td>{{$administrator->phone}}</td>
                <td>
                  @foreach($administrator->roles as $role)
                    <span class=" badge badge-primary mr-2 mb-1 text-small">{{$role->name}}</span>
                  @endforeach
                </td>
                <td>
                  <label class="badge {{ 'badge_'.strtolower(Helper::getStatusName('default',$administrator->status))}}">
                  {{ Helper::getStatusName('default',$administrator->status)}}
                  </label>
                </td>

              <td class="text-center"> 
                  @if(Auth::user()->can('admin.edit'))
                    @foreach($administrator->roles as $role)
                        @if($role->name != 'superadmin')
                          <a class="text-success"  href="{{ route('admin.administrator.edit',$administrator->id)}}" ><i class="mdi mdi-pencil-box-outline"></i></a>
                        @endif
                      @endforeach
                  @endif

                  @if(Auth::user()->can('admin.delete'))
                    @foreach($administrator->roles as $role)
                        @if($role->name != 'superadmin')
                          <a class="text-danger delete_btn" data-url="{{ route('admin.administrator.delete',$administrator->id)}}" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>
                        @endif
                    @endforeach
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
        <p>Once you delete this item, you can restore this from trash list!</p>
        <textarea name="reason" id="reason" placeholder="Write reason, why you want to delete this item." class="form-control"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <a type="button" href="#" class="btn btn-danger delete_trigger">Delete</a>
      </div>
    </div>
  </div>
</div>
      
@endsection