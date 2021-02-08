<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;
use JWTAuth;
use Illuminate\Support\Facades\DB;


class TestController extends Controller
{


    public function index(Request $request, News $news)
    {
        DB::enableQueryLog();
        $postData = file_get_contents('php://input');
        $postData = json_decode($postData, true);
        dd($postData);
        dd(DB::getQueryLog());
    }

//    public function index()
//    {
//        DB::enableQueryLog();
//            $query;
//        dd(DB::getQueryLog());
//    }

//    public function index()
//    {
//        $page=intval($_REQUEST['page']??0);
//        $page_size=intval($_REQUEST['page_size']??0);
//        $tagTitle = 'Economy';
//        $userName = $_REQUEST['userName']??'';
//
//        DB::enableQueryLog();
//        $news = News::latest()
//        ->with(['user:id,name', 'tag:tag_title'])
//        ->whereHas('user', function($query) use ($userName) {
//                        $query->whereName($userName);
//                    })
//        ->whereHas('tag', function($query) use ($tagTitle) {
//            $query->whereTagTitle($tagTitle);
//        })
//            ->offset(($page-1)*$page_size)->limit($page_size)
//            ->get(["id", "title", "details", "created_by"]);
//        dd(DB::getQueryLog());
//    }
}
