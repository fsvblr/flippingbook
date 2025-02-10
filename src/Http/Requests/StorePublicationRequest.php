<?php

namespace Flippingbook\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePublicationRequest extends FormRequest
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
            'category_id' => ['required', 'exists:flippingbook_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'state' => ['required', 'boolean'],
            'preview' => ['nullable', 'string'],
            'direction' => ['required', Rule::in(['right', 'left'])],
            'show_slider' => ['required', 'boolean'],
            'author' => ['nullable', 'string'],
            'show_author_category' => ['required', 'boolean'],
            'show_author_publication' => ['required', 'boolean'],
            'description' => ['nullable', 'string'],
            'show_description_category' => ['required', 'boolean'],
            'show_description_publication' => ['required', 'boolean'],
            'task' => ['required', 'string'],
        ];
    }
}
