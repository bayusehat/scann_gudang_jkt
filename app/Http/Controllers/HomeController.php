<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\In;
use App\Models\Out;

class HomeController extends Controller
{
    public function index(){
        $data = [
            'title' => 'Home',
            'content' => 'home',
            'dashboard' => true,
            'item_masuk_count' => In::count(),
            'item_keluar_count' => Out::count()
        ];

        return view('layout.index',['data' => $data]);
    }
}
