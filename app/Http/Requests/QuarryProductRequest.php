<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class QuarryProductRequest extends FormRequest
{
    // authorization
    public function authorize() {
        return true;
    }


    // rules
    public function rules() {
        return [
            'product_id' => 'required',
            'price' => ['required', 'gt:0'],
            'ef' =>  ['required', 'gt:0'],
            'rmf' =>  ['required', 'gt:0']
        ];
    }


    // rules
    public function messages() {
        return [
            'required' => ' is required.',
            'gt' => ' must be greater than 0.',
        ];
    }


    // force json response
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([ 'errors' => $validator->errors(), 'status' => 'Missing Fields' ], 422));
    }
}
