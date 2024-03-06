<?php

namespace App\Http\Controllers\Backend;

use App\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;

class CouponController extends Controller
{
    //

    public function apply_coupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->coupon)->first();

        if (!$coupon) {
            return response()->json([
                'message', 'Invalid Coupon',
            ]);
        }
        else {
            if ($coupon->expire <= Carbon::now()) {
                return response()->json([
                    'message', 'Invalid Expired!',
                ]);
            } else {
                $couponCart = [
                    'code' => $coupon->code,
                    'value' => $coupon->value,
                ];
                session()->put('couponCart', $couponCart);
                return response()->json([
                    'message' => 'Coupon applied successfully!',
                    'value' => $coupon->value
                ]);
            }
        }
    }

    public function remove_coupon()
    {
        session()->forget('couponCart');

        return redirect(route('carts'));
    }
}
