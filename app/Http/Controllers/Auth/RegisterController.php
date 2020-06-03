<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail;
use App\Mail\VerifyUser;
use App\Mail\NewUser;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            
        ]);
    }
    public function showRegisterForm(Request $request)
    {
        return view('auth.register');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'g-recaptcha-response' => ['required','recaptcha'],
            'mobile'=> ['required', 'string', 'max:255'],
        ]);
        if($validate->fails())
        {

            return redirect()->back()
                ->withInput($request->input())
                ->withErrors($validate->errors());

        }
        $code = 'EN' . Carbon::now().$request->first_name;
        $user =  User::create([
            'name' => $request->first_name .' '.$request->last_name,
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'code'=> substr(preg_replace('/[^A-Za-z0-9\-]/', '', Hash::make($code)),0,6)
        ]);

        \Session::flash( 'success', "Your Account Has Been created , Please check your email to activate it" );
        \Session::flash( 'title', "Congratulations!" );
        Mail::to($request->email)->send(new VerifyUser($user));
        $sales_email = app('settings')->email_sales;
        Mail::to($sales_email)->send(new NewUser($user));
        
        return redirect(route('home'));
    }
    public function checkEmail(Request $request)
    {

        $validate=  Validator::make( ($request->all()) , [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);
        
        if($validate->fails())
        {
            return json_encode(array('status'=>false ));
        }
        return json_encode(array('status'=>true));
    }
}
