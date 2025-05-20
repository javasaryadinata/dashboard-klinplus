<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_order';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id_order',
        'id_pelanggan',
        'status',
        'gmaps',
        'tanggal_pembersihan',
        'waktu_pembersihan'
    ];

    /**
     * Relasi ke pelanggan (many-to-one)
     */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    /**
     * Relasi ke layanan (many-to-many via order_details)
     */
    public function layanans(): BelongsToMany
{
    return $this->belongsToMany(
        \App\Models\Layanan::class,
        'order_details',
        'id_order',
        'id_pricelist',
        'id_order',
        'id_pricelist'
    )
    ->withPivot('estimasi_selesai', 'petugas', 'sub_total')
    ->withTimestamps();
}
    /**
     * Relasi ke detail order (one-to-many)
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'id_order', 'id_order');
    }

    /**
     * Mutator untuk mengubah format tanggal_pembersihan menjadi Carbon
     */
    public function setTanggalPembersihanAttribute($value)
    {
        $this->attributes['tanggal_pembersihan'] = $value ? \Carbon\Carbon::parse($value) : null;
    }
}
