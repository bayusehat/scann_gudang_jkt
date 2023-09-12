<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\In;
use App\Models\Out;
use DataTables;

class ActionController extends Controller
{
    public function indexIn(){
        $data = [
            'title' => 'Item Masuk',
            'content' => 'in'
        ];
        return view('layout.index',['data' => $data]);
    }

    public function indexOut(){
        $data = [
            'title' => 'Item Keluar',
            'content' => 'out'
        ];
        return view('layout.index',['data' => $data]);
    }

    public function loadIn(Request $request){
        if ($request->ajax()) {
            $data = In::with('user')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('kode_item', function($row){
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

    public function loadOut(Request $request){
        if ($request->ajax()) {
            $data = Out::with('user')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('kode_item', function($row){
                    return $row->kode_item;
                })
                ->addColumn('created_at', function($row){
                    return date('d-m-Y H:i',strtotime($row->created_at));
                })
                ->addColumn('user_out', function($row){
                    return $row->user->name;
                })
                ->make(true);
        }
    }

    public function auto_add(Request $request){
        $kode = $request->input('kode_item');
        if(strlen($kode) == 10){
            if($request->part == 'in'){
                $in = new In;
                $in->kode_item = $kode;
                $in->user_in = auth()->user()->id;
                if($in->save())
                    return response(['status' => 'success', 'message' => 'Kode item masuk berhasil terinput']);
            }else{
                $out = new Out;
                $out->kode_item = $kode;
                $out->user_out = auth()->user()->id;
                if($out->save())
                    return response(['status' => 'success', 'message' => 'Kode item keluar berhasil terinput']);
            }
            return response(['status' => 'error', 'message' => 'Terjadi kesalahan kode tidak terinput!']);
        }else{
            return response(['status' => 'error','message' => 'Kode tidak valid!']);
        }
    }
}
