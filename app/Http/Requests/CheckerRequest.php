<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckerRequest extends FormRequest
{
    // authorization
    public function authorize() {
        return true;
    }


    // rules
    public function rules() {
        $rules = [
            'quarry_id' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => ['required', 'email', 'unique:checkers,email', 'unique:companies,email', 'unique:quarries,email', 'unique:supermities,email']
        ];

        if (isset($this->id)) {
            $rules['email'][2] = 'unique:checkers,email,' . $this->id;
        }

        return $rules;
    }


    // rules
    public function messages() {
        return [
            'required' => ' is required.',
            'email' => ' format is invalid.',
            'unique' => ' already exist.',
        ];
    }


    // force json response
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([ 'errors' => $validator->errors(), 'status' => 'Missing Fields' ], 422));
    }
}