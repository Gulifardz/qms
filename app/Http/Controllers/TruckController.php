<?php

namespace App\Http\Controllers;

use App\Events\TruckCrudEvent;
use App\Http\Requests\TruckRequest;
use App\Models\Truck;
use App\Models\TruckDriver;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TruckController extends Controller
{
    // trucks (ui)
    public function index () {
        return view('app', [
            'dashboard' => true, 
            'resource' => 'trucks', 
            'class' => 'trucks', 
            'title' => 'Trucks'
        ]);
    }


    // list (backend)
    public function list (Request $request) {
        $trucks = Truck::with(['truck_category', 'drivers'])
            ->where('company_id', Auth::guard('company')->user()->id)
            ->orderBy('id')
            ->filterSearch($request);

        $per_page = $request->per_page;
        $skip = ($request->page - 1) * $per_page;
        $count = $trucks->count();

        $trucks = $trucks->skip($skip)->take($per_page)->get();
        
        $data = [ 
            'trucks' => $trucks,
            'total' => $count,
            'page' => $request->page,
            'per_page' => $per_page,
        ];
        
        return response()->json($data, 200);
    }


    // add (backend)
    public function add () {
        return view('app', [
            'dashboard' => true, 
            'resource' => 'truck-form', 
            'class' => 'truck-form', 
            'title' => 'Trucks'
        ]);
    }


    // store (backend)
    public function store (TruckRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            $data = [ 'status' => 'Failed Query' ];
            $status = 422;

            $truck = new Truck([
                'company_id' => Auth::guard('company')->user()->id,
                'brand' => $request->brand,
                'year_model' => $request->year_model,
                'capacity' => $request->capacity,
                'truck_category_id' => $request->truck_category_id,
                'orcr' => $request->orcr,
                'plate_no' => $request->plate_no
            ]);

            $truck->save();

            TruckCrudEvent::dispatch(Auth::guard('company')->user()->id, $truck->id, $request->drivers);
    
            if ($truck) {
                $data = [];
                $status = 200;
            }
        }

        return response()->json($data, $status);
    }


    // edit (ui)
    public function edit ($id) {
        $truck = Truck::where('id', $id)->first();

        $truck['drivers'] = TruckDriver::where('company_id', Auth::guard('company')->user()->id)
            ->where('truck_id', $id)
            ->pluck('driver_id')
            ->toArray();

        return view('app', [
            'dashboard' => true, 
            'resource' => 'truck-form', 
            'class' => 'truck-form', 
            'title' => 'Trucks',
            'id' => $id,
            'truck' => $truck
        ]);
    }


    // update (backend)
    public function update (TruckRequest $request, $id) 
    {
        $validated = $request->validated();

        if ($validated) {
            $data = [ 'status' => 'Failed Query' ];
            $status = 422;

            $truck = Truck::where('id', $id)->first();
            $truck->update([
                'brand' => $request->brand,
                'year_model' => $request->year_model,
                'capacity' => $request->capacity,
                'truck_category_id' => $request->truck_category_id,
                'orcr' => $request->orcr,
                'plate_no' => $request->plate_no,
            ]);

            TruckCrudEvent::dispatch(Auth::guard('company')->user()->id, $truck->id, $request->drivers);

            if ($truck) {
                $data = [];
                $status = 200;
            }
        }

        return response()->json($data, $status);
    }


    // delete (backend)
    public function destroy ($id) {
        $data = [ 'status' => 'Failed Query' ];
        $status = 422;

        $driver = Truck::destroy($id);

        TruckCrudEvent::dispatch(Auth::guard('company')->user()->id, $id, []);

        if ($driver) {
            $data = [];
            $status = 200;
        }

        return response()->json($data, $status);
    }
}
