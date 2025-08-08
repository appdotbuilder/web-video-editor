<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTimelineClipRequest extends FormRequest
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
            'track' => 'nullable|integer|min:0',
            'start_time' => 'nullable|numeric|min:0',
            'duration' => 'nullable|numeric|min:0.1',
            'media_start' => 'nullable|numeric|min:0',
            'media_end' => 'nullable|numeric|min:0',
            'properties' => 'nullable|array',
            'z_index' => 'nullable|integer|min:0',
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
            'duration.min' => 'Duration must be at least 0.1 seconds.',
            'start_time.min' => 'Start time cannot be negative.',
            'track.min' => 'Track number cannot be negative.',
        ];
    }
}