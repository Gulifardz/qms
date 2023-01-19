<?php

namespace App\Http\Controllers;

use App\Models\Checker;
use Illuminate\Http\Request;
use App\Http\Requests\CheckerRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CheckerController extends Controller
{
    // checker
    public function index () {
        return view('app', [ 
            'dashboard' => true, 
            'resource' => 'checkers', 
            'class' => 'checkers',
            'title' => 'Checkers'
        ]);
    }


    // list
    public function list (Request $request) {
        if (Auth::guard('web')->check()) {
            $checkers = Checker::with(['quarry'])
                ->orderBy('id')
                ->filterSearch($request);
        } else if (Auth::guard('quarry')->check()) {
            $checkers = Checker::with(['quarry'])
                ->where('quarry_id', Auth::guard('quarry')->user()->id)
                ->orderBy('id')
                ->filterSearch($request);
        }
       

        $per_page = $request->per_page;
        $skip = ($request->page - 1) * $per_page;
        $count = $checkers->count();

        $checkers = $checkers->skip($skip)->take($per_page)->get();
        
        $data = [ 
            'checkers' => $checkers,
            'total' => $count,
            'page' => $request->page,
            'per_page' => $per_page,
        ];
        
        return response()->json($data, 200);
    }


    // add
    public function add () {
        return view('app', [ 
            'dashboard' => true, 
            'resource' => 'checkers-form', 
            'class' => 'checkers-form',
            'title' => 'Checkers'
        ]);
    }


    // store
    public function store (CheckerRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            $data = [ 'status' => 'Failed Query' ];
            $status = 422;

            $request['password'] = Hash::make('1');
            $checker = new Checker($request->all());
            $checker->save();

            if ($checker) {
                $data = [];
                $status = 200;
            }
        }

        return response()->json($data, $status);
    }


    // edit
    public function edit ($id) {
        $checker = Checker::where('id', $id)->first();

        return view('app', [ 
            'dashboard' => true, 
            'resource' => 'checkers-form', 
            'class' => 'checkers-form',
            'title' => 'Checkers',
            'id' => $id,
            'checker' => $checker
        ]);
    }


    // update
    public function update (CheckerRequest $request, $id) {
        $validated = $request->validated();

        if ($validated) {
            $data = [ 'status' => 'Failed Query' ];
            $status = 422;

            $checker = Checker::where('id', $id)->first();
            $checker->update($request->all());

            if ($checker) {
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

        $checker = Checker::destroy($id);

        if ($checker) {
            $data = [];
            $status = 200;
        }

        return response()->json($data, $status);
    }
}
