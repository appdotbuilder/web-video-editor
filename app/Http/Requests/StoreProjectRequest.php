<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'width' => 'required|integer|min:480|max:7680',
            'height' => 'required|integer|min:360|max:4320',
            'fps' => 'required|numeric|min:15|max:120',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required.',
            'width.required' => 'Video width is required.',
            'width.min' => 'Video width must be at least 480 pixels.',
            'height.required' => 'Video height is required.',
            'height.min' => 'Video height must be at least 360 pixels.',
            'fps.required' => 'Frame rate is required.',
            'fps.min' => 'Frame rate must be at least 15 fps.',
        ];
    }
}