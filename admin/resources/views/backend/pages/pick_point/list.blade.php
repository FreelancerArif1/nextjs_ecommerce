@extends('backend.layouts.master')
@section('title','Pick Point List - '.config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
      <span class="card-title">Dashboard > Settings > Pick Points</span>
      <a class="btn btn-success float-right" href="{{ route('admin.pick_points.create')}}">Create New Pick Point</a>
		  </div>
	</div>
</div>
<div class="grid-margin stretch-card">
    <div class="card">
      <div class="designed_table table-responsive">
        <table id="listTable" class="table">
          <thead>
            <tr>
              <th>Point Name</th>
              <th>Address</th>
              <th>Discount</th>
              <th>Phone</th>
              <th>Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>


        @foreach ($pick_points as $point)
            <tr>
               
                <td class="pl-3">
                  <div class="media">
                     <div class="media-body">
                        <p class="product_title">{{$point==null?'':$point->title}}</p>
                    </div>
                  </div>
               </td>

                <td>{{$point==null?'':$point->address}}, {{$point->union==null?'':$point->union->title}}, {{$point->upazila==null?'':$point->upazila->title}}, {{$point->district==null?'':$point->district->title}}, {{$point->division==null?'':$point->division->title}}-{{$point==null?'':$point->post_code}}</td>
                <td>BDT {{$point->discount}}</td>
                <td>{{$point->phone}}</td>
                
                <td>
                  <label class="badge {{ 'badge_'.strtolower(Helper::getStatusName('default',$point->status))}}">
                  {{ Helper::getStatusName('default',$point->status)}}
                  </label>
                </td>

              <td class="text-center">  
                @if(Auth::user()->can('pick_point.edit'))
                  <a class="text-success" href="{{ route('admin.pick_points.edit',$point->id)}}"><i class="mdi mdi-pencil-box-outline"></i></a>
                @endif

                @if(Auth::user()->can('pick_point.delete'))
                <a class="text-danger delete_btn" data-url="{{ route('admin.pick_points.delete',$point->id)}}" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>
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