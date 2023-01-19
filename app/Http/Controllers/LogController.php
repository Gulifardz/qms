<?php

namespace App\Http\Controllers;

use App\Events\ScannedSuccessfully;
use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\QuarryCompany;
use Illuminate\Support\Facades\Auth;
use App\Models\Driver;
use App\Models\Truck;
use App\Models\TruckDriver;
use App\Models\Checker;
use App\Models\QuarryProduct;

class LogController extends Controller
{
    // index
    public function index () {
        return view('app', [
            'dashboard' => true, 
            'resource' => 'logs', 
            'class' => 'logs', 
            'title' => 'Logs'
        ]);
    }


    // scanner
    public function scanner () {
        return view('app', [
            'dashboard' => true, 
            'resource' => 'scanner', 
            'class' => 'scanner', 
            'title' => 'Scanner'
        ]);
    }


    // list (backend)
    public function list (Request $request) {
        $logs = Log::with(['company', 'Truck.Truck_Category', 'driver', 'quarry', 'checker', 'supermity', 'Bought.Product']);

        if (Auth::guard('supermity')->check()) {
            $logs = $logs->where('supermity_id', Auth::guard('supermity')->user()->id);
        } else if (Auth::guard('checker')->check()) {
            $logs = $logs->where('quarry_id', Auth::guard('checker')->user()->quarry_id)
                ->where('checker_id', Auth::guard('checker')->user()->id);
        } else if (Auth::guard('company')->check()) {
            $logs = $logs->where('company_id', Auth::guard('company')->user()->id);
        } else if (Auth::guard('quarry')->check()) {
            $logs = $logs->where('quarry_id', Auth::guard('quarry')->user()->id);
        }

        $logs = $logs->filterSearch($request);

        // $logs->when($request->date, function ($q) use ($request) {
        //     $from = date('Y-m-d', strtotime('+1' , strtotime($request->date[0])));
        //     $to = date('Y-m-d', strtotime('+1' , strtotime($request->date[1])));

        //     $q->whereDate('entered_on', '>=', $from)
        //         ->whereDate('entered_on', '<=', $to);
        // });

        $per_page = $request->per_page;
        $skip = ($request->page - 1) * $per_page;
        $count = $logs->count();

        $logs = $logs->skip($skip)->take($per_page)->orderBy('logs.id', 'DESC')->get();

        $data = [ 
            'logs' => $logs,
            'total' => $count,
            'page' => $request->page,
            'per_page' => $per_page,
        ];

        return response()->json($data, 200);
    }


    // store (backend)
    public function store (Request $request) {
        $data = [ 'status' => 'Failed Query' ];
        $status = 422;

        if (Auth::guard('supermity')->check()) {
            $log = new Log([
                'supermity_id' => Auth::guard('supermity')->user()->id,
                'company_id' => $request->company_id,
                'quarry_id' => Auth::guard('supermity')->user()->quarry_id,
                'driver_id' => $request->driver_id,
                'truck_id' => $request->truck_id,
            ]);

            $log->save();

            ScannedSuccessfully::dispatch($log->id, $request);

            if ($log) {
                $data = [];
                $status = 200;
            }
        }

        return response()->json($data, $status);
    }


    // update (backend)
    public function update ($log_id) {
        $data = [ 'status' => 'Failed Query' ];
        $status = 422;

        if (Auth::guard('checker')->check()) {
            $log = Log::where('id', $log_id)->first();
            $log->update([
                'checker_id' => Auth::guard('checker')->user()->id,
                'check_out' => now()
            ]);

            if ($log) {
                $data = [];
                $status = 200;
            }
        }

        return response()->json($data, $status);
    }


    // records
    public function checker_records ($id) {
        return view('app', [ 
            'dashboard' => true, 
            'resource' => 'checkers-records', 
            'class' => 'checkers-records',
            'title' => 'Checkers',
            'id' => $id,
            'checker' => Checker::where('id', $id)->first()
        ]);
    }


    // checker records (backend)
    public function checker_records_list (Request $request) {
        $records = Log::with(['company', 'truck', 'driver', 'quarry'])
            ->where('checker_id', $request->checker_id)
            ->filterSearch($request);

        $per_page = isset($request->per_page) ? $request->per_page : 10;
        $skip = isset($request->page) ? ($request->page - 1) * $per_page : 0;
        $count = $records->count();

        $records = $records->skip($skip)->take($per_page)->get();
        
        $data = [ 
            'records' => $records,
            'total' => $count,
            'page' => isset($request->page) ? $request->page : 1,
            'per_page' => $per_page,
        ];
        
        return response()->json($data, 200);
    }


    // select product
    public function select (Request $request) {
        $product = QuarryProduct::with(['product'])
            ->where('quarry_id', Auth::guard('supermity')->user()->quarry_id)
            ->where('product_id', $request->product_id)
            ->first();
        
        // $product['supermity_id'] = Auth::guard('supermity')->user()->id;
        // $product['company_id'] = $request->company_id;
        // $product['quarry_id'] = Auth::guard('supermity')->user()->quarry_id;
        // $product['driver_id'] = $request->driver_id;
        // $product['truck_id'] = $request->truck_id;
        $product['quantity'] = $request->quantity;

        return response()->json(['product' => $product], 200);
    }


    // truck details
    public function details (Request $request) {
        if (Auth::guard('supermity')->check()) {
            $log = Log::where('company_id', $request->company_id)
                ->where('driver_id', $request->driver_id)
                ->where('checker_id', null)
                ->where('check_out', null)
                ->first();

            if ($log) {
                $data = ['status' => 'Pending'];
                $status = 422;  
            } else {
                $details = TruckDriver::with(['driver', 'company', 'Truck.Truck_Category'])
                    ->where('company_id', $request->company_id)
                    ->where('driver_id', $request->driver_id)
                    ->first();

                if ($details) {
                    $data = ['details' => $details];
                    $status = 200;
                }
            }
            
        } else if (Auth::guard('checker')->check()) {
            $data = ['status' => 'No Result'];
            $status = 422;

            $details = Log::with(['Bought.Product', 'company', 'Truck.Truck_Category', 'driver'])
                ->where('checker_id', null)
                ->where('company_id', $request->company_id)
                ->where('driver_id', $request->driver_id)
                ->where('quarry_id', Auth::guard('checker')->user()->quarry_id)
                ->where('check_out', null)
                ->first();

            if ($details) {
                $data = ['details' => $details];
                $status = 200;
            }
        }
        
        return response()->json($data, $status);
    }
}
