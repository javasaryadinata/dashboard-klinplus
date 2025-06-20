<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwals'; // Nama tabel di database

    protected $primaryKey = 'id'; // Kolom primary key

    protected $fillable = [
        'status',
        'id_order',
        'nama_pelanggan',
        'alamat',
        'gmaps',
        'catatan',
        'tanggal_pengerjaan',
        'waktu_pengerjaan',
        'durasi',
        'waktu_selesai',
        'nama_petugas',
        'status_pembayaran',
    ];

    // Definisikan relasi dengan model Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }
}
