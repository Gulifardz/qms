<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TruckCategoryRequest;
use App\Models\TruckCategory;

class TruckCategoryController extends Controller
{
    // products (ui)
    public function index () {
        return view('app', [ 
            'dashboard' => true, 
            'resource' => 'truck-categories', 
            'class' => 'truck-categories',
            'title' => 'Truck Categories'
        ]);
    }


    // list (backend)
    public function list (Request $request) {
        $truck_categories = TruckCategory::orderBy('name')
            ->filterSearch($request);

        $per_page = $request->per_page;
        $skip = ($request->page - 1) * $per_page;
        $count = $truck_categories->count();

        $truck_categories = $truck_categories->skip($skip)->take($per_page)->get();
        
        $data = [ 
            'truck_categories' => $truck_categories,
            'total' => $count,
            'page' => $request->page,
            'per_page' => $per_page,
        ];
        
        return response()->json($data, 200);
    }


    // store (backend)
    public function store (TruckCategoryRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            $data = [ 'status' => 'Failed Query' ];
            $status = 422;

            $truck_categories = new TruckCategory($request->all());
            $truck_categories->save();

            if ($truck_categories) {
                $data = [];
                $status = 200;
            }
        }

        return response()->json($data, $status);
    }


    // update (backend)
    public function update (TruckCategoryRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            $data = [ 'status' => 'Failed Query' ];
            $status = 422;

            $truck_category = TruckCategory::where('id', $request->id)->first();
            $truck_category->update($request->all());

            if ($truck_category) {
                $data = [];
                $status = 200;
            }
        }

        return response()->json($data, $status);
    }


    // destroy (backend)
    public function destroy ($id) {
        $data = [ 'status' => 'Failed Query' ];
        $status = 422;

        $truck_category = TruckCategory::destroy($id);

        if ($truck_category) {
            $data = [];
            $status = 200;
        }

        return response()->json($data, $status);
    }
}
