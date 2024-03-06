@extends('backend.layouts.master')
@section('title','Product Update - '.config('concave.cnf_appname'))
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
         <span class="card-title">Dashboard > Catalog > Products > 
            <span class="create_product_title">Update
               @if(isset($product->product_type)) {{ $product->product_type }} @endif
            Product</span></span>
         </span>
         <a class="btn btn-primary float-right" href="{{ route('admin.product')}}">View Product List</a>
      </div>
	</div>
</div>


<div class="product_form_section">
   @if($product->product_type == 'simple')
      @include('backend.pages.product.parts.edit.simple')
   @elseif($product->product_type == 'variable')
      @include('backend.pages.product.parts.edit.variable')
   @elseif($product->product_type == 'digital')
      @include('backend.pages.product.parts.edit.digital')
   @elseif($product->product_type == 'service')
      @include('backend.pages.product.parts.edit.service')
   @endif
</div>

 @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
      <script type="text/javascript">
         jQuery(document).ready(function () {
              $(".tag_field").tagsinput('items');
            // jQuery("#is_grocery").trigger("click");

            function checkShippingCost(){
               var type_for_shipping = '';
               if($('#is_grocery').is(":checked")) {
                  type_for_shipping = "grocery";
               }else{
                  type_for_shipping = "other";
               }
               // alert(type_for_shipping);
              var weight = $('#weight').val();
              // var weight = Math.ceil($('#weight').val());
              // if (weight > 9000 && weight < 10000) {
              //     weight = 10000;
              // }
              // if (weight > 8000 && weight < 9000) {
              //     weight = 9000;
              // }
              // if (weight > 7000 && weight < 8000) {
              //     weight = 8000;
              // }
              // if (weight > 6000 && weight < 7000) {
              //     weight = 7000;
              // }
              // if (weight > 5000 && weight < 6000) {
              //     weight = 6000;
              // }
              // if (weight > 4000 && weight < 5000) {
              //     weight = 5000;
              // }
              // if (weight > 3000 && weight < 4000) {
              //     weight = 4000;
              // }
              // if (weight > 2000 && weight < 3000) {
              //     weight = 3000;
              // }
              // if (weight > 1000 && weight < 2000) {
              //     weight = 2000;
              // }
              // if (weight > 0 && weight < 1000) {
              //     weight = 1000;
              // }

               // var weight = $('input[name=weight]').val();
               var weight_unit = $('#weight_unit').val();
               if(weight == ''){
                  weight = 0;
               }else{
                  weight = weight;
               }
               // alert(weight);
               $.ajax({
                  url: "{{  url('/admin/products/check/shipping/cost/') }}/"+type_for_shipping+"/"+weight+"/"+weight_unit,
                  type: "GET",
                  dataType: "json",
                  success: function (data) {
                     jQuery("#inside_standard_shipping").val(data.inside_origin_standard);
                     jQuery("#inside_express_shipping").val(data.inside_origin_express);
                     jQuery("#outside_standard_shipping").val(data.outside_origin_standard);
                     jQuery("#outside_express_shipping").val(data.outside_origin_express);
                     // console.log(data);
                  }
               })
            }

            // checkShippingCost();


            jQuery(document).on("change", "#is_grocery", function(){
              
               checkShippingCost();
               
            });

            jQuery(document).on("change", "#weight_unit", function(){
               
                  checkShippingCost();
               
            });

            jQuery(document).on("keyup", "#weight", function(){
               
                  checkShippingCost();
               
            });

         });
      </script>
   @endpush


@endsection