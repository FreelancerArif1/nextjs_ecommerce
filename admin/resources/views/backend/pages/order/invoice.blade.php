<style type="text/css" media="print">
  @page{
    size:  auto;
    margin: 0mm;
  }
  #print_invoice{
    display: block;
  }
</style>


<section id="print_invoice">
  <table style="width: 650px;margin:30px auto">
    <tbody>
        <tr >
 
           <td style="width: 375px;padding-top: 0px; padding-bottom: 10px;">
                <p style="margin-bottom: 5px;"><strong style="text-transform:uppercase;font-size: 14px;">Delivery Information</strong><br>
               
               @if($order->is_pickpoint == 1)
                <div style="font-size: 12px;padding-right: 0px;">
                    <span style="font-weight: 600;">  Address: </span> {{$order->pickpoint_address->title}}, {{$order->pickpoint_address->union->title ?? ''}}, {{$order->pickpoint_address->upazila->title ?? ''}},{{$order->pickpoint_address->district->title ?? ''}},{{$order->pickpoint_address->division->title ?? ''}}
                    <br>
                    <span style="font-weight: 600;"> Point Name: </span>  {{$order->pickpoint_address->title}}
                    <br>
                    <span style="font-weight: 600;">  HP: </span>  {{$order->pickpoint_address->phone}}
                    <br>
                    <span style="font-weight: 600;">  E-mail: </span>  {{$order->pickpoint_address->email}}
                </div>
               @else
                  <div style="font-size: 12px;padding-right: 0px;">
                    <span style="font-weight: 600;">  Address: </span> {{$order->address->shipping_address}},{{$order->address->union->title ?? ''}}, {{$order->address->upazila->title ?? ''}},{{$order->address->district->title ?? ''}},{{$order->address->division->title ?? ''}}
                    <br>
                    <span style="font-weight: 600;"> Name: </span>  {{$order->address->shipping_first_name.' '.$order->address->shipping_last_name}}
                    <br>
                    <span style="font-weight: 600;">  HP: </span>  {{$order->address->shipping_phone}}
                    <br>
                    <span style="font-weight: 600;">  E-mail: </span>  {{$order->address->shipping_email}}
                </div>
               @endif

                </p>
            </td>

            <td style="width: 245px;text-align:right;padding-left: 0px; font-size: 12px; padding-bottom: 10px;">
                <p style="font-size:12px; padding: 0;margin: 0;width: 280px;">
                    <img src="{{ asset('uploads/images/'.config('concave.cnf_logo'))}}"  alt="" width="70" />
                    <br>
                    <span style="font-weight: 600;"></span> {{ config('concave.cnf_address') }} 
                    <br>
                    <span style="font-weight: 600;">  HP: </span> {{ config('concave.cnf_phone') }}
                    <br>
                    <span style="font-weight: 600;">  E-mail: </span> {{ config('concave.cnf_email') }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="width: 100%;position: relative;"><hr style="width: 100%;margin: 0px;width: 650px;margin: 0px;position: absolute;z-index: 9999;background: #fdfdfd;"></td>
        </tr>
     </tbody>
  </table>
  <table style="width: 650px;margin:50px auto">
    <tbody>
        <tr>
            <td style="width: 650px;">
                <div class="row" style="display: flex; -ms-flex-wrap: wrap;flex-wrap: wrap;padding: 0!important;">
                    <div class="col-md-6" style="flex: 0 0 55%;max-width: 55%;position: relative;text-align: right!important;padding: 0!important;"> <span style="text-transform: uppercase;font-size: 20px;font-weight: 600;">Invoice</span>  </div>
                    <div class="col-md-6" style="flex: 0 0 45%;max-width: 45%;position: relative;text-align: right!important;padding: 0!important;">  <span style="text-transform: uppercase;font-size: 14px;font-weight: 600;">  Order ID: DR{{ date("y", strtotime($order->created_at)) }}{{$order->id}} </span> </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>


<table id="product" style="width: 650px;margin:30px auto">
   <tbody>
      <tr style="padding: 5px;">
          <td style="width: 70px; text-align: left;text-transform: uppercase;font-size: 12px;"><strong>  SL</strong></td>
          <td style="width: 400px; text-align: left;text-transform: uppercase;font-size: 12px;"><strong> Item</strong></td>
          <td style="width: 200px; text-align: left;text-transform: uppercase;font-size: 12px;"><strong> Qty</strong></td>
          <td style="width: 200px; text-align: left;text-transform: uppercase;font-size: 12px;"><strong> Price</strong></td>
          
          <td style="width: 120px; text-align: right;text-transform: uppercase;font-size: 12px;"><strong>  Sub Total</strong></td>
      </tr>
      @php $cancelled = 0; $subtotal = 0; $shipping_cost =0; $packaging_cost = 0; $security_charge = 0; $refunded = 0; @endphp
    @foreach($order->order_details as $item)
      @php 
         if(\Auth::user()->getRoleNames()[0] == 'seller'){
            if(\Auth::id() != $item->seller_id){
               continue;
            }
         }

         if ($item->status == 8) {
            $refunded += ($item->product_qty * $item->price);
         }
         if ($item->status == 5) {
            $cancelled += ($item->product_qty * $item->price);
         }

         $subtotal += ($item->product_qty * $item->price); 
         $shipping_cost += ($item->shipping_cost*$item->product_qty);
         $packaging_cost += $item->packaging_cost;
         $security_charge += $item->security_charge;

      @endphp
      <tr style="padding: 5px;">
         <td style="width: 70px; text-align: left;font-size: 12px;padding-top: 5px;">{{$loop->index + 1}}.</td>
         <td style="width: 70px; text-align: left;font-size: 12px;padding-top: 5px;">
            {{ $item->product->title }} 
            @if($item->status == 5)
               <span style="background-color: #d30d44; font-size: 12px !important;font-weight: 400; padding: 3px 11px;">Canceled</span>
            @endif
            <br>
            @if($item->product->product_type == 'variable' || $item->product->product_type == 'service')
                <small><b>SKU:</b> {{ $item->product_sku }}</small><br>
                @php 
                  $variable_option = json_decode($item->product_options);
                @endphp
                @if($variable_option)
                @foreach($variable_option as $key=> $val)
                    <span style="margin-right: 5px;"> <b>{{ $key }}: </b> {{ $val }}</span>
                @endforeach
                @endif
                <br>
            @else
                <small><b>SKU:</b> {{ $item->product_sku }}</small><br>
            @endif
          </td>
         <td style="width: 70px; text-align: left;font-size: 12px;padding-top: 5px;"><span style="font-size: 12px;"> {{ $item->product_qty }}</span></td>
         <td style="width: 70px; text-align: left;font-size: 12px;padding-top: 5px;">BDT <span style="font-size: 12px;"> {{ $item->price }}</span></td>
         <td style="width: 120px; text-align: right;font-size: 12px;">BDT {{ ($item->product_qty*$item->price) }}</td>
      </tr>
    @endforeach


   </tbody>
</table>

  <table style="width: 650px;text-align:right; margin:50px auto">
        <tbody>
            <tr>
                <td style="width: 170px;">&nbsp;</td>
                <td style="width: 80px;">
                    <p style="margin: 1px;font-size: 12px;"><strong style="float: left;">Sub Total:&nbsp;</strong>BDT {{ $subtotal }}</p>

                    {{-- @if($order->grocery_shipping_cost && $order->grocery_shipping_cost > 0)
                      <p style="margin: 1px;font-size: 12px;"><strong style="float: left;">Grocery Shipping Cost:&nbsp;</strong>BDT  {{ $order->grocery_shipping_cost ?? 0 }}</p>
                    @endif --}}
                    @if($order->shipping_cost > 0)
                    <p style="margin: 1px;font-size: 12px;"><strong style="float: left;">Total Shipping Cost(+):&nbsp;</strong>BDT  {{ $order->shipping_cost}}</p>
                    @endif

                    @if($order->vat > 0)
                    <p style="margin: 1px;font-size: 12px;"><strong style="float: left;">VAT(+):&nbsp;</strong>BDT  {{ $order->vat}}</p>
                    @endif

                    @if($order->coupon_amount > 0)
                    <p style="margin: 1px;font-size: 12px;" ><strong style="float: left;">Coupon Discount(-):&nbsp;</strong>BDT {{ $order->coupon_amount }} </p>
                    @endif

                    @if($order->voucher_amount > 0)
                    <p style="margin: 1px;font-size: 12px;" ><strong style="float: left;">Voucher Discount(-):&nbsp;</strong>BDT  {{ $order->voucher_amount }}</p>
                    @endif

                    @if($packaging_cost > 0)
                    <p style="margin: 1px;font-size: 12px;" ><strong style="float: left;">Packaging Cost(+):&nbsp;</strong>BDT  {{ $packaging_cost }}</p>
                    @endif

                    @if($security_charge > 0)
                    <p style="margin: 1px;font-size: 12px;" ><strong style="float: left;">Security Charge(+):&nbsp;</strong>BDT  {{ $security_charge }}</p>
                    @endif

                    @if($refunded > 0)
                    <p style="margin: 1px;font-size: 12px;" ><strong style="float: left;">Refund Amount(-):&nbsp;</strong>BDT  {{ $refunded }}</p>
                    @endif
                    @if($cancelled > 0)
                    <p style="margin: 1px;font-size: 12px;"><strong style="float: left;">Cancelled Amount(-):&nbsp;</strong>BDT  {{ $cancelled}}</p>
                    @endif
                    
                    <p style="margin: 1px;font-size: 12px;" ><strong style="float: left;">Total:&nbsp;</strong>BDT  {{ $total = $subtotal+$order->shipping_cost-$cancelled-$refunded }}</p>
                    <p style="margin: 1px;font-size: 12px;"><strong style="float: left;">Paid:&nbsp;</strong>BDT {{ $order->paid_amount }} </p>
                    <p style="margin: 1px;font-size: 12px;"><strong style="float: left;">Due:&nbsp;</strong>BDT {{ $total-$order->paid_amount }} </p>

                    {{-- @if($order->payment_method == 'cash_on_delivery')
                      @if($order->status == 6)
                        <p style="margin: 1px;font-size: 12px;"><strong style="float: left;">Paid:&nbsp;</strong>BDT {{ $order->paid_amount + $order->grocery_shipping_cost + $packaging_cost + $security_charge  }} </p>
                        <p style="margin: 1px;font-size: 12px;"><strong style="float: left;">Due:&nbsp;</strong>BDT 0.00 </p>
                      @else
                        <p style="margin: 1px;font-size: 12px;"><strong style="float: left;">Paid:&nbsp;</strong>BDT  0.00</p>
                        <p style="margin: 1px;font-size: 12px;"><strong style="float: left;">Due:&nbsp;</strong>BDT {{ $order->paid_amount + $order->grocery_shipping_cost + $packaging_cost + $security_charge }} </p>
                      @endif
                    @else
                        
                    @endif --}}
                    
                </td>
            </tr>
        </tbody>
    </table>
    <table style="width: 650px;text-align:left; margin:50px auto">
        <tbody style="text-align: center;">
            <p style="font-size:12px;text-align: center;"><strong>Order Note: </strong>{{$order->note ?? ''}}</p>
            <p style="font-size:12px;text-align: center;">Thank you for being with us. Stay connected with <b>{{config('concave.cnf_comname')}}</b> </p>
            <p style="text-align: center;"><img src="data:image/png;base64, {{DNS1D::getBarcodePNG('DR'.date("y", strtotime($order->created_at)).$order->id, 'C39',5,10) }}" alt=""  width="150px" height="30px"></p>
        </tbody>
    </table>
  
</section>

<script>
    function printInvoice(div){
      var divToPrint= jQuery('#print_invoice').html();
      var newWin=window.open('','Print-Window');
      newWin.document.open();
      newWin.document.write('<html><link href=""><body onload="window.print()">'+divToPrint+'</body></html>');
      newWin.document.close();
      setTimeout(function(){newWin.close();},100);
    }
</script>