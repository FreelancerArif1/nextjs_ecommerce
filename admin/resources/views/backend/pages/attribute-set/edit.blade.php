@extends('backend.layouts.master')
@section('title','Attribute Set Update - '.config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
   <div class="card">
      <div class="card-body">
         <span class="card-title">Dashboard > Attribute > Update Attribute Set</span>
         <a class="btn btn-success float-right" href="{{ route('admin.attribute-list')}}">View Attribute Set List</a>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12 grid-margin">
      <div class="card">
         <div class="card-body">
            <form class="form-sample" method="post" action="{{ route('admin.attribute-set.update',$attributeset->id)}}" enctype="multipart/form-data" >
               @csrf
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Title <span class="required">*</span></label>
                        <div class="col-sm-8">
                           <input type="text" name="title" placeholder="Title" class="form-control" required value="{{$attributeset->title}}" />
                        </div>
                     </div>
                  </div>
				  <div class="col-md-6">
                     <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Attribute Set Code <span class="required">*</span> </label>
                        <div class="col-sm-8">
                           <input type="text" name="attribute_set_code" placeholder="Attribute Set Code" class="form-control" required value="{{$attributeset->attribute_set_code}}" />
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                           <textarea type="text" name="description" placeholder="Description" class="form-control" >{{$attributeset->description}}</textarea>
                        </div>
                     </div>
                  </div>
               </div>

				<div class="row">
                     <div class="col-md-6">
                        <div class="form-group row">
                           <label class="col-sm-4 col-form-label">Select Attributes</label>
                           
                           @php 
                           $attribute_ids = $attributeset->attribute_ids; 
                           $attribute_ids_array = explode(',', $attribute_ids);
                           
                           @endphp


                           <select  data-max-options="10" class="col-sm-8 selectpicker form-control" id="attribute_list_picker" data-show-subtext="true" data-live-search="true" >
                              <option value="-1">--Select Attribute--</option>
                              @foreach(App\Models\Attribute::orderBy('title','asc')->where('is_active',1)->where('is_deleted',0)->get() as $attribute)
                                 <option  value="{{$attribute->id}}">{{$attribute->title}}</option>
                              @endforeach
                           </select>
                        </div>

                        <div class="form-group row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-8" style="background: #f3f3f3;">
                           <div class="dynamic_attributes sortable">

                                 @foreach(App\Models\Attribute::whereIn('id',$attribute_ids_array)->orderByRaw("FIELD(id, $attribute_ids)")->where('is_active',1)->where('is_deleted',0)->get() as $attribute)     
                                       <p class="attribute_list">
                                          <i class="mdi mdi-format-list-bulleted"></i>{{$attribute->title}}
                                          <span class="remove_attribute mdi mdi-delete"></span>
                                          <input type="hidden" name="attribute_ids[]" value="{{$attribute->id}}">
                                       </p>
                                 @endforeach

                           </div>
                        </div>
                        </div>
                  
                  </div>
                  <div class="col-md-6"></div>
                  <div class="col-md-6"></div>
                  <div class="col-md-4">
                     <div class="form-group row">
                     <label class="col-sm-5 col-form-label">Status <span class="required">*</span></label>
                     <div class="col-sm-7">
                        <select name="is_active" class="form-control" required>
                           <option value="1" @if($attributeset->is_active == 1) selected @endif >Active</option>
                           <option value="0" @if($attributeset->is_active == 0) selected @endif >Inactive</option>
                        </select>
                     </div>
                     </div>
                  </div>
                  <div class="col-md-2">
                        <div class="form-group">
                        <p class="text-right">
                           <button class="btn btn-primary" name="save" type="submit">Edit Attribute Set</button>
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

@push('footer')
<script>

   jQuery(document).on('change','#attribute_list_picker',function(){

      if(selectedVal != -1){
         var selectedVal = jQuery(this).find('option:selected').val();
         var selectedText = jQuery(this).find('option:selected').text();

         var html = '<p class="attribute_list">'+
               '<i class="mdi mdi-format-list-bulleted"></i>'+selectedText+
               '<span class="remove_attribute mdi mdi-delete"></span>'+
               '<input type="hidden" name="attribute_ids[]" value="'+selectedVal+'">'+
            '</p>';
         
         var alreadyExists = false;
         jQuery('.dynamic_attributes input').each(function(key,val){
            if(jQuery(this).val() == selectedVal){
               alreadyExists = true;
            }
         });

         if(!alreadyExists){
            jQuery('.dynamic_attributes').append(html);
         }else{
            alert('Already added on attribute list!');
         }
      }

   });

   jQuery(document).on('click','.remove_attribute',function(){
      jQuery(this).closest('.attribute_list').remove();
   });

</script>
@endpush

@endsection