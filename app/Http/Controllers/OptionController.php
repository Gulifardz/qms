<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Product;
use App\Models\Quarry;
use App\Models\QuarryProduct;
use App\Models\TruckDriver;
use App\Models\TruckCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OptionController extends Controller
{
    // products
    public function products (Request $request) {
        $products = Product::select('id', 'name');
        
        if (Auth::guard('quarry')->check()) {
            if ($request->has('id')) {
                $products = $products->whereNotIn('id', QuarryProduct::where('quarry_id', Auth::guard('quarry')->user()->id)->where('id', '!=', $request->id)->pluck('product_id')->toArray());
            } else {
                $products = $products->whereNotIn('id', QuarryProduct::where('quarry_id', Auth::guard('quarry')->user()->id)->pluck('product_id')->toArray());
            }
        } else if (Auth::guard('supermity')->check()) {
            $products = $products->whereIn('id', 
                QuarryProduct::where('quarry_id', Auth::guard('supermity')->user()->quarry_id)
                    ->whereNotIn('product_id', $request->selected)
                    ->pluck('product_id')
                    ->toArray());
        }

        $products = $products->orderBy('name')->get();

        return response()->json(['products' => $products], 200);
    }


    // quarries
    public function quarries () {
        $quarries = Quarry::select('id', 'name')->get();

        return response()->json(['quarries' => $quarries], 200);
    }


    // drivers
    public function drivers (Request $request) {

        $drivers = Driver::select('id', 'firstname', 'middlename', 'lastname');

        if ($request->has('drivers')) {
            $except = [];

            foreach ($request->drivers as $driver) {
                if (!in_array($driver, $except) && !in_array($driver, $request->drivers)) {
                    array_push($except, $driver);
                }
            }
            
            $drivers = $drivers->whereNotIn('id', $except);
        } else {
            $drivers = $drivers->whereNotIn('id', TruckDriver::where('company_id', Auth::guard('company')->user()->id)->pluck('driver_id')->toArray());
        }

        $drivers = $drivers->get();

        return response()->json(['drivers' => $drivers], 200);
    }


    // truck categories
    public function truck_categories () {
        $truck_categories = TruckCategory::select('id', 'name')->get();

        return response()->json(['truck_categories' => $truck_categories], 200);
    }


    /**
     * List of Products
     * /admin/quarries/{quarry_id}/products/{product_id}/edit
     */
    // public function products (Request $request) {
    //     $products = Product::select('id', 'name')
    //         ->whereNotIn('id', QuarryProduct::where('quarry_id', $request->quarry_id)
    //             ->where('id', '!=', $request->quarry_product_id)
    //             ->pluck('product_id')
    //             ->toArray()
    //         )
    //         ->orderBy('name')
    //         ->get();

    //     return response()->json(['products' => $products], 200);
    // }
}
