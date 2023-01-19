<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DriverRequest extends FormRequest
{
    // authorization
    public function authorize() {
        return true;
    }


    // rules
    public function rules() {
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'license_no' => ['required', 'unique:drivers,license_no'],
            'contact_no' => ['required', 'regex:/^(09|9)\d{9}$/', 'unique:drivers,contact_no', 'unique:companies,contact_no', 'unique:quarries,contact_no'],
            'address' => 'required',
            'picture' => 'required'
    ];

        if (isset($this->id)) {
            $rules['license_no'][1] = 'unique:drivers,license_no,' . $this->id;
            $rules['contact_no'][2] = 'unique:drivers,contact_no,' . $this->id;
        }

        return $rules;
    }


    // rules
    public function messages() {
        return [
            'required' => ' is required.',
            'unique' => ' already exist.',
            'regex' => ' format is invalid.',
            'image' => ' is invalid.'
        ];
    }


    // force json response
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors(), 'status' => 'Missing Fields'], 422));
    }
}