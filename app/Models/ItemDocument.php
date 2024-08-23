<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\In;
use App\Models\Out;
use App\Models\User;

class ItemDocument extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'item_documents';
    protected $primaryKey = 'id_document';
    protected $guarded = [];
    public $timestamp = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item_in(){
        return $this->hasMany(In::class, 'id_document', 'id_document');
    }

    public function item_out(){
        return $this->hasMany(Out::class, 'id_document','id_document');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'id_warehouse', 'id_warehouse');
    }
}
