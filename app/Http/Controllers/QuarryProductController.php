<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\QuarryProduct;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\QuarryProductRequest;

class QuarryProductController extends Controller
{
    // index (ui)
    public function index () {
        return view('app', [ 
            'dashboard' => true, 
            'resource' => 'products', 
            'class' => 'products',
            'title' => 'Products'
        ]);
    }


    // list (backend)
    public function list (Request $request) {
        $products = QuarryProduct::with(['product'])
            ->where('quarry_id', Auth::guard('quarry')->user()->id)
            ->filterSearch($request);

        $per_page = $request->per_page;
        $skip = ($request->page - 1) * $per_page;
        $count = $products->count();

        $products = $products->skip($skip)->take($per_page)->get();
        
        $data = [ 
            'products' => $products,
            'total' => $count,
            'page' => $request->page,
            'per_page' => $per_page,
        ];
        
        return response()->json($data, 200);
    }


    // add (ui)
    public function add () {
        return view('app', [ 
            'dashboard' => true, 
            'resource' => 'product-form', 
            'class' => 'product-form',
            'title' => 'Products'
        ]);
    }


    // store (backend)
    public function store (QuarryProductRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            $data = [ 'status' => 'Failed Query' ];
            $status = 422;

            $product = new QuarryProduct($request->all());
            $product->save();

            if ($product) {
                $data = [];
                $status = 200;
            }
        }

        return response()->json($data, $status);
    }


    // edit (ui)
    public function edit ($id) {
        $product = QuarryProduct::where('id', $id)->first();

        return view('app', [ 
            'dashboard' => true, 
            'resource' => 'product-form', 
            'class' => 'product-form',
            'title' => 'Products',
            'id' => $id,
            'product' => $product
        ]);
    }


    // update (backend)
    public function update (QuarryProductRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            $data = [ 'status' => 'Failed Query' ];
            $status = 422;

            $product = QuarryProduct::where('id', $request->id)->first();
            $product->update($request->all());

            if ($product) {
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

        $quarry_product = QuarryProduct::destroy($id);

        if ($quarry_product) {
            $data = [];
            $status = 200;
        }
        
        return response()->json($data, $status);
    }
}
