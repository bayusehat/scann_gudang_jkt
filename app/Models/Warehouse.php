<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\In;
use App\Models\Out;
use App\Models\User;

class Warehouse extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'warehouses';
    protected $primaryKey = 'id_warehouse';
    protected $guarded = [];
    public $timestamp = true;

    public function item_document(){
        return $this->hasMany(ItemDocument::class, 'id_warehouse', 'id_document');
    }
}
