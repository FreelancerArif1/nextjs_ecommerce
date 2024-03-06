<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\OrderDetails;
use App\Models\PartialOrderTransaction;

class SslCommerzPaymentController extends Controller
{

    public function success(Request $request){
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $value_a = $request->input('value_a');
        $currency = $request->input('currency');
        $sslc = new SslCommerzNotification();

        $order_details = DB::table('orders')
            ->where('payment_id', $value_a)
            ->select('payment_id', 'status', 'currency', 'paid_amount','id','is_partial_payment')->first();

        if ($order_details->status == 1 || $order_details->status == 5 || $order_details->is_partial_payment == 1) {

            

            if($order_details->is_partial_payment == 1){

                $update_product = DB::table('orders')
                    ->where('payment_id', $tran_id)
                    ->update([
                        'payment_id' => $value_a,
                        'status' => 2,
                        'paid_amount' => $amount+$order_details->paid_amount
                    ]);

                //Partial Payment History

                $PartialOrderTransaction = new PartialOrderTransaction;
                $PartialOrderTransaction->order_id = $order_details->id;
                $PartialOrderTransaction->transaction_id = $tran_id;
                $PartialOrderTransaction->amount = $amount;
                $PartialOrderTransaction->status = 2;
                $PartialOrderTransaction->save();

                    
                //Response
                $data['status'] = 1;
                $data['message'] = 'Transaction is successfully Completed';

                $order_id = $order_details->id;

                //Pending Maturation State for seller Balance
                DB::table('seller_account_history')->where('order_id',$order_id)->where('status',0)->update(['status' => 1]);

                //Stock Management
                foreach(OrderDetails::where('order_id',$order_id)->with('product')->get() as $orderDetails){
                    \Helper::update_product_quantity($orderDetails->product->id, $orderDetails->product_qty, $orderDetails->product_options);
                }
                
            }else{
                $validation = $sslc->orderValidate($tran_id, $amount, $currency,$request->all());
                if ($validation == TRUE) {
                    $update_product = DB::table('orders')
                        ->where('payment_id', $tran_id)
                        ->update([
                            'status' => 2,
                            'paid_amount' => $amount
                        ]);
                        
                    //Response
                    $data['status'] = 1;
                    $data['message'] = 'Transaction is successfully Completed';
    
                    $order_id = $order_details->id;
    
                    //Pending Maturation State for seller Balance
                    DB::table('seller_account_history')->where('order_id',$order_id)->where('status',0)->update(['status' => 1]);
    
                    //Stock Management
                    foreach(OrderDetails::where('order_id',$order_id)->with('product')->get() as $orderDetails){
                        \Helper::update_product_quantity($orderDetails->product->id, $orderDetails->product_qty, $orderDetails->product_options);
                    }
                } else {
                    $update_product = DB::table('orders')
                        ->where('payment_id', $tran_id)
                        ->update(['status' => 4]);
                    //Response
                    $data['status'] = 0;
                    $data['message'] = 'Validation Failed!';
                
                }
            }

   


        } else if ($order_details->status == 2 || $order_details->status == 6) {
				//Response
				$data['status'] = 1;
				$data['message'] = 'Transaction is successfully Completed.';
				
                $order_id = $order_details->id;

                //Pending Maturation State for seller Balance
                DB::table('seller_account_history')->where('order_id',$order_id)->where('status',0)->update(['status' => 1]);

                //Stock Management
                foreach(OrderDetails::where('order_id',$order_id)->with('product')->get() as $orderDetails){
                    \Helper::update_product_quantity($orderDetails->product->id, $orderDetails->product_qty, $orderDetails->product_options);
                }

        } else {
			//Response
			$data['status'] = 0;
			$data['message'] = 'Invalid Transaction.';
        }
		return response()->json($data, 200);
    }

    public function failed(Request $request){
        $tran_id = $request->input('tran_id');

        $order_details = DB::table('orders')
            ->where('payment_id', $tran_id)
            ->select('payment_id', 'status', 'currency', 'paid_amount','id')->first();

        if($order_details->status == 1){
            $update_product = DB::table('orders')
                ->where('payment_id', $tran_id)
                ->update(['status' => 4]);
			//Response
			$data['status'] = 0;
			$data['message'] = 'Transaction is Falied.';
        }elseif ($order_details->status == 2 || $order_details->status == 6) {
			//Response
			$data['status'] = 1;
			$data['message'] = 'Transaction is already Successful.';
        } else {
			//Response
			$data['status'] = 1;
			$data['message'] = 'Transaction is Invalid.';
        }
		
		return response()->json($data, 200);
    }

    public function cancel(Request $request){
        $tran_id = $request->input('tran_id');

        $order_details = DB::table('orders')
            ->where('payment_id', $tran_id)
            ->select('payment_id', 'status', 'currency', 'paid_amount','id')->first();

        if ($order_details->status == 1) {
            $update_product = DB::table('orders')
                ->where('payment_id', $tran_id)
                ->update(['status' => 5]);
				
			//Response
			$data['status'] = 0;
			$data['message'] = 'Transaction is Canceled.';

        } else if ($order_details->status == 2 || $order_details->status == 6) {

			//Response
			$data['status'] = 0;
			$data['message'] = 'Transaction is already Successful.';
			
        } else {
			//Response
			$data['status'] = 0;
			$data['message'] = 'Transaction is Invalid.';
        }
		
		return response()->json($data, 200);
    }

    public function ipn(Request $request){
        if ($request->input('tran_id'))
        {
            $tran_id = $request->input('tran_id');
            $amount = $request->input('amount');
            $order_details = DB::table('orders')
                ->where('payment_id', $tran_id)
                ->select('payment_id', 'status', 'currency', 'paid_amount','id')->first();

            if ($order_details->status == 1) {
                $sslc = new SslCommerzNotification();
               // $validation = $sslc->orderValidate($tran_id, $order_details->paid_amount, $order_details->currency,$request->all());
               // if ($validation == TRUE) {
                    $update_product = DB::table('orders')
                        ->where('payment_id', $tran_id)
                        ->update([
                            'status' => 2,
                            'paid_amount' => $amount,
                        ]);

					$data['status'] = 1;
					$data['message'] = 'Transaction is successfully Completed.';

                    $order_id = $order_details->id;

                    //Pending Maturation State for seller Balance
                    DB::table('seller_account_history')->where('order_id',$order_id)->where('status',0)->update(['status' => 1]);

                    //Stock Management
                    foreach(OrderDetails::where('order_id',$order_id)->with('product')->get() as $orderDetails){
                        \Helper::update_product_quantity($orderDetails->product->id, $orderDetails->product_qty, $orderDetails->product_options);
                    }
			
                // } else {
                //     $update_product = DB::table('orders')
                //         ->where('payment_id', $tran_id)
                //         ->update(['status' => 4]);
				// 	$data['status'] = 0;
				// 	$data['message'] = 'validation Failed.';
                // }

            } else if ($order_details->status == 2 || $order_details->status == 6) {
				$data['status'] = 1;
				$data['message'] = 'Transaction is already successfully Completed.';
            } else {
				$data['status'] = 0;
				$data['message'] = 'Invalid Transaction.';
            }
        } else {
			$data['status'] = 0;
			$data['message'] = 'Invalid Data.';
        }
		
		return response()->json($data, 200);
    }

}
