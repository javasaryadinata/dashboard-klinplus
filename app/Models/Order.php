<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table      = 'orders';
    protected $primaryKey = 'id_order';
    public $incrementing  = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'id_order',
        'id_pelanggan',
        'alamat_lokasi',
        'lokasi_gmaps',
        'catatan',
        'tanggal_pengerjaan',
        'jam_pengerjaan',
        'total_harga',
        'diskon',
        'kode',
        'metode_pembayaran',
        'tipe_pembayaran',
        'alasan_reschedule',
        'reschedule_from',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'id_order', 'id_order');
    }
    
    /**
    * Relasi ke order yang di-reschedule (parent)
    */
    public function rescheduleParent()
    {
        return $this->belongsTo(Order::class, 'reschedule_from', 'id_order');
    }

    /**
     * Relasi ke order hasil reschedule dari order ini (children)
     */
    public function reschedules()
    {
        return $this->hasMany(Order::class, 'reschedule_from', 'id_order');
    }

    // Order hasil reschedule dari order sebelumnya
    public function orderAsal()
    {
        return $this->belongsTo(Order::class, 'reschedule_from', 'id_order');
    }

    // Order yang menggantikan order ini (kalau ada)
    public function orderPengganti()
    {
        return $this->hasOne(Order::class, 'reschedule_from', 'id_order');
    }

}
