<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $guarded = [];

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
     * Relation : Un utilisateur peut avoir plusieurs stocks qu'il a créés
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'user_id');
    }

    /**
     * Relation : Les stocks créés par cet utilisateur
     */
    public function myStocks()
    {
        return $this->hasMany(Stock::class, 'user_id');
    }

    /**
     * Relation : Les stocks qui sont partagés avec cet utilisateur (many-to-many)
     */
    public function sharedStocks()
    {
        return $this->belongsToMany(Stock::class, 'stock_user', 'user_id', 'stock_id');
    }
}