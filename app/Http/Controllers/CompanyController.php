<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    // index
    public function index () {
        return view('app', [ 
            'dashboard' => true, 
            'resource' => 'companies', 
            'class' => 'companies',
            'title' => 'Companies'
        ]);
    }


    // list (backend)
    public function list (Request $request) {
        $companies = Company::orderBy('id')
            ->filterSearch($request);

        $per_page = $request->per_page;
        $skip = ($request->page - 1) * $per_page;
        $count = $companies->count();

        $companies = $companies->skip($skip)->take($per_page)->get();
        
        $data = [ 
            'companies' => $companies,
            'total' => $count,
            'page' => $request->page,
            'per_page' => $per_page,
        ];
        
        return response()->json($data, 200);
    }


    // store
    public function store (CompanyRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            $data = [ 'status' => 'Failed Query' ];
            $status = 422;

            $company = new Company([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'address' => $request->input('address'),
                'contact_no' => $request->input('contact_no'),
                'password' => Hash::make('1')
            ]);

            $company->save();

            if ($company) {
                $data = [];
                $status = 200;
            }
        }

        return response()->json($data, $status);
    }


    // edit
    public function edit ($id) {
        $company = Company::where('id', $id)->first();

        return view('app', [ 
            'dashboard' => true, 
            'resource' => 'company-form', 
            'class' => 'company-form',
            'title' => 'Edit Company',
            'company' => $company
        ]);
    }


    // update
    public function update (CompanyRequest $request, $id) {
        $validated = $request->validated();

        if ($validated) {
            $data = [ 'status' => 'Failed Query' ];
            $status = 422;

            $company = Company::where('id', $id)->first();
            $company->update($request->all());

            if ($company) {
                $data = [];
                $status = 200;
            }
        }

        return response()->json($data, $status);
    }


    // destroy
    public function destroy ($id) {
        $data = [ 'status' => 'Failed Query' ];
        $status = 422;

        $company = Company::destroy($id);

        if ($company) {
            $data = [];
            $status = 200;
        }

        return response()->json($data, $status);
    }
}
