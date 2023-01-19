<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    // authorization
    public function authorize() {
        return true;
    }


    // rules
    public function rules() {
        return [
            'email' => 'required',
            'password' => 'required'
        ];
    }


    // rules
    public function messages() {
        return [
            'required' => ' is required.',
        ];
    }


    // force json response
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([ 'errors' => $validator->errors(), 'status' => 'Missing Fields' ], 422));
    }
}