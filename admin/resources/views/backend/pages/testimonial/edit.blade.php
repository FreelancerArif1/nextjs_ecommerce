@extends('backend.layouts.master')
@section('title','Testimonial Update - '.config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
      <span class="card-title">Dashboard > Appearance > Sliders > Update Testimonial</span>
      <a class="btn btn-success float-right" href="{{ route('admin.testimonial')}}">View Testimonial List</a>
		  </div>
	</div>
</div>
			<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                  <form class="form-sample" method="post" action="{{ route('admin.testimonial.update',$testimonial->id)}}" enctype="multipart/form-data" >
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                              <input type="text" name="name" value="{{ $testimonial->name }}" placeholder="Name" class="form-control" />
                            </div>
                          </div>
                        </div>
						<div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Dialuge</label>
                            <div class="col-sm-9">
                              <textarea type="text" name="dialuge" style="min-height: 110px;" class="form-control" >{{ $testimonial->dialuge }}</textarea>
                            </div>
                          </div>
                        </div>
                    </div>




                      <div class="row">
						<div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Profession</label>
                            <div class="col-sm-9">
                              <input type="text" name="profession" value="{{ $testimonial->profession }}" placeholder="Profession"  class="form-control" />
                            </div>
                          </div>
                        </div>
						<div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                              <select name="status" class="form-control">
                                <option value="1" @if($testimonial->status == 1) selected @endif  >Active</option>
                                <option value="0" @if($testimonial->status == 0) selected @endif >Inactive</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
					  
					  
					  <div class="row">
					    <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-12 col-form-label">Image</label>
                            <div class="col-sm-12">
                            <div class="slider_image">
                              @if(!empty($testimonial->image))
                                <img src="/uploads/images/testimonials/{{$testimonial->image}}" hieght="100px" width="100px"/>
                              @endif
                             </div>
								<input style="display:block;margin-top:10px;" type="file" name="image">
                            </div>
                          </div>
                        </div>
					  </div>
                         
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <p class="text-right">
                                <button class="btn btn-primary" name="save" type="submit">Update Testimonial</button>
                            </p>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
			</div>
@endsection