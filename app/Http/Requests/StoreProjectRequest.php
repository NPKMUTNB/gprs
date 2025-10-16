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
            'title_th' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'abstract' => 'required|string',
            'year' => 'required|integer|min:2000|max:2100',
            'semester' => 'required|in:1,2,3',
            'category_id' => 'required|exists:categories,id',
            'advisor_id' => 'required|exists:users,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
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
            'title_th.required' => 'The Thai title is required.',
            'title_th.max' => 'The Thai title must not exceed 255 characters.',
            'title_en.required' => 'The English title is required.',
            'title_en.max' => 'The English title must not exceed 255 characters.',
            'abstract.required' => 'The abstract is required.',
            'year.required' => 'The year is required.',
            'year.integer' => 'The year must be a valid number.',
            'year.min' => 'The year must be at least 2000.',
            'year.max' => 'The year must not exceed 2100.',
            'semester.required' => 'The semester is required.',
            'semester.in' => 'The semester must be 1, 2, or 3.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'advisor_id.required' => 'Please select an advisor.',
            'advisor_id.exists' => 'The selected advisor is invalid.',
            'tags.array' => 'Tags must be provided as an array.',
            'tags.*.exists' => 'One or more selected tags are invalid.',
        ];
    }
}
