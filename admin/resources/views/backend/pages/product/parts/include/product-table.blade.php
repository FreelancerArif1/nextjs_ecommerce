<table  class="table">
   	<thead>
     	<tr>
	        <th>
	           <div class="form-check form-check-flat">
	              <label class="form-check-label">
	              <input id="select_all" type="checkbox"  class="form-check-input"><i class="input-helper"></i>
	              </label>
	           </div>
	        </th>
	        <th>Product</th>
	        <th>SKU</th>
	        <th>Category</th>
	        <th>Type</th>
	        <th>Price</th>
	        <th>Weight</th>
	        <th>Quantity</th>
	        <th>Status</th>
	        <th>Action</th>
	    </tr>
   	</thead>
   	<tbody id="product_body">
        @foreach ($products as $key=>$product)
        <tr>
           <td style="padding-left: 18px;">
              <div class="form-check form-check-flat">
                 <label class="form-check-label">
                 <input name="select_all[]" type="checkbox"  class="form-check-input checkbox_single_select" value="{{$product->id}}"><i class="input-helper"></i>
                 </label>
              </div>
           </td>
           <td>
              <div class="media">
                 <img class="list_img mr-3" src="{{'/'.$product->default_image}}" alt="">
                 <div class="media-body">
                    <p class="product_title">{{$product->title}}</p>
                    <a target="_blank" href="{{'/admin/seller/edit/'.$product->seller_id}}" class="seller_name text-decoration-none">{{ \DB::table('admins')->where('id',$product->seller_id)->first()->name ?? '' }}</a>
                 </div>
              </div>
           </td>
           <td>{{$product->sku ?? '-'}}</td>
           <td>{{ $product->category->title ?? '' }}</td>
           <td class="text-uppercase">
           <span class="{{'badge_'.$product->product_type}} plb">{{$product->product_type}}</span>
           </td>
           <td>{{ Helper::getDefaultCurrency()->currency_symbol.' '.$product->price }}</td>
           <td>{{ ($product->weight) ? $product->weight.$product->weight_unit : '-' }}</td>
           <td>{{ ($product->qty != 0) ? $product->qty : '-'  }}</td>
           <td>
           <label class="badge {{ 'badge_'.strtolower(Helper::getStatusName('default',$product->is_active))}}">
           {{ Helper::getStatusName('default',$product->is_active)}}
           </label>
           </td>
           <td class="text-center">  

            @if(Auth::user()->can('product.view'))
            <a class="text-success text-decoration-none productViewBtn" id="{{$product->id}}"  href="#"><i class="mdi mdi-eye"></i></a>
            @endif

            @if(Auth::user()->can('product.edit'))
            <a class="text-success text-decoration-none" href="{{ route('admin.product.edit',$product->id)}}"><i class="mdi mdi-pencil-box-outline"></i></a>
            @endif

            @if(Auth::user()->can('product.delete'))
              <a class="text-danger delete_btn text-decoration-none" data-url="{{ route('admin.product.delete',$product->id)}}" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>
              @endif
           </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div  class="pagination_center" style="display: table;">{!! $products->links() !!}</div>

     
