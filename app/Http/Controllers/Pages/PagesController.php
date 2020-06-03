<?php

namespace App\Http\Controllers\Pages;

use App\Models\Category;
use App\Models\ProductReview;
use App\Models\ProductSizes;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductOffer;
use App\Http\Controllers\Controller;
use App\Models\Vendors;
use Carbon\Carbon;
use App\Models\Pages;
use Mail;
use App\Mail\ContactUs;
class PagesController extends Controller
{
    public function index(Request $request)
    {
        $page = Pages::where('slug', $request->slug)->first();
        return view('Pages.page')->with(['page'=>$page]);

    }
    public function contactus(Request $request)
    {
        $settings = Settings::first();
        return view('Pages.contactUs')->with(['settings'=>$settings]);
    }
    public function sendContactus(Request $request)
    {
        $settings = Settings::first();
        Mail::to($settings->email)->send(new ContactUs($request->all()));
            \Session::flash('success', "Your Message Has Been sent");
            \Session::flash('title', "Congratulations!");

        return view('Pages.contactUs')->with(['settings'=>$settings]);

    }
}
