<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    //
    public function order()
{
    return $this->belongsTo(Order::class, 'id_order');
}

public function layanans()
{
    return $this->belongsTo(Layanan::class, 'id_layanan');
}

}
