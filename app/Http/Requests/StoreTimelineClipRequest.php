<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTimelineClipRequest extends FormRequest
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
            'project_id' => 'required|exists:projects,id',
            'media_file_id' => 'required|exists:media_files,id',
            'track' => 'required|integer|min:0',
            'start_time' => 'required|numeric|min:0',
            'duration' => 'required|numeric|min:0.1',
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
            'project_id.required' => 'Project ID is required.',
            'media_file_id.required' => 'Media file ID is required.',
            'track.required' => 'Track number is required.',
            'start_time.required' => 'Start time is required.',
            'duration.required' => 'Duration is required.',
            'duration.min' => 'Duration must be at least 0.1 seconds.',
        ];
    }
}