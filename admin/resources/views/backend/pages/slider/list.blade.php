@extends('backend.layouts.master')
@section('title','Slider List - '.config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
	<div class="card">
		<div class="card-body">
      <span class="card-title">Dashboard > Settings > Sliders</span>
      <a class="btn btn-success float-right" href="{{ route('admin.slider.create')}}">Create New slider</a>
		</div>
	</div>
</div>
<div class="grid-margin stretch-card" style="width: 100%;">
  <div class="card">
    <form action="{{ route('admin.slider.reorder') }}" method="POST">
      @csrf
      <div class="">
        <table id="" class="table">
          <thead>
            <tr>
              <th></th>
              <th>Slider Image</th>
              <th>Slider Title</th>
              <th>Button Title</th>
              <th>Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody class="dynamic_attributes sortable">
            @foreach ($sliders as $key=>$slider)
              <tr class="attribute_list">  
                <td><i class="mdi mdi-format-list-bulleted"></i></td> 
                <td class="pl-3">
                  <div class="media">
                    <input type="hidden" name="slider_ids[]" value="{{$slider->id}}">
                    @if($slider->image)
                      <img class="thumb-image" src="{{ asset('/'.$slider->image)}}">
                    @else
                      <img class="thumb-image" src="{{ asset('uploads/images/default/no-image.png')}}">
                    @endif
                  </div>
                </td>
                <td>
                  <div class="media-body">
                    <p class="product_title">{{$slider->title}}</p>
                  </div>
                </td>
                <td>{{$slider->button_title}}</td>
                <td>
                  <label class="badge {{ 'badge_'.strtolower(Helper::getStatusName('default',$slider->status))}}">
                    {{ Helper::getStatusName('default',$slider->status)}}
                  </label>
                </td>

                <td class="text-center">  
                  @if(Auth::user()->can('slider.edit'))
                    <a class="text-success" href="{{ route('admin.slider.edit',$slider->id)}}"><i class="mdi mdi-pencil-box-outline"></i></a>
                  @endif

                  @if(Auth::user()->can('slider.delete'))
                    <a class="text-danger delete_btn" data-url="{{ route('admin.slider.delete',$slider->id)}}" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>
                  @endif
                </td> 
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="form-group p-2">
        <p class="text-right">
          <button class="btn btn-primary" type="submit">Re-Order Slider</button>
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