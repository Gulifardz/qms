<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class QuarryRequest extends FormRequest
{
    // authorization
    public function authorize() {
        return true;
    }


    // rules
    public function rules() {
        return [
            'name' =>  [
                'sometimes', 
                'required', 
                Rule::unique('quarries')->where(function ($query) {
                    if (isset($this->id)) {
                        return $query->where('id', '!=', $this->id)
                            ->where('name', $this->name)
                            ->where('deleted_at', null);
                    } else {
                        return $query->where('name', $this->name)
                            ->where('deleted_at', null);
                    }
                }), 
            ],
            'address' => [
                'sometimes', 
                'required'
            ],
            'contact_no' =>  [
                'sometimes', 
                'required', 
                'regex:/^(09|9)\d{9}$/', 
                'unique:quarries,contact_no' . (isset($this->id) ? ',' . $this->id : ''), 
                'unique:companies,contact_no'
            ],
            'email' => [
                'sometimes', 
                'required', 
                'email', 
                'unique:quarries,email' . (isset($this->id) ? ',' . $this->id : ''),
                'unique:companies,email',
                'unique:checkers,email', 
                'unique:supermities,email',
            ],
            'lgu' => [
                'sometimes', 
                'required'
            ],
            'host_barangay' => [
                'sometimes', 
                'required'
            ],
            'quarry_area_td' => [
                'sometimes', 
                'required'
            ],
            'ie_route' => [
                'sometimes', 
                'required'
            ],
            'provincial_permit' => [
                'sometimes', 
                'required'
            ],
            'regional_permit' => [
                'sometimes', 
                'required'
            ],
        ];
    }


    // rules
    public function messages() {
        return [
            'required' => ' is required.',
            'email' => ' format is not valid.',
            'unique' => ' already exist.',
            'regex' => ' format is not valid.'
        ];
    }


    // force json response
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([ 'errors' => $validator->errors(), 'status' => 'Missing Fields' ], 422));
    }
}
