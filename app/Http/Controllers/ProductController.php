<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
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
        $products = Product::filterSearch($request);

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


    // store (backend)
    public function store (ProductRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            $data = [ 'status' => 'Failed Query' ];
            $status = 422;

            $product = new Product($request->all());
            $product->save();

            if ($product) {
                $data = [];
                $status = 200;
            }
        }

        return response()->json($data, $status);
    }


    // update (backend)
    public function update (ProductRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            $data = [ 'status' => 'Failed Query' ];
            $status = 422;

            $product = Product::where('id', $request->id)->first();
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

        $product = Product::destroy($id);

        if ($product) {
            $data = [];
            $status = 200;
        }
        
        return response()->json($data, $status);
    }
}
