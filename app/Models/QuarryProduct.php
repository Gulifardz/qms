<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuarryProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'quarry_id',
        'product_id',
        'price',
        'ef',
        'rmf'
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    // filter data based on search
    public function scopeFilterSearch($query, $request) {
        return $query->when($request->search, function ($query) use ($request) {
            $query->join('products', function ($join) use ($request) {
                $join->on('quarry_products.product_id', '=', 'products.id')
                    ->where('products.name', 'LIKE', '%'. $request->search . '%');
            });
        });
    }


    // associated product
    public function product () {
        return $this->belongsTo(Product::class);
    }
}
