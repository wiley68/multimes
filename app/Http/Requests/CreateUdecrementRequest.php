<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUdecrementRequest extends FormRequest
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
            'uproduction' => [
                'required',
                'array',
            ],
            'product' => [
                'required',
                'array',
            ],
            'quantity' => [
                'required',
                'numeric',
            ],
            'price' => [
                'required',
                'numeric',
            ],
            'status.value' => [
                'required',
                Rule::in([0, 1]),
            ],
        ];
    }
}
