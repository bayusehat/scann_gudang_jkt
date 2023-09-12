<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class In extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ins';
    protected $primaryKey = 'id_in';
    protected $hidden = ['created_at','deleted_at'];

    public $timestamps = true;

    /**
     * Get the user that owns the In
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_in');
    }
}
