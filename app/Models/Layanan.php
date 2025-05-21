<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pricelist';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pricelist',
        'nama_layanan',
        'durasi',
        'harga',
        'deskripsi'
    ];

    protected $casts = [
        'harga' => 'integer'
    ];

    // Relasi ke orders (jika pakai pivot)
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_details', 'id_pricelist', 'id_order')
            ->withPivot('estimasi_selesai', 'petugas', 'sub_total')
            ->withTimestamps();
    }
}
