<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quarry extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'contact_no',
        'email',
        'lgu',
        'host_barangay',
        'quarry_area_td',
        'ie_route',
        'provincial_permit',
        'regional_permit',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    protected $casts = [
        'ie_route' => 'array',
        'provincial_permit' => 'array',
        'regional_permit' => 'array'
    ];


    // filter data based on search
    public function scopeFilterSearch($query, $request) {
        return $query->when($request->search, function($q) use ($request) {
            $q->where('name', 'LIKE', '%'. $request->search . '%')
                ->orWhere('email', 'LIKE', '%'. $request->search . '%')
                ->orWhere('address', 'LIKE', '%'. $request->search . '%')
                ->orWhere('contact_no', 'LIKE', '%'. $request->search . '%');
        });
    }


    // associated product
    public function quarry_product () {
        return $this->hasMany(QuarryProduct::class);
    }


    // associated logs
    public function logs () {
        return $this->hasMany(Log::class);
    }
}
