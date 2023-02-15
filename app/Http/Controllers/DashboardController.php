<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quarry;
use App\Models\Log;

class DashboardController extends Controller
{
    // idnex
    public function index () {
        return view('app', [ 
            'dashboard' => true, 
            'resource' => 'dashboard', 
            'class' => 'dashboard',
            'title' => 'Dashboard'
        ]);
    }


    // report
    public function reports (Request $request) {
        $quarries = Quarry::
            // ->whereHas('logs', function ($q) use ($request) {
            //     $q->whereDate('check_out', '>=', date('Y-m-d', strtotime('+1' , strtotime($request->dates[0]))))
            //         ->whereDate('check_out', '<=', date('Y-m-d', strtotime('+1' , strtotime($request->dates[1]))));
            // })
            filterSearch($request)
            ->get();

        $quarries = $quarries->map(function($column) use ($request) {
            if (empty($request->dates)) {
                $logs = Log::with(['Truck.Truck_Category'])
                    ->where('quarry_id', $column->id)
                    ->where('checker_id', '!=', null)
                    ->get();
            } else {
                $logs = Log::with(['Truck.Truck_Category'])
                    ->where('quarry_id', $column->id)
                    ->where('checker_id', '!=', null)
                    ->whereDate('check_out', '>=', date('Y-m-d', strtotime('+1' , strtotime($request->dates[0]))))
                    ->whereDate('check_out', '<=', date('Y-m-d', strtotime('+1' , strtotime($request->dates[1]))))
                    ->get();
            }

            $total_amount = 0;
            $extraction_fee = 0;
            $road_maintenance_fee = 0;
            $system_fee = 0;

            foreach($logs as $log) {
                $total_amount += $log->total_amount;
                $extraction_fee += ($log->ef * $log->truck->capacity);
                $road_maintenance_fee += ($log->rmf * $log->truck->capacity);
                $system_fee += $log->truck->truck_category->otf;
            }

            return [
                'id' => $column->id,
                'name' => $column->name,
                'total_amount' => $total_amount,
                'extraction_fee' => $extraction_fee,
                'road_maintenance_fee' => $road_maintenance_fee,
                'system_fee' => $system_fee
            ];
        });


        // $quarries = $quarries->map(function($column) {
        //         $total_amount = 0;
        //         $extraction_fee = 0;
        //         $road_maintenance_fee = 0;
        //         $system_fee = 0;

        //         foreach($column->logs as $log) {
        //             if ($log->checker_id !== null) {
        //                 $total_amount += $log->total_amount;
        //                 $extraction_fee += ($log->ef * $log->truck->capacity);
        //                 $road_maintenance_fee += ($log->rmf * $log->truck->capacity);
        //                 $system_fee += $log->truck->truck_category->otf;
        //             }
        //         }

        //         return [
        //             'id' => $column->id,
        //             'name' => $column->name,
        //             'total_amount' => $total_amount,
        //             'extraction_fee' => $extraction_fee,
        //             'road_maintenance_fee' => $road_maintenance_fee,
        //             'system_fee' => $system_fee
        //         ];
        // });

        $per_page = $request->per_page;
        $skip = ($request->page - 1) * $per_page;
        $count = $quarries->count();

        $quarries = $quarries->skip($skip)->take($per_page);

        $data = [ 
            'quarries' => $quarries,
            'total' => $count,
            'page' => $request->page,
            'per_page' => $per_page,
        ];

        return response()->json($data, 200);
    }
}
