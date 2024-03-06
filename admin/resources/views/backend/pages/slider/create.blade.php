@extends('backend.layouts.master')
@section('title','Slider Create - '.config('concave.cnf_appname'))

@section('content')
<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
      <span class="card-title">Dashboard > Settings > Sliders > Create New Slider</span>
      <a class="btn btn-success float-right" href="{{ route('admin.slider')}}">View Slider List</a>
		  </div>
	</div>
</div>
			<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                  <form class="form-sample" method="post" action="{{ route('admin.slider.store')}}" enctype="multipart/form-data" >
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Title <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                              <input type="text" name="title" value="{{ old('title') }}" placeholder="Title" class="form-control" required />
                            </div>
                          </div>
                        </div>

                        @foreach(\Helper::availableLanguages() as $lan)
                             <div class="col-md-6">
                                <div class="form-group row">
                                   <label class="col-sm-3 col-form-label lan_title">Title ({{$lan->title}}) </label>
                                   <div class="col-sm-9">
                                      <input type="text" name="{{'title__'.$lan->lang_code}}" value="{{ old('title__'.$lan->lang_code ) }}" class="form-control" placeholder="Title"  />
                                   </div>
                                </div>
                             </div>
                        @endforeach


						            <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Slider Text</label>
                            <div class="col-sm-9">
                              <textarea type="text" name="slider_text" placeholder="Slider Text" class="form-control" >{{ old('slider_text') }}</textarea>
                            </div>
                          </div>
                        </div>

                        @foreach(\Helper::availableLanguages() as $lan)
                             <div class="col-md-6">
                                <div class="form-group row">
                                   <label class="col-sm-3 col-form-label lan_title">Slider Text ({{$lan->title}}) </label>
                                   <div class="col-sm-9">
                                      <textarea type="text" name="{{'slider_text__'.$lan->lang_code}}" placeholder="Slider Text" class="form-control" >{{ old('slider_text__'.$lan->lang_code ) }}</textarea>
                                   </div>
                                </div>
                             </div>
                        @endforeach

                      </div>

                      <div class="row">
					               <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Button Title</label>
                            <div class="col-sm-9">
                              <input type="text" name="button_title" value="{{ old('button_title') }}" placeholder="Button Title" class="form-control" />
                            </div>
                          </div>
                        </div>

                        @foreach(\Helper::availableLanguages() as $lan)
                             <div class="col-md-6">
                                <div class="form-group row">
                                   <label class="col-sm-3 col-form-label lan_title">Button Title ({{$lan->title}}) </label>
                                   <div class="col-sm-9">
                                      <input type="text" name="{{'button_title__'.$lan->lang_code}}" value="{{ old('button_title__'.$lan->lang_code ) }}" class="form-control" placeholder="Button Title"  />
                                   </div>
                                </div>
                             </div>
                        @endforeach


						            <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Button Link <span class="text-danger">*</span> </label>
                            <div class="col-sm-9">
                              <input type="text" name="button_link" value="{{ old('button_link') }}" placeholder="Button Link"  class="form-control" required />
                            </div>
                          </div>
                        </div>
                      </div>


                      <div class="row">


                        <div class="col-md-6">
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Slider Image</label>
                                <div class="col-sm-9">
                                      <button  type="button" 
                                        data-image-width="1280" 
                                        data-image-height="720"  
                                        data-input-name="image" 
                                        data-input-type="single" 
                                        class="btn btn-success initConcaveMedia" >Select Image
                                    </button>
                                </div>
                              </div>
                        </div>

                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Is Grocery</label>
                          <div class="col-sm-3">
                              <div class="form-check form-check-flat">
                                  <label class="form-check-label">
                                  <label class="switch"><input name="is_grocery" id="is_grocery"
                                          type="checkbox"
                                          @if (old('is_grocery')) checked="" @endif><span
                                          class="slider round"></span></label>
                              </div>
                          </div>
                        </div>
                      </div>


						            <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                              <select name="status" class="form-control">
                                <option value="1" @if(old('status') == 1) selected @endif >Active</option>
                                <option value="0" @if(old('status') == 0) selected @endif >Inactive</option>
                              </select>
                            </div>
                          </div>
                        </div>


                      </div>
                         
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <p class="text-right">
                                <button class="btn btn-primary" name="save" type="submit">Add Slider</button>
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