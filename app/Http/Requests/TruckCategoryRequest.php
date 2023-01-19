<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TruckCategoryRequest extends FormRequest
{
    // authorization
    public function authorize() {
        return true;
    }


    // rules
    public function rules() {
        $rules = [
            'name' => ['required', 'unique:truck_categories,name'],
            'otf' => ['required', 'gt:0']
        ];

        if (isset($this->id)) {
            $rules['name'][1] = 'unique:truck_categories,name,' . $this->id;
        }

        return $rules;
    }


    // rules
    public function messages() {
        return [
            'required' => ' is required.',
            'unique' => ' already exist.',
            'gt' => ' must be greater than 0.'
        ];
    }


    // force json response
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([ 'errors' => $validator->errors(), 'status' => 'Missing Fields' ], 422));
    }
}