<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckDriver extends Model
{
    use HasFactory;

    // protected
    protected $fillable = [
        'company_id',
        'truck_id',
        'driver_id',
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
        // 'deleted_at'
    ];


    protected $with = ['driver'];


    // associated driver
    public function driver () {
        return $this->hasOne(Driver::class, 'id', 'driver_id');
    }


    // associated company
    public function company () {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }


    // associated truck
    public function truck () {
        return $this->hasOne(Truck::class, 'id', 'truck_id');
    }
}
