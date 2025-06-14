<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    
    protected $fillable = [
        'id_pelanggan',
        'nama_pelanggan',
        'telp_pelanggan',
        'email',
        'id_kota',
        'alamat_lokasi',
        'lokasi_gmaps',
        'catatan'
    ];
    
    protected $primaryKey = 'id_pelanggan';
    public $incrementing = false;
    protected $keyType = 'string';

    public function kota()
    {
        return $this->belongsTo(\App\Models\Kota::class, 'id_kota', 'id_kota');
    }

}



