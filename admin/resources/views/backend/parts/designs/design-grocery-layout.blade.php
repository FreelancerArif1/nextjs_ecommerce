<p class="content_title">Grocery Layout</p>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Set Grocery Parent Category<span class="required">*</span></label>
    <div class="col-sm-9">
       	<select name="grocery_parent_category" class="selectpicker form-control"  data-show-subtext="true" data-live-search="true">
            @foreach($categories as $category)
                <option @if($category->id == Helper::getsettings('grocery_parent_category')) selected @endif value="{{$category->id}}">{{$category->title}}</option>
            @endforeach
        </select>       
    </div>
</div>



<div class="form-group row">
    <label class="col-sm-3 col-form-label">Shipping Validation Amount<span class="required">*</span></label>
    <div class="col-sm-9">
    	<input type="number" min="0" name="shipping_validation_amount" value="{{ Helper::getsettings('shipping_validation_amount') ?? 0 }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Shipping Minimum Amount<span class="required">*</span></label>
    <div class="col-sm-9">
    	<input type="number" min="0" name="shipping_minimum_amount" value="{{ Helper::getsettings('shipping_minimum_amount') ?? 0 }}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Shipping Maximum Amount<span class="required">*</span></label>
    <div class="col-sm-9">
    	<input type="number" min="0" name="shipping_maximum_amount" value="{{ Helper::getsettings('shipping_maximum_amount') ?? 0 }}" class="form-control">
    </div>
</div>

<div class="promotional_banner promotional_banner_1">
   	<p class="content_title">Ad banner - 1</p>

	<div class="form-group row">
		<label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
		<div class="col-sm-9">
			<div class="form-check form-check-flat">
				<label class="form-check-label">
					<input type="hidden" name="grocery_ad_banner_1_status" value="0">
					<input name="grocery_ad_banner_1_status" type="checkbox" class="form-check-input" value="1"
						@if (Helper::getsettings('grocery_ad_banner_1_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
						class="input-helper"></i></label>
			</div>
		</div>
	</div>

   	<div class="form-group row">
      	<label class="col-sm-3 col-form-label">Banner Image</label>
      	<div class="col-sm-9">
         	<button type="button"
	            data-image-width="450" 
	            data-image-height="61"  
	            data-input-name="grocery_ad_banner_1" 
	            data-input-type="single" 
	            class="btn btn-success initConcaveMedia" >Select Image
         	</button>
         	@if($grocery_ad_banner_1 = Helper::getsettings('grocery_ad_banner_1'))
	         	<p class="selected_images_gallery">
	            	<span>
		            	<input type="hidden" value="{{$grocery_ad_banner_1}}" name="grocery_ad_banner_1">
		            	<img src="{{'/'.$grocery_ad_banner_1}}"> 
		            	<b data-file-url="{{$grocery_ad_banner_1}}" class="selected_image_remove">X</b>
	            	</span>
	         	</p>
         	@endif
      	</div>
   	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">Link Type<span class="required">*</span></label>
		<div class="col-sm-9">
			<select name="grocery_ad_banner_1_link_type" class="selectpicker form-control">
				<option data-tokens="internal_url" value="internal_url"
					@if (Helper::getSettings('grocery_ad_banner_1_link_type') == 'internal_url') selected @endif>Internal URL</option>
				<option data-tokens="external_url" value="external_url"
					@if (Helper::getSettings('grocery_ad_banner_1_link_type') == 'external_url') selected @endif>External URL</option>
			</select>
		</div>
	</div>
   	<div class="form-group row">
      	<label class="col-sm-3 col-form-label">Link <span class="required">*</span></label>
      	<div class="col-sm-9">
         	<input type="text" name="grocery_ad_banner_1_link" class="form-control" value="{{Helper::getSettings('grocery_ad_banner_1_link')}}" placeholder="Enter banner link">
      	</div>
   	</div>
</div>


<div class="promotional_banner promotional_banner_1">
   	<p class="content_title">Ad banner - 2</p>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
		<div class="col-sm-9">
			<div class="form-check form-check-flat">
				<label class="form-check-label">
					<input type="hidden" name="grocery_ad_banner_2_status" value="0">
					<input name="grocery_ad_banner_2_status" type="checkbox" class="form-check-input" value="1"
						@if (Helper::getsettings('grocery_ad_banner_2_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
						class="input-helper"></i></label>
			</div>
		</div>
	</div>
   	<div class="form-group row">
      	<label class="col-sm-3 col-form-label">Banner Image</label>
      	<div class="col-sm-9">
         	<button type="button"
	            data-image-width="930" 
	            data-image-height="500"  
	            data-input-name="grocery_ad_banner_2" 
	            data-input-type="single" 
	            class="btn btn-success initConcaveMedia" >Select Image
         	</button>
         	@if($grocery_ad_banner_2 = Helper::getsettings('grocery_ad_banner_2'))
	         	<p class="selected_images_gallery">
	            	<span>
		            	<input type="hidden" value="{{$grocery_ad_banner_2}}" name="grocery_ad_banner_2">
		            	<img src="{{'/'.$grocery_ad_banner_2}}"> 
		            	<b data-file-url="{{$grocery_ad_banner_2}}" class="selected_image_remove">X</b>
	            	</span>
	         	</p>
         	@endif
      	</div>
   	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">Link Type<span class="required">*</span></label>
		<div class="col-sm-9">
			<select name="grocery_ad_banner_2_link_type" class="selectpicker form-control">
				<option data-tokens="internal_url" value="internal_url"
					@if (Helper::getSettings('grocery_ad_banner_2_link_type') == 'internal_url') selected @endif>Internal URL</option>
				<option data-tokens="external_url" value="external_url"
					@if (Helper::getSettings('grocery_ad_banner_2_link_type') == 'external_url') selected @endif>External URL</option>
			</select>
		</div>
	</div>
   	<div class="form-group row">
      	<label class="col-sm-3 col-form-label">Link <span class="required">*</span></label>
      	<div class="col-sm-9">
         	<input type="text" name="grocery_ad_banner_2_link" class="form-control" value="{{Helper::getSettings('grocery_ad_banner_2_link')}}" placeholder="Enter banner link">
      	</div>
   	</div>
</div>

<div class="promotional_banner promotional_banner_1">
   	<p class="content_title">Ad banner - 3</p>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
		<div class="col-sm-9">
			<div class="form-check form-check-flat">
				<label class="form-check-label">
					<input type="hidden" name="grocery_ad_banner_3_status" value="0">
					<input name="grocery_ad_banner_3_status" type="checkbox" class="form-check-input" value="1"
						@if (Helper::getsettings('grocery_ad_banner_3_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
						class="input-helper"></i></label>
			</div>
		</div>
	</div>
   	<div class="form-group row">
      	<label class="col-sm-3 col-form-label">Banner Image</label>
      	<div class="col-sm-9">
         	<button type="button"
	            data-image-width="450" 
	            data-image-height="230"  
	            data-input-name="grocery_ad_banner_3" 
	            data-input-type="single" 
	            class="btn btn-success initConcaveMedia" >Select Image
         	</button>
         	@if($grocery_ad_banner_3 = Helper::getsettings('grocery_ad_banner_3'))
	         	<p class="selected_images_gallery">
	            	<span>
		            	<input type="hidden" value="{{$grocery_ad_banner_3}}" name="grocery_ad_banner_3">
		            	<img src="{{'/'.$grocery_ad_banner_3}}"> 
		            	<b data-file-url="{{$grocery_ad_banner_3}}" class="selected_image_remove">X</b>
	            	</span>
	         	</p>
         	@endif
      	</div>
   	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">Link Type<span class="required">*</span></label>
		<div class="col-sm-9">
			<select name="grocery_ad_banner_3_link_type" class="selectpicker form-control">
				<option data-tokens="internal_url" value="internal_url"
					@if (Helper::getSettings('grocery_ad_banner_3_link_type') == 'internal_url') selected @endif>Internal URL</option>
				<option data-tokens="external_url" value="external_url"
					@if (Helper::getSettings('grocery_ad_banner_3_link_type') == 'external_url') selected @endif>External URL</option>
			</select>
		</div>
	</div>
   	<div class="form-group row">
      	<label class="col-sm-3 col-form-label">Link <span class="required">*</span></label>
      	<div class="col-sm-9">
         	<input type="text" name="grocery_ad_banner_3_link" class="form-control" value="{{Helper::getSettings('grocery_ad_banner_3_link')}}" placeholder="Enter banner link">
      	</div>
   	</div>
</div>

<div class="promotional_banner promotional_banner_1">
   	<p class="content_title">Ad banner - 4</p>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
		<div class="col-sm-9">
			<div class="form-check form-check-flat">
				<label class="form-check-label">
					<input type="hidden" name="grocery_ad_banner_4_status" value="0">
					<input name="grocery_ad_banner_4_status" type="checkbox" class="form-check-input" value="1"
						@if (Helper::getsettings('grocery_ad_banner_4_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
						class="input-helper"></i></label>
			</div>
		</div>
	</div>
   	<div class="form-group row">
      	<label class="col-sm-3 col-form-label">Banner Image</label>
      	<div class="col-sm-9">
         	<button type="button"
	            data-image-width="450" 
	            data-image-height="230"  
	            data-input-name="grocery_ad_banner_4" 
	            data-input-type="single" 
	            class="btn btn-success initConcaveMedia" >Select Image
         	</button>
         	@if($grocery_ad_banner_4 = Helper::getsettings('grocery_ad_banner_4'))
	         	<p class="selected_images_gallery">
	            	<span>
		            	<input type="hidden" value="{{$grocery_ad_banner_4}}" name="grocery_ad_banner_4">
		            	<img src="{{'/'.$grocery_ad_banner_4}}"> 
		            	<b data-file-url="{{$grocery_ad_banner_4}}" class="selected_image_remove">X</b>
	            	</span>
	         	</p>
         	@endif
      	</div>
   	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">Link Type<span class="required">*</span></label>
		<div class="col-sm-9">
			<select name="grocery_ad_banner_4_link_type" class="selectpicker form-control">
				<option data-tokens="internal_url" value="internal_url"
					@if (Helper::getSettings('grocery_ad_banner_4_link_type') == 'internal_url') selected @endif>Internal URL</option>
				<option data-tokens="external_url" value="external_url"
					@if (Helper::getSettings('grocery_ad_banner_4_link_type') == 'external_url') selected @endif>External URL</option>
			</select>
		</div>
	</div>
   	<div class="form-group row">
      	<label class="col-sm-3 col-form-label">Link <span class="required">*</span></label>
      	<div class="col-sm-9">
         	<input type="text" name="grocery_ad_banner_4_link" class="form-control" value="{{Helper::getSettings('grocery_ad_banner_4_link')}}" placeholder="Enter banner link">
      	</div>
   	</div>
</div>

<div class="promotional_banner promotional_banner_1">
   	<p class="content_title">Ad banner - 5</p>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
		<div class="col-sm-9">
			<div class="form-check form-check-flat">
				<label class="form-check-label">
					<input type="hidden" name="grocery_ad_banner_5_status" value="0">
					<input name="grocery_ad_banner_5_status" type="checkbox" class="form-check-input" value="1"
						@if (Helper::getsettings('grocery_ad_banner_5_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
						class="input-helper"></i></label>
			</div>
		</div>
	</div>
   	<div class="form-group row">
      	<label class="col-sm-3 col-form-label">Banner Image</label>
      	<div class="col-sm-9">
         	<button type="button"
	            data-image-width="1410" 
	            data-image-height="67"  
	            data-input-name="grocery_ad_banner_5" 
	            data-input-type="single" 
	            class="btn btn-success initConcaveMedia" >Select Image
         	</button>
         	@if($grocery_ad_banner_5 = Helper::getsettings('grocery_ad_banner_5'))
	         	<p class="selected_images_gallery">
	            	<span>
		            	<input type="hidden" value="{{$grocery_ad_banner_5}}" name="grocery_ad_banner_5">
		            	<img src="{{'/'.$grocery_ad_banner_5}}"> 
		            	<b data-file-url="{{$grocery_ad_banner_5}}" class="selected_image_remove">X</b>
	            	</span>
	         	</p>
         	@endif
      	</div>
   	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">Link Type<span class="required">*</span></label>
		<div class="col-sm-9">
			<select name="grocery_ad_banner_5_link_type" class="selectpicker form-control">
				<option data-tokens="internal_url" value="internal_url"
					@if (Helper::getSettings('grocery_ad_banner_5_link_type') == 'internal_url') selected @endif>Internal URL</option>
				<option data-tokens="external_url" value="external_url"
					@if (Helper::getSettings('grocery_ad_banner_5_link_type') == 'external_url') selected @endif>External URL</option>
			</select>
		</div>
	</div>
   	<div class="form-group row">
      	<label class="col-sm-3 col-form-label">Link <span class="required">*</span></label>
      	<div class="col-sm-9">
         	<input type="text" name="grocery_ad_banner_5_link" class="form-control" value="{{Helper::getSettings('grocery_ad_banner_5_link')}}" placeholder="Enter banner link">
      	</div>
   	</div>
</div>

<div class="promotional_banner promotional_banner_1">
   	<p class="content_title">Sponsor banner - 1</p>

	   <div class="form-group row">
			<label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
			<div class="col-sm-9">
				<div class="form-check form-check-flat">
					<label class="form-check-label">
						<input type="hidden" name="grocery_ad_banner_6_status" value="0">
						<input name="grocery_ad_banner_6_status" type="checkbox" class="form-check-input" value="1"
							@if (Helper::getsettings('grocery_ad_banner_6_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
							class="input-helper"></i></label>
				</div>
			</div>
		</div>




   	<div class="form-group row">
   		<label class="col-sm-3 col-form-label">Title</label>
   		<div class="col-sm-9">
   			<input type="text" name="grocery_ad_banner_6_title" class="form-control" value="{{Helper::getSettings('grocery_ad_banner_6_title')}}">
   		</div>
   	</div>
   	<div class="form-group row">
   		<label class="col-sm-3 col-form-label">Details</label>
   		<div class="col-sm-9">
   			<textarea class="form-control" name="grocery_ad_banner_6_details">{{Helper::getSettings('grocery_ad_banner_6_details')}}</textarea>
   		</div>
   	</div>
   	<div class="form-group row">
   		<label class="col-sm-3 col-form-label">Sponsored By</label>
   		<div class="col-sm-9">
   			<button type="button"
	            data-image-width="70" 
	            data-image-height="30"  
	            data-input-name="grocery_ad_banner_6_sponsor_by" 
	            data-input-type="single" 
	            class="btn btn-success initConcaveMedia" >Select Image
         	</button>
         	@if($grocery_ad_banner_6_sponsor_by = Helper::getsettings('grocery_ad_banner_6_sponsor_by'))
	         	<p class="selected_images_gallery">
	            	<span>
		            	<input type="hidden" value="{{$grocery_ad_banner_6_sponsor_by}}" name="grocery_ad_banner_6_sponsor_by">
		            	<img src="{{'/'.$grocery_ad_banner_6_sponsor_by}}"> 
		            	<b data-file-url="{{$grocery_ad_banner_6_sponsor_by}}" class="selected_image_remove">X</b>
	            	</span>
	         	</p>
         	@endif
   		</div>
   	</div>
   	<div class="form-group row">
      	<label class="col-sm-3 col-form-label">Banner Image</label>
      	<div class="col-sm-9">
         	<button type="button"
	            data-image-width="450" 
	            data-image-height="285"  
	            data-input-name="grocery_ad_banner_6" 
	            data-input-type="single" 
	            class="btn btn-success initConcaveMedia" >Select Image
         	</button>
         	@if($grocery_ad_banner_6 = Helper::getsettings('grocery_ad_banner_6'))
	         	<p class="selected_images_gallery">
	            	<span>
		            	<input type="hidden" value="{{$grocery_ad_banner_6}}" name="grocery_ad_banner_6">
		            	<img src="{{'/'.$grocery_ad_banner_6}}"> 
		            	<b data-file-url="{{$grocery_ad_banner_6}}" class="selected_image_remove">X</b>
	            	</span>
	         	</p>
         	@endif
      	</div>
   	</div>
   	<div class="form-group row">
      	<label class="col-sm-3 col-form-label">Link <span class="required">*</span></label>
      	<div class="col-sm-9">
         	<input type="text" name="grocery_ad_banner_6_link" class="form-control" value="{{Helper::getSettings('grocery_ad_banner_6_link')}}" placeholder="Enter banner link">
      	</div>
   	</div>
</div>

<div class="promotional_banner promotional_banner_1">
   	<p class="content_title">Sponsored banner - 2</p>

	<div class="form-group row">
		<label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
		<div class="col-sm-9">
			<div class="form-check form-check-flat">
				<label class="form-check-label">
					<input type="hidden" name="grocery_ad_banner_7_status" value="0">
					<input name="grocery_ad_banner_7_status" type="checkbox" class="form-check-input" value="1"
						@if (Helper::getsettings('grocery_ad_banner_7_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
						class="input-helper"></i></label>
			</div>
		</div>
	</div>



   	<div class="form-group row">
   		<label class="col-sm-3 col-form-label">Title</label>
   		<div class="col-sm-9">
   			<input type="text" name="grocery_ad_banner_7_title" class="form-control" value="{{Helper::getSettings('grocery_ad_banner_7_title')}}">
   		</div>
   	</div>
   	<div class="form-group row">
   		<label class="col-sm-3 col-form-label">Details</label>
   		<div class="col-sm-9">
   			<textarea class="form-control" name="grocery_ad_banner_7_details">{{Helper::getSettings('grocery_ad_banner_7_details')}}</textarea>
   		</div>
   	</div>
   	<div class="form-group row">
   		<label class="col-sm-3 col-form-label">Sponsored By</label>
   		<div class="col-sm-9">
   			<button type="button"
	            data-image-width="70" 
	            data-image-height="30"  
	            data-input-name="grocery_ad_banner_7_sponsor_by" 
	            data-input-type="single" 
	            class="btn btn-success initConcaveMedia" >Select Image
         	</button>
         	@if($grocery_ad_banner_7_sponsor_by = Helper::getsettings('grocery_ad_banner_7_sponsor_by'))
	         	<p class="selected_images_gallery">
	            	<span>
		            	<input type="hidden" value="{{$grocery_ad_banner_7_sponsor_by}}" name="grocery_ad_banner_7_sponsor_by">
		            	<img src="{{'/'.$grocery_ad_banner_7_sponsor_by}}"> 
		            	<b data-file-url="{{$grocery_ad_banner_7_sponsor_by}}" class="selected_image_remove">X</b>
	            	</span>
	         	</p>
         	@endif
   		</div>
   	</div>
   	<div class="form-group row">
      	<label class="col-sm-3 col-form-label">Banner Image</label>
      	<div class="col-sm-9">
         	<button type="button"
	            data-image-width="450" 
	            data-image-height="285"  
	            data-input-name="grocery_ad_banner_7" 
	            data-input-type="single" 
	            class="btn btn-success initConcaveMedia" >Select Image
         	</button>
         	@if($grocery_ad_banner_7 = Helper::getsettings('grocery_ad_banner_7'))
	         	<p class="selected_images_gallery">
	            	<span>
		            	<input type="hidden" value="{{$grocery_ad_banner_7}}" name="grocery_ad_banner_7">
		            	<img src="{{'/'.$grocery_ad_banner_7}}"> 
		            	<b data-file-url="{{$grocery_ad_banner_7}}" class="selected_image_remove">X</b>
	            	</span>
	         	</p>
         	@endif
      	</div>
   	</div>
   	<div class="form-group row">
      	<label class="col-sm-3 col-form-label">Link <span class="required">*</span></label>
      	<div class="col-sm-9">
         	<input type="text" name="grocery_ad_banner_7_link" class="form-control" value="{{Helper::getSettings('grocery_ad_banner_7_link')}}" placeholder="Enter banner link">
      	</div>
   	</div>
</div>

<div class="promotional_banner promotional_banner_1">
   	<p class="content_title">Sponsored banner - 3</p>

	<div class="form-group row">
		<label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
		<div class="col-sm-9">
			<div class="form-check form-check-flat">
				<label class="form-check-label">
					<input type="hidden" name="grocery_ad_banner_8_status" value="0">
					<input name="grocery_ad_banner_8_status" type="checkbox" class="form-check-input" value="1"
						@if (Helper::getsettings('grocery_ad_banner_8_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
						class="input-helper"></i></label>
			</div>
		</div>
	</div>

   	<div class="form-group row">
   		<label class="col-sm-3 col-form-label">Title</label>
   		<div class="col-sm-9">
   			<input type="text" name="grocery_ad_banner_8_title" class="form-control" value="{{Helper::getSettings('grocery_ad_banner_8_title')}}">
   		</div>
   	</div>
   	<div class="form-group row">
   		<label class="col-sm-3 col-form-label">Details</label>
   		<div class="col-sm-9">
   			<textarea class="form-control" name="grocery_ad_banner_8_details">{{Helper::getSettings('grocery_ad_banner_8_details')}}</textarea>
   		</div>
   	</div>
   	<div class="form-group row">
   		<label class="col-sm-3 col-form-label">Sponsored By</label>
   		<div class="col-sm-9">
   			<button type="button"
	            data-image-width="70" 
	            data-image-height="30"  
	            data-input-name="grocery_ad_banner_8_sponsor_by" 
	            data-input-type="single" 
	            class="btn btn-success initConcaveMedia" >Select Image
         	</button>
         	@if($grocery_ad_banner_8_sponsor_by = Helper::getsettings('grocery_ad_banner_8_sponsor_by'))
	         	<p class="selected_images_gallery">
	            	<span>
		            	<input type="hidden" value="{{$grocery_ad_banner_8_sponsor_by}}" name="grocery_ad_banner_8_sponsor_by">
		            	<img src="{{'/'.$grocery_ad_banner_8_sponsor_by}}"> 
		            	<b data-file-url="{{$grocery_ad_banner_8_sponsor_by}}" class="selected_image_remove">X</b>
	            	</span>
	         	</p>
         	@endif
   		</div>
   	</div>
   	<div class="form-group row">
      	<label class="col-sm-3 col-form-label">Banner Image</label>
      	<div class="col-sm-9">
         	<button type="button"
	            data-image-width="450" 
	            data-image-height="285"  
	            data-input-name="grocery_ad_banner_8" 
	            data-input-type="single" 
	            class="btn btn-success initConcaveMedia" >Select Image
         	</button>
         	@if($grocery_ad_banner_8 = Helper::getsettings('grocery_ad_banner_8'))
	         	<p class="selected_images_gallery">
	            	<span>
		            	<input type="hidden" value="{{$grocery_ad_banner_8}}" name="grocery_ad_banner_8">
		            	<img src="{{'/'.$grocery_ad_banner_8}}"> 
		            	<b data-file-url="{{$grocery_ad_banner_8}}" class="selected_image_remove">X</b>
	            	</span>
	         	</p>
         	@endif
      	</div>
   	</div>
   	<div class="form-group row">
      	<label class="col-sm-3 col-form-label">Link <span class="required">*</span></label>
      	<div class="col-sm-9">
         	<input type="text" name="grocery_ad_banner_8_link" class="form-control" value="{{Helper::getSettings('grocery_ad_banner_8_link')}}" placeholder="Enter banner link">
      	</div>
   	</div>
</div>