<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
{
    // authorization
    public function authorize() {
        return true;
    }


    // rules
    public function rules() {
        $rules = [
            'name' => ['sometimes', 'required', 'unique:products,name'],
        ];

        if (isset($this->id)) {
            $rules['name'][2] = 'unique:products,name,' . $this->id;
        }

        return $rules;
    }


    // rules
    public function messages() {
        return [
            'required' => ' is required.',
            'unique' => ' already exists.',
        ];
    }


    // force json response
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([ 'errors' => $validator->errors(), 'status' => 'Missing Fields' ], 422));
    }
}