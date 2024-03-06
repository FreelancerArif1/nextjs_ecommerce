<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Socialite;
use Exception;
use Auth;
use DB;
//JWT
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;

class SocialController extends Controller
{
    public function facebookRedirect()
    {
        return Socialite::driver('facebook')->redirect();
    }
    
    
    public function loginWithFacebook(Request $request)
    {
        try {
                
            $user = Socialite::driver('facebook')->stateless()->userFromToken($request->accessToken);
            $dbUser = false;
            
            if($user->email){
                $dbUser = User::where('email', $user->email)->first();
            }


            if(!$dbUser){
                if($user->id){
                    $dbUser = User::where('fb_id', $user->id)->first();
                } 
            }
           
            if($dbUser){
                if($dbUser->status == 1){
                    $token = auth('customer-api')->login($dbUser,true);
                }else{
                     return response()->json(['msg' => 'This account is not active!'], 404);
                }
               
            }else{
                
                $data = [
                        'name' => $user->name,
                        'email' => $user->email,
                        'fb_id' => $user->id,
                        'status' => 1,
                        'password' => \Hash::make(rand(111111,999999))
                    ];
                
                $user_id = DB::table('users')->insertGetId($data);
                $dbUser = User::where('id', $user_id)->first();
 
               $token = auth('customer-api')->login($dbUser,true);
            }
            
            if (!$token) {
                return response()->json(['msg' => 'Credentials not found!'], 404);
            }
    
            return response()->json([
                'customer' => auth('customer-api')->user(),
                'token' => $token,
                'expire' => auth('customer-api')->factory()->getTTL() * 60
            ], 200);
            
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 200);
        }
    }
    
    
    public function loginWithGoogle(Request $request){
        try {
                
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://www.googleapis.com/oauth2/v3/tokeninfo?id_token='.$request->token,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
            
            $response = json_decode($response);
            
            $email = $response->email;
            $google_id = $response->sub;
            $name = $response->name;
    
            $dbUser = User::where('email', $email)->first();
            if(!$dbUser){
                $dbUser = User::where('google_id', $google_id)->first();
            }
           
            if($dbUser){
                if($dbUser->status == 1){
                    $token = auth('customer-api')->login($dbUser,true);
                }else{
                     return response()->json(['msg' => 'This account is not active!'], 404);
                }
               
            }else{
                
                $data = [
                        'name' => $name,
                        'email' => $email,
                        'google_id' => $google_id,
                        'password' => \Hash::make(rand(111111,999999))
                    ];
                
                $user_id = DB::table('users')->insertGetId($data);
                $dbUser = User::where('id', $user_id)->first();
 
               $token = auth('customer-api')->login($dbUser,true);
            }
            
            if (!$token) {
                return response()->json(['msg' => 'Credentials not found!'], 404);
            }
    
            return response()->json([
                'customer' => auth('customer-api')->user(),
                'token' => $token,
                'expire' => auth('customer-api')->factory()->getTTL() * 60
            ], 200);
            
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 200);
        }
    }
	
}