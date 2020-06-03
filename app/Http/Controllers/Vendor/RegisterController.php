<?php
/**
 * Created by PhpStorm.
 * User: sh3ban
 * Date: 3/31/19
 * Time: 9:13 AM
 */
namespace App\Http\Controllers\Vendor;

use App\Models\Ads;
use App\Models\Area;
use App\Models\Category;
use App\Models\Settings;
use App\Models\Sliders;
use App\Models\VendorAreaDeliveryCharges;
use App\Models\VendorCommissions;
use App\Models\Vendors;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductOffer;
use Carbon\Carbon;
use Mail;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Mail\VerifyVendor;
use App\Mail\AdminRegVendor;
use App\Mail\VendorForgetPassword;
class RegisterController extends Controller
{
    use AuthenticatesUsers;
    protected function guard()
    {
        return Auth::guard('vendor');
    }


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/vendor/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:vendor')->except('logout') ;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function showRegisterForm(Request $request)
    {
        return view('vendors.auth.register');
    }

    public function showLoginForm(Request $request)
    {
        return view('vendors.auth.login');
    }
    public function login(Request $request)
    {

        $credentials = array(
            'email' => $request->email,
            'password' => ($request->password),
            'is_active' => 1
        );

        if ($this->guard()->attempt($credentials))
        {

            return redirect(route('vendor.dashboard.index'));
        }

        return redirect()->back()->withInput() ->withErrors([
            'error' => 'Invalid Credentials',
        ]);
    }
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:vendors'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'g-recaptcha-response' => ['required','recaptcha']
        ]);
        if($validate->fails())
        {

            return redirect()->back()
                ->withInput($request->input())
                ->withErrors($validate->errors());

        }
        $code = 'EN' . Carbon::now().$request->first_name;
        $user =  Vendors::create([
            'name' => $request->first_name .' '.$request->last_name,
            'name_ar' => $request->first_name .' '.$request->last_name,
             'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'password' => Hash::make($request->password),
            'activatecode'=>  substr(preg_replace('/[^A-Za-z0-9\-]/', '', Hash::make($code)),0,6),
            'permission'=> \App\Models\Vendors::VENDOR_FULL_ACCESS
        ]);
        $settings = Settings::first();
        foreach(Area::all() as $area)
        {
            $vendorarea = VendorAreaDeliveryCharges::create([
                'area_id'=>$area->id,
                'vendor_id'=>$user->id,
                'delivery_charge'=>'4.5'

            ]);
        }
        VendorCommissions::create([
            'fixed'=>$settings->default_commission_kwd,
            'precentage'=>$settings->default_commission_precentage,
            'vendor_id'=>$user->id
        ]);
        \Session::flash( 'success', "Your Account Has Been created , Please check your email to activate it" );
        \Session::flash( 'title', "Congratulations!" );
        Mail::to($request->email)->send(new VerifyVendor($user));

        (Mail::to($settings->email)->send(new AdminRegVendor($user)));


        return redirect(route('home'));
    }
    public function logout(Request $request)
    {
        Auth::guard('vendor')->logout();
        Auth::logout();
        $request->session()->flush();
        return redirect()->guest(route('vendor.login'));
    }
    public function activateaccount(Request $request)
    {

        $user = Vendors::where('activatecode',$request->token)->first();

        if($user)
        {
            $user->email_verified_at=Carbon::now();
            $user->code = '';
            $user->is_active = 1 ;
            $user->save();
            \Session::flash('success', "Your Account Has Been Activated");

            \Session::flash('title', "Congratulations!");

            return redirect(route('vendor.login'));
        }
        return redirect(route('home'));
    }
    public function showRequestPasswordForm(Request $request)
    {
        return view('vendors.auth.passwords.email');
    }
    public function sendEmailLink(Request $request)
    {
        $vendor = Vendors::where('email',$request->email)->firstOrfail();
        $code = 'EN' . Carbon::now().$vendor->first_name;
        $vendor->passwordtoken = substr(preg_replace('/[^A-Za-z0-9\-]/', '', Hash::make($code)),0,6) ;
        $vendor->update();
        $vendor->refresh();
        Mail::to($vendor->email)->send(new VendorForgetPassword($vendor));
        \Session::flash( 'success', "من فضلك تفقد بريدك الالكتروني! تم إرسال رسالة بريد إلكتروني إليك لتغيير كلمة المرور الخاصة بك" );
        return redirect(route('home'));

    }
    public function setPassword(Request $request)
    {
        return view('vendors.auth.settpasswords')->with('passtoken',$request->token);

    }
    public function updatePassword(Request $request)
    {
        $vendor = Vendors::where('email',$request->email)->where('passwordtoken',$request->passtoken)->firstOrfail();
        if($vendor)
        {
                $vendor->password =  Hash::make($request->password);
                $vendor->passwordtoken='';
                $vendor->update();
                \Session::flash( 'success', "تم تغيير كلمة المرور الخاصة بك" );
                return redirect(route('home'));

        }
        \Session::flash( 'fail', " خطا في تغيير كلمة المرور الخاصة بك" );
        return redirect(route('home'));
    }

}
