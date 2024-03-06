@extends('backend.layouts.master')
@section('title','Flash Deal Create - '.config('concave.cnf_appname'))
@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet" />
<style type="text/css">
.bootstrap-tagsinput .tag {
  margin-right: 2px;
  color: white !important;
  background-color: #5daf21;
  padding: 0.2rem;
  line-height: 28px;
}
</style>

    <div class="grid-margin stretch-card">
    	<div class="card">
    		  <div class="card-body">
          <span class="card-title">Dashboard > Marketing > Flash Deals > Create New Flash Deal</span>
          <a class="btn btn-success float-right" href="{{ route('admin.flash_deal')}}">View Flash Deal List</a>
    		  </div>
    	</div>
    </div>
			<div class="row">
        <div class="col-12 grid-margin">
          <div class="card">
            <div class="card-body">
            <form class="form-sample" method="post" action="{{ route('admin.flash_deal.store')}}" enctype="multipart/form-data" >
              @csrf
              <div class="row">
                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Title</label>
                      <div class="col-sm-9">
                        <input type="text" data-slugable-model="flash_deal" name="title" id="title" value="{{ old('title') }}" placeholder="Title" class="form-control slug_maker" />
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
                      <label class="col-sm-3 col-form-label">Flash Deal Slug</label>
                      <div class="col-sm-9">
                        <input type="text" name="slug" id="slug" placeholder="Flash Deal Slug" class="form-control slug_taker" value="{{ old('slug') }}" >
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Button Title</label>
                      <div class="col-sm-9">
                        <input type="text" name="button_title" value="{{ old('button_title') }}" placeholder="Button Title" class="form-control" />
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Button Link</label>
                      <div class="col-sm-9">
                        <input type="text" name="button_link" value="{{ old('button_link') }}" placeholder="Button Link" class="form-control" />
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Background Color</label>
                      <div class="col-sm-9">
                        <input type="text" name="background_color" value="{{ old('background_color') }}" placeholder="Background Color" class="form-control" />
                      </div>
                    </div>
                  </div>


			            <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Text Color</label>
                      <div class="col-sm-9">
                        <input type="text" name="text_color" value="{{ old('text_color') }}" placeholder="Button Text color"  class="form-control" />
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Banner Image</label>
                        <div class="col-sm-9">
                              <button  type="button" 
                                data-image-width="1280" 
                                data-image-height="720"  
                                data-input-name="banner" 
                                data-input-type="single" 
                                class="btn btn-success initConcaveMedia" >Select Image
                            </button>
                            @if(old('banner'))
                              <p class="selected_images_gallery">
                                  <span>
                                    <input type="hidden" value="{{old('banner')}}" name="banner">
                                    <img src="{{'/'.old('banner')}}"> 
                                    <b data-file-url="{{old('banner')}}" class="selected_image_remove">X</b>
                                 </span>
                              </p>
                            @endif
                            <br>
                            <small><b>Note:</b> If grocery deal then  <b >Image Size (width:800 X height:450)</b></small>
                        </div>
                      </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Start Date</label>
                    <div class="col-sm-9">
                      <input type="datetime-local" name="from_date" value="{{ old('from_date') }}" placeholder="Start Date"  class="form-control" />
                    </div>
                  </div>
                </div>


                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">End Date</label>
                    <div class="col-sm-9">
                      <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" placeholder="End Date"  class="form-control" />
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Meta Title</label>
                    <div class="col-sm-9">
                      <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="Meta Title"  class="form-control" />
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Meta Keyword</label>
                    <div class="col-sm-9">
                      <input type="text" name="meta_keyword" value="{{ old('meta_keyword') }}" placeholder="Meta Keyword"  class="tag form-control tag_field" data-role="tagsinput" > <br>
                      <small class="hint_text">Write something & press enter.</small>
                    </div>
                  </div>
                </div>

                
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Meta Description</label>
                    <div class="col-sm-9">
                      <textarea type="text" name="meta_description"  placeholder="Meta Description"  class="form-control" >{{ old('meta_description') }}</textarea>
                    </div>
                  </div>
                </div>


                <div class="col-md-2">
                  <div class="form-group row">
                    <label class="col-sm-6 col-form-label">Show Category Wise</label>
                    <div class="col-sm-6">
                       <div class="form-check form-check-flat">
                          <label class="form-check-label">
                          <label class="switch"><input name="show_category_wise" type="checkbox" @if(old('show_category_wise')) checked @endif ><span class="slider round"></span></label>
                       </div>
                    </div>
                 </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group row">
                    <label class="col-sm-6 col-form-label">Is Grocery</label>
                    <div class="col-sm-6">
                       <div class="form-check form-check-flat">
                          <label class="form-check-label">
                          <label class="switch"><input name="is_grocery" type="checkbox" @if(old('is_grocery')) checked @endif><span class="slider round"></span></label>
                       </div>
                    </div>
                 </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group row">
                    <label class="col-sm-6 col-form-label">Status</label>
                    <div class="col-sm-6">
                       <div class="form-check form-check-flat">
                          <label class="form-check-label">
                          <label class="switch"><input name="status" type="checkbox" @if(old('status')) checked @endif><span class="slider round"></span></label>
                       </div>
                    </div>
                 </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Select Products</label>
                    <div class="col-sm-10">
                      @php $products = \App\Models\Product::where('is_active',1)->where('is_deleted',0)->where('product_qc',1)->get(); @endphp
                       <select name="product_ids[]" id="select_deal_products" data-show-subtext="true" data-live-search="true"  class="selectpicker form-control" multiple required >
                          @foreach($products as $product)
                           <option value="{{$product->id}}" @if(!empty(old('product_ids')) && in_array($product->id, old('product_ids'))) selected @endif>{{$product->title}}</option>
                          @endforeach
                       </select>
                    </div>
                 </div>
                </div>

                <div class="col-md-12 mb-5 mt-4">
                  <div class="card">
                    <div class="card-body">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <td></td>
                            <td>Product</td>
                            <td>Base Price</td>
                            <td>Discount</td>
                            <td>Discount Type</td>
                          </tr>
                        </thead>
                        <tbody id="all_selected_products_area" class="dynamic_attributes sortable">
                          
                        </tbody>
                      </table>
                    </div>
                  </div>

                </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <p class="text-right">
                          <button class="btn btn-primary" name="save" type="submit">Add Flash Deal</button>
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

@push('footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>

  <script type="text/javascript">
     jQuery(document).ready(function () {
        $(".tag_field").tagsinput('items');

        jQuery(document).on('change','#select_deal_products',function(e){
          e.preventDefault();

          var ids = jQuery(this).val();
          if(ids != ''){
            jQuery.ajax({
              url: "/admin/flash-deals/get/products/"+ids,
              type: "get",
              data: {},
              success: function(response) {
                jQuery('#all_selected_products_area').html(response);
              }
            });
          }else{
            jQuery('#all_selected_products_area').html('');
          }

        })

     });
  </script>
@endpush