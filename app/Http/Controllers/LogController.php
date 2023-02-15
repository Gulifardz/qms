<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\TruckDriver;
use App\Models\Checker;
use App\Models\QuarryProduct;
use Illuminate\Support\Facades\DB;

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
        $logs = Log::with(['company', 'Truck.Truck_Category', 'driver', 'quarry', 'checker', 'supermity', 'product']);

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
            $request['supermity_id'] = Auth::guard('supermity')->user()->id;
            $request['quarry_id'] = Auth::guard('supermity')->user()->quarry_id;

            $log = new Log($request->all());
            $log->save();

            if ($log) {
                $data = [];
                $status = 200;
            }
        }

        return response()->json($data, $status);
    }


    // update (backend)
    public function update (Request $request, $log_id) {
        $data = [ 'status' => 'Failed Query' ];
        $status = 422;

        if (Auth::guard('checker')->check()) {
            $log = Log::where('id', $log_id)->first();

            if ($request->has('rejected')) {
                $log->update([
                    'checker_comment' => $request->comment,
                    'status' => 422
                ]);
            } else {
                $log->update([
                    'checker_id' => Auth::guard('checker')->user()->id,
                    'check_out' => now()
                ]);
            }

            if ($log) {
                $data = [];
                $status = 200;
            }
        } else if (Auth::guard('supermity')->check()) {
            $log = Log::where('id', $log_id)->first();
            $log->update([
                'checker_comment' => null,
                'status' => null
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


    // truck details
    public function truck_details (Request $request) {
        $data = ['status' => 'No Result'];
        $status = 422;

        if (Auth::guard('supermity')->check()) {
            $log = Log::with(['company', 'driver', 'Truck.Truck_Category', 'quarry', 'supermity'])
                ->where('company_id', $request->company_id)
                ->where('driver_id', $request->driver_id)
                ->where('quarry_id', Auth::guard('supermity')->user()->quarry_id)
                ->where('checker_id', null)
                ->where('check_out', null)
                ->first();

            if ($log) {
                $data = ['status' => 'Pending'];
                $status = 422; 

                if ($log->status !== null) {
                    $data = ['details' => $log];
                    $status = 200;    
                }
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
            $details = Log::with(['company', 'Truck.Truck_Category', 'driver', 'product'])
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


    // product details
    public function product_details (Request $request) {
        if (Auth::guard('supermity')->check()) {
            $product = QuarryProduct::where('product_id', $request->product_id)
                ->where('quarry_id', Auth::guard('supermity')->user()->id)
                ->first();
        }
        
        return response()->json(['product' => $product], 200);
    }


    // month revenue
    public function monthly_revenue () {
        if (Auth::guard('web')->check()) {
            $months = [1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12];
            $lyr = []; // last years revenue
            $tyr = []; // this years revenue

            foreach ($months as $key => $month) {
                $lyr[$key] = Log::whereMonth('check_out', $month)->whereYear('check_out', now()->year - 1)->get();
                $tyr[$key] = Log::whereMonth('check_out', $month)->whereYear('check_out', now()->year)->get();
            }
        }

        return response()->json([$lyr, $tyr], 200);
    }
}
