<?php

namespace Flippingbook\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePageRequest extends FormRequest
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
            'publication_id' => ['required', 'exists:flippingbook_publications,id'],
            'title' => ['required', 'string', 'max:255'],
            'ordering' => ['required', 'integer'],
            'image_upload' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
            'image' => ['required', 'string', 'max:255'],
            'task' => ['required', 'string'],
        ];
    }
}
