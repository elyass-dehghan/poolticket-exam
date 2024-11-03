<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexEventRequest extends FormRequest
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
            'cursor' => ['nullable', 'string', 'min:10'],
            'from' => ['nullable', 'date', 'date_format:Y-m-d H:i:s'],
            'user_id' => ['nullable', 'numeric'] // I did not add exists:user,id rule because I suppose user is correct. another reason is this rule reduce performance.
        ];
    }
}
