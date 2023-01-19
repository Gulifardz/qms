<?php

namespace App\Http\Controllers;

use App\Events\DriverSuccessfullyCreated;
use App\Events\DriverSuccessfullyDeleted;
use Illuminate\Http\Request;
use App\Http\Requests\DriverRequest;
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;
use App\Models\TruckDriver;

class DriverController extends Controller
{
    /// drivers
    public function index () {
        return view('app', [
            'dashboard' => true, 
            'resource' => 'drivers', 
            'class' => 'drivers', 
            'title' => 'Drivers'
        ]);
    }


    // list
    public function list (Request $request) {
        $drivers = Driver::with(['truck'])
            ->where('company_id', Auth::guard('company')->user()->id)
            ->orderBy('id')
            ->filterSearch($request);

        $per_page = $request->per_page;
        $skip = ($request->page - 1) * $per_page;
        $count = $drivers->count();

        $drivers = $drivers->skip($skip)->take($per_page)->get();
        
        $data = [ 
            'drivers' => $drivers,
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
            'resource' => 'drivers-form', 
            'class' => 'drivers-form', 
            'title' => 'Drivers'
        ]);
    }


    // store
    public function store (DriverRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            $data = [ 'status' => 'Failed Query' ];
            $status = 422;

            $name = $this->generateRandomString();
            $name .= ('_' . str_replace(' ', '_', $name));

            $driver = new Driver([
                'company_id' => Auth::guard('company')->user()->id,
                'firstname' => $request->firstname,
                'middlename' => trim($request->middlename),
                'lastname' => $request->lastname,
                'license_no' => $request->license_no,
                'contact_no' => $request->contact_no,
                'address' => $request->address,
                'picture' => env('GOOGLE_CLOUD_STORAGE_API_URI') . '/' . env('BUCKET_NAME') . '/drivers/' . $name
            ]);
            
            $driver->save();

            DriverSuccessfullyCreated::dispatch($name, $request);

            if ($driver) {
                $data = [];
                $status = 200;
            }
        }

        return response()->json($data, $status);
    }


    // edit
    public function edit ($id) {
        $driver = Driver::where('id', $id)->first();

        return view('app', [
            'dashboard' => true, 
            'resource' => 'drivers-form', 
            'class' => 'drivers-form', 
            'title' => 'Drivers',
            'id' => $id,
            'driver' => $driver
        ]);
    }


    // update
    public function update (DriverRequest $request, $id) {
        $validated = $request->validated();

        if ($validated) {
            $data = [ 'status' => 'Failed Query' ];
            $status = 422;

            $driver = Driver::where('id', $id)->first();
            $name = explode('/', $driver->picture);
            $name = end($name);

            $driver->update([
                'firstname' => $request->input('firstname'),
                'middlename' => trim($request->input('middlename')),
                'lastname' => $request->input('lastname'),
                'license_no' => $request->input('license_no'),
                'contact_no' => $request->input('contact_no'),
                'address' => $request->input('address'),
                'picture' => $driver->picture,
            ]);

            if ($request->hasFile('picture')) {
                DriverSuccessfullyCreated::dispatch($name, $request);
            }

            if ($driver) {
                $data = [];
                $status = 200;
            }

        }

        return response()->json($data, $status);
    }


    // delete
    public function destroy ($id) {
        $data = [ 'status' => 'Failed Query' ];
        $status = 422;

        $name = explode('/', Driver::where('id', $id)->first()->picture);
        $name = end($name);

        $driver = Driver::destroy($id);

        DriverSuccessfullyDeleted::dispatch($name);

        if ($driver) {
            $data = [];
            $status = 200;
        }

        return response()->json($data, $status);
    }


    // qr
    public function qr ($id) {    
        $driver = Driver::where('id', $id)->first();
        $driver['truck_id'] = TruckDriver::where('driver_id', $id)->first()->truck_id;

        return view('app', [
            'dashboard' => true, 
            'resource' => 'drivers-qr', 
            'class' => 'drivers-qr', 
            'title' => 'Drivers',
            'driver' => $driver
        ]);
    }


    // Generate Random
    public function generateRandomString () {
        $today = date('YmdHi');
        $startDate = date('YmdHi', strtotime('-9 days'));
        $range = $today - $startDate;
        $rand1 = rand(0, $range);
        $rand2 = rand(0, 600000);

        return  $rand1 + $rand2;
    }
}
