<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\In;
use App\Models\Out;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;


class ItemImport implements ToModel, WithStartRow
{
    private $flag;

    public function __construct($flag) {
        $this->flag = $flag;
    }
    public function startRow(): int
    {
        return 2;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if($this->flag == 'master'){
            return new Item([
                'barcode' => $row[0],
                'artikel' => $row[1],
                'warna' => $row[2],
                'size' => $row[3],
                'harga' => $row[4],
                'gudang' => $row[5],
                'brand' => $row[6]
            ]);
        }else if($this->flag == 'in'){
            return new In([
                'kode_item' => $row[0],
                'user_in' => auth()->user()->id,
            ]);
        }else{
            return new Out([
                'kode_item' => $row[0],
                'user_out' => auth()->user()->id
            ]);
        }
    }
}
