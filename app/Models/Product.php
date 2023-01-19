<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    // filter data based on search
    public function scopeFilterSearch($query, $request) {
        return $query->when($request->search, function($q) use ($request) {
            $q->where('name', 'LIKE', '%'. $request->search . '%');
        });
    }


    // get associated bought
    public function bought () {
        return $this->hasMany(BoughtProduct::class, 'product_id', 'id');
    }
}
