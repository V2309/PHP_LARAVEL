<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'session_id', 'user_id'
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
{
    return $this->hasMany(CartItem::class, 'cart_id');
}
}
