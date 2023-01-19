<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class TruckRequest extends FormRequest
{
    // authorization
    public function authorize() {
        return true;
    }


    // rules
    public function rules() {
        return [
            'brand' => 'required',
            'year_model' => 'required',
            'capacity' => 'required',
            'orcr' => 'required',
            'plate_no' => [
                'required', 
                'unique:trucks,plate_no' . (isset($this->id) ? ',' . $this->id : '')
            ],
            'drivers' => 'required'
        ];
    }


    // rules
    public function messages() {
        return [
            'required' => ' is required.',
            'unique' => ' already exist.',
        ];
    }


    // force json response
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([ 'errors' => $validator->errors(), 'status' => 'Missing Fields' ], 422));
    }
}