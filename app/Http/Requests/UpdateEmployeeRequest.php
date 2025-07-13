<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guard('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'nullable|image|max:2048', // Optional image file, max 2MB
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'division' => 'required|uuid|exists:divisions,id',
            'position' => 'required|string|max:255',
        ];
    }
}
