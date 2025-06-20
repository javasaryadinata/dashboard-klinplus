<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetailPetugas extends Model
{
    protected $table   = 'order_detail_petugas';
    public $timestamps = false; // karena tabel ini tidak pakai created_at/updated_at

    protected $fillable = [
        'id_order_detail',
        'id_petugas',
    ];

    // RELASI KE OrderDetail
    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class, 'id_order_detail', 'id_order_detail');
    }

    // RELASI KE Petugas
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }
}
