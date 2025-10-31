<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SiteController extends Controller
{

    public function index($slug = '')
    {
      $page = DB::table('pages')->where('p_slug', $slug)->first();

        if($page){
            $content = DB::table('content')->where('page_id',$page->id)->first();
            if($slug == 'home'){
                $view = 'index';
            } elseif($slug == 'about'){
                $view = 'about';
            } elseif($slug == 'contact'){
                $view = 'contact';
            } elseif($slug == 'shop'){
                $view = 'shop';
            }
            elseif($slug == 'blog'){
                $view = 'blog';
            }elseif($slug == 'details'){
                $view = 'details';
            }elseif($slug == 'shoping-cart'){
                $view = 'Cart';
            }
            else{
                $view = 'index';
            }
            $categories = Category::where('is_parent', 1)->get();
            $product = Product::all();
            return view('site.'.$view,compact('categories','product','content','page'));
        } else {
            die('404');
        }
        // $categories = Category::where('is_parent', 1)->get();
        // $product = Product::all();
        // // return view('admin.category.index' ,compact('categories'));
        // return view('site.index',compact('categories','product'));
    }


}
