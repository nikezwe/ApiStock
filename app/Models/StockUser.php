<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockUser extends Model
{
    use HasFactory;

    protected $table = 'stock_user';

    protected $fillable = ['user_id', 'stock_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function stock() {
        return $this->belongsTo(Stock::class);
    }
}
