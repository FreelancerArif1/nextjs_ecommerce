@extends('backend.layouts.master')
@section('title','Testimonial List - '.config('concave.cnf_appname'))

@section('content')
<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
      <span class="card-title">Dashboard > Appearance > Testimonials</span>
      <a class="btn btn-success float-right" href="{{ route('admin.testimonial.create')}}">Create New testimonial</a>
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
              <th>Name</th>
              <th>Profession</th>
              <th>Image</th>
			        <th>Dialuge</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>


        @foreach ($testimonials as $key=>$testimonial)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$testimonial->name}}</td>
				<td>{{$testimonial->profession}}</td>
                <td>
                  @if($testimonial->image)
                  <img class="thumb-image" src="{{asset('uploads/images/testimonials/'.$testimonial->image)}}">
                  @else
                  <img class="thumb-image" src="{{ asset('uploads/images/default/no-image.png')}}">
                  @endif
                </td>

				<td>{!! \Illuminate\Support\Str::limit(strip_tags($testimonial->dialuge),50, $end='...')!!}</td>
				
                <td>
                  <label class="badge {{ 'badge_'.strtolower(Helper::getStatusName('default',$testimonial->status))}}">
                  {{ Helper::getStatusName('default',$testimonial->status)}}
                  </label>
                </td>
            <td>  
				@if(Auth::user()->can('testimonials.edit'))
                <a class="btn btn-success" href="{{ route('admin.testimonial.edit',$testimonial->id)}}">Edit</a> | 
                @endif
				
				@if(Auth::user()->can('testimonials.delete'))
				<a class="btn btn-danger delete_btn" data-url="{{ route('admin.testimonial.delete',$testimonial->id)}}" data-toggle="modal" data-target="#deleteModal" href="#">Delete</a>
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