<?php

namespace App\Http\Controllers;

use App\Models\Supermity;
use App\Http\Requests\SupermityRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SupermityController extends Controller
{
    // index
    public function index () {
        return view('app', [ 
            'dashboard' => true, 
            'resource' => 'supermities', 
            'class' => 'supermities',
            'title' => 'Supermities'
        ]);
    }


    // list
    public function list (Request $request) {
        if (Auth::guard('web')->check()) {
            $supermities = Supermity::with(['quarry'])
                ->orderBy('id')
                ->filterSearch($request);
        } else if (Auth::guard('quarry')->check()) {
            $supermities = Supermity::where('quarry_id', Auth::guard('quarry')->user()->id)
                ->orderBy('id')
                ->filterSearch($request);
        }
        

        $per_page = $request->per_page;
        $skip = ($request->page - 1) * $per_page;
        $count = $supermities->count();

        $supermities = $supermities->skip($skip)->take($per_page)->get();
        
        $data = [ 
            'supermities' => $supermities,
            'total' => $count,
            'page' =>  $request->page,
            'per_page' => $per_page,
        ];
        
        return response()->json($data, 200);
    }


    // add
    public function add () {
        return view('app', [ 
            'dashboard' => true, 
            'resource' => 'supermity-form', 
            'class' => 'supermity-form',
            'title' => 'Supermities'
        ]);
    }


    // store
    public function store (SupermityRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            $data = [ 'status' => 'Failed Query' ];
            $status = 422;

            $request['password'] = Hash::make('1');
            $supermity = new Supermity($request->all());
            $supermity->save();

            if ($supermity) {
                $data = [];
                $status = 200;
            }
        }

        return response()->json($data, $status);
    }


    // edit
    public function edit ($id) {
        $supermity = Supermity::where('id', $id)->first();

        return view('app', [ 
            'dashboard' => true, 
            'resource' => 'supermity-form', 
            'class' => 'supermity-form',
            'title' => 'Supermities',
            'id' => $id,
            'supermity' => $supermity
        ]);
    }


    // update
    public function update (SupermityRequest $request, $id) {
        $validated = $request->validated();

        if ($validated) {
            $data = [ 'status' => 'Failed Query' ];
            $status = 422;

            $supermity = Supermity::where('id', $id)->first();
            $supermity->update($request->all());

            if ($supermity) {
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

        $checker = Supermity::destroy($id);

        if ($checker) {
            $data = [];
            $status = 200;
        }

        return response()->json($data, $status);
    }
}
