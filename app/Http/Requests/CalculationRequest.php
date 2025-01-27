<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalculationRequest extends FormRequest
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
            'numbers' => ['required', 'array', 'min:2'], 
            'numbers.*' => ['required', 'numeric'], 
            'operators' => ['required', 'array'], 
            'operators.*' => ['required', 'string', 'in:+,-,*,/'], 
        ];
    }
}
