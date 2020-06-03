<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\User;
use Carbon\Carbon;
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function showLoginForm(Request $request)
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $chkemail = User::where('email', $request->email)->first();

        if (!$chkemail) {
            return redirect()->back()->withInput() ->withErrors([
                'error' => 'Invalid Email',
            ]);
         } else if ($chkemail->is_active != 1) {
            return redirect()->back()->withInput() ->withErrors([
                'error' => 'Please Verify your account',
            ]);

        }
        $credentials = array(
            'email' => $request->email,
            'password' => ($request->password),
            'is_active' => 1
        );

        if (Auth::attempt($credentials))
        {
            return redirect(route('home'));
        }
        return redirect()->back()->withInput() ->withErrors([
            'error' => 'Invalid Credentials',
        ]);
    }
    public function activateaccount(Request $request)
    {

        $user = User::where('code',$request->token)->first();

        if($user)
        {
            $user->email_verified_at=Carbon::now();
            $user->code = '';
            $user->is_active = 1 ;
            $user->save();
            \Session::flash('success', "Your Account Has Been Activated");

            \Session::flash('title', "Congratulations!");

            return redirect(route('website.login'));
        }
        return redirect(route('home'));
    }
}
