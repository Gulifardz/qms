<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Driver;
use App\Models\TruckDriver;
use Illuminate\Support\Facades\Auth;

class Truck extends Model
{
    use HasFactory;

    // protected
    protected $fillable = [
        'company_id',
        'brand',
        'year_model',
        'capacity',
        'truck_category_id',
        'orcr',
        'plate_no',
    ]; 

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    // filter data based on search
    public function scopeFilterSearch($query, $request) 
    {
        return $query->when($request->search, function($q) use ($request) {
            $q->where('brand', 'LIKE', '%'. $request->search . '%')
                ->orWhere('year_model', 'LIKE', '%'. $request->search . '%')
                ->orWhere('orcr', 'LIKE', '%'. $request->search . '%')
                ->orWhere('plate_no', 'LIKE', '%'. $request->search . '%');
        });
    }


    // associated truck category
    public function truck_category () {
        return $this->hasOne(TruckCategory::class, 'id', 'truck_category_id');
    }


    // associated company
    public function company () {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }


    // associated truck drivers
    public function drivers () {
        return $this->hasMany(TruckDriver::class, 'truck_id', 'id');
    }
}
