<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreURLRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'url' => 'required|url'
        ];
    }
    
    /**
     * failedValidation
     *
     * @param  Validator $validator
     * @return HttpResponseException
     */
    protected function failedValidation(Validator $validator): HttpResponseException
    {
        throw new HttpResponseException(response()->json([
            'error' => $validator->errors()->messages()
        ], 422));
    }
}
