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
            'item_masuk' => In::count(),
            'item_keluar' => Out::count()
        ];

        return view('layout.index',['data' => $data]);
    }
}
