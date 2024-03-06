<div class="row">
	<div class="col-md-12">
		<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
		  	<div class="carousel-inner">
			    <div class="carousel-item active">
			      	<img class="d-block w-100" height="100%" src="/{{$blog->image}}" alt="First slide">
			    </div>
			    @foreach(explode(',', $blog->gallery_images) as $row)
				    <div class="carousel-item">
				      	<img class="d-block w-100" src="/{{$row}}" alt="Second slide">
				    </div>
			   	@endforeach
		  	</div>
		  	<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
		    	<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		    	<span class="sr-only">Previous</span>
		  	</a>
		  	<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
		    	<span class="carousel-control-next-icon" aria-hidden="true"></span>
		    	<span class="sr-only">Next</span>
		  	</a>
		</div>
	</div>

	<div class="col-md-12 mt-2">
		<h4>{{ $blog->title }}</h4>
		<p>{{ $blog->specification }}</p>
		{!! $blog->description !!}
	</div>

	<div class="col-md-12 mt-2">
		<h4>Related Products</h4>

		<div class="row">
			@foreach(explode(',', $blog->related_products) as $key => $value)
				@php 
					$product = App\Models\Product::where('id',$value)->first();
				@endphp
				@if($product)
					<div class="col-md-3">
						<img src="/{{ $product->default_image}}" height="200px" width="100%">
						<h6>{{$product->title}}</h6>
						<span>{{ \Helper::getDefaultCurrency()->currency_symbol }} {{$product->price}}</span>
					</div>
				@endif
			@endforeach
		</div>
	</div>
</div>