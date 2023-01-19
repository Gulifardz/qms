<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'address',
        'contact_no',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    // /**
    //  * Append custom columns to the model
    //  * 
    //  * @var array
    //  */
    // protected $appends = [
    //     'tagged'
    // ];

    // /**
    //  * Define the type column to every Item object instance
    //  * 
    //  * @return string
    //  */
    // public function getTaggedAttribute()
    // {
    //     return 'car';
    // }


    // filter data based on search
    public function scopeFilterSearch($query, $request) {
        return $query->when($request->search, function($q) use ($request) {
            $q->where('name', 'LIKE', '%'. $request->search . '%')
                ->orWhere('email', 'LIKE', '%'. $request->search . '%')
                ->orWhere('address', 'LIKE', '%'. $request->search . '%')
                ->orWhere('contact_no', 'LIKE', '%'. $request->search . '%');
        });
    }
}
