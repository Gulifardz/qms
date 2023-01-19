<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuarryCompany;
use App\Models\Company;
use App\Models\Quarry;
use Illuminate\Support\Facades\Auth;

class QuarryCompanyController extends Controller
{
    // index
    public function index ($quarry_id) {
        if (Auth::guard('web')->check()) {
            $quarry = Quarry::where('id', $quarry_id)->first();

            return view('app', [ 
                'dashboard' => true, 
                'resource' => 'quarry-companies', 
                'class' => 'quarry-companies',
                'title' => $quarry->name,
                'quarry' => $quarry
            ]);
        }
    }


    // list
    public function list (Request $request) {
        $tagged_companies_id = QuarryCompany::where('quarry_id', $request->quarry_id)
            ->pluck('company_id')
            ->toArray();

        $quarry_companies = Company::orderBy('id')
            ->filterSearch($request);

        if ($request->status === 'allowed') {
            $quarry_companies = $quarry_companies->whereIn('id', $tagged_companies_id)
                ->filterSearch($request);
        }

        if ($request->status === 'restricted') {
            $quarry_companies = $quarry_companies->whereNotIn('id', $tagged_companies_id)
                ->filterSearch($request);
        }

        $per_page = $request->per_page;
        $skip = ($request->page - 1) * $per_page;
        $count = $quarry_companies->count();

        $quarry_companies = $quarry_companies->skip($skip)->take($per_page)->get();

        $quarry_companies->map(function($column) use ($tagged_companies_id) {
            $column['tagged'] = in_array($column->id, $tagged_companies_id);
        });
        
        $data = [ 
            'quarry_companies' => $quarry_companies,
            'total' => $count,
            'page' => $request->page,
            'per_page' => $per_page,
        ];
        
        return response()->json($data, 200);
    }


    // grant
    public function grant (Request $request) {
        $data = [ 'status' => 'Failed Query' ];
        $status = 422;

        $quarry_company = new QuarryCompany([
            'quarry_id' => $request->input('quarry_id'),
            'company_id' => $request->input('company_id'),
        ]);

        $quarry_company->save();

        if ($quarry_company) {
            $data = [];
            $status = 200;
        }

        return response()->json($data, $status);
    }


    // revoke
    public function revoke (Request $request) {
        $data = [ 'status' => 'Failed Query' ];
        $status = 422;

        $quarry_company = QuarryCompany::where('quarry_id', $request->quarry_id)
            ->where('company_id', $request->company_id)
            ->delete();

        if ($quarry_company) {
            $data = [];
            $status = 200;
        }

        return response()->json($data, $status);
    }
}
