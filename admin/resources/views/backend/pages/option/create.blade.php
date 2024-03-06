@extends('backend.layouts.master')
@section('content')
<div class="grid-margin stretch-card">
   <div class="card">
      <div class="card-body">
         <span class="card-title">Dashboard > Catalog > Options > Create New Option</span>
         <a class="btn btn-success float-right" href="{{ route('admin.option')}}">View Option List</a>
      </div>
   </div>
</div>
<div class="row">
<div class="col-12 grid-margin">
   <form class="form-sample" method="post" action="{{ route('admin.option.store')}}" enctype="multipart/form-data" >
      @csrf
      <div class="card mb-2">
         <div class="card-body">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group row">
                     <label class="col-sm-3 col-form-label">Title</label>
                     <div class="col-sm-9">
                        <input type="text" name="title"  class="form-control" />
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group row">
                     <label class="col-sm-3 col-form-label">Type</label>
                     <div class="col-sm-9">
                        <select class="form-control" name="type" >
                           <option value="text">Text</option>
                           <option value="dropdown">Dropdown</option>
                           <option value="checkbox">Checkbox</option>
                           <option value="radio">Radio Button</option>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group row">
                     <label class="col-sm-3 col-form-label"></label>
                     <div class="col-sm-9">
                        <input type="hidden" name="is_required" value="0">
                        <input name="is_required" type="checkbox" class="form-check-input" value="1" >This field is required<i class="input-helper"></i><i class="input-helper"></i></label>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <ul id="sortable">
               <li class="ui-state-default">
                  <div class="row">
                     <span class="mdi mdi-drag"></span>
                     <div class="col-md-4">
                        <div class="form-group">
                           <input type="text" name="value[0][title]" placeholder="Title" class="form-control" />
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-group">
                           <input type="text" name="value[0][sku]" placeholder="SKU" class="form-control" />
                        </div>
                     </div>
                     <div class="col-md-1">
                        <div class="form-group">
                           <input type="number" name="value[0][qty]" placeholder="Qty" class="form-control value_qty" />
                        </div>
                     </div>
                     <div class="col-md-2 variant_values">
                        <div class="form-group">
                           <button type="button" data-image-width="400" data-image-height="400" data-input-name="value[0][variant_image]" 
                              data-input-type="single" class="btn btn-success initConcaveMedia" >Image</button>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-group">
                           <input type="number" name="value[0][price]" placeholder="Price" class="form-control value_price" />
                        </div>
                     </div>
                     <div class="col-md-1">
                        <i class="mdi mdi-delete text-danger delete_btn"></i>
                     </div>
                  </div>
               </li>
            </ul>
            <p><a id="add_values" class="btn btn-info float-right" href="javascript:void(0)">Add new</a></p>
         </div>
         <div class="card">
            <div class="card-body">
               <button  class="btn btn-success float-right" >Save Option</button>
            </div>
         </div>
   </form>
   </div>
</div>
@endsection