<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TheOrder extends Model
{
    use HasFactory;
    protected $table = 'TheOrder';
    protected $primaryKey = 'id_donhang';
    protected $fillable = [
        'tendonhang', 'hinhthucthanhtoan', 'tenkhachhang', 'diachi', 'sdt',
        'email', 'ngaydat', 'trangthai', 'user_id', 'session_id'
    ];

    public $timestamps = true;

    protected $casts = [
        'trangthai' => 'string', // Enum: 'Chờ xác nhận', 'Đã xác nhận'
        'ngaydat' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'id_donhang', 'id_donhang');
    }
}
