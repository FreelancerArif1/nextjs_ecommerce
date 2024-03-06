@extends('backend.layouts.master')
@section('title','Currencies - '.config('concave.cnf_appname'))
@section('content')

<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
      <span class="card-title">Dashboard > Catalog > Currencies</span>
      <a class="btn btn-success float-right" data-toggle="modal" data-target="#SaveModal" href="#">Create New Currency</a>
		  </div>
	</div>
</div>


<div class="grid-margin stretch-card">
    <div class="card">
      <div class="designed_table table-responsive">
        <table id="listTable" class="table">
          <thead>
            <tr>
              <th>Currency Title</th>
              <th>Currency Symbol</th>
              <th>Exchange Rate</th>
              <th>Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>


        @foreach ($currency as $val)
            <tr>
                <td class="pl-3">{{$val->title}}</td>
                <td>{{$val->currency_symbol}}</td>
                <td>{{$val->exchange_rate}}</td>
                <td>
                  <label class="badge {{ 'badge_'.strtolower(Helper::getStatusName('default',$val->status))}}">
                  {{ Helper::getStatusName('default',$val->status)}}
                  </label>
                </td>
                <td class="text-center">
                  @if(Auth::user()->can('brand.delete'))
                    <a class="text-danger delete_btn" data-url="{{ route('admin.currency.delete',$val->id)}}" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>
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
        <h5 class="modal-title" id="SaveModalLabel">Add New Shipping Method </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-sample" method="post" action="{{ route('admin.currency.store')}}" enctype="multipart/form-data" >
          @csrf
          <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Currency Title</label>
                  <div class="col-sm-9">
                    <input type="text" name="title" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Currency Symbol</label>
                  <div class="col-sm-9">
                    <input type="text" name="currency_symbol" class="form-control" />
                  </div>
                </div>
              </div>
            </div>
      
            <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Exchange Rate</label>
                  <div class="col-sm-9">
                    <input type="text" name="exchange_rate" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
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

              <div class="col-md-12">
                <div class="form-group">
                  <p class="text-right">
                      <button class="btn btn-primary" name="save" type="submit">Add Currency</button>
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