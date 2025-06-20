<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table      = 'order_detail';
    protected $primaryKey = 'id_order_detail';
    public $incrementing  = true;
    protected $keyType    = 'int';

    protected $fillable = [
        'id_order',
        'id_layanan_subkategori',
        'harga',
        'id_petugas',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    public function layananSubkategori()
    {
        return $this->belongsTo(LayananSubkategori::class, 'id_layanan_subkategori', 'id');
    }

    public function petugas()
    {
        return $this->belongsToMany(Petugas::class, 'order_detail_petugas', 'id_order_detail', 'id_petugas', 'id_order_detail', 'id_petugas');
    }
}
