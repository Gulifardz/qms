<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Truck;
use App\Models\TruckCategory;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'supermity_id',
        'checker_id',
        'company_id',
        'quarry_id',
        'truck_id',
        'driver_id',
        'product_id',
        'price',
        'ef',
        'rmf',
        'status',
        'checker_comment',
        'check_out',
    ];

    protected $appends = [
        'total_amount'
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    // get total amount attribute
    public function getTotalAmountAttribute () {
        $truck = Truck::with(['truck_category'])->where('id', $this->truck_id)->first();
        $category = TruckCategory::where('id', $this->truck_id)->first();
        $capacity = $truck->capacity;

        return (($this->price + $this->ef + $this->rmf) * $capacity) + $category->otf;
    }


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


    // get associated product
    public function product () {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
