<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use App\Models\Category;
use App\Models\OrderDestination;
use App\Models\OrderRequesters;
use App\Models\ProductCategories;
use App\Models\Sliders;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductOffer;
use Carbon\Carbon;

use Auth;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if(!Auth::user())
        {
            return  redirect(route('login'));
        }
        $orderRequester = OrderRequesters::all();
        $orderDes = OrderDestination::all();
        $orderCats = Category::all();

        return view('home')->with(compact('orderRequester','orderDes','orderCats'));
    }
    public function sitemap()
    {
          return response()->view('sitemap')->header('Content-Type', 'text/xml');
    }

}
