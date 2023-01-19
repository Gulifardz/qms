<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\QuarryRequest;

class RegistrationController extends Controller
{
    // index (ui)
    public function index () {
        return view('app', [
            'dashboard' => false, 
            'resource' => 'registration', 
            'class' => 'registration', 
            'title' => 'Registration'
        ]);
    }


    // validate current form
    public function validate_step (QuarryRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            $data = [];
            $status = 200;
        }

        return response()->json($data, $status);
    }
}
