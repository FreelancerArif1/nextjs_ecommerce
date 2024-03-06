@extends('backend.layouts.master')
@section('title','Brand Create - '.config('concave.cnf_appname'))
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
                  <span class="card-title">Dashboard > Catalog > Brands > Create New Brand</span>
                  <a class="btn btn-success float-right" href="{{ route('admin.brand')}}">View Brand List</a>
            		  </div>
            	</div>
            </div>
			<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                  <form class="form-sample" method="post" action="{{ route('admin.brand.store')}}" enctype="multipart/form-data" >
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Title</label>
                            <div class="col-sm-9">
                              <input type="text" data-slugable-model="brand" name="title" placeholder="Title" class="form-control slug_maker" />
                            </div>
                          </div>
                        </div>

                        @foreach(\Helper::availableLanguages() as $lan)
                          <div class="col-md-6">
                           <div class="form-group row">
                              <label class="col-sm-3 col-form-label lan_title">Title ({{$lan->title}})</label>
                              <div class="col-sm-9">
                                 <input data-slugable-model="category" type="text" name="{{'title__'.$lan->lang_code}}" value="{{ old('title__'.$lan->lang_code ) }}" placeholder="Title" class="form-control slug_maker" />
                              </div>
                           </div>
                        </div>
                        @endforeach

                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Slug</label>
                            <div class="col-sm-9">
                              <input type="text" data-slugable-model="brand" name="slug" placeholder="Slug" class="form-control slug_taker" />
                            </div>
                          </div>
                        </div>

                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-12">
                              <textarea type="text" name="description" placeholder="Description" class="form-control textEditor" ></textarea>
                            </div>
                          </div>
                        </div>

                        @foreach(\Helper::availableLanguages() as $lan)
                        <div class="col-md-12">
                           <div class="form-group row">
                              <label class="col-sm-2 col-form-label lan_title">Description ({{$lan->title}})</label>
                              <div class="col-sm-12">
                                 <textarea type="text" name="{{'description__'.$lan->lang_code }}" placeholder="Description" class="form-control textEditor" >{{ old('description__'.$lan->lang_code ) }}</textarea>
                              </div>
                           </div>
                        </div>
                        @endforeach

                        <div class="col-md-6">
                          <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Meta Title</label>
                              <div class="col-sm-9">
                                  <input type="text" name="meta_title" placeholder="Meta Title"  class="form-control" />
                              </div>
                          </div>
                      </div>
                 
                      <div class="col-md-6">
                          <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Meta Keyword</label>
                              <div class="col-sm-9">
                                  <input type="text" name="meta_keyword" id="tag_field" placeholder="Meta Keyword" class="form-control" />
                              </div>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Meta Description</label>
                              <div class="col-sm-9">
                                <textarea type="text" name="meta_description" placeholder="Meta Description"  class="form-control"></textarea>
                              </div>
                          </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label">Status</label>
                          <div class="col-sm-10">
                             <div class="form-check form-check-flat">
                                <label class="form-check-label">
                                <label class="switch"><input name="is_active" type="checkbox" checked><span class="slider round"></span></label>
                             </div>
                          </div>
                       </div>
                      </div>



                      </div>



					          <div class="row">

                        <div class="col-md-6">
                          <div class="form-group">
                            <label >Brand Image</label><br>
                            <button type="button"
                              data-image-width="400" 
                              data-image-height="400"  
                              data-input-name="image" 
                              data-input-type="single" 
                              class="btn btn-success initConcaveMedia" >Select Image
                          </button>
                          </div>
                      </div>

                    </div>


                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <p class="text-right">
                                <button class="btn btn-primary" name="save" type="submit">Add Brand</button>
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
                $("#tag_field").tagsinput('items');
            });
        </script>
    @endpush
    
@endsection