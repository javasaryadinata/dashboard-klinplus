<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;

    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id_petugas', 'nama_petugas', 'no_telp'];

    public function orderDetails()
    {
        return $this->belongsToMany(OrderDetail::class, 'order_detail_petugas','id_order_detail', 'id_petugas', 'id_order_detail', 'id_petugas');
    }
}