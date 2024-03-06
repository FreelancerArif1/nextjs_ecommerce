<div class="row">
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
                                            <a class="nav-link" data-toggle="pill" href="#Price" role="tab"
                                                aria-selected="false">Price & Costs</a>
                                            <a class="nav-link" data-toggle="pill" href="#Inventory" role="tab"
                                                aria-selected="false">Inventory</a>
                                            <a class="nav-link" data-toggle="pill" href="#Images" role="tab"
                                                aria-selected="false">Media</a>
                                            <a class="nav-link" data-toggle="pill" href="#SEO" role="tab"
                                                aria-selected="false">SEO</a>
                                            <a class="nav-link" data-toggle="pill" href="#Taboption" role="tab"
                                                aria-selected="true">Tab Option</a>
                                            <a class="nav-link" data-toggle="pill" href="#AdditionalOptions"
                                                role="tab" aria-selected="false">Additional Options</a>

                                                @if (Auth::user()->getRoleNames() != '["seller"]')
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
                        <form class="form-sample" id="product_form" method="post"
                            action="{{ route('admin.product.update', $product->id) }}">
                            @csrf
                            <input type="hidden" name="product_type" value="service">
                            <div class="tab-content" id="v-pills-tabContent">
                                <!-- General Section Starts -->
                                <div class="tab-pane fade show active" id="General" role="tabpanel">
                                    <p class="content_title">General</p>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Title<span
                                                class="required">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="title" value="{{ $product->title }}"
                                                class="form-control" />
                                        </div>
                                    </div>

                                    @foreach (\Helper::availableLanguages() as $lan)
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label lan_title">Title
                                                ({{ $lan->title }})
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="{{ 'title__' . $lan->lang_code }}"
                                                    value="{{ App\Models\ProductLocalization::where('product_id', $product->id)->where('lang_code', $lan->lang_code)->first()->title ?? '' }}"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Barcode</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="barcode" value="{{ $product->barcode }}"
                                                class="form-control" />
                                        </div>
                                    </div>

                                    @if (Auth::user()->getRoleNames() != '["seller"]')
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Is Promotion</label>
                                        <div class="col-sm-9">
                                            <div class="form-check form-check-flat">
                                                <label class="form-check-label">
                                                    <label class="switch"><input name="is_promotion" type="checkbox"
                                                            @if ($product->is_promotion == 1) checked="" @endif><span
                                                            class="slider round"></span></label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Short Description<span
                                                class="required">*</span></label>
                                        <div class="col-sm-9">
                                            <textarea type="text" name="short_description" class="form-control">{{ $product->short_description }}</textarea>
                                        </div>
                                    </div>

                                    @foreach (\Helper::availableLanguages() as $lan)
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label lan_title">Short Description
                                                ({{ $lan->title }})
                                            </label>
                                            <div class="col-sm-9">
                                                <textarea type="text" name="{{ 'short_description__' . $lan->lang_code }}" class="form-control">{{ App\Models\ProductLocalization::where('product_id', $product->id)->where('lang_code', $lan->lang_code)->first()->short_description ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach


                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Description</label>
                                        <div class="col-sm-9">
                                            <textarea type="text" name="description" class="textEditor form-control">{{ $product->description }}</textarea>
                                        </div>
                                    </div>

                                    @foreach (\Helper::availableLanguages() as $lan)
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label lan_title">Description
                                                ({{ $lan->title }})
                                            </label>
                                            <div class="col-sm-9">
                                                <textarea type="text" name="{{ 'description__' . $lan->lang_code }}" class="textEditor form-control">{!! App\Models\ProductLocalization::where('product_id', $product->id)->where('lang_code', $lan->lang_code)->first()->description ?? '' !!}</textarea>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Brand</label>
                                        <div class="col-sm-9">
                                            <select name="brand_id" data-size="10" class="selectpicker form-control"
                                                data-show-subtext="true" data-live-search="true">
                                                <option value="0">Select Brand</option>
                                                @foreach ($brands as $brand)
                                                    <option data-tokens="{{ $brand->title }}"
                                                        value="{{ $brand->id }}"
                                                        @if ($product->brand_id == $brand->id) selected @endif>
                                                        {{ $brand->title }}</option>
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
                                                        @foreach (explode(',', $product->category_id) as $catId)
                                            @if ($category->id == $catId) selected @endif @endforeach>
                                                        {{ $category->title }}</option>
                                                    @foreach (App\Models\Category::orderBy('title', 'asc')->where('parent_id', $category->id)->where('is_deleted', 0)->get() as $child)
                                                        <option data-tokens="{{ $child->title }}"
                                                            value="{{ $child->id }}"
                                                            @foreach (explode(',', $product->category_id) as $catId)
                                                  @if ($child->id == $catId) selected @endif @endforeach>
                                                            {{ '¦––' . $child->title }}</option>

                                                        @foreach (App\Models\Category::orderBy('title', 'asc')->where('parent_id', $child->id)->where('is_deleted', 0)->get() as $child2)
                                                            <option data-tokens="{{ $child2->title }}"
                                                                value="{{ $child2->id }}"
                                                                @foreach (explode(',', $product->category_id) as $catId)
                                                  @if ($child2->id == $catId) selected @endif @endforeach>
                                                                {{ '¦––––' . $child2->title }}</option>

                                                            @foreach (App\Models\Category::orderBy('title', 'asc')->where('parent_id', $child2->id)->where('is_deleted', 0)->get() as $child3)
                                                                <option data-tokens="{{ $child3->title }}"
                                                                    value="{{ $child3->id }}"
                                                                    @foreach (explode(',', $product->category_id) as $catId)
                                                  @if ($child3->id == $catId) selected @endif @endforeach>
                                                                    {{ '¦––––--' . $child3->title }}</option>
                                                                @foreach (App\Models\Category::orderBy('title', 'asc')->where('parent_id', $child3->id)->where('is_deleted', 0)->get() as $child4)
                                                                    <option data-tokens="{{ $child4->title }}"
                                                                        value="{{ $child4->id }}"
                                                                        @foreach (explode(',', $product->category_id) as $catId)
                                                      @if ($child4->id == $catId) selected @endif @endforeach>
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
                                                        <option @if ($product->seller_id == $seller->id) selected @endif
                                                            value="{{ $seller->id }}">
                                                            {{ $seller->shopinfo->name ?? '' }}
                                                        </option>
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
                                                @foreach (App\Models\Product::orderBy('title', 'asc')->where('is_active', 1)->get() as $row)
                                                    <option value="{{ $row->id }}"
                                                        @foreach (explode(',', $product->related_products) as $rp)
                                          @if ($row->id == $rp) selected="" @endif @endforeach>
                                                        {{ $row->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Number For Shuffle </label>
                                        <div class="col-sm-9">
                                            <input type="number" min="1"
                                                value="{{ $product->shuffle_number ?? 1 }}" name="shuffle_number"
                                                class="form-control">
                                        </div>
                                    </div>
                                    @endif


                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Status</label>
                                        <div class="col-sm-9">
                                            <div class="form-check form-check-flat">
                                                <label class="form-check-label">
                                                    <label class="switch"><input name="is_active" type="checkbox"
                                                            @if ($product->is_active == 1) checked="" @endif><span
                                                            class="slider round"></span></label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- General Section ends -->

                                <!-- Tab Option Start -->
                                @php
                                    $optionsCount = 0;
                                    if (
                                        !is_null(
                                            $options = App\Models\ProductMeta::where('product_id', $product->id)
                                                ->where('meta_key', 'tab_option')
                                                ->first(),
                                        )
                                    ) {
                                        $op = unserialize($options->meta_value);
                                        $optionsCount = count($op);
                                    }
                                @endphp
                                <div class="tab-pane fade" id="Taboption" role="tabpanel">
                                    <p class="content_title">Tab Options</p>
                                    <div class="row button_section">
                                        <div class="col-md-4">
                                            <p class="text-left"><a data-added-option="{{ $optionsCount }}"
                                                    id="add_tab_opiton" class="btn btn-warning"
                                                    href="javascript:void(0)">Add New Tab</a></p>
                                        </div>
                                    </div>
                                    <div id="add_here_tab_opiton">
                                        @if (!is_null(
                                            $all_options = App\Models\ProductMeta::where('product_id', $product->id)->where('meta_key', 'tab_option')->first()))
                                            @php
                                                $options = unserialize($all_options->meta_value);
                                            @endphp
                                            @foreach ($options as $option)
                                                <div class="card mb-2">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group row">
                                                                    <label>Title</label>
                                                                    <input type="text"
                                                                        name="tab_option[{{ $loop->index }}][tab_option_title]"
                                                                        value="{{ $option['tab_option_title'] }} "
                                                                        class="form-control option_title" />
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label>Description</label>
                                                                    <textarea type="text" name="tab_option[{{ $loop->index }}][tab_option_description]" class="form-control"
                                                                        id="tab_option_description">{{ $option['tab_option_description'] }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <!-- Tab option End -->


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
                                                <input type="number" step="0.01" name="price"
                                                    value="{{ $product->price }}" class="form-control" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Special Price</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend bg-primary border-primary">
                                                    <span
                                                        class="input-group-text bg-transparent text-white">{{ Helper::getDefaultCurrency()->currency_symbol }}</span>
                                                </div>
                                                <input type="number" step="0.01" name="special_price"
                                                    value="{{ $product->special_price }}" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Special Price Type</label>
                                        <div class="col-sm-9">
                                            <select name="special_price_type" class="form-control">
                                                <option value="1"
                                                    @if ($product->special_price_type == 1) selected @endif>Fixed</option>
                                                <option value="2"
                                                    @if ($product->special_price_type == 2) selected @endif>Percent</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Special Price Start</label>
                                        <div class="col-sm-9">
                                            <input type="datetime-local" name="special_price_start"
                                                value="{{ date('Y-m-d\TH:i:s', strtotime($product->special_price_start)) }}"
                                                class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Special Price End</label>
                                        <div class="col-sm-9">
                                            <input type="datetime-local" name="special_price_end"
                                                class="form-control "
                                                value="{{ date('Y-m-d\TH:i:s', strtotime($product->special_price_end)) }}" />
                                        </div>
                                    </div>

                                    @if (Auth::user()->getRoleNames() == '["seller"]')
                                
                                    @else
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Cost</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <div class="input-group-prepend bg-primary border-primary">
                                                        <span
                                                            class="input-group-text bg-transparent text-white">{{ Helper::getDefaultCurrency()->currency_symbol }}</span>
                                                    </div>
                                                    <input type="number" step="0.01" 
                                                        name="product_cost" value="{{ $product->product_cost }}"
                                                        class="form-control" />
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
                                                        name="packaging_cost" value="{{ $product->packaging_cost }}"
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
                                                        name="security_charge"
                                                        value="{{ $product->security_charge }}"
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
                                                        name="vat" value="{{ $product->vat }}"
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
                                                    <input type="number" min="0" name="loyalty_point"
                                                        value="{{ $product->loyalty_point }}" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                                <!-- Price Section ends -->
                                <!-- Inventory Section Starts -->
                                <div class="tab-pane fade" id="Inventory" role="tabpanel">
                                    <p class="content_title">Inventory</p>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">SKU<span
                                                class="required">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="sku"
                                                value="{{ $product->sku ?? substr(str_shuffle('0123456789abcdefghijklmnopqrstvwxyz'), 0, 6) }}"
                                                class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Inventory Management</label>
                                        <div class="col-sm-9">
                                            <select name="manage_stock" class="form-control">
                                                <option value="0"
                                                    @if ($product->manage_stock == 0) selected @endif>Don't Track
                                                    Inventory</option>
                                                <option value="1"
                                                    @if ($product->manage_stock == 1) selected @endif>Track Inventory
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Quantity</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="qty"class="form-control"
                                                value="{{ $product->qty }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Maximum Cart Qty</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="max_cart_qty"class="form-control"
                                                value="{{ $product->max_cart_qty ?? 5 }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Minimum Cart Value</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="minimum_cart_value"class="form-control"
                                                value="{{ $product->minimum_cart_value ?? 0 }}">
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Stock Availability</label>
                                        <div class="col-sm-9">
                                            <select name="in_stock" class="form-control">
                                                <option value="1"
                                                    @if ($product->in_stock == 1) selected @endif>In Stock
                                                </option>
                                                <option value="0"
                                                    @if ($product->in_stock == 0) selected @endif>Out of Stock
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Inventory Section Ends -->

                                <!-- Image Section Starts -->
                                <div class="tab-pane fade" id="Images" role="tabpanel">
                                    <p class="content_title">Images</p>
                                    <div class="form-group row ">
                                        <div class="col-sm-3"><label class="col-form-label">Defalut Image</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <button type="button" data-image-width="1000" data-image-height="1000"
                                                data-input-name="default_image" data-input-type="single"
                                                class="btn btn-success initConcaveMedia">Select File</button>
                                            @if ($product->default_image)
                                                <p class="selected_images_gallery">
                                                    <span>
                                                        <input type="hidden" value="{{ $product->default_image }}"
                                                            name="default_image">
                                                        <img src="{{ '/' . $product->default_image }}">
                                                        <b data-file-url="{{ $product->default_image }}"
                                                            class="selected_image_remove">X</b>
                                                    </span>
                                                </p>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <div class="col-sm-3"><label class="col-form-label">Gallery Image</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <button type="button" data-image-width="1000" data-image-height="1000"
                                                data-input-name="gallery_images" data-input-type="multiple"
                                                class="btn btn-success initConcaveMedia">Select File</button>

                                            <p class="selected_images_gallery">
                                                @foreach (explode(',', $product->gallery_images) as $img)
                                                    @if ($img)
                                                        <span>
                                                            <input type="hidden" value="{{ $img }}"
                                                                name="gallery_images[]">
                                                            <img src="{{ '/' . $img }}"> <b
                                                                data-file-url="{{ $img }}"
                                                                class="selected_image_remove">X</b>
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </p>

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <div class="col-sm-3"><label class="col-form-label">Video Link</label></div>
                                        <div class="col-sm-9">
                                            <input type="text" name="video_link"class="form-control"
                                                value="{{ $product->video_link }}">
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
                                            <input type="text"
                                                value="{{ $product->tag ?? 'kholabazar, nurtaj product' }}"
                                                id="" name="tag" class="form-control tag_field"
                                                data-role="tagsinput" /><br>
                                            <small class="hint_text">Write something & press enter.</small>
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Slug</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="slug" value="{{ $product->slug }}"
                                                class="form-control" maxlength="2048" readonly /><br>
                                            <small class="hint_text">The maximum length of url title is about 2048
                                                characters.</small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Meta Title</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="meta_title" class="form-control"
                                                value="{{ $product->meta_title }}" maxlength="60"><br>
                                            <small class="hint_text">The ideal length of meta title is about 60
                                                characters.</small>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Meta Keyword</label>
                                        <div class="col-sm-9">

                                            <input type="text"
                                                value="{{ App\Models\ProductMeta::where('product_id', $product->id)->where('meta_key', 'meta_keyword')->first()->meta_value ?? '' }}"
                                                name="meta_keyword" class="form-control tag_field"
                                                data-role="tagsinput" /><br>
                                            <small class="hint_text">It is a good practice to have kewords less than
                                                10% of the total words of a page.</small>

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Meta Description</label>
                                        <div class="col-sm-9">
                                            <textarea name="meta_description" class="form-control" maxlength="160">{{ App\Models\ProductMeta::where('product_id', $product->id)->where('meta_key', 'meta_description')->first()->meta_value ?? '' }}</textarea><br>
                                            <small class="hint_text">The ideal length of meta description is about
                                                between 50 and 160 characters</small>

                                        </div>
                                    </div>
                                </div>
                                <!-- SEO Section Ends -->




                                @php
                                    $miscellaneous_information = App\Models\ProductMeta::where('product_id', $product->id)
                                        ->where('meta_key', 'product_miscellaneous_information')
                                        ->first();
                                    if ($miscellaneous_information) {
                                        $miscellaneous_information = unserialize($miscellaneous_information->meta_value);
                                    }
                                @endphp

                                <!-- Additional Options Starts -->
                                <div class="tab-pane fade" id="AdditionalOptions" role="tabpanel">
                                    <p class="content_title">Shipping Options</p>

                                    <div class="card">
                                        <div class="card-body">
                                            <p class="content_title">Miscellaneous Information</p>
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
                                        <option value="{{ $upazila->id }}" @foreach (explode(',', $product->delivery_location) as $location)
                                                  @if ($upazila->id == $location) selected @endif @endforeach>{{ $upazila->title }}</option>
                                        @endforeach
                                        </select>
                                        </div>
                                        </div>
                                            <div class="form-group row">
                                            @if (Auth::user()->getRoleNames() != '["seller"]')
                                                <div class="col-sm-3">
                                                    <label class="col-form-label">Allow Change of Mind</label>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-check form-check-flat">
                                                        <label class="form-check-label">
                                                            <label class="switch">
                                                                <input
                                                                    name="miscellaneous_information[allow_change_of_mind]"
                                                                    type="checkbox"
                                                                    @if (isset($miscellaneous_information['allow_change_of_mind']) &&
                                                                        $miscellaneous_information['allow_change_of_mind'] == 'on') checked @endif><span
                                                                    class="slider round"></span>
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
                                                                <input name="allow_review" type="checkbox"
                                                                    @if ($product->allow_review == 1) checked="" @endif><span
                                                                    class="slider round"></span>
                                                            </label>
                                                    </div>
                                                </div>

                                                <div
                                                    class="col-sm-3 @if (Auth::user()->getRoleNames() == '["seller"]') d-none @endif">
                                                    <label class="col-form-label">Required Moderation Review</label>
                                                </div>
                                                <div
                                                    class="col-sm-3 @if (Auth::user()->getRoleNames() == '["seller"]') d-none @endif">
                                                    <div class="form-check form-check-flat">
                                                        <label class="form-check-label">
                                                            <label class="switch">
                                                                <input name="require_moderation" type="checkbox"
                                                                    @if ($product->require_moderation == 1) checked="" @endif><span
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
                                                                <input name="allow_refund" type="checkbox"
                                                                    @if ($product->allow_refund == 1) checked="" @endif><span
                                                                    class="slider round"></span>
                                                            </label>
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-sm-3">
                                                    <label class="col-form-label">QC Approved?</label>
                                                </div>
                                                <div
                                                    class="col-sm-3">
                                                    <div class="form-check form-check-flat">
                                                        <label class="form-check-label">
                                                            <label class="switch">
                                                                <input name="product_qc" type="checkbox"
                                                                    @if ($product->product_qc == 1) checked @endif><span
                                                                    class="slider round"></span>
                                                            </label>
                                                    </div>
                                                </div>
                                                @endif

                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- Additional Options Ends -->

                                <div class="tab-pane fade" id="Affiliates" role="tabpanel">
                                    <p class="content_title">Affiliate</p>

                                    <div class="card">
                                        <div class="card-body">
                                             <!-- <p>Inside My District</p>
                                            <hr> -->
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Commission Amount</label>
                                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend bg-primary border-primary">
                                                            <span
                                                                class="input-group-text bg-transparent text-white">{{ Helper::getDefaultCurrency()->currency_symbol }}</span>
                                                        </div>
                                                        <input type="number" name="aff_commission_amount"
                                                            {{-- value="{{ old('aff_commission_amount') }}" --}}
                                                            value="{{ $product->aff_commission_amount }}"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Commission Type</label>
                                                <div class="col-sm-9">
                                                    <select name="aff_commission_type" class="form-control">
                                                        <option value="1"
                                                            @if ($product->aff_commission_type == 1) selected @endif>Fixed
                                                        </option>
                                                        <option value="2"
                                                            @if ($product->aff_commission_type == 2) selected @endif>Percent
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Commission From</label>
                                                <div class="col-sm-9">
                                                    <input type="datetime-local" name="aff_commission_from"
                                                        value="{{ date('Y-m-d\TH:i:s', strtotime($product->aff_commission_from)) }}"
                                                        class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Commission To</label>
                                                <div class="col-sm-9">
                                                    <input type="datetime-local" name="aff_commission_to"
                                                        class="form-control "
                                                        value="{{ date('Y-m-d\TH:i:s', strtotime($product->aff_commission_to)) }}" />
                                                </div>
                                            </div>


                                        </div>
                                    </div> <br>
                                </div>


                                <p class="text-right submit_button mt-2"> <button type="submit"
                                        class="btn btn-primary">Update Product</button> </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
