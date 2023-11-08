<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemSold;
use Validator;
use Str;
use DataTables;

class ItemController extends Controller
{
    public function index(){
        $data = [
            'title' => 'Item Master',
            'content' => 'item',
            'active' => true
        ];

        return view('layout.index',['data' => $data]);
    }
    
    public function loadItem(Request $request){
        if ($request->ajax()) {
            $data = Item::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('barcode', function($row){
                    return $row->barcode;
                })
                ->addColumn('created_at', function($row){
                    return date('d-m-Y H:i',strtotime($row->created_at));
                })
                ->addColumn('harga', function($row){
                    return 'Rp '.number_format($row->harga);
                })
                ->addColumn('action', function($row){
                    return '
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id_item.')" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function auto_add(Request $request){
        $kode = $request->input('barcode');
        if(strlen($kode) == 14){
                $check = Item::where('barcode',$kode)->first();
                if($check){
                    $in = new ItemSold;
                    $in->id_item = $check->id_item;
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

    public function item_scan(){
        $data = [
            'title' => 'Item Scan Page',
            'content' => 'item_sold',
            'active' => true
        ];
        
        return view('layout.index',['data' => $data]);
    }

    public function loadSoldItem(Request $request){
        if ($request->ajax()) {
            $data = ItemSold::with('item')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('barcode', function($row){
                    return $row->item->barcode;
                })
                ->addColumn('artikel', function($row){
                    return $row->item->artikel;
                })
                ->addColumn('warna', function($row){
                    return $row->item->warna;
                })
                ->addColumn('size', function($row){
                    return $row->item->size;
                })
                ->addColumn('harga', function($row){
                    return 'Rp '.number_format($row->item->harga);
                })
                ->addColumn('created_at', function($row){
                    return date('d-m-Y H:i',strtotime($row->created_at));
                })
                ->make(true);
        }
    }

}