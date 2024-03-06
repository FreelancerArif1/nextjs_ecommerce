@extends('backend.layouts.master')
@section('title','Attribute Create - '.config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
   <div class="card">
      <div class="card-body">
         <span class="card-title">Dashboard > Attribute > Create New Attribute</span>
         <a class="btn btn-success float-right" href="{{ route('admin.attribute-list')}}">View Attribute List</a>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12 grid-margin">
      <div class="card">
         <div class="card-body">

            <form class="form-sample" method="post" action="{{ route('admin.attribute.store')}}" enctype="multipart/form-data" >
               @csrf
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Title <span class="required">*</span></label>
                        <div class="col-sm-8">
                           <input type="text" name="title" class="form-control" placeholder="Title" required />
                        </div>
                     </div>
                  </div>

                  @foreach(\Helper::availableLanguages() as $lan)

                     <div class="col-md-6">
                        <div class="form-group row">
                           <label class="col-sm-4 col-form-label lan_title">Title ({{$lan->title}}) </label>
                           <div class="col-sm-8">
                              <input type="text" name="{{'title__'.$lan->lang_code}}" value="{{ old('title__'.$lan->lang_code ) }}" class="form-control" placeholder="Title"  />
                           </div>
                        </div>
                     </div>
                  @endforeach


				  <div class="col-md-6">
                     <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Attribute Code <span class="required">*</span> </label>
                        <div class="col-sm-8">
                           <input type="text" name="attribute_code" placeholder="Attribute Code" class="form-control" required />
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                           <textarea type="text" name="description" placeholder="Description" class="form-control" ></textarea>
                        </div>
                     </div>
                  </div>

                  @foreach(\Helper::availableLanguages() as $lan)
                     <div class="col-md-12">
                        <div class="form-group row">
                           <label class="col-sm-2 col-form-label lan_title">Description ({{$lan->title}})</label>
                           <div class="col-sm-10">
                              <textarea type="text" name="{{'description__'.$lan->lang_code }}" placeholder="Description" class="form-control" >{{ old('description__'.$lan->lang_code ) }}</textarea>
                           </div>
                        </div>
                     </div>
                  @endforeach
               </div>
               <div class="row">
                  <div class="col-md-3">
                     <div class="form-group row">
                        <label class="col-sm-12 col-form-label">
                        <input type="checkbox" name="show_on_specification"  value='1' checked/>
                        &nbsp;&nbsp;Show on Specification
                        </label>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group row">
                        <label class="col-sm-12 col-form-label">
                        <input type="checkbox" name="show_on_advance_search"  value='1' checked/>
                        &nbsp;&nbsp;Show on Advance Search
                        </label>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group row">
                        <label class="col-sm-12 col-form-label">
                        <input type="checkbox" name="show_on_filter" value='1' checked/>
                        &nbsp;&nbsp;Show on Filter
                        </label>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                           <input type="hidden" name="is_required" value="0">
                           <input type="checkbox" name="is_required"  value='1'/> &nbsp;&nbsp;This field is required
                        </div>
                     </div>
                  </div>
				</div>

                  <!-- Product Custom Options Starts -->
                     <p class="content_title">Attribute Options</p>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group row">
                              <label class="col-sm-4 col-form-label">Placeholder</label>
                              <div class="col-sm-8">
                                 <input type="text" name="placeholder_text" placeholder="Attribute Placeholder" class="form-control" />
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group row">
                              <label class="col-sm-2 col-form-label">Type</label>
                              <div class="col-sm-10">
                                 <select class="form-control option_type" name="catalog_input_type" >
                                    <option value="textfield">Text</option>
                                    {{-- <option value="radio">Radio Button</option>
                                    <option value="checkbox">Checkbox</option>
                                    <option value="dropdown">Dropdown</option> --}}
                                    <option value="textarea">Text Area</option>
                                    <option value="textareawitheditor">Text Area With Editor</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                     </div>

                  <!-- Product Custom Options Ends -->
				<div class="row custom_values_section">
					  <div class="col-md-12 dynamic_attribute_box">
						 <div class="form-group row duplicate_attribute_box">
						  <label class="col-sm-2 col-form-label">Option Values</label>
							<div class="col-sm-4">
							   <input type="text" placeholder="Option Label" class="form-control" name="attribute_values[0][label]">
							</div>
							<div class="col-sm-4">
							   <input type="text" placeholder="Option Value" class="form-control" name="attribute_values[0][value]">
							</div>
							<div class="col-sm-2"><a id="add_product_attribute_options" data-count="0" class="btn btn-warning" href="javascript:void(0)"> + Add Values</a></div>
						 </div>
					  </div>
				</div>


				<div class="row">
					  <div class="col-md-6">
						 <div class="form-group row">
							<label class="col-sm-4 col-form-label">Status <span class="required">*</span></label>
							<div class="col-sm-8">
							   <select name="is_active" class="form-control" required>
								  <option value="1">Active</option>
								  <option  value="0">Inactive</option>
							   </select>
							</div>
						 </div>
					  </div>
					<div class="col-md-6">
						 <div class="form-group">
							<p class="text-right">
							   <button class="btn btn-primary" name="save" type="submit">Add Attribute</button>
							</p>
						 </div>
					</div>
				</div>


               </div>
            </form>
         </div>
      </div>
   </div>
</div>

@endsection
