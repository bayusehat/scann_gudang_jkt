<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemSold;
use App\Models\ItemDocument;
use Validator;
use Str;
use DataTables;
use App\Imports\ItemImport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use App\Models\Warehouse;

class ItemController extends Controller
{
    public function index(){
        $data = [
            'title' => 'Item Master',
            'content' => 'item',
            'menu_active' => true,
            'item' => true,
            'warna' => ['BLACK','WHITE','COFFEE','TANE','BEIGE','DARK GREY','BLUE','RED','TOSCA','BLUE ORANGE'],
            'size' => ['39','40','41', '42','43','44','45'],
            'lbrand' => DB::select("SELECT DISTINCT brand FROM items WHERE brand <> '' OR brand = NULL")
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
            'brand' => 'required'
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
            $i->brand = $request->input('brand');
            if($i->save()){
                return response(['status' => 200, 'message' => 'Item created successfully']);
            }else{
                return response(['status' => 500, 'message' => 'Caught an Error, cannot create new item']);
            }
        }
    }

    public function edit($id_item){
        $check = Item::leftJoin('brands','items.barcode','=','brands.barcode')->find($id_item);
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
            'menu_active' => true,
            'item_scan' => true
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

    public function importItem(Request $request){
        $rules = [
            'file' => 'required|mimes:csv,xls,xlsx'
        ];

        $isValid = Validator::make($request->all(),$rules);
        if($isValid->fails())
            return redirect()->back()->withErrors($isValid->errors());

            $file = $request->file('file');
            $nama_file = $file->hashName();
            $path = $file->storeAs('public/excel/',$nama_file);
            $import = Excel::import(new ItemImport($request->get('flag')), storage_path('app/public/excel/'.$nama_file));
            Storage::delete($path);
    
            if($import) {
                return response([
                    'status' => 200,
                    'message' => 'Data berhasil diimport!'
                ]);
            } else {
                return response([
                    'status' => 400,
                    'message' => 'Data gagal diimport! silahkan coba lagi'
                ]);
            }
    }

    public function report_stok_view(){
        $data = [
            'title' => 'Laporan Stok SO',
            'content' => 'report_stok',
            'menu_active' => true,
            'item_stok' => true,
            'gudang_list' => Warehouse::all()
        ];

        return view('layout.index',['data' => $data]);
    }

    public function reportStok(Request $request){
        $date_from = $request->input('date_from') ?? date('Y-m-d');
        $date_to = $request->input('date_to') ?? date('Y-m-d');
        $id_warehouse = $request->input('id_warehouse') ?? 0;
        if ($request->ajax()) {
        $data = DB::select("select *, (a_39+a_40+a_41+a_42+a_43+a_44+a_45) grand_total_awal,(k_39+k_40+k_41+k_42+k_43+k_44+k_45) grand_total_mutasi, (s_39+s_40+s_41+s_42+s_43+s_44+s_45) grand_total_akhir
        from(
            select artikel, warna, brand,
				sum(case when size = 39 then item_masuk else 0 end) a_39,
                sum(case when size = 40 then item_masuk else 0 end) a_40,
                sum(case when size = 41 then item_masuk else 0 end) a_41,
                sum(case when size = 42 then item_masuk else 0 end) a_42,
                sum(case when size = 43 then item_masuk else 0 end) a_43,
                sum(case when size = 44 then item_masuk else 0 end) a_44,
                sum(case when size = 45 then item_masuk else 0 end) a_45,
								
				sum(case when size = 39 then item_keluar else 0 end) k_39,
                sum(case when size = 40 then item_keluar else 0 end) k_40,
                sum(case when size = 41 then item_keluar else 0 end) k_41,
                sum(case when size = 42 then item_keluar else 0 end) k_42,
                sum(case when size = 43 then item_keluar else 0 end) k_43,
                sum(case when size = 44 then item_keluar else 0 end) k_44,
                sum(case when size = 45 then item_keluar else 0 end) k_45,
								
                sum(case when size = 39 then (item_masuk - item_keluar) else 0 end) s_39,
                sum(case when size = 40 then (item_masuk - item_keluar) else 0 end) s_40,
                sum(case when size = 41 then (item_masuk - item_keluar) else 0 end) s_41,
                sum(case when size = 42 then (item_masuk - item_keluar) else 0 end) s_42,
                sum(case when size = 43 then (item_masuk - item_keluar) else 0 end) s_43,
                sum(case when size = 44 then (item_masuk - item_keluar) else 0 end) s_44,
                sum(case when size = 45 then (item_masuk - item_keluar) else 0 end) s_45
            from(
                select d.*, (item_masuk - item_keluar) sisa_stok 
                from(
                select barcode, artikel, warna, size, gudang, brand, coalesce(item_masuk,0) item_masuk, coalesce(item_keluar,0) item_keluar
                from items a 
                    left join (
                    select a.kode_item, item_masuk, item_keluar
                    from(
                            select kode_item, count(kode_item) item_masuk from ins
                                left join item_documents b on ins.id_document = b.id_document
                                where b.document_date between '".$date_from."' and '".$date_to."' and b.deleted_at is null and b.document_type = 'IM' and b.id_warehouse = ".$id_warehouse."
                            group by kode_item
                        ) a 
                        left join (
                            select kode_item, count(kode_item) item_keluar from outs
                                left join item_documents b on outs.id_document = b.id_document
                                where b.document_date between '".$date_from."' and '".$date_to."' and b.deleted_at is null and b.document_type = 'IK' and b.id_warehouse = ".$id_warehouse."
                            group by kode_item
                        ) b
                    on a.kode_item = b.kode_item
                    ) b on a.barcode = b.kode_item
                ) d
            ) e
        group by artikel, warna, brand
        ) f
        ");

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('artikel', function($row){
                    return $row->artikel;
                })
                ->addColumn('warna', function($row){
                    return $row->warna;
                })
                ->addColumn('brand', function($row){
                    return $row->brand;
                })
                ->addColumn('a_39', function($row){
                    return $row->a_39 > 0 ? $row->a_39 : '.';
                })
                ->addColumn('a_40', function($row){
                    return $row->a_40 > 0 ? $row->a_40 : '.';
                })
                ->addColumn('a_41', function($row){
                    return $row->a_41 > 0 ? $row->a_41 : '.';
                })
                ->addColumn('a_42', function($row){
                    return $row->a_42 > 0 ? $row->a_42: '.';
                })
                ->addColumn('a_43', function($row){
                    return $row->a_43 > 0 ? $row->a_43: '.';
                })
                ->addColumn('a_44', function($row){
                    return $row->a_44 > 0 ? $row->a_44: '.';
                })
                ->addColumn('a_45', function($row){
                    return $row->a_45 > 0 ? row->a_45: '.';
                })
                ->addColumn('grand_total_awal', function($row){
                    return $row->grand_total_awal > 0 ? $row->grand_total_awal : '.';
                })
                ->addColumn('k_39', function($row){
                    return $row->k_39 > 0 ? $row->k_39 : '.';
                })
                ->addColumn('k_40', function($row){
                    return $row->k_40 > 0 ? $row->k_40 : '.';
                })
                ->addColumn('k_41', function($row){
                    return $row->k_41 > 0 ? $row->k_41 : '.';
                })
                ->addColumn('k_42', function($row){
                    return $row->k_42 > 0 ? $row->k_42 : '.';
                })
                ->addColumn('k_43', function($row){
                    return $row->k_43 > 0 ? $row->k_43 : '.';
                })
                ->addColumn('k_44', function($row){
                    return $row->k_44 > 0 ? $row->k_44 : '.';
                })
                ->addColumn('k_45', function($row){
                    return $row->k_45 > 0 ? $row->k_45 : '.';
                })
                ->addColumn('grand_total_mutasi', function($row){
                    return $row->grand_total_mutasi > 0 ? $row->grand_total_mutasi : '.';
                })
                ->addColumn('s_39', function($row){
                    return $row->s_39 > 0 ? $row->s_39 : '.';
                })
                ->addColumn('s_40', function($row){
                    return $row->s_40 > 0 ? $row->s_40 : '.';
                })
                ->addColumn('s_41', function($row){
                    return $row->s_41 > 0 ? $row->s_41 : '.';
                })
                ->addColumn('s_42', function($row){
                    return $row->s_42 > 0 ? $row->s_42 : '.';
                })
                ->addColumn('s_43', function($row){
                    return $row->s_43 > 0 ? $row->s_43 : '.';
                })
                ->addColumn('s_44', function($row){
                    return $row->s_44 > 0 ? $row->s_44 : '.';
                })
                ->addColumn('s_45', function($row){
                    return $row->s_45 > 0 ? $row->s_45 : '.';
                })
                ->addColumn('grand_total_akhir', function($row){
                    return $row->grand_total_akhir > 0 ? $row->grand_total_akhir : '.';
                })
                ->make(true);
            }
    }

    //Item Document
    public function generateNumber($type){
        $datenow = date('Y-m');
        $getItem = ItemDocument::where('document_date','LIKE',"$datenow%")->latest()->first();

        if(empty($getItem->counter)){
            $counterVal = 1;
        }else{
            $counterVal = $getItem->counter + 1;
        }

        // $document_number = $type.date('ym').sprintf("%04s",$counterVal);

        // return response([
        //     'docnum' => $document_number,
        //     'counter' => $counterVal,
        //     'type' => $type
        // ]);
    }

    public function printStok(Request $request){
        $date_from = $request->get('from') ?? date('Y-m-d');
        $date_to = $request->get('to') ?? date('Y-m-d');
        $id_warehouse = $request->get('warehouse') ?? 0;
        $q = DB::select("select *, (a_39+a_40+a_41+a_42+a_43+a_44+a_45) grand_total_awal,(k_39+k_40+k_41+k_42+k_43+k_44+k_45) grand_total_mutasi, (s_39+s_40+s_41+s_42+s_43+s_44+s_45) grand_total_akhir
        from(
            select artikel, warna, brand,
				sum(case when size = 39 then item_masuk else 0 end) a_39,
                sum(case when size = 40 then item_masuk else 0 end) a_40,
                sum(case when size = 41 then item_masuk else 0 end) a_41,
                sum(case when size = 42 then item_masuk else 0 end) a_42,
                sum(case when size = 43 then item_masuk else 0 end) a_43,
                sum(case when size = 44 then item_masuk else 0 end) a_44,
                sum(case when size = 45 then item_masuk else 0 end) a_45,
								
				sum(case when size = 39 then item_keluar else 0 end) k_39,
                sum(case when size = 40 then item_keluar else 0 end) k_40,
                sum(case when size = 41 then item_keluar else 0 end) k_41,
                sum(case when size = 42 then item_keluar else 0 end) k_42,
                sum(case when size = 43 then item_keluar else 0 end) k_43,
                sum(case when size = 44 then item_keluar else 0 end) k_44,
                sum(case when size = 45 then item_keluar else 0 end) k_45,
								
                sum(case when size = 39 then (item_masuk - item_keluar) else 0 end) s_39,
                sum(case when size = 40 then (item_masuk - item_keluar) else 0 end) s_40,
                sum(case when size = 41 then (item_masuk - item_keluar) else 0 end) s_41,
                sum(case when size = 42 then (item_masuk - item_keluar) else 0 end) s_42,
                sum(case when size = 43 then (item_masuk - item_keluar) else 0 end) s_43,
                sum(case when size = 44 then (item_masuk - item_keluar) else 0 end) s_44,
                sum(case when size = 45 then (item_masuk - item_keluar) else 0 end) s_45
            from(
                select d.*, (item_masuk - item_keluar) sisa_stok 
                from(
                select barcode, artikel, warna, size, gudang, brand, coalesce(item_masuk,0) item_masuk, coalesce(item_keluar,0) item_keluar
                from items a 
                    left join (
                    select a.kode_item, item_masuk, item_keluar
                    from(
                            select kode_item, count(kode_item) item_masuk from ins
                                left join item_documents b on ins.id_document = b.id_document
                                where b.document_date between '".$date_from."' and '".$date_to."' and b.deleted_at is null and b.document_type = 'IM' and b.id_warehouse = ".$id_warehouse."
                            group by kode_item
                        ) a 
                        left join (
                            select kode_item, count(kode_item) item_keluar from outs
                                left join item_documents b on outs.id_document = b.id_document
                                where b.document_date between '".$date_from."' and '".$date_to."' and b.deleted_at is null and b.document_type = 'IK' and b.id_warehouse = ".$id_warehouse."
                            group by kode_item
                        ) b
                    on a.kode_item = b.kode_item
                    ) b on a.barcode = b.kode_item
                ) d
            ) e
        group by artikel, warna, brand
        ) f
        ");

        $data = [
            'title' => 'Print Stok ',
            'data' => $q,
            'gudang' => Warehouse::find($id_warehouse)
        ];

        return view('print.print_stok', ['data' =>  $data]);
    }

    public function printIn($id){
       
        $q = DB::select("
        select *, (a_39+a_40+a_41+a_42+a_43+a_44+a_45) grand_total_awal
            from(
                select artikel, warna, brand,
                sum(case when size = 39 then item_masuk else 0 end) a_39,
                sum(case when size = 40 then item_masuk else 0 end) a_40,
                sum(case when size = 41 then item_masuk else 0 end) a_41,
                sum(case when size = 42 then item_masuk else 0 end) a_42,
                sum(case when size = 43 then item_masuk else 0 end) a_43,
                sum(case when size = 44 then item_masuk else 0 end) a_44,
                sum(case when size = 45 then item_masuk else 0 end) a_45
                from (
                    select barcode, artikel, warna, size, gudang, brand, coalesce(item_masuk,0) item_masuk
                    from items a 
                        join (
                        select kode_item, count(kode_item) item_masuk from ins
                            left join item_documents b on ins.id_document = b.id_document
                            where b.id_document = $id
                        group by kode_item
                    ) b on a.barcode = b.kode_item
                ) a 
                group by artikel, warna, brand
            ) b 
        ");

        $data = [
            'title' => 'Print Stok ',
            'data' => $q,
            'main' => ItemDocument::with('warehouse')->find($id)
        ];

        return view('print.print_in', ['data' =>  $data]);
    }

    public function printOut($id){
       
        $q = DB::select("
        select *, (k_39+k_40+k_41+k_42+k_43+k_44+k_45) grand_total_akhir
            from(
                select artikel, warna, brand,
                sum(case when size = 39 then item_keluar else 0 end) k_39,
                sum(case when size = 40 then item_keluar else 0 end) k_40,
                sum(case when size = 41 then item_keluar else 0 end) k_41,
                sum(case when size = 42 then item_keluar else 0 end) k_42,
                sum(case when size = 43 then item_keluar else 0 end) k_43,
                sum(case when size = 44 then item_keluar else 0 end) k_44,
                sum(case when size = 45 then item_keluar else 0 end) k_45
                from (
                    select barcode, artikel, warna, size, gudang, brand, coalesce(item_keluar,0) item_keluar
                    from items a 
                        join (
                        select kode_item, count(kode_item) item_keluar from outs
                            left join item_documents b on outs.id_document = b.id_document
                            where b.id_document = $id
                        group by kode_item
                    ) b on a.barcode = b.kode_item
                ) a 
                group by artikel, warna, brand
            ) b 
        ");

        $data = [
            'title' => 'Print Stok ',
            'data' => $q,
            'main' => ItemDocument::with('warehouse')->find($id)
        ];

        return view('print.print_out', ['data' =>  $data]);
    }

}
