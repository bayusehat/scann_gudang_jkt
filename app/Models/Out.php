<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Out extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'outs';
    protected $primaryKey = 'id_out';
    protected $guarded = [];
    protected $hidden = ['created_at','deleted_at'];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_out');
    }
}
