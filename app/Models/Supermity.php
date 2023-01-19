<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supermity extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quarry_id',
        'firstname',
        'lastname',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];


    // appends
    protected $appends = ['name'];


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
                ->orWhere('lastname', 'LIKE', '%'. $request->search . '%')
                ->orWhere('email', 'LIKE', '%'. $request->search . '%');
        });
    }


    // associated quarry
    public function quarry () {
        return $this->hasOne(Quarry::class, 'id', 'quarry_id');
    }
}
