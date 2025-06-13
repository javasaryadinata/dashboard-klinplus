<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayananSubKategori extends Model
{
    protected $table = 'layanan_subkategori';

    protected $fillable = [
        'layanan_rootkategori_id',
        'nama_subkategori',
        'harga',
    ];

    public function rootkategori()
    {
        return $this->belongsTo(LayananRootKategori::class, 'layanan_rootkategori_id');
    }
}