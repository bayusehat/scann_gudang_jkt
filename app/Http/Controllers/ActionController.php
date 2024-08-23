<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\In;
use App\Models\Out;
use App\Models\ItemDocument;
use DataTables;
use App\Models\Warehouse;

class ActionController extends Controller
{
    public function indexIn(){
        $data = [
            'title' => 'Item Masuk',
            'content' => 'in_data',
            'item_masuk' => true,
            'gudang_list' => Warehouse::all()
        ];
        return view('layout.index',['data' => $data]);
    }

    public function indexOut(){
        $data = [
            'title' => 'Item Keluar',
            'content' => 'out_data',
            'item_keluar' => true
        ];
        return view('layout.index',['data' => $data]);
    }

    public function loadIn(Request $request){
        if ($request->ajax()) {
            $data = ItemDocument::where('document_type','IM')->with('user')->latest()->withTrashed()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('document_number', function($row){
                    return $row->document_number;
                })
                ->addColumn('document_date', function($row){
                    return date('d-m-Y H:i',strtotime($row->document_date));
                })
                ->addColumn('user_id', function($row){
                    return $row->user->name;
                })
                ->addColumn('status', function($row){
                    $status = $row->deleted_at != null ? '<span class="badge bg-danger">Batal</span>' : '<span class="badge bg-success">Aktif</span>';
                    return $status;
                })
                ->addColumn('action', function($row){
                    return '<a href="'.url("in/document/".$row->id_document).'" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit / Detail</a>
                    <a href="'.url("print/in/".$row->id_document).'" target="__blank" class="btn btn-success btn-sm"><i class="fa fa-print"></i> Print</a>';
                })
                ->rawColumns(['action','status'])
                ->make(true);
        }
    }

    public function loadInDetail(Request $request){
        if ($request->ajax()) {
            $data = In::where('id_document',$request->id_document)->with('user')->latest()->withTrashed()->get();
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

    public function generateNumber($type){
        $data = [];
        $datenow = date('Y-m');
        $getItem = ItemDocument::where('document_date','LIKE',"$datenow%")
                    ->where('document_type','=',$type)
                    ->latest()->withTrashed()->first();
        if(empty($getItem->counter)){
            $counterVal = 1;
        }else{
            $counterVal = $getItem->counter + 1;
        }

        $document_number = $type.date('ym').sprintf("%04s",$counterVal);

        return $data[] = [
            'docnum' => $document_number,
            'counter' => $counterVal,
            'type' => $type
        ];
    }

    public function createIn($id){
        $data = [
            'title' => 'Tambah Dokumen Item Masuk',
            'content' => 'in',
            'data' => ItemDocument::withTrashed()->find($id),
            'item_masuk' => true,
            'gudang_list' => Warehouse::all()
        ];
        return view('layout.index',['data' => $data]);
    }

    public function insertDocument($type){
        if($type == 'IM'){
            $segment = 'in';
        }else{
            $segment = 'out';
        }

        $gen = $this->generateNumber($type);
        $ido = new ItemDocument;
        $ido->document_number = $gen['docnum'];
        $ido->document_type = $gen['type'];
        $ido->document_date = date('Y-m-d');
        $ido->counter = $gen['counter'];
        $ido->user_id = auth()->user()->id;
        if($ido->save()){

            return redirect($segment.'/document/'.$ido->id_document);
        }else{
           return redirect()->back();
        }
    }

    public function deleteDocument($id){
        $doc = ItemDocument::find($id);
        if(!$doc)
            return response(['status' => 500, 'message' => 'Data tidak ditemukan!']);

        if($doc->delete()){

            if($doc->document_type == 'IM'){
                In::where('id_document',$id)->delete();
                $segment = 'in';
            }else{
                Out::where('id_document',$id)->delete();
                $segment = 'out';
            }

            return response([
                'status' => 200,
                'message' => 'Data telah dihapus',
                'url' => $segment
            ]);
        }

        return response([
            'status' => 400,
            'message' => "Error!"
        ]);
    }

    public function loadOut(Request $request){
        if ($request->ajax()) {
            $data = ItemDocument::where('document_type','IK')->with('user')->latest()->withTrashed()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('document_number', function($row){
                    return $row->document_number;
                })
                ->addColumn('document_date', function($row){
                    return date('d-m-Y H:i',strtotime($row->document_date));
                })
                ->addColumn('user_id', function($row){
                    return $row->user->name;
                })
                ->addColumn('status', function($row){
                    $status = $row->deleted_at != null ? '<span class="badge bg-danger">Batal</span>' : '<span class="badge bg-success">Aktif</span>';
                    return $status;
                })
                ->addColumn('action', function($row){
                    return '<a href="'.url("out/document/".$row->id_document).'" class="btn btn-primary btn-sm"> Edit / Detail</a>
                     <a href="'.url("print/out/".$row->id_document).'" target="__blank" class="btn btn-success btn-sm"><i class="fa fa-print"></i> Print</a>';
                })
                ->rawColumns(['action','status'])
                ->make(true);
        }
    }

    public function createOut($id){
        $data = [
            'title' => 'Tambah Dokumen Item Keluar',
            'content' => 'out',
            'data' => ItemDocument::withTrashed()->find($id),
            'item_keluar' => true,
            'gudang_list' => Warehouse::all()
        ];
        return view('layout.index',['data' => $data]);
    }

    public function loadOutDetail(Request $request){
        if ($request->ajax()) {
            $data = Out::where('id_document',$request->id_document)->with('user')->latest()->get();
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
        if(strlen($kode) == 14){
            if($request->part == 'in'){
                $in = new In;
                $in->id_document = $request->id_document;
                $in->kode_item = $kode;
                $in->user_in = auth()->user()->id;
                if($in->save())
                    return response(['status' => 'success', 'message' => 'Kode item masuk berhasil terinput']);
            }else{
                //Check
                $checkIn = In::where('kode_item',$kode)->count();
                if($checkIn){
                    $out = new Out;
                    $out->id_document = $request->id_document;
                    $out->kode_item = $kode;
                    $out->user_out = auth()->user()->id;
                    if($out->save())
                        return response(['status' => 'success', 'message' => 'Kode item keluar berhasil terinput']);
                }else{
                   return response(['status' => 'error', 'message' => 'Kode item tidak ditemukan di Item masuk!']);
                }
            }
            return response(['status' => 'error', 'message' => 'Terjadi kesalahan kode tidak terinput!']);
        }else{
            return response(['status' => 'error','message' => 'Kode tidak valid!']);
        }
    }
}
