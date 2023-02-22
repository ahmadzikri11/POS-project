<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'produck_id',
        'qty',
        'total',
    ];

    public function productOrder()
    {
        return $this->hasmany('App\models\OrderProduct', 'order_id');
    }
}
