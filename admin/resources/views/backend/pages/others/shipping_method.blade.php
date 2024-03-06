@extends('backend.layouts.master')
@section('title','Free Shipping ZIP - '.config('concave.cnf_appname'))
@section('content')

<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
      <span class="card-title">Dashboard > Catalog > Free Shipping ZIP</span>
      <a class="btn btn-success float-right" data-toggle="modal" data-target="#SaveModal" href="#">Create New Free Shipping ZIP</a>
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
              <th>ZIP</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>


        @foreach ($shipping_method as $key=>$val)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$val->zip}}</td>
                <td>
                  <label class="badge badge-danger">
                  {{ Helper::getStatusName('default',$val->status)}}
                  </label>
                </td>
            <td>
                <a class="btn btn-danger delete_btn" data-url="{{ route('admin.shipping_method.delete',$val->id)}}" data-toggle="modal" data-target="#deleteModal" href="#">Delete</a>
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
        <h5 class="modal-title" id="SaveModalLabel">Add Free Shipping ZIP </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-sample" method="post" action="{{ route('admin.shipping_method.store')}}" enctype="multipart/form-data" >
          @csrf
          <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">ZIP</label>
                  <div class="col-sm-9">
                    <input type="text" name="zip" class="form-control" required />
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

              <div class="col-md-6">
                <div class="form-group">
                  <p class="text-right">
                      <button class="btn btn-primary" name="save" type="submit">Add Shipping Method</button>
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