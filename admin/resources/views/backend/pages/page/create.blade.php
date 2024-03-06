@extends('backend.layouts.master')
@section('title','Page Create - '.config('concave.cnf_appname'))
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet" />
<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
      <span class="card-title">Dashboard > Pages > Create New Page</span>
      <a class="btn btn-success float-right" href="{{ route('admin.page')}}">View Page List</a>
		  </div>
	</div>
</div>
			<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                  <form class="form-sample" method="post" action="{{ route('admin.page.store')}}" enctype="multipart/form-data" >
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Title</label>
                            <div class="col-sm-9">
                              <input type="text" name="title" placeholder="Title" data-slugable-model="category" class="form-control slug_maker" />
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
                            <label class="col-sm-3 col-form-label">Slug</label>
                            <div class="col-sm-9">
                              <input type="text" name="slug" placeholder="Slug" data-slugable-model="category"  class="form-control slug_taker" />
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
                        
                      </div>

					            <div class="row">
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Meta Title</label>
                            <div class="col-sm-12">
                               <input type="text" name="meta_title"  placeholder="Meta Title"  class="form-control" />
                            </div>
                          </div>
                        </div>
                      </div>

					            <div class="row">
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Meta Keyword</label>
                            <div class="col-sm-12">
								<input type="text" value="kholabazar"  name="meta_keyword" class="form-control tag_field" data-role="tagsinput" /><br>
                                 <small class="hint_text">Write something & press enter.</small>
                            </div>
                          </div>
                        </div>
                      </div>


					            <div class="row">
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Meta Description</label>
                            <div class="col-sm-12">
                              <textarea type="text" name="meta_description" placeholder="Meta Description" class="form-control" ></textarea>
                            </div>
                          </div>
                        </div>
                      </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Static Template</label>
                        <div class="col-sm-9">
                          <select name="static_template" class="form-control">
                           @foreach(Helper::getTemplateName() as $template)
                            <option value="{{$template}}">{{ ucfirst($template).' Template'}}</option>
                           @endforeach
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">
                          <select name="status" class="form-control">
                            <option value="1">Active</option>
                            <option  value="0">Inactive</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                          
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <p class="text-right">
                                <button class="btn btn-primary" name="save" type="submit">Add Page</button>
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