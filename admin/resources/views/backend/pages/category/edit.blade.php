
              <p class="content_title">Update Category</p>
              <form class="form-sample" method="post" action="{{ route('admin.category.update',$category->id)}}" enctype="multipart/form-data" >
              @csrf
              <div class="row">
                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Title</label>
                      <div class="col-sm-9">
                        <input data-slugable-model="category" type="text" name="title" placeholder="Title" value="{{$category->title}}" placeholder="Title" class="form-control slug_maker" />
                      </div>
                    </div>
                  </div>

                  @foreach(\Helper::availableLanguages() as $lan)
                    <div class="col-md-6">
                     <div class="form-group row">
                        <label class="col-sm-3 col-form-label lan_title">Title ({{$lan->title}})</label>
                        <div class="col-sm-9">
                           <input data-slugable-model="category" type="text" name="{{'title__'.$lan->lang_code}}" value="{{ App\Models\CategoryLocalization::where('category_id', $category->id)->where('lang_code', $lan->lang_code)->first()->title ?? '' }}" placeholder="Title" class="form-control slug_maker" />
                        </div>
                     </div>
                  </div>
                  @endforeach

                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Slug</label>
                      <div class="col-sm-9">
                        <input data-slugable-model="category" type="text" name="slug" placeholder="Slug" value="{{$category->slug}}" placeholder="Slug" class="form-control slug_taker" />
                      </div>
                    </div>
                  </div>

                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Parent Category</label>
                      <div class="col-sm-9">

                      <select name="parent_id" class="selectpicker form-control" data-show-subtext="true" data-live-search="true">
                          <option value="0">Primary Category</option>
                          @foreach($allcategories as $c)
                          <option data-tokens="{{$c->title}}" value="{{$c->id}}"  @if($category->parent_id == $c->id) selected @endif >{{$c->title}}</option>
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
                            <label class="switch"><input name="hide_on_menu" type="checkbox"  @if($category->hide_on_menu == 1) checked @endif ><span class="slider round"></span>
                         </div>
                      </div>
                   </div>
                </div>


                
                <div class="col-md-3">
                    <div class="form-group row">
                      <label class="col-sm-8 col-form-label">Show Child Products</label>
                      <div class="col-sm-4">
                        <div class="form-check form-check-flat">
                            <label class="switch"><input name="show_child_products" type="checkbox"  @if($category->show_child_products == 1) checked @endif ><span class="slider round"></span>
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
                        <textarea type="text" name="description" placeholder="Description" class="form-control textEditor" >{!! $category->description !!}</textarea>
                      </div>
                    </div>
                  </div>

                  @foreach(\Helper::availableLanguages() as $lan)
                  <div class="col-md-12">
                     <div class="form-group row">
                        <label class="col-sm-2 col-form-label lan_title">Description ({{$lan->title}})</label>
                        <div class="col-sm-12">
                           <textarea type="text" name="{{'description__'.$lan->lang_code }}" placeholder="Description" class="form-control textEditor" >{!! App\Models\CategoryLocalization::where('category_id', $category->id)->where('lang_code', $lan->lang_code)->first()->description ?? '' !!}</textarea>
                        </div>
                     </div>
                  </div>
                  @endforeach

                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Meta Title</label>
                        <div class="col-sm-9">
                            <input type="text" name="meta_title" placeholder="Meta Title" value="{{$category->meta_title}}" class="form-control" />
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Meta Keyword</label>
                        <div class="col-sm-9">
                            <input type="text" name="meta_keyword" placeholder="Meta Keyword" value="{{$category->meta_keyword}}" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Meta Description</label>
                        <div class="col-sm-9">
                            <textarea type="text" name="meta_description" placeholder="Meta Description"  class="form-control">{{$category->meta_description}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Status</label>
                      <div class="col-sm-9">
                        <div class="form-check form-check-flat">
                            <label class="form-check-label">
                            <label class="switch"><input name="is_active" type="checkbox" @if($category->is_active == 1) checked @endif ><span class="slider round"></span></label>
                        </div>
                      </div>
                  </div>
                </div>

                </div>

                <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                        <label>Category Thumbnail</label><br>
                        <button  type="button" 
                          data-image-width="400" 
                          data-image-height="400"  
                          data-input-name="image" 
                          data-input-type="single" 
                          class="btn btn-success initConcaveMedia" >Select Image
                      </button> <small style="color:red;font-style:italic;margin-left: 10px;white-space: nowrap;">Image Size (width:400 X height:400)</small>
                      
                      @if($category->image)
                        <p class="selected_images_gallery">
                             <span>
                               <input type="hidden" value="{{$category->image}}" name="image">
                               <img src="{{'/'.$category->image}}"> 
                               <b data-file-url="{{$category->image}}" class="selected_image_remove">X</b>
                            </span>
                         </p>
                      @endif

                      </div>
                  </div>

                  <div class="col-md-6">
                      <div class="form-group">
                        <label>Category Banner</label><br>
                        <button  type="button" 
                        data-image-width="400" 
                        data-image-height="400"  
                        data-input-name="banner" 
                        data-input-type="single" 
                        class="btn btn-success initConcaveMedia" >Select Image
                    </button> <small style="color:red;font-style:italic;margin-left: 10px;white-space: nowrap;">Image Size (width:400 X height:400)</small>
                      
                      @if($category->banner)
                        <p class="selected_images_gallery">
                             <span>
                               <input type="hidden" value="{{$category->banner}}" name="banner">
                               <img src="{{'/'.$category->banner}}"> 
                               <b data-file-url="{{$category->banner}}" class="selected_image_remove">X</b>
                            </span>
                         </p>
                      @endif

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



                      <table id="listTable3" class="table table-striped">
                        <thead>
                          <tr>
                            <td style="width:10%">#</td>
                            <td style="width:20%" >Product ID</td>
                            <td style="width:50%" >Product Name</td>
                            <td style="width:20%">Status</td>
                          </tr>
                        </thead>
                        <tbody class="list">

                        @php $selectedProduct = []; @endphp
                        @foreach(
                          DB::TABLE('products')
                          ->select('id', 'category_id','title','is_active')
                          //->whereRaw("FIND_IN_SET($category->id,category_id)")
                          ->orderBy('id','asc')
                          ->where('is_deleted',0)
                          ->get() as $product)

                          @php $catIdArray = explode(',',$product->category_id);  @endphp

                          <tr>
                            <td style="width:10%;text-align: left;">
                              <input type="checkbox" name="products[]" class="change_product_status" value="{{$product->id}}" @if(in_array($category->id,$catIdArray)) checked @endif> 
                              
                              @if(in_array($category->id,$catIdArray))

                              @php $selectedProduct[] = $product->id; @endphp
                              
                              <span class="d-none">1</span> @else <span class="d-none">2</span> 
                              @endif


                            </td>
                            <td style="width:20%">{{$product->id}}</td>
                            <td style="width:50%">{{ substr($product->title, 0, 100) }}</td>
                            <td style="width:20%">
                              <span class="{{'badge badge_'.strtolower(Helper::getStatusName('default',$product->is_active))}}">
                                {{Helper::getStatusName('default',$product->is_active)}}
                              </span>
                            </td>
                          </tr>
                        @endforeach
                        </tbody>
                      </table>

                      <input type="hidden" name="remove_cat_product" id="remove_cat_products" value="{{implode(',',$selectedProduct)}}">

                      {{-- @if(in_array($category->id,$catIdArray))  data-order="1" @else data-order="2" @endif  --}}
                      {{-- @php $selectedProduct = ''; @endphp
                      <select name="products[]" data-size="10" class="selectpicker form-control" data-show-subtext="true" data-live-search="true" multiple>
                       @foreach(DB::TABLE('products')->select('id', 'category_id','title')->orderBy('title','asc')->where('is_active',1)->get() as $product)
                        <option   value="{{$product->id}}"
                          @php 
                          
                          $catIdArray = explode(',',$product->category_id); 
                          if(in_array($category->id,$catIdArray)){
                            echo 'selected=""';
                            $selectedProduct .= $product->id.',';
                          }  
                          
                          
                          @endphp
                          

                         >{{$product->title}}</option>
                       @endforeach
                      </select>

                       --}}


                    </div>
                  </div>
                </div>
                    
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <p class="text-right">
                          <button class="btn btn-success" name="save" type="submit">Update Category</button>
                      </p>
                    </div>
                  </div>
                </div>
              </form>