<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateDeliveryRequest extends FormRequest
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
            'document' => [
                'required',
                'string',
                'max:32',
            ],
            'supplier' => [
                'nullable',
                'string',
                'max:128',
            ],
            'status' => [
                'required',
                'array',
            ],
            'status.label' => [
                'required',
                Rule::in(['Типов документ', 'Приключен документ']),
            ],
            'status.value' => [
                'required',
                Rule::in([0, 1]),
            ],
        ];
    }
}
