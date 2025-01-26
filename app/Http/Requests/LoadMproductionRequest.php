<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoadMproductionRequest extends FormRequest
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
            'stock' => [
                'required',
                'numeric',
                'min:1',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'product' => [
                'required',
                'array',
            ],
            'product.id' => [
                'required',
                'exists:products,id',
            ],
        ];
    }
}
