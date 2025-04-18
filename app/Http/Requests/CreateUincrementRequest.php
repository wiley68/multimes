<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUincrementRequest extends FormRequest
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
            'uproduction_id' => [
                'required',
                'exists:uproductions,id',
            ],
            'product' => [
                'required',
                'array',
            ],
            'product.id' => [
                'required',
                'exists:products,id',
            ],
            'quantity' => [
                'required',
                'numeric',
            ],
            'weight' => [
                'required',
                'numeric',
                'min:0',
            ],
            'price' => [
                'required',
                'numeric',
            ],
            'status' => [
                'required',
                Rule::in([0, 1]),
            ],
            'type' => [
                'required',
                Rule::in(['Продажба', 'Умрели']),
            ],
        ];
    }
}
