<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateMincrementRequest extends FormRequest
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
            'mproduction_id' => [
                'required',
                'exists:mproductions,id',
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
            'price' => [
                'required',
                'numeric',
            ],
            'status' => [
                'required',
                Rule::in([0, 1]),
            ],
        ];
    }
}
