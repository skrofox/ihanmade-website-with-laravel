<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class WebController extends Controller
{
    //
    public function index()
    {
        $newProducts =  Product::with(['variants.currentPrice'])
            ->orderBy("id", "desc")
            ->take(4)
            ->get();
        return view("home", compact("newProducts"));
    }

    public function detail($slug){
        $product = Product::where("slug", $slug)->first();
        // $recommendProduct = Product::where()
        return view("components.web.detail", compact("product"));
    }
}
