<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationRequest extends FormRequest
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
            'technical_score' => 'required|numeric|min:0|max:100',
            'design_score' => 'required|numeric|min:0|max:100',
            'documentation_score' => 'required|numeric|min:0|max:100',
            'presentation_score' => 'required|numeric|min:0|max:100',
            'comment' => 'nullable|string',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'technical_score.required' => 'The technical score is required.',
            'technical_score.numeric' => 'The technical score must be a number.',
            'technical_score.min' => 'The technical score must be at least 0.',
            'technical_score.max' => 'The technical score must not exceed 100.',
            'design_score.required' => 'The design score is required.',
            'design_score.numeric' => 'The design score must be a number.',
            'design_score.min' => 'The design score must be at least 0.',
            'design_score.max' => 'The design score must not exceed 100.',
            'documentation_score.required' => 'The documentation score is required.',
            'documentation_score.numeric' => 'The documentation score must be a number.',
            'documentation_score.min' => 'The documentation score must be at least 0.',
            'documentation_score.max' => 'The documentation score must not exceed 100.',
            'presentation_score.required' => 'The presentation score is required.',
            'presentation_score.numeric' => 'The presentation score must be a number.',
            'presentation_score.min' => 'The presentation score must be at least 0.',
            'presentation_score.max' => 'The presentation score must not exceed 100.',
            'comment.string' => 'The comment must be a valid text.',
        ];
    }
}
