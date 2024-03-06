<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo = RouteServiceProvider::ADMIN;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest')->except('logout');
		$this->middleware('guest:web')->except('logout');
	}




	public function showLoginForm(Request $request)
	{


		if ($request->server('HTTP_HOST') == 'api.dhroobo.com' || $request->server('HTTP_HOST') == 'api.dhroobo.com') {
			echo json_encode('Sorry please provide valid api url!');
		} elseif ($request->server('HTTP_HOST') == 'seller.dhroobo.com' || $request->server('HTTP_HOST') == '127.0.0.1:8001' || $request->server('HTTP_HOST') == 'seller.dhroobo.com') {
			return view('auth.admin.login');
		} elseif ($request->server('HTTP_HOST') == 'admin.dhroobo.com' || $request->server('HTTP_HOST') == '127.0.0.1:8000' || $request->server('HTTP_HOST') == 'admin.dhroobo.com') {
			return view('auth.admin.login');
		} else {
			echo json_encode('Sorry you are not allowed to access this url!');
		}
	}




	public function login(Request $request)
	{

		if ($request->server('HTTP_HOST') == 'seller.dhroobo.com' || $request->server('HTTP_HOST') == '127.0.0.1:8001' || $request->server('HTTP_HOST') == 'seller.dhroobo.com') {
			$this->validate($request, [
				'email' => 'required',
				'password' => 'required'
			]);

			if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
				if (Auth::user()->getRoleNames()[0] == 'seller') {

					if (Auth::check() && Auth::user()->status != 1) {
						Auth::logout();
						$request->session()->invalidate();
						session()->flash('failed', 'Your account is disabled. Please contact with customer support!');
						return back();
					} else {
						return redirect()->intended(route('admin.index'));
					}
				} else {
					Auth::logout();
					$request->session()->invalidate();
					session()->flash('failed', 'Invalid Credentials!');
					return back();
				}
			} elseif (Auth::attempt(['phone' => $request->email, 'password' => $request->password])) {
				if (Auth::user()->getRoleNames()[0] == 'seller') {

					if (Auth::check() && Auth::user()->status != 1) {
						Auth::logout();
						$request->session()->invalidate();
						session()->flash('failed', 'Your account is disabled. Please contact with customer support!');
						return back();
					} else {
						return redirect()->intended(route('admin.index'));
					}
				} else {
					Auth::logout();
					$request->session()->invalidate();
					session()->flash('failed', 'Invalid Credentials!');
					return back();
				}
			} else {
				session()->flash('failed', 'Invalid Credentials!');
				return back();
			}
		} elseif ($request->server('HTTP_HOST') == 'admin.dhroobo.com' || $request->server('HTTP_HOST') == 'admin.dhroobo.com' || $request->server('HTTP_HOST') == '127.0.0.1:8000') {
			$this->validate($request, [
				'email' => 'required',
				'password' => 'required'
			]);

			if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
				if (Auth::user()->getRoleNames()[0] == 'seller') {
					Auth::logout();
					$request->session()->invalidate();
					session()->flash('failed', 'Invalid Credentials!');
					return back();
				} else {

					if (Auth::check() && Auth::user()->status != 1) {
						Auth::logout();
						$request->session()->invalidate();
						session()->flash('failed', 'Your account is disabled. Please contact with customer support!');
						return back();
					} else {
						return redirect()->intended(route('admin.index'));
					}
				}
			} elseif (Auth::attempt(['phone' => $request->email, 'password' => $request->password])) {
				if (Auth::user()->getRoleNames()[0] == 'seller') {
					Auth::logout();
					$request->session()->invalidate();
					session()->flash('failed', 'Invalid Credentials!');
					return back();
				} else {
					if (Auth::check() && Auth::user()->status != 1) {
						Auth::logout();
						$request->session()->invalidate();
						session()->flash('failed', 'Your account is disabled. Please contact with customer support!');
						return back();
					} else {
						return redirect()->intended(route('admin.index'));
					}
				}
			} else {
				session()->flash('failed', 'Invalid Credentials!');
				return back();
			}
		}
	}



	public function logout(Request $request)
	{
		Auth::logout();
		$request->session()->invalidate();
		return redirect()->route('admin.login');
	}
}