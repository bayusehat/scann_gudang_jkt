<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Item;
use App\Models\ItemSold;
use App\Models\User;

class ItemSold extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'item_solds';
    protected $primaryKey = 'id_item_sold';
    public $timestamp = true;

    /**
     * Get the user that owns the ItemSold
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
