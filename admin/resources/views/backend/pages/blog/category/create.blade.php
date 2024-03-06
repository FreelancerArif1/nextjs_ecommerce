@extends('backend.layouts.master')
@section('title','Create Blog Category - '.config('concave.cnf_appname'))
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet" />
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Blog > Create Blog Category</span>
                <a class="btn btn-success float-right" href="{{ route('admin.blog.category')}}">View Blog Category List</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form class="form-sample" method="post" action="{{ route('admin.blog.category.store') }}" >
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Title <span style="color: #f00">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" data-slugable-model="blogcategory" name="title" class="form-control slug_maker" placeholder="Title" required />
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
                                    <label class="col-sm-3 col-form-label">Slug<span style="color: #f00">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" data-slugable-model="blogcategory" name="slug" placeholder="Url Key" class="form-control slug_taker"  required/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Meta Title</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="meta_title" placeholder="Meta Title" class="form-control" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Meta Keyword</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="meta_keyword" placeholder="Meta Keyword" class="form-control tag_field" data-role="tagsinput" /><br>
                                        <small class="hint_text">Write something & press enter.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Meta Description</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="meta_description" placeholder="Meta Description" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Status <span style="color: #f00">*</span></label>
                                    <div class="col-sm-9">
                                       <div class="form-check form-check-flat">
                                          <label class="switch"><input name="is_active" type="checkbox" checked required><span class="slider round"></span></label>
                                       </div>
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
                        </div>


                        <div class="row">
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
                            <div class="col-md-6">
                                <div class="form-group row ">
                                    <div class="col-sm-3"><label class="col-form-label">Category Icon</label></div>
                                      <div class="col-sm-9">
                                         <button type="button"
                                             data-image-width="100" 
                                             data-image-height="100"  
                                             data-input-name="icon" 
                                             data-input-type="single" 
                                             class="btn btn-success initConcaveMedia" >Select File
                                          </button>
                                      </div>
                                 </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row ">
                                    <div class="col-sm-3"><label class="col-form-label">Category Image</label></div>
                                      <div class="col-sm-9">
                                         <button type="button"
                                             data-image-width="800" 
                                             data-image-height="800"  
                                             data-input-name="image" 
                                             data-input-type="single" 
                                             class="btn btn-success initConcaveMedia" >Select File
                                          </button>
                                      </div>
                                 </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p class="text-right">
                                        <button class="btn btn-primary" name="save" type="submit">Add Blog Category</button>
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
            })
        </script>
    @endpush

@endsection
