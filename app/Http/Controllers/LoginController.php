<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // login (ui)
    public function index () {
        return view('app', [ 
            'dashboard' => false, 
            'resource' => 'login', 
            'class' => 'login', 
            'title' => 'Login'
        ]);
    }


    // login (backend)
    public function login (LoginRequest $request) {
        $validated = $request->validated();
        
        $data = ['status' => 'Invalid Credentials'];
        $status = 422;

        $is_admin = Auth::guard('web')->attempt($request->only([ 'email', 'password' ]));
        $is_quarry = Auth::guard('company')->attempt($request->only([ 'email', 'password' ]));
        $is_company = Auth::guard('quarry')->attempt($request->only([ 'email', 'password' ]));
        $is_checker = Auth::guard('checker')->attempt($request->only([ 'email', 'password' ]));
        $is_supermity = Auth::guard('supermity')->attempt($request->only([ 'email', 'password' ]));

        if ($validated) {
            if ($is_admin || $is_quarry || $is_company || $is_checker || $is_supermity) {
                $data = [ 'redirect' => '/' ];
                $status = 200;
            }
        }

        return response()->json($data, $status);
    }


    // logout (backend)
    public function logout () {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        } else if (Auth::guard('company')->check()) {
            Auth::guard('company')->logout();
        } else if (Auth::guard('quarry')->check()) {
            Auth::guard('quarry')->logout();
        } else if (Auth::guard('checker')->check()) {
            Auth::guard('checker')->logout();
        } else if (Auth::guard('supermity')->check()) {
            Auth::guard('supermity')->logout();
        }

        return response()->json([ 'redirect' => '/' ]);
    }
}
