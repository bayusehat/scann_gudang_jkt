<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemSold;
use Validator;
use Str;

class ItemController extends Controller
{
    public function index(){
        $data = [
            'title' => 'Item Master',
            'content' => 'item'
        ];

        return view('layout.index',['data' => $data]);
    }
    
    public function loadItem(Request $request){
        if ($request->ajax()) {
            $data = Item::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('barcode', function($row){
                    return $row->kode_item;
                })
                ->addColumn('created_at', function($row){
                    return date('d-m-Y H:i',strtotime($row->created_at));
                })
                ->addColumn('user_in', function($row){
                    return $row->user->name;
                })
                ->make(true);
        }
    }

    public function auto_add(Request $request){
        $kode = $request->input('barcode');
        if(strlen($kode) == 14){
                $check = ItemSold::where('barcode',$kode)->first();
                if($check){
                    $in = new ItemSold;
                    $in->barcode = $kode;
                    $in->user_id = auth()->user()->id;
                    if($in->save())
                        return response(['status' => 'success', 'message' => 'Kode item masuk berhasil terinput']);
                }else{
                    return response(['status' => 'error','message' => 'Kode tidak ditemukan!']);
                }
        }else{
            return response(['status' => 'error','message' => 'Kode tidak valid!']);
        }
    }
}
