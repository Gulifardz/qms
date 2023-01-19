<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoughtProduct extends Model
{
    use HasFactory;


    // protected
    protected $fillable = [
        'log_id',
        'product_id',
        'price',
        'quantity',
        'ef',
        'rmf',
        'capacity'
    ];


    // associated product
    public function product () {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
