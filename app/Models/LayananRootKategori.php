<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayananRootKategori extends Model
{
    protected $table = 'layanan_rootkategori';

    protected $fillable = [
        'nama_rootkategori',
    ];

    public function subkategori()
    {
        return $this->hasMany(LayananSubKategori::class, 'layanan_rootkategori_id');
    }
}