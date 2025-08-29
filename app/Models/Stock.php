<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stocks';
    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'stock_user', 'stock_id', 'user_id')
            ->withTimestamps();
    }
}
