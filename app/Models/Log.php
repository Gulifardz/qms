<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'supermity_id',
        'checker_id',
        'company_id',
        'quarry_id',
        'driver_id',
        'truck_id',
        'check_out',
    ];

    protected $casts = [];


    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    // filter data based on search
    public function scopeFilterSearch($query, $request) {
        return $query->when($request->search, function ($query) use ($request) {
            $query->join('companies', function ($join) use ($request) {
                $join->on('logs.company_id', '=', 'companies.id')
                    ->where('companies.name', 'LIKE', '%'. $request->search . '%');
            });
        });
    }


    // get associated company
    public function company () {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }


    // get associated truck
    public function truck () {
        return $this->hasOne(Truck::class, 'id', 'truck_id');
    }


    // get associated driver
    public function driver () {
        return $this->hasOne(Driver::class, 'id', 'driver_id');
    }


    // get associated quarry
    public function quarry () {
        return $this->hasOne(Quarry::class, 'id', 'quarry_id');
    }


    // get associated checker
    public function checker () {
        return $this->hasOne(Checker::class, 'id', 'checker_id');
    }


    // get associated supermity
    public function supermity () {
        return $this->hasOne(Supermity::class, 'id', 'supermity_id');
    }


    // get associated bought
    public function bought () {
        return $this->hasMany(BoughtProduct::class, 'log_id', 'id');
    }
}
