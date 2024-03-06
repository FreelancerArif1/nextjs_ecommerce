<p class="content_title">Create New Category</p>
<form class="form-sample" method="post" action="{{ route('admin.category.store')}}" enctype="multipart/form-data" >
   @csrf
   <div class="row">
      <div class="col-md-6">
         <div class="form-group row">
            <label class="col-sm-3 col-form-label">Title</label>
            <div class="col-sm-9">
               <input data-slugable-model="category" type="text" name="title" placeholder="Title" class="form-control slug_maker" />
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
            <label class="col-sm-3 col-form-label">Slug</label>
            <div class="col-sm-9">
               <input data-slugable-model="category" type="text" name="slug" placeholder="Slug" class="form-control slug_taker" />
            </div>
         </div>
      </div>
   </div>


   <div class="row">
      <div class="col-md-6">
         <div class="form-group row">
            <label class="col-sm-3 col-form-label">Parent Category</label>
            <div class="col-sm-9">
               <select name="parent_id" data-show-subtext="true" data-live-search="true"  class="selectpicker form-control">
                  <option value="0">Primary Category</option>
                  @foreach($allcategories as $category)
                  <option value="{{$category->id}}">{{$category->title}}</option>
                  @endforeach
               </select>
            </div>
         </div>
      </div>

      <div class="col-md-3">
         <div class="form-group row">
           <label class="col-sm-8 col-form-label">Hide on Menu</label>
           <div class="col-sm-4">
              <div class="form-check form-check-flat">
                 <label class="switch"><input name="hide_on_menu" type="checkbox"><span class="slider round"></span>
              </div>
           </div>
        </div>
      </div>

      <div class="col-md-3">
         <div class="form-group row">
           <label class="col-sm-8 col-form-label">Show Child Products</label>
           <div class="col-sm-4">
              <div class="form-check form-check-flat">
                 <label class="switch"><input name="show_child_products" type="checkbox" checked><span class="slider round"></span>
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

      <div class="col-md-6">
         <div class="form-group row">
             <label class="col-sm-3 col-form-label">Meta Title</label>
             <div class="col-sm-9">
                 <input type="text" name="meta_title" placeholder="Meta Title"  class="form-control" />
             </div>
         </div>
     </div>

     <div class="col-md-6">
         <div class="form-group row">
             <label class="col-sm-3 col-form-label">Meta Keyword</label>
             <div class="col-sm-9">
                 <input type="text" name="meta_keyword" placeholder="Meta Keyword" class="form-control" />
             </div>
         </div>
     </div>
     <div class="col-md-6">
         <div class="form-group row">
             <label class="col-sm-3 col-form-label">Meta Description</label>
             <div class="col-sm-9">
               <textarea type="text" name="meta_description" placeholder="Meta Description"  class="form-control"></textarea>
             </div>
         </div>
     </div>
     <div class="col-md-6">
      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Status</label>
        <div class="col-sm-9">
           <div class="form-check form-check-flat">
              <label class="switch"><input name="is_active" type="checkbox" checked><span class="slider round"></span>
           </div>
        </div>
     </div>
  </div>
   </div>
   <div class="row">
      <div class="col-md-6">
         <div class="form-group">
            <label >Category Thumbnail</label><br>

            <button  type="button" 
               data-image-width="400" 
               data-image-height="400"  
               data-input-name="image" 
               data-input-type="single" 
               class="btn btn-success initConcaveMedia" >Select Image
            </button>
         </div>
      </div>
      <div class="col-md-6">
         <div class="form-group">
            <label >Category Banner</label><br>
            <button  type="button" 
               data-image-width="400" 
               data-image-height="400"  
               data-input-name="banner" 
               data-input-type="single" 
               class="btn btn-success initConcaveMedia" >Select Image
            </button>
         </div>
      </div>
   </div>
<hr>
   <div class="row">
     <div class="col-md-12">
        <div class="form-group">

         <div class="filter_header">
            <div class="row">
              <div class="col-md-8"> <label class="mt-2">Products in Category</label></div>
              <div class="col-md-4"></div>
            </div>
          </div>

          {{-- <select name="products[]" data-size="10" class="selectpicker form-control" data-show-subtext="true" data-live-search="true" multiple>
           @foreach(DB::TABLE('products')->select('id', 'category_id','title')->orderBy('title','asc')->where('is_active',1)->get() as $product)
            <option  value="{{$product->id}}"
             >{{$product->title}}</option>
           @endforeach
          </select> --}}


          <table id="listTable2" class="table table-striped" style="width: 100%;">
            <thead>
              <tr>
                <td style="width:5%">#</td>
                <td style="width:20%" > ID</td>
                <td style="width:60%" > Name</td>
                <td style="width:10%">Status</td>
              </tr>
            </thead>
            <tbody class="list">

            
            @foreach(
              DB::TABLE('products')
              ->select('id', 'category_id','title','is_active')
              ->orderBy('id','asc')
              ->where('is_deleted',0)
              ->get() as $product)

              <tr >
                <td style="width:5%"> <input type="checkbox" name="products[]" value="{{$product->id}}"></td>
                <td style="width:20%">{{$product->id}}</td>
                <td style="width:60%">{{ substr($product->title, 0, 100) }}</td>
                <td style="width:10%">
                  <span class="{{'badge badge_'.strtolower(Helper::getStatusName('default',$product->is_active))}}">
                    {{Helper::getStatusName('default',$product->is_active)}}
                  </span>
                </td>
              </tr>
            @endforeach

            </tbody>
          </table>





        </div>
      </div>

   </div>


   <div class="row">
      <div class="col-md-12">
         <div class="form-group">
            <p class="text-right">
               <button class="btn btn-success" name="save" type="submit">Add Category</button>
            </p>
         </div>
      </div>
   </div>
</form>