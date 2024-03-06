@extends('backend.layouts.master')
@section('title','navbar Update - '.config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
      <span class="card-title">Dashboard > Settings > navbars > Update navbar</span>
      <a class="btn btn-success float-right" href="{{ route('admin.navbar')}}">View navbar List</a>
		  </div>
	</div>
</div>
			<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                  <form class="form-sample" method="post" action="{{ route('admin.navbar.update',$navbar->id)}}" enctype="multipart/form-data" >
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Title <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                              <input type="text" name="title" value="{{ $navbar->title }}" placeholder="Title" class="form-control"  required />
                            </div>
                          </div>
                        </div>

                        @foreach(\Helper::availableLanguages() as $lan)
                             <div class="col-md-6">
                                <div class="form-group row">
                                   <label class="col-sm-3 col-form-label lan_title">Title ({{$lan->title}}) </label>
                                   <div class="col-sm-9">
                                      <input type="text" name="{{'title__'.$lan->lang_code}}" value="{{ App\Models\NavbarLocalization::where('navbar_id', $navbar->id)->where('lang_code', $lan->lang_code)->first()->title ?? '' }}" class="form-control" placeholder="Title"  />
                                   </div>
                                </div>
                             </div>
                        @endforeach
                      </div>

                      <div class="row">
					               <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Link</label>
                            <div class="col-sm-9">
                              <input type="text" name="link" placeholder="Button Title" value="{{ $navbar->link }}" class="form-control" />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Link Type</label>
                            <div class="col-sm-9">
                              <select name="link_type" class="form-control">
                                <option value="Internal" @if($navbar->link_type == 'Internal') selected @endif  >Internal</option>
                                <option value="External" @if($navbar->link_type == 'External') selected @endif >External</option>
                              </select>
                            </div>
                          </div>
                        </div>

                      </div>


                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                              <select name="status" class="form-control">
                                <option value="1" @if($navbar->status == 1) selected @endif  >Active</option>
                                <option value="0" @if($navbar->status == 0) selected @endif >Inactive</option>
                              </select>
                            </div>
                          </div>
                        </div>

                      </div>
                         
                      <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <p class="text-right">
                                <button class="btn btn-primary" name="save" type="submit">Update</button>
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