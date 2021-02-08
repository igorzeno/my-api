<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;
use JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Psr\Log\NullLogger;


class NewsController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        $page=intval($_REQUEST['page']??0);
        $page_size=intval($_REQUEST['page_size']??0);
        $tagTitle = $_REQUEST['tag']??0;
        $userName = $_REQUEST['user']??'';
/*
        $postData = file_get_contents('php://input');
        $postData = json_decode($postData, true);

        $page=intval($postData['page']??0);
        $page_size=intval($postData['page_size']??0);
*/
        if($page_size<=0) $page_size=20;
        if($page_size>100) $page_size=100;

        $news = News::latest()
            ->with(['user:id,name', 'tag:tag_title']);
        if($userName) {
            $news = $news->whereHas('user', function ($query) use ($userName) {
                $query->whereName($userName);
            });
        }
        if($tagTitle) {
            $news = $news->whereHas('tag', function ($query) use ($tagTitle) {
                $query->whereTagTitle($tagTitle);
            });
        }
        $news = $news->offset(($page-1)*$page_size)->limit($page_size);
        $news = $news->get(["id", "title", "details", "created_by"]);
        return $news;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:20',
            'details' => 'required',
            'tag' => 'required',
        ]);
        if ($validator->fails()) {
            $fieldsWithErrorMessagesArray = $validator->messages()->get('*');
            return response()->json([
                "status" => false,
                "errors" =>$fieldsWithErrorMessagesArray
            ]);
        }

        $news = new News();
        $news->title = $request->title;
        $news->details = $request->details;
//        print_r($request->tag);

        if ($this->user->news()->save($news)) {

            $tagIds = [];
            foreach ($request->tag as $tagName) {
                $tag = Tag::firstOrCreate(['tag_title' => $tagName['tag_title']]);
                if ($tag) {
                    $tagIds[] = $tag->id;
                }
            }
            $news->tags()->sync($tagIds);

            return response()->json([
                "status" => true,
                "news" => $news
            ]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\News $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        $newID = $news->id;
        $news = News::with(['user:id,name', 'tag:tag_title'])
            ->where('news.id', $newID)
            ->get(["id", "title", "details", "created_by"]);

        if($news ){
            return $news;
        } else {
            return response()->json([
                "status" => false,
                "message" => "New could not found."
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\News $news
     * @return \Illuminate\Http\Response
     */
    public function reopen(Request $request)
    {
        $postData = file_get_contents('php://input');
        $postData = json_decode($postData, true);

        $author=intval($postData['author']??0);
        $newID = $request->id;
        $news = News::withTrashed()
            ->where('id', $newID)
            ->where('created_by', $author)
            ->restore();
        if ($news) {
            return response()->json([
                "status" => true,
                "news" => $news
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Ops, new could not be updated."
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\News $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $news, Tag $tag)
    {
        //return $request->id . '--------';
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:20',
            'details' => 'required',
            'tag' => 'required',
        ]);
        if ($validator->fails()) {
            $fieldsWithErrorMessagesArray = $validator->messages()->get('*');
            return response()->json([
                "status" => false,
                "errors" =>$fieldsWithErrorMessagesArray
            ]);
        }

        $news = News::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'id' => $request->id,
                'title' => $request->title,
                'details' => $request->details,
                'created_by' => $this->user->id,
            ]
        );
        if ($news) {
            $tagIds = [];
            foreach ($request->tag as $tagName) {
                $tag = Tag::firstOrCreate(['tag_title' => $tagName['tag_title']]);
                if ($tag) {
                    $tagIds[] = $tag->id;
                }
            }
            //print_r($news);
            $news->tags()->sync($tagIds);

            return response()->json([
                "status" => true,
                "news" => $news
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\News $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        $this->authorize('delete', $news);

        if ($news->delete()) {
            return response()->json([
                "status" => true,
                "news" => $news
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Ops, new could not be deleted."
            ]);
        }
    }
}
