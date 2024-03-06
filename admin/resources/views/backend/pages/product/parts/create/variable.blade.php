<div id="variable_product" class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">


                        <div id="accordion">
                            <div class="product_card">
                                <div id="headingBasicInformation">
                                    <a class="btn btn-link" data-toggle="collapse" data-target="#BasicInformation"
                                        aria-expanded="true" aria-controls="collapseOne">
                                        Product Information
                                    </a>
                                </div>

                                <div id="BasicInformation" class="collapse show"
                                    aria-labelledby="headingBasicInformation" data-parent="#accordion">
                                    <div class="product_card_body">
                                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                            aria-orientation="vertical">
                                            <a class="nav-link active" data-toggle="pill" href="#General" role="tab"
                                                aria-selected="true">General</a>
                                            <a class="nav-link" data-toggle="pill" href="#Specification" role="tab"
                                                aria-selected="true">Specification</a>
                                            <a class="nav-link" data-toggle="pill" href="#Price" role="tab"
                                                aria-selected="false">Price & Costs</a>
                                            <a class="nav-link" data-toggle="pill" href="#Images" role="tab"
                                                aria-selected="false">Media</a>
                                            <a class="nav-link" data-toggle="pill" href="#SEO" role="tab"
                                                aria-selected="false">SEO</a>
                                            <a class="nav-link" data-toggle="pill" href="#Options" role="tab"
                                                aria-selected="false">Custom Options</a>
                                            <a class="nav-link" data-toggle="pill" href="#Taboption" role="tab"
                                                aria-selected="true">Tab Option</a>
                                            <a class="nav-link" data-toggle="pill" href="#AdditionalOptions"
                                                role="tab" aria-selected="false">Additional Options</a>
                                                @if(Auth::user()->getRoleNames() != '["seller"]')
                                            <a class="nav-link" data-toggle="pill" href="#Affiliates" role="tab"
                                                aria-selected="false">Affiliate</a>
                                                @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                    <div class="col-sm-9">
                        <form class="form-sample" method="post" action="{{ route('admin.product.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_type" value="variable">
                            <div class="tab-content" id="v-pills-tabContent">
                                <!-- General Section Starts -->
                                <div class="tab-pane fade show active" id="General" role="tabpanel">
                                    <p class="content_title">General</p>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Title<span
                                                class="required">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="title" value="{{ old('title') }}"
                                                data-slugable-model="product" class="form-control slug_maker" />
                                        </div>
                                    </div>

                                    @foreach (\Helper::availableLanguages() as $lan)
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label lan_title">Title
                                                ({{ $lan->title }})
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="{{ 'title__' . $lan->lang_code }}"
                                                    value="{{ old('title__' . $lan->lang_code) }}"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Barcode</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="barcode" value="{{ old('barcode') }}"
                                                class="form-control" />
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Weight <span
                                                class="required">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend bg-primary border-primary">
                                                    <span class="input-group-text bg-transparent text-white">
                                                        <select name="weight_unit" id="weight_unit">
                                                            <option @if (old('weight_unit') == 'gram') selected @endif
                                                                value="gram">gram</option>
                                                            <option @if (old('weight_unit') == 'kg') selected @endif
                                                                value="kg">kg</option>
                                                            <option @if (old('weight_unit') == 'ml') selected @endif
                                                                value="ml">ml</option>
                                                            <option @if (old('weight_unit') == 'l') selected @endif
                                                                value="l">liter</option>
                                                        </select>
                                                    </span>
                                                </div>
                                                <input type="text" name="weight" value="{{ old('weight') }}"
                                                    class="form-control" id="weight" />
                                                &nbsp;&nbsp;<div class="form-check form-check-flat font_small_11">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" name="is_approximate"
                                                            class="form-check-input"
                                                            @if (old('is_approximate')) checked="" @endif>Approximate
                                                        Weight<i class="input-helper"></i>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Is Grocery</label>
                                        <div class="col-sm-3">
                                            <div class="form-check form-check-flat">
                                                <label class="form-check-label">
                                                    <label class="switch"><input name="is_grocery" id="is_grocery"
                                                            type="checkbox"
                                                            @if (old('is_grocery')) checked="" @endif><span
                                                            class="slider round"></span></label>
                                            </div>
                                        </div>
                                        @if(Auth::user()->getRoleNames() != '["seller"]')
                                        <label class="col-sm-3 col-form-label">Is Promotional</label>
                                        <div class="col-sm-3">
                                            <div class="form-check form-check-flat">
                                                <label class="form-check-label">
                                                    <label class="switch"><input name="is_promotion" type="checkbox"
                                                            @if (old('is_promotion')) checked="" @endif><span
                                                            class="slider round"></span></label>
                                            </div>
                                        </div>
                                        @endif
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Short Description<span
                                                class="required">*</span></label>
                                        <div class="col-sm-9">
                                            <textarea type="text" name="short_description" class="form-control">{{ old('short_description') }}</textarea>
                                        </div>
                                    </div>

                                    @foreach (\Helper::availableLanguages() as $lan)
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label lan_title">Short Description
                                                ({{ $lan->title }})
                                            </label>
                                            <div class="col-sm-9">
                                                <textarea type="text" name="{{ 'short_description__' . $lan->lang_code }}" class="form-control">{{ old('short_description__' . $lan->lang_code) }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Description</label>
                                        <div class="col-sm-9">
                                            <textarea type="text" name="description" class="textEditor form-control">{{ old('description') }}</textarea>
                                        </div>
                                    </div>

                                    @foreach (\Helper::availableLanguages() as $lan)
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label lan_title">Description
                                                ({{ $lan->title }})
                                            </label>
                                            <div class="col-sm-9">
                                                <textarea type="text" name="{{ 'description__' . $lan->lang_code }}" class="textEditor form-control">{{ old('description__' . $lan->lang_code) }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Maximum Cart Qty</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="max_cart_qty"class="form-control"
                                                value="{{ old('max_cart_qty') ?? 5 }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Minimum Cart Value</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="minimum_cart_value"class="form-control"
                                                value="{{ old('minimum_cart_value') ?? 0 }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Brand</label>
                                        <div class="col-sm-9">
                                            <select name="brand_id" data-size="10" class="selectpicker form-control"
                                                data-show-subtext="true" data-live-search="true">
                                                <option value="0">Select Brand</option>
                                                @foreach ($brands as $brand)
                                                    <option data-tokens="{{ $brand->title }}"
                                                        @if (old('brand_id') == $brand->id) selected @endif
                                                        value="{{ $brand->id }}">{{ $brand->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Category</label>
                                        <div class="col-sm-9">
                                            <select name="category_id[]" data-size="10"
                                                class="selectpicker form-control" data-show-subtext="true"
                                                data-live-search="true" multiple>
                                                <option value="0">Primary Category</option>
                                                @foreach (App\Models\Category::orderBy('title', 'asc')->where('parent_id', 0)->where('is_deleted', 0)->get() as $category)
                                                    <option data-tokens="{{ $category->title }}"
                                                        value="{{ $category->id }}"
                                                        @if (!empty(old('category_id')) && in_array($category->id, old('category_id'))) selected @endif>
                                                        {{ $category->title }}</option>
                                                    @foreach (App\Models\Category::orderBy('title', 'asc')->where('parent_id', $category->id)->where('is_deleted', 0)->get() as $child)
                                                        <option data-tokens="{{ $child->title }}"
                                                            value="{{ $child->id }}"
                                                            @if (!empty(old('category_id')) && in_array($child->id, old('category_id'))) selected @endif>
                                                            {{ '¦–– ' . $child->title }}</option>
                                                        @foreach (App\Models\Category::orderBy('title', 'asc')->where('parent_id', $child->id)->where('is_deleted', 0)->get() as $child2)
                                                            <option data-tokens="{{ $child2->title }}"
                                                                value="{{ $child2->id }}"
                                                                @if (!empty(old('category_id')) && in_array($child2->id, old('category_id'))) selected @endif>
                                                                {{ '¦––––' . $child2->title }}</option>
                                                            @foreach (App\Models\Category::orderBy('title', 'asc')->where('parent_id', $child2->id)->where('is_deleted', 0)->get() as $child3)
                                                                <option data-tokens="{{ $child3->title }}"
                                                                    value="{{ $child3->id }}"
                                                                    @if (!empty(old('category_id')) && in_array($child3->id, old('category_id'))) selected @endif>
                                                                    {{ '¦––––--' . $child3->title }}</option>
                                                                @foreach (App\Models\Category::orderBy('title', 'asc')->where('parent_id', $child3->id)->where('is_deleted', 0)->get() as $child4)
                                                                    <option data-tokens="{{ $child4->title }}"
                                                                        value="{{ $child4->id }}"
                                                                        @if (!empty(old('category_id')) && in_array($child4->id, old('category_id'))) selected @endif>
                                                                        {{ '¦––––----' . $child4->title }}</option>
                                                                @endforeach
                                                            @endforeach
                                                        @endforeach
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    @if (Auth::user()->getRoleNames() == '["seller"]')
                                        
                                    @else
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Seller<span
                                                    class="required">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="selectpicker form-control" data-size="10"
                                                    name="seller_id" data-live-search="true">

                                                    @php
                                                        $vendors = App\Models\Admins::orderBy('name', 'asc')
                                                            ->with('shopinfo')
                                                            ->get();
                                                        $vendorArray = [];
                                                        foreach ($vendors as $vendor) {
                                                            if ($vendor->hasRole('seller')) {
                                                                $vendorArray[] = $vendor;
                                                            }
                                                        }
                                                    @endphp


                                                    @foreach ($vendorArray as $seller)
                                                        <option value="{{ $seller->id }}"
                                                            @if (old('seller_id') == $seller->id) selected @endif>
                                                            {{ $seller->shopinfo->name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Related Products</label>
                                        <div class="col-sm-9">
                                            <select name="related_products[]" data-max-options="20" data-size="10"
                                                class="selectpicker form-control" data-show-subtext="true"
                                                data-live-search="true" multiple>
                                                @foreach (App\Models\Product::orderBy('title', 'asc')->where('is_active', 1)->get() as $product)
                                                    <option value="{{ $product->id }}"
                                                        @if (!empty(old('related_products')) && in_array($product->id, old('related_products'))) selected @endif>
                                                        {{ $product->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Number For Shuffle </label>
                                        <div class="col-sm-9">
                                            <input type="number" min="1"
                                                value="{{ App\Models\Product::orderBy('shuffle_number', 'desc')->first()->shuffle_number ?? 1 + 1 }}"
                                                name="shuffle_number" class="form-control">
                                        </div>
                                    </div>
                                    @endif


                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Manage Stock</label>
                                        <div class="col-sm-9">
                                            <div class="form-check form-check-flat">
                                                <label class="form-check-label">
                                                    <label class="switch"><input name="manage_stock" type="checkbox"
                                                            checked><span class="slider round"></span></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Status</label>
                                        <div class="col-sm-9">
                                            <div class="form-check form-check-flat">
                                                <label class="form-check-label">
                                                    <label class="switch"><input name="is_active" type="checkbox"
                                                            checked><span class="slider round"></span></label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- General Section ends -->



                                <!-- Image Section Starts -->
                                <div class="tab-pane fade" id="Images" role="tabpanel">
                                    <p class="content_title">Images</p>
                                    <div class="form-group row ">
                                        <div class="col-sm-3"><label class="col-form-label">Defalut Image</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <button type="button" data-image-width="1000" data-image-height="1000"
                                                data-input-name="default_image" data-input-type="single"
                                                class="btn btn-success initConcaveMedia">Select File
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <div class="col-sm-3"><label class="col-form-label"> Gallery Image</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <button type="button" data-image-width="1000" data-image-height="1000"
                                                data-input-name="gallery_images" data-input-type="multiple"
                                                class="btn btn-success initConcaveMedia">Select File
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <div class="col-sm-3"><label class="col-form-label">Video Link</label></div>
                                        <div class="col-sm-9">
                                            <input type="text" name="video_link"class="form-control"
                                                value="{{ old('video_link') }}">
                                            <small> <b>Example:</b> https://www.youtube.com/embed/ZS2aX37I4HE</small>
                                        </div>
                                    </div>


                                </div>
                                <!-- Image Section Ends -->


                                <!-- SEO Section Starts -->
                                <div class="tab-pane fade" id="SEO" role="tabpanel">
                                    <p class="content_title">SEO</p>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Tags</label>
                                        <div class="col-sm-9">
                                            <input type="text" value="{{ old('tag') }}" name="tag"
                                                class="form-control tag_field" data-role="tagsinput" /><br>
                                            <small class="hint_text">Write something & press enter.</small>
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Slug</label>
                                        <div class="col-sm-9">
                                            <input type="text" data-slugable-model="product" name="slug"
                                                value="{{ old('slug') }}" class="form-control slug_taker"
                                                maxlength="2048" /><br>
                                            <small class="hint_text">The maximum length of url title is about 2048
                                                characters.</small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Meta Title</label>
                                        <div class="col-sm-9">

                                            <input type="text" name="meta_title" class="form-control"
                                                value="{{ old('meta_title') }}" maxlength="60"><br>
                                            <small class="hint_text">The ideal length of meta title is about 60
                                                characters.</small>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Meta Keyword</label>
                                        <div class="col-sm-9">
                                            <input type="text" value="{{ old('meta_keyword') }}"
                                                name="meta_keyword" class="form-control tag_field"
                                                data-role="tagsinput" /><br>
                                            <small class="hint_text">It is a good practice to have kewords less than
                                                10% of the total words of a page.</small>

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Meta Description</label>
                                        <div class="col-sm-9">
                                            <textarea name="meta_description" class="form-control" maxlength="160">{{ old('meta_description') }}</textarea><br>
                                            <small class="hint_text">The ideal length of meta description is about
                                                between 50 and 160 characters</small>

                                        </div>
                                    </div>
                                </div>
                                <!-- SEO Section Ends -->


                                <!-- Product Specification  Starts -->
                                <div class="tab-pane fade" id="Specification" role="tabpanel">
                                    <p class="content_title">Specification</p>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Attribute Set</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <select id="attribute_set" name="attribute_set_id"
                                                    class="form-control">
                                                    <option value="-1">-- Select Attribute Set --</option>
                                                    @foreach ($attributeSet as $attributeset)
                                                        <option value="{{ $attributeset->id }}"
                                                            @if (old('attribute_set_id') == $attributeset->id) selected @endif>
                                                            {{ $attributeset->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="dynamic_fields"></div>
                                </div>
                                <!-- Product Specification Ends -->



                                <!-- Tab Option Start-->
                                <div class="tab-pane fade" id="Taboption" role="tabpanel">
                                    <p class="content_title">Tab Option</p>
                                    <div id="add_tab_opiton" data-added-option="0" class="btn btn-warning mb-2">Add
                                        Option</div>
                                    <div id="add_here_tab_opiton"></div>
                                </div>
                                <!-- Tab Option End-->




                                <!-- Price Section starts -->
                                <div class="tab-pane fade" id="Price" role="tabpanel">
                                    <p class="content_title">Price</p>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Price<span
                                                class="required">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend bg-primary border-primary">
                                                    <span
                                                        class="input-group-text bg-transparent text-white">{{ Helper::getDefaultCurrency()->currency_symbol }}</span>
                                                </div>
                                                <input type="number" step="0.01" min="0" name="price"
                                                    value="{{ old('price') }}" class="form-control" />
                                            </div>
                                        </div>
                                    </div>



                                    {{-- <div class="form-group row">
                               <label class="col-sm-3 col-form-label">Loyalty Point</label>
                               <div class="col-sm-9">
                                  <div class="input-group">
                                     <div class="input-group-prepend bg-primary border-primary">
                                        <span class="input-group-text bg-transparent text-white">{{ Helper::getDefaultCurrency()->currency_symbol }}</span>
                                     </div>
                                     <input type="number" step="0.01" min="1" name="loyalty_point" value="{{ old('loyalty_point')}}"  class="form-control" />
                                  </div>
                               </div>
                            </div> --}}



                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Special Price</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend bg-primary border-primary">
                                                    <span
                                                        class="input-group-text bg-transparent text-white">{{ Helper::getDefaultCurrency()->currency_symbol }}</span>
                                                </div>
                                                <input type="number" step="0.01" min="0"
                                                    name="special_price" value="{{ old('special_price') }}"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Special Price Type</label>
                                        <div class="col-sm-9">
                                            <select name="special_price_type" class="form-control">
                                                <option @if (old('special_price_type') == 1) selected @endif
                                                    value="1">Fixed</option>
                                                <option @if (old('special_price_type') == 2) selected @endif
                                                    value="2">Percent</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Special Price Start</label>
                                        <div class="col-sm-9">
                                            <input type="datetime-local" name="special_price_start"
                                                value="{{ old('special_price_start') }}" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Special Price End</label>
                                        <div class="col-sm-9">
                                            <input type="datetime-local" name="special_price_end"
                                                class="form-control " value="{{ old('special_price_end') }}" />
                                        </div>
                                    </div>

                                    @if (Auth::user()->getRoleNames() == '["seller"]')
                                       
                                    @else
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label"> Cost</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <div class="input-group-prepend bg-primary border-primary">
                                                        <span
                                                            class="input-group-text bg-transparent text-white">{{ Helper::getDefaultCurrency()->currency_symbol }}</span>
                                                    </div>
                                                    <input type="number" step="0.01" 
                                                        name="product_cost" value="" class="form-control" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Packaging Cost</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <div class="input-group-prepend bg-primary border-primary">
                                                        <span
                                                            class="input-group-text bg-transparent text-white">{{ Helper::getDefaultCurrency()->currency_symbol }}</span>
                                                    </div>
                                                    <input type="number" step="0.01" min="0"
                                                        name="packaging_cost" value="{{ old('packaging_cost') }}"
                                                        class="form-control" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Security Charge</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <div class="input-group-prepend bg-primary border-primary">
                                                        <span
                                                            class="input-group-text bg-transparent text-white">{{ Helper::getDefaultCurrency()->currency_symbol }}</span>
                                                    </div>
                                                    <input type="number" step="0.01" min="0"
                                                        name="security_charge" value="{{ old('security_charge') }}"
                                                        class="form-control" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label"> Vat (%)</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <div class="input-group-prepend bg-primary border-primary">
                                                        
                                                    </div>
                                                    <input type="number" step="0.01" min="0"
                                                        name="vat" value="{{ old('vat') }}"
                                                        class="form-control" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Loyalty Point</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <div class="input-group-prepend bg-primary border-primary">
                                                       
                                                    </div>
                                                    <input type="number" step="0.01" min="0"
                                                        name="loyalty_point" value="{{ old('loyalty_point') }}"
                                                        class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    @endif



                                </div>
                                <!-- Price Section ends -->

                                <!-- Product Custom Options Starts -->
                                <div class="tab-pane fade" id="Options" role="tabpanel">
                                    <p class="content_title">Custom Options</p>
                                    <div class="row button_section">
                                        <div class="col-md-4">
                                            <p class="text-left"><a data-added-option="0" id="add_options"
                                                    class="btn btn-warning" href="javascript:void(0)">Add Option</a>
                                            </p>
                                        </div>
                                    </div>
                                    <div id="option_div"></div>
                                </div>
                                <!-- Product Custom Options Ends -->

                                <!-- Additional Options Starts -->
                                <div class="tab-pane fade" id="AdditionalOptions" role="tabpanel">
                                    <p class="content_title">Shipping Options</p>

                                    <div class="card">
                                        <div class="card-body">
                                            <p>Inside My District</p>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label">Allow Free Shipping</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="form-check form-check-flat">
                                                        <label class="form-check-label">
                                                            <label class="switch">
                                                                <input
                                                                    name="shipping_option[inside_origin][inside_allow_free_shipping]"
                                                                    type="checkbox"
                                                                    @if (isset(old('shipping_option')['inside_origin']['inside_allow_free_shipping'])) checked @endif><span
                                                                    class="slider round"></span>
                                                            </label>
                                                    </div>
                                                </div>
                                            </div>

                                            @if(Auth::user()->getRoleNames() != '["seller"]')
                                            <div
                                                class="form-group row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label">Standard Shipping Cost<span
                                                            style="color:red;">*</span></label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend bg-primary border-primary">
                                                            <span
                                                                class="input-group-text bg-transparent text-white">{{ Helper::getDefaultCurrency()->currency_symbol }}</span>
                                                        </div>
                                                        <input required type="number"
                                                            placeholder="Standard shipping cost for this product"
                                                            step="0.01"
                                                            name="shipping_option[inside_origin][inside_standard_shipping]"
                                                            value="{{ old('shipping_option[inside_origin][inside_standard_shipping]') ?? 0 }}"
                                                            class="form-control" id="inside_standard_shipping" />
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <label class="col-form-label"> <small> Package must be send in
                                                            4 to
                                                            7 days </small> </label>
                                                </div>

                                            </div>

                                            <div
                                                class="form-group row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label">Express Shipping Cost </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend bg-primary border-primary">
                                                            <span
                                                                class="input-group-text bg-transparent text-white">{{ Helper::getDefaultCurrency()->currency_symbol }}</span>
                                                        </div>
                                                        <input type="number"
                                                            placeholder="Express shipping cost for this product"
                                                            step="0.01"
                                                            name="shipping_option[inside_origin][inside_express_shipping]"
                                                            value="{{ old('shipping_option[inside_origin][inside_express_shipping]') ?? 0 }}"
                                                            id="inside_express_shipping" class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label class="col-form-label"><small>Package must be send in 1
                                                            to 3
                                                            days</small></label>
                                                </div>

                                            </div>
                                            @endif


                                        </div>
                                    </div> <br>
                                    <div class="card">
                                        <div class="card-body">
                                            <p>Outside of my District</p>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label">Allow Free Shipping</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="form-check form-check-flat">
                                                        <label class="form-check-label">
                                                            <label class="switch">
                                                                <input
                                                                    name="shipping_option[outside_origin][outside_allow_free_shipping]"
                                                                    type="checkbox"
                                                                    @if (isset(old('shipping_option')['outside_origin']['outside_allow_free_shipping'])) checked @endif><span
                                                                    class="slider round"></span>
                                                            </label>
                                                    </div>
                                                </div>
                                            </div>

                                            @if(Auth::user()->getRoleNames() != '["seller"]')
                                            <div
                                                class="form-group row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label">Standard Shipping Cost <span
                                                            style="color:red;">*</span></label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend bg-primary border-primary">
                                                            <span
                                                                class="input-group-text bg-transparent text-white">{{ Helper::getDefaultCurrency()->currency_symbol }}</span>
                                                        </div>
                                                        <input type="number"
                                                            placeholder="Standard shipping cost for this product"
                                                            step="0.01"
                                                            name="shipping_option[outside_origin][outside_standard_shipping]"
                                                            value="{{ old('shipping_option[outside_origin][outside_standard_shipping]') ?? 0 }}"
                                                            required class="form-control"
                                                            id="outside_standard_shipping" />
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <label class="col-form-label"> <small> Package must be send in
                                                            4 to
                                                            7 days </small> </label>
                                                </div>

                                            </div>

                                            <div
                                                class="form-group row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label">Express Shipping Cost </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend bg-primary border-primary">
                                                            <span
                                                                class="input-group-text bg-transparent text-white">{{ Helper::getDefaultCurrency()->currency_symbol }}</span>
                                                        </div>
                                                        <input type="number"
                                                            placeholder="Express shipping cost for this product"
                                                            step="0.01"
                                                            name="shipping_option[outside_origin][outside_express_shipping]"
                                                            value="{{ old('shipping_option[outside_origin][outside_express_shipping]') ?? 0 }}"
                                                            class="form-control" id="outside_express_shipping" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label class="col-form-label"><small>Package must be send in 1
                                                            to 3
                                                            days</small></label>
                                                </div>

                                            </div>
                                           
                                            @endif


                                        </div>
                                    </div> <br>


                                    <p class="content_title">Miscellaneous Information</p>

                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Allow Cash on Delivery</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="form-check form-check-flat">
                                                <label class="form-check-label">
                                                    <label class="switch">
                                                        <input name="miscellaneous_information[allow_cash_on_delivery]"
                                                            type="checkbox"
                                                            @if (isset(old('miscellaneous_information')['allow_cash_on_delivery'])) checked @endif><span
                                                            class="slider round"></span>
                                                    </label>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        $upazilas = DB::table('upazilas')->where('is_deleted',0)->where('status',1)->get();
                                    @endphp
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Delivery Location</label>
                                        </div>
                                        <div class="col-sm-9">
                                        <select class="selectpicker form-control" name="delivery_location[]" aria-label="Default select example" data-size="10" data-show-subtext="true" data-live-search="true"  multiple>
                                        @foreach ($upazilas as $upazila)
                                        <option value="{{ $upazila->id }}">{{ $upazila->title }}</option>
                                        @endforeach
                                        </select>
                                        </div>
                                        </div>

                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Warrenty Period (Days)</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" placeholder="Ex: 90 Days"
                                                    name="miscellaneous_information[warrenty_period]"
                                                    value="{{ old('warrenty_period') }}" class="form-control" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                    @if(Auth::user()->getRoleNames() != '["seller"]')
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Allow Change of Mind</label>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-check form-check-flat">
                                                <label class="form-check-label">
                                                    <label class="switch">
                                                        <input name="miscellaneous_information[allow_change_of_mind]"
                                                            type="checkbox" checked><span class="slider round"></span>
                                                    </label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Reviews Allowed</label>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-check form-check-flat">
                                                <label class="form-check-label">
                                                    <label class="switch">
                                                        <input name="allow_review" type="checkbox" checked><span
                                                            class="slider round"></span>
                                                    </label>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <label class="col-form-label">Required Moderation Review</label>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-check form-check-flat">
                                                <label class="form-check-label">
                                                    <label class="switch">
                                                        <input name="require_moderation" type="checkbox" checked><span
                                                            class="slider round"></span>
                                                    </label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Allowed Refund</label>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-check form-check-flat">
                                                <label class="form-check-label">
                                                    <label class="switch">
                                                        <input name="allow_refund" type="checkbox" checked><span
                                                            class="slider round"></span>
                                                    </label>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <label class="col-form-label">QC Approved?</label>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-check form-check-flat">
                                                <label class="form-check-label">
                                                    <label class="switch">
                                                        <input name="product_qc" type="checkbox"
                                                            @if (old('product_qc')) checked @endif><span
                                                            class="slider round"></span>
                                                    </label>
                                            </div>
                                        </div>
                                        @endif

                                    </div>
                                </div>
                                <!-- Additional Options Ends -->

                                <div class="tab-pane fade" id="Affiliates" role="tabpanel">
                                    <p class="content_title">Affiliate</p>

                                    <div class="card">
                                        <div class="card-body">
                                            <!-- <p>Inside My District</p>
                                            <hr>  -->
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Commission Amount</label>
                                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend bg-primary border-primary">
                                                            <span
                                                                class="input-group-text bg-transparent text-white">{{ Helper::getDefaultCurrency()->currency_symbol }}</span>
                                                        </div>
                                                        <input type="number" name="aff_commission_amount"
                                                            value="{{ old('aff_commission_amount') }}"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Commission Type</label>
                                                <div class="col-sm-9">
                                                    <select name="aff_commission_type" class="form-control">
                                                        <option @if (old('aff_commission_type') == 1) selected @endif
                                                            value="1">Fixed</option>
                                                        <option @if (old('aff_commission_type') == 2) selected @endif
                                                            value="2">Percent</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Commission From</label>
                                                <div class="col-sm-9">
                                                    <input type="datetime-local" name="aff_commission_from"
                                                        value="{{ old('aff_commission_from') }}"
                                                        class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Commission To</label>
                                                <div class="col-sm-9">
                                                    <input type="datetime-local" name="aff_commission_to"
                                                        class="form-control "
                                                        value="{{ old('aff_commission_to') }}" />
                                                </div>
                                            </div>


                                        </div>
                                    </div> <br>
                                </div>


                                <!-- <div class="tab-pane fade" id="Attributes" role="tabpanel" >Attributes</div>-->
                                <p class="text-right"> <button type="submit" class="btn btn-primary">Create
                                        Product</button> </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>