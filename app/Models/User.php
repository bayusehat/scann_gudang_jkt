<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\In;
use App\Models\Out;
use App\Models\ItemSold;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get all of the in for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function in()
    {
        return $this->hasMany(In::class, 'user_in', 'id');
    }

    public function out()
    {
        return $this->hasMany(Out::class, 'user_out', 'id');
    }

    public function item_sold(){
        return $this->belongsTo(ItemSold::class,'user_id','id');
    }
}
