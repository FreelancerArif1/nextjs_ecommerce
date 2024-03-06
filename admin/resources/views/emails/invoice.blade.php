<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
</head>
<body>
    
<div>
@php 




if($data->is_pickpoint == 1){
        $address = \DB::table('pick_points')->where('id',$data->address_id)->first();
        $shipping_union = \DB::table('unions')->where('id',$address->union_id)->first();
        $shipping_thana = \DB::table('upazilas')->where('id',$address->upazila_id)->first();
        $shipping_district = \DB::table('districts')->where('id',$address->district_id)->first();
        $shipping_division = \DB::table('divisions')->where('id',$address->division_id)->first();
}else{
    $address = \DB::table('addresses')->where('id',$data->address_id)->first();
    $shipping_union = \DB::table('unions')->where('id',$address->shipping_union)->first();
    $shipping_thana = \DB::table('upazilas')->where('id',$address->shipping_thana)->first();
    $shipping_district = \DB::table('districts')->where('id',$address->shipping_district)->first();
    $shipping_division = \DB::table('divisions')->where('id',$address->shipping_division)->first();
}

 

@endphp

 
<h2 style="text-align:center">Thanks for your order</h2>
<p style="text-align:center">You'll receive an email when your items are shipped. If you have any questions, Call us {{\Helper::getsettings('phone_number')}}</p><br><br><br>
    
<section style="background: #f1f1f1; max-width: 750px;margin: 0 auto; padding: 20px;">
  <table style="width: 650px;margin:0px auto">
    <tbody>
        <tr>
             <td>
                <div style="display: flex; -ms-flex-wrap: wrap;flex-wrap: wrap;padding: 0!important;">
                    <div  style="display: inline-block; margin-right: auto;">
                        <div style="font-size: 12px;padding-right: 0px;">
                            <strong style="text-transform:uppercase;font-size: 14px;">Delivery Information</strong><br>
                            <span style="font-weight: 600;">  Address: </span> 
                            @if($data->is_pickpoint == 1)
                            {{$address->title}}, {{$address->address}}, 
                                 {{$shipping_union->title ?? ''}}, {{$shipping_thana->title ?? ''}},{{$shipping_district->title ?? ''}},{{$shipping_division->title ?? '' }} 
                                <br>
                                <span style="font-weight: 600;">  HP: </span>  {{$address->phone}}
                                <br>
                                <span style="font-weight: 600;">  E-mail: </span>  {{$address->email}}
                            @else
                                {{$address->shipping_address}}, 
                                 {{$shipping_union->title ?? ''}}, {{$shipping_thana->title ?? ''}},{{$shipping_district->title ?? ''}},{{$shipping_division->title ?? '' }} 
                                <br>
                                <span style="font-weight: 600;"> Name: </span>  {{$address->shipping_first_name.' '.$address->shipping_last_name}}
                                <br>
                                <span style="font-weight: 600;">  HP: </span>  {{$address->shipping_phone}}
                                <br>
                                <span style="font-weight: 600;">  E-mail: </span>  {{$address->shipping_email}}
                            @endif
                            
                           
                        </div>
                    </div>
                    <div  style="display: inline-block; margin-left: auto;">
                            <div style="font-size:12px; padding: 0;margin: 0;width: 280px;">
                                <img src="{{ asset('uploads/images/'.config('concave.cnf_logo'))}}"  alt="" width="70" />
                                <br>
                                <span style="font-weight: 600;"></span> {{ config('concave.cnf_address') }} 
                                <br>
                                <span style="font-weight: 600;">  HP: </span> {{ config('concave.cnf_phone') }}
                                <br>
                                <span style="font-weight: 600;">  E-mail: </span> {{ config('concave.cnf_email') }}
                            </div>
                    </div>
                </div>
            </td>
            

            
        </tr>
     </tbody>
  </table>
  
  <hr>
  
  
  <table style="width: 650px;margin:50px auto">
    <tbody>
        <tr>
            <td style="width: 650px;">
                <div style="display: flex; -ms-flex-wrap: wrap;flex-wrap: wrap;padding: 0!important;">
                    <div  style="display: inline-block; margin-right: auto;"> <span style="text-transform: uppercase;font-size: 20px;font-weight: 600;">Invoice</span>  </div>
                    <div  style="display: inline-block; margin-left: auto;">  <span style="text-transform: uppercase;font-size: 14px;font-weight: 600;">  Order ID: KB{{ date("y", strtotime($data->created_at)) }}{{$data->id}} </span> </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>


<table style="width: 650px;margin:30px auto">
   <tbody>
      <tr style="padding: 5px;">
          <td style="width: 70px; text-align: left;text-transform: uppercase;font-size: 12px;"><strong>  SL</strong></td>
          <td style="width: 400px; text-align: left;text-transform: uppercase;font-size: 12px;"><strong> Item</strong></td>
          <td style="width: 200px; text-align: left;text-transform: uppercase;font-size: 12px;"><strong> Qty</strong></td>
          <td style="width: 200px; text-align: left;text-transform: uppercase;font-size: 12px;"><strong> Price</strong></td>
          <td style="width: 220px; text-align: left;text-transform: uppercase;font-size: 12px;"><strong> Shipping Cost</strong></td>
          <td style="width: 120px; text-align: right;text-transform: uppercase;font-size: 12px;"><strong>  Sub Total</strong></td>
      </tr>
      @php $subtotal = 0; $shipping_cost =0; @endphp
    @foreach($data->order_details as $item)
      @php
         $subtotal += ($item->product_qty*$item->price); 
         $shipping_cost += ($item->shipping_cost*$item->product_qty);
      @endphp
      <tr style="padding: 5px;">
         <td style="width: 70px; text-align: left;font-size: 12px;padding-top: 5px;">{{$loop->index + 1}}.</td>
         <td style="width: 70px; text-align: left;font-size: 12px;padding-top: 5px;">{{ $item->product->title }}</td>
         <td style="width: 70px; text-align: left;font-size: 12px;padding-top: 5px;"><span style="font-size: 12px;"> {{ $item->product_qty }}</span></td>
         <td style="width: 70px; text-align: left;font-size: 12px;padding-top: 5px;">BDT <span style="font-size: 12px;"> {{ $item->price }}</span></td>
         <td style="width: 120px; text-align: left;font-size: 12px;">BDT {{ $item->shipping_cost }}</td>
         <td style="width: 120px; text-align: right;font-size: 12px;">BDT {{ ($item->product_qty*$item->price) }}</td>
      </tr>
    @endforeach


   </tbody>
</table>

  <table style="width: 650px;text-align:right; margin:50px auto">
        <tbody>
            <tr>
                <td style="width: 260px;">&nbsp;</td>
                <td style="width: 100px;">
                    <p style="margin: 1px;font-size: 12px;"><strong style="float: left;">Sub Total:&nbsp;</strong>BDT {{ $subtotal }}</p>
                    <p style="margin: 1px;font-size: 12px;"><strong style="float: left;">Shipping Cost(+):&nbsp;</strong>BDT  {{ $shipping_cost}}</p>
                    @if($data->discount_amount > 0)
                    <p style="margin: 1px;font-size: 12px;"><strong style="float: left;">Discount(-) :&nbsp;</strong>BDT  {{ $data->discount_amount }}</p>
                    @endif
                    @if($data->coupon_amount > 0)
                    <p style="margin: 1px;font-size: 12px;" ><strong style="float: left;">Coupon Discount(-):&nbsp;</strong>BDT {{ $data->coupon_amount }} </p>
                    @endif
                    @if($data->voucher_amount > 0)
                    <p style="margin: 1px;font-size: 12px;" ><strong style="float: left;">Voucher Discount(-):&nbsp;</strong>BDT  {{ $data->voucher_amount }}</p>
                    @endif
                    <p style="margin: 1px;font-size: 12px;"><strong style="float: left;">Paid:&nbsp;</strong>BDT {{ $data->paid_amount }} </p>
                    <p style="margin: 1px;font-size: 12px;"><strong style="float: left;">Due:&nbsp;</strong>BDT {{(($subtotal+$shipping_cost)-($data->discount_amount+$data->coupon_amount+$data->voucher_amount))-$data->paid_amount }} </p>
                    
                </td>
            </tr>
        </tbody>
    </table>
    <table style="width: 650px;text-align:left; margin:50px auto">
        <tbody style="text-align: center;">
            <p style="font-size:12px;text-align: center;">Thank you for being with us. Stay connected with <b>www.nurtaj.com</b> </p>
        </tbody>
    </table>
  
</section>

</div>


</body>
</html>
