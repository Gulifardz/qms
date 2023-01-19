<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuarryRequest;
use App\Models\Quarry;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class QuarryController extends Controller
{
    // index
    public function index () {
        if (Auth::guard('web')->check()) {
            return view('app', [ 
                'dashboard' => true, 
                'resource' => 'quarries', 
                'class' => 'quarries',
                'title' => 'Quarries'
            ]);
        }
    }


    // list
    public function list (Request $request) {
        if (Auth::guard('web')->check()) {
            $quarries = Quarry::orderBy('id')
                ->filterSearch($request);
        }
        
        $per_page = $request->per_page;
        $skip = ($request->page - 1) * $per_page;
        $count = $quarries->count();

        $quarries = $quarries->skip($skip)->take($per_page)->get();
        
        $data = [ 
            'quarries' => $quarries,
            'total' => $count,
            'page' => $request->page,
            'per_page' => $per_page,
        ];
        
        return response()->json($data, 200);
    }


    // store
    public function store (QuarryRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            $data = [ 'status' => 'Failed Query' ];
            $status = 422;

            $request['password'] = Hash::make('1'); 

            $quarry = new Quarry($request->all());
            $quarry->save();

            if ($quarry) {
                $data = [];
                $status = 200;
            }
        }

        return response()->json($data, $status);
    }


    // edit
    public function edit ($id) {
        if (Auth::guard('web')->check()) {
            $quarry = Quarry::where('id', $id)->first();

            return view('app', [ 
                'dashboard' => true, 
                'resource' => 'quarry-form', 
                'class' => 'quarry-form',
                'title' => 'Edit Quarry',
                'quarry' => $quarry
            ]);
        }
    }


    // update
    public function update (QuarryRequest $request, $id) {
        $validated = $request->validated();

        if ($validated) {
            $data = [ 'status' => 'Failed Query' ];
            $status = 422;

            if (Auth::guard('web')->check()) {
                $quarry = Quarry::where('id', $id)->first();
            }
            
            $quarry->update($request->all());

            if ($quarry) {
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

        $quarry = Quarry::destroy($id);

        if ($quarry) {
            $data = [];
            $status = 200;
        }

        return response()->json($data, $status);
    }
}
