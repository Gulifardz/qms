<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'otf'
    ];


    // filter data based on search
    public function scopeFilterSearch($query, $request) {
        return $query->when($request->search, function($q) use ($request) {
            $q->where('name', 'LIKE', '%'. $request->search . '%');
        });
    }


    // associated truck drivers
    public function truck () {
        return $this->belongsTo(Truck::class, 'truck_category_id', 'id');
    }
}
