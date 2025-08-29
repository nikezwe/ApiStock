<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'adresse',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relation Many-to-Many avec Stock
    public function stocks()
    {
        return $this->belongsToMany(Stock::class, 'stock_user', 'user_id', 'stock_id')
            ->withTimestamps();
    }
}
