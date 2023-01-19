<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompanyRequest extends FormRequest
{
    // authorization
    public function authorize() {
        return true;
    }


    // rules
    public function rules() {
        $rules = [
            'name' => ['required', 'unique:companies,name'],
            'email' => ['required', 'email', 'unique:companies,email', 'unique:quarries,email', 'unique:checkers,email', 'unique:supermities,email'],
            'address' => ['required'],
            'contact_no' => ['required', 'regex:/^(09|9)\d{9}$/', 'unique:companies,contact_no', 'unique:quarries,contact_no']
        ];

        if (isset($this->id)) {
            $rules['name'][1] = 'unique:companies,name,' . $this->id;
            $rules['email'][2] = 'unique:companies,email,' . $this->id;
            $rules['contact_no'][2] = 'unique:companies,contact_no,' . $this->id;
        }

        return $rules;
    }


    // rules
    public function messages() {
        return [
            'required' => ' is required.',
            'email' => ' format is invalid.',
            'unique' => ' already exist.',
            'regex' => ' format is invalid.'
        ];
    }


    // force json response
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([ 'errors' => $validator->errors(), 'status' => 'Missing Fields' ], 422));
    }
}