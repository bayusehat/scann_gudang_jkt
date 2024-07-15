<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ItemSold;

class Item extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'items';
    protected $primaryKey = 'id_item';
    protected $guarded = [];
    public $timestamp = true;

    public function item_sold(){
        return $this->hasMany(ItemSold::class, 'id_item', 'id_item');
    }
}
