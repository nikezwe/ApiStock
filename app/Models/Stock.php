<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'stocks';
    protected $guarded = [];

    /**
     * Relation : Un stock appartient à un utilisateur (créateur)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation : Un stock peut être partagé avec plusieurs utilisateurs (many-to-many)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'stock_user', 'stock_id', 'user_id');
    }

    /**
     * Relation : Le créateur du stock
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}