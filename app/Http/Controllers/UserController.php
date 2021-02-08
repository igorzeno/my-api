<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $page=intval($_REQUEST['page']??0);
        $page_size=intval($_REQUEST['page_size']??0);
        if($page_size<=0) $page_size=2;
        if($page_size>100) $page_size=100;

        $user = User::orderBy('id', 'DESC')
            ->offset(($page-1)*$page_size)->limit($page_size)
            ->get(["id", "name", "email"]);
        return $user;
    }
}
