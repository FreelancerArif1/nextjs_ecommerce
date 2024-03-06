@extends('backend.layouts.master')
@section('title', 'Flash Deal Update - ' . config('concave.cnf_appname'))
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
                <span class="card-title">Dashboard > Marketing > Flash Deals > Update</span>
                <a class="btn btn-success float-right" href="<?php echo e(route('admin.flash_deal')); ?>">View Flash Deals List</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form class="form-sample" method="post"
                        action="{{ route('admin.flash_deal.update', $flash_deal->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Title</label>
                                    <div class="col-sm-9">
                                        <input type="text" data-slugable-model="flash_deal" name="title" id="title"
                                            value="{{ $flash_deal->title }}" placeholder="Title"
                                            class="form-control slug_maker" />
                                    </div>
                                </div>
                            </div>

                            @foreach (\Helper::availableLanguages() as $lan)
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label lan_title">Title ({{ $lan->title }})
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" name="{{ 'title__' . $lan->lang_code }}"
                                                value="{{ App\Models\FlashDealLocalization::where('flash_deal_id', $flash_deal->id)->where('lang_code', $lan->lang_code)->first()->title ?? '' }}"
                                                class="form-control" placeholder="Title" />
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Flash Deal Slug</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="slug" id="slug" placeholder="Flash Deal Slug"
                                            class="form-control slug_taker" value="{{ $flash_deal->slug }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Button Title</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="button_title" value="{{ $flash_deal->button_title }}"
                                            placeholder="Button Title" class="form-control" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Button Link</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="button_link" value="{{ $flash_deal->button_link }}"
                                            placeholder="Button Link" class="form-control" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Background Color</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="background_color"
                                            value="{{ $flash_deal->background_color }}" placeholder="Background Color"
                                            class="form-control" />
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Text Color</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="text_color" value="{{ $flash_deal->text_color }}"
                                            placeholder="Button Link" class="form-control" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Banner Image</label>
                                    <div class="col-sm-9">
                                        <button type="button" data-image-width="1280" data-image-height="720"
                                            data-input-name="banner" data-input-type="single"
                                            class="btn btn-success initConcaveMedia">Select Image
                                        </button>
                                        <br>
                                        <small><b>Note:</b> If grocery deal then  <b>Image Size (width:800 X height:450)</b></small>
                                        @if ($flash_deal->banner)
                                            <p class="selected_images_gallery">
                                                <span>
                                                    <input type="hidden" value="{{ $flash_deal->banner }}" name="banner">
                                                    <img src="{{ '/' . $flash_deal->banner }}">
                                                    <b data-file-url="{{ $flash_deal->banner }}"
                                                        class="selected_image_remove">X</b>
                                                </span>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Start Date</label>
                                    <div class="col-sm-9">
                                        <input type="datetime-local" name="from_date"
                                            value="{{ $flash_deal->start_date }}" placeholder="Start Date"
                                            class="form-control" />
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">End Date</label>
                                    <div class="col-sm-9">
                                        <input type="datetime-local" name="end_date" value="{{ $flash_deal->end_date }}"
                                            placeholder="End Date" class="form-control" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Meta Title</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="meta_title" value="{{ $flash_deal->meta_title }}"
                                            placeholder="Meta Title" class="form-control" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Meta Keyword</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="meta_keyword"
                                            value="{{ $flash_deal->meta_keyword }}" placeholder="Meta Keyword"
                                            class="tag form-control tag_field" data-role="tagsinput"> <br>
                                        <small class="hint_text">Write something & press enter.</small>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Meta Description</label>
                                    <div class="col-sm-9">
                                        <textarea type="text" name="meta_description" placeholder="Meta Description" class="form-control">{{ $flash_deal->meta_description }}</textarea>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Show Category Wise</label>
                                    <div class="col-sm-6">
                                        <div class="form-check form-check-flat">
                                            <label class="form-check-label">
                                                <label class="switch"><input name="show_category_wise" type="checkbox"
                                                        @if ($flash_deal->show_category_wise == 1) checked @endif><span
                                                        class="slider round"></span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                             <div class="col-md-2">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Is Grocery</label>
                                    <div class="col-sm-6">
                                        <div class="form-check form-check-flat">
                                            <label class="switch"><input name="is_grocery" type="checkbox"
                                                @if ($flash_deal->is_grocery == 1) checked @endif>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Status</label>
                                    <div class="col-sm-6">
                                        <div class="form-check form-check-flat">
                                                <label class="switch"><input name="status" type="checkbox"
                                                    @if ($flash_deal->status == 1) checked @endif><span
                                                        class="slider round"></span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Select Products</label>
                                    <div class="col-sm-10">
                                        @php
                                            $products = \App\Models\Product::where('is_active', 1)
                                                ->where('is_deleted', 0)
                                                ->where('product_qc', 1)
                                                ->get();
                                        @endphp
                                        <select name="product_ids[]" id="select_deal_products"
                                            data-selected-product="{{ $flash_deal->product_ids }}"
                                            data-show-subtext="true" data-live-search="true"
                                            class="selectpicker form-control" multiple required>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    @if (!empty($flash_deal->product_ids) && in_array($product->id, explode(',', $flash_deal->product_ids))) selected @endif>{{ $product->title }}
                                                </option>
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
                                        <input type="hidden" name="flash_deal_id" id="flas_deal_id"
                                            value="{{ $flash_deal->id }}" />
                                        <button class="btn btn-primary" name="save" type="submit">Update Flash
                                            Deal</button>
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
        jQuery(document).ready(function() {
            $(".tag_field").tagsinput('items');

            function getSelectedProducts() {
                // alert($('#select_deal_products').val());
                var ids = jQuery('#select_deal_products').attr('data-selected-product') + ',' + $(
                        '#select_deal_products')
                    .val();
                // alert(ids);
                if (ids != '') {
                    jQuery.ajax({
                        url: "/admin/flash-deals/get/products/" + ids + '?id=' + {{ $flash_deal->id }},
                        type: "get",
                        data: {},
                        success: function(response) {
                            jQuery('#all_selected_products_area').html(response);
                        }
                    });
                } else {
                    jQuery('#all_selected_products_area').html('');
                }
            }

            getSelectedProducts();

            jQuery(document).on('change', '#select_deal_products', function(e) {
                // alert($(this).val());
                // jQuery('#select_deal_products').attr('data-selected-product')
                // var values = $(this).val();
                // alert(explode(",", values));
                // $.each(values.split(","), function(i, e) {
                //     alert(value);
                //     // $("#select_deal_products option[value='" + e + "']").prop("selected", true);
                // });



                e.preventDefault();
                getSelectedProducts($(this).val());
            })



        });
    </script>
@endpush
