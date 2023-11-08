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
            'active' => true,
            'warna' => ['BLACK','WHITE','COFFEE','TANE','BEIGE','DARK GREY','BLUE','RED','TOSCA','BLUE ORANGE'],
            'size' => ['39','40','41', '42','43','44','25']
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
                        <a href="javascript:void(0)" onclick="editItem('.$row->id_item.')" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id_item.')" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function insert(Request $request){
        $rules = [
            'barcode' => 'required',
            'artikel' => 'required',
            'warna' => 'required',
            'size' => 'required',
        ];

        $isValid = Validator::make($request->all(),$rules);

        if($isValid->fails()){
            return response(['status' => 400, 'errors' => $isValid->errors()]);
        }else{
            $i = new Item;
            $i->barcode = $request->input('barcode');
            $i->artikel = $request->input('artikel');
            $i->warna = $request->input('warna');
            $i->size = $request->input('size');
            $i->harga = $request->input('harga');
            if($i->save()){
                return response(['status' => 200, 'message' => 'Item created successfully']);
            }else{
                return response(['status' => 500, 'message' => 'Caught an Error, cannot create new item']);
            }
        }
    }

    public function edit($id_item){
        $check = Item::find($id_item);
        if($check){
            return response(['status' => 200, 'data' => $check]);
        }

        return response(['status' => 500,'message' => 'Data not found']);
    }

    public function update(Request $request, $id_item){
        $i = Item::find($id_item);
        if(!$i){
            return response(['status' => 500, 'message' => 'Data not found']);
        }
        
        $rules = [
            'barcode' => 'required',
            'artikel' => 'required',
            'warna' => 'required',
            'size' => 'required',
        ];

        $isValid = Validator::make($request->all(),$rules);

        if($isValid->fails()){
            return response(['status' => 400, 'errors' => $isValid->errors()]);
        }else{
            $i->barcode = $request->input('barcode');
            $i->artikel = $request->input('artikel');
            $i->warna = $request->input('warna');
            $i->size = $request->input('size');
            $i->harga = $request->input('harga');
            if($i->save()){
                return response(['status' => 200, 'message' => 'Item updated successfully']);
            }else{
                return response(['status' => 500, 'message' => 'Caught an Error, cannot update new item']);
            }
        }
    }

    public function destroy($id_item){
        $item = Item::find($id_item);
        if($item->delete()){
            return response(['status' => 200, 'message' => 'Item deleted successfully']);
        }

        return response(['status' => 500, 'message' => 'Error! cannot delete item']);
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
                ->addColumn('action',function($row){
                    return '<div class="btn-group" role="group" aria-label="Basic example">
                            <a href="javascript:void(0)" onclick="deleteItemSold('.$row->id_item_sold.')" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function destroyItemScan($id_item){
        $dis = ItemSold::find($id_item);
        if(!$dis){
            return response(['status' => 500, 'message' => 'Data not found']);
        }

        if($dis->delete()){
            return response(['status' => 200, 'message' => 'Item Sold deleted successfully']);
        }

        return response(['status' => 500, 'message' => 'Error! cannot delete Item Sold']);
    }

}
