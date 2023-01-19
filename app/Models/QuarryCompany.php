<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuarryCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'quarry_id',
        'company_id',
    ];

    // filter data based on search
    // public function scopeFilterSearch($query, $request) {
    //     return $query->when($request->search, function ($query) use ($request) {
    //         $query->join('companies', function ($join) use ($request) {
    //             $join->on('quarry_companies.company_id', '=', 'companies.id')
    //                 ->where('companies.name', 'LIKE', '%'. $request->search . '%');
    //         });
    //     });
    // }
}
