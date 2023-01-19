<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    // protected
    protected $fillable = [
        'company_id',
        'firstname',
        'middlename',
        'lastname',
        'license_no',
        'contact_no',
        'address',
        'picture'
    ];


    // appends
    protected $appends = ['name'];


    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    // name attribute
    public function getNameAttribute()
    {
        $name = $this->firstname . ' ' . $this->lastname;

        return trim($name);
    }


    // filter data based on search
    public function scopeFilterSearch($query, $request) {
        return $query->when($request->search, function($q) use ($request) {
            $q->where('firstname', 'LIKE', '%'. $request->search . '%')
                ->orWhere('middlename', 'LIKE', '%'. $request->search . '%')
                ->orWhere('lastname', 'LIKE', '%'. $request->search . '%')
                ->orWhere('license_no', 'LIKE', '%'. $request->search . '%')
                ->orWhere('contact_no', 'LIKE', '%'. $request->search . '%')
                ->orWhere('address', 'LIKE', '%'. $request->search . '%');
        });
    }


    // associated truck
    public function truck () {
        return $this->hasOne(TruckDriver::class, 'driver_id', 'id')->select('truck_id');
    }


    // associated trcuk driver
    public function truck_driver () {
        return $this->belongsTo(Driver::class, 'id', 'driver_id');
    }
}
