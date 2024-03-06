@extends('backend.layouts.master')
@section('content')
<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
              <span class="card-title">Dashboard > Catalog > Options > Update Option</span>
              <a class="btn btn-success float-right" href="{{ route('admin.option')}}">View Option List</a>
		  </div>
	</div>
</div>
			<div class="row">
              <div class="col-12 grid-margin">

           <form class="form-sample" method="post" action="{{ route('admin.option.update',$option->id)}}" enctype="multipart/form-data" >
                  @csrf

                <div class="card mb-2">
                  <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Title</label>
                            <div class="col-sm-9">
                              <input type="text" name="title" value="{{ $option->title }}" class="form-control" />
                            </div>
                          </div>
                        </div>
                        

                        <div class="col-md-4">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Type</label>
                            <div class="col-sm-9">
                              <select class="form-control" name="type" >
                                <option @if($option->type == 'text') selected @endif value="text">Text</option>
                                <option @if($option->type == 'dropdown') selected @endif value="dropdown">Dropdown</option>
                                <option @if($option->type == 'checkbox') selected @endif value="checkbox">Checkbox</option>
                                <option @if($option->type == 'radio') selected @endif value="radio">Radio Button</option>
                              </select>
                            </div>
                          </div>
                        </div>

                        

                        <div class="col-md-4">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9">
                              <input type="hidden" name="is_required" value="0">
                              <input name="is_required" type="checkbox" class="form-check-input" value="1"
									@if($option->is_required == 1) checked @endif 
							  >This field is required<i class="input-helper"></i><i class="input-helper"></i></label>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                </div>


                <div class="card">
                  <div class="card-body">
                      
                        <ul id="sortable">
						@php $i = 0; @endphp
						@foreach($values as $value)
                              <li class="ui-state-default">
							  <input type="hidden" name="value[{{$i}}][value_id]" value="{{$value->id}}" />
                                    <div class="row">
                                        <span class="mdi mdi-drag"></span>
                                        <div class="col-md-3">
                                          <div class="form-group">
                                              <input type="text" name="value[{{$i}}][title]" placeholder="Title" class="form-control" value="{{ $value->title }}" />
                                          </div>
                                        </div>
                                        <div class="col-md-3">
                                          <div class="form-group">
                                              <input type="text" name="value[{{$i}}][sku]" placeholder="SKU" class="form-control" value="{{ $value->sku }}" />
                                          </div>
                                        </div>
                                        <div class="col-md-3">
                                          <div class="form-group">
                                              <input type="text" name="value[{{$i}}][price]" placeholder="price" class="form-control" value="{{ $value->price }}" />
                                          </div>
                                        </div>
                                         <div class="col-md-2">
                                          <div class="form-group">
                                              <select class="form-control" name="value[{{$i}}][price_type]"  >
                                                <option @if($value->price_type == 'fixed') selected @endif value="fixed">Fixed</option>
                                                <option @if($value->price_type == 'percent') selected @endif  value="percent">Percent</option>
                                              </select>
                                          </div>
                                        </div>
										<div class="col-md-1">
											<i class="mdi mdi-delete text-danger delete_btn"></i>
										</div>
                                    </div>
                     
                              </li>
							  @php $i++; @endphp
							@endforeach

                        </ul>
                        
                        <p><a id="add_values" class="btn btn-info float-right" href="javascript:void(0)">Add new</a></p>
                </div>
				<div class="card">
				<div class="card-body">
					<button  class="btn btn-success float-right" >Update Option</button>
				</div>
				</div>
				
              </form>
              </div>
      </div>
@endsection