@extends('backend.layouts.master')
@section('title','Languages - '.config('concave.cnf_appname'))
@section('content')

<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
      <span class="card-title">Dashboard > Languages</span>
      <a class="btn btn-success float-right" data-toggle="modal" data-target="#SaveModal" href="#">Create New Language</a>
		  </div>
	</div>
</div>


<div class="grid-margin stretch-card">
    <div class="card">
      <div class="designed_table table-responsive">
        <table id="listTable" class="table">
          <thead>
            <tr>
              <th>Language Title</th>
              <th>Language Code</th>
              <th>Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>


        @foreach ($language as $val)
            <tr>
                <td class="pl-3">{{$val->title}}</td>
                <td>{{$val->lang_code}}</td>
                <td>
                  <label class="badge {{ 'badge_'.strtolower(Helper::getStatusName('default',$val->is_active))}}">
                  {{ Helper::getStatusName('default',$val->is_active)}}
                  </label>
                </td>
                <td class="text-center">
                  @if(Auth::user()->can('language.delete'))
                    <a class="text-danger delete_btn" data-url="{{ route('admin.language.delete',$val->id)}}" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>
                  @endif
                </td>
            </tr>
        @endforeach
       
          </tbody>
        </table>
      </div>
    </div>
  </div>

    <!--Save Modal -->
<div class="modal fade" id="SaveModal" tabindex="-1" role="dialog" aria-labelledby="SaveModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="SaveModalLabel">Add New Language </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-sample" method="post" action="{{ route('admin.language.store')}}" enctype="multipart/form-data" >
          @csrf
          <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Language Title</label>
                  <div class="col-sm-9">
                    <input type="text" name="title" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Language Code</label>
                  <div class="col-sm-9">
                    <input type="text" name="language_code" class="form-control" />
                  </div>
                </div>
              </div>
            </div>
      
            <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Status</label>
                  <div class="col-sm-9">
                    <select name="is_active" class="form-control">
                      <option value="1">Active</option>
                      <option  value="0">Inactive</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <p class="text-right">
                      <button class="btn btn-primary" name="save" type="submit">Add Language</button>
                  </p>
                </div>
              </div>

            </div>
          </form>
      </div>

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