@extends('backend.layouts.master')
@section('title','Brand Update - '.config('concave.cnf_appname'))
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet" />
    <style>
        .bootstrap-tagsinput .tag {
            margin-right: 2px;
            color: white !important;
            background-color: #5daf21;
            padding: 0.2rem;
            line-height: 28px;
        }
    </style>
            <div class="grid-margin stretch-card">
            	<div class="card">
            		  <div class="card-body">
                  <span class="card-title">Dashboard > Catalog > Brands > Update Brand</span>
                  <a class="btn btn-success float-right" href="{{ route('admin.brand')}}">View Brand List</a>
            		  </div>
            	</div>
            </div>
			<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                  <form class="form-sample" method="post" action="{{ route('admin.brand.update',$brand->id)}}" enctype="multipart/form-data" >
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Title</label>
                            <div class="col-sm-9">
                              <input type="text" name="title" value="{{$brand->title}}" placeholder="Title" class="form-control" />
                            </div>
                          </div>
                        </div>

                        @foreach(\Helper::availableLanguages() as $lan)
                          <div class="col-md-6">
                           <div class="form-group row">
                              <label class="col-sm-3 col-form-label lan_title">Title ({{$lan->title}})</label>
                              <div class="col-sm-9">
                                 <input data-slugable-model="category" type="text" name="{{'title__'.$lan->lang_code}}" value="{{ App\Models\BrandLocalization::where('brand_id', $brand->id)->where('lang_code', $lan->lang_code)->first()->title ?? '' }}" placeholder="Title" class="form-control slug_maker" />
                              </div>
                           </div>
                        </div>
                        @endforeach

                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Slug</label>
                            <div class="col-sm-9">
                              <input type="text" name="slug"  value="{{$brand->slug}}" placeholder="Slug" class="form-control" readonly/>
                            </div>
                          </div>
                        </div>

                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-12">
                              <textarea type="text" name="description" placeholder="Description" class="form-control textEditor" >{!! $brand->description !!}</textarea>
                            </div>
                          </div>
                        </div>

                        @foreach(\Helper::availableLanguages() as $lan)
                        <div class="col-md-12">
                           <div class="form-group row">
                              <label class="col-sm-2 col-form-label lan_title">Description ({{$lan->title}})</label>
                              <div class="col-sm-12">
                                 <textarea type="text" name="{{'description__'.$lan->lang_code }}" placeholder="Description" class="form-control textEditor" >{!! App\Models\BrandLocalization::where('brand_id', $brand->id)->where('lang_code', $lan->lang_code)->first()->description ?? '' !!}</textarea>
                              </div>
                           </div>
                        </div>
                        @endforeach
                        
                          <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Meta Title</label>
                                <div class="col-sm-9">
                                    <input type="text" name="meta_title" placeholder="Meta Title" value="{{$brand->meta_title}}" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Meta Keyword</label>
                                <div class="col-sm-9">
                                    <input type="text" name="meta_keyword" placeholder="Meta Keyword" value="{{$brand->meta_keyword}}" class="form-control tag_field" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Meta Description</label>
                                <div class="col-sm-9">
                                    <textarea type="text" name="meta_description" placeholder="Meta Description"  class="form-control">{{$brand->meta_description}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                               <div class="form-check form-check-flat">
                                  <label class="form-check-label">
                                  <label class="switch"><input name="is_active" type="checkbox" @if($brand->is_active == 1) checked @endif ><span class="slider round"></span></label>
                               </div>
                            </div>
                         </div>
                        </div>
                      </div>

    
                      <div class="row">
 
                        <div class="col-md-6">
                          <div class="form-group">
                            <label >Brand Image</label>
                            <br>
                            <button type="button" data-image-width="400" data-image-height="400" data-input-name="image" data-input-type="single" class="btn btn-success initConcaveMedia" >Select File</button>
                            @if($brand->image)
                              <p class="selected_images_gallery">
                                   <span>
                                     <input type="hidden" value="{{$brand->image}}" name="image">
                                     <img src="{{'/'.$brand->image}}"> 
                                     <b data-file-url="{{$brand->image}}" class="selected_image_remove">X</b>
                                  </span>
                               </p>
                            @endif
                          </div>
                        </div>


                      </div>

                          
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <p class="text-right">
                                <button class="btn btn-primary" name="save" type="submit">Update Brand</button>
                            </p>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
      </div>
      
    @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                $(".tag_field").tagsinput('items');
            });
        </script>
    @endpush  

@endsection