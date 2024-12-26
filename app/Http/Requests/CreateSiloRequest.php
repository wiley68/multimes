<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSiloRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('silos', 'name')->ignore($this->silo),
            ],
            'maxqt' => [
                'required',
                'numeric',
                'min:100',
            ],
            'stock' => [
                'nullable',
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
            'factory' => [
                'required',
                'array',
            ],
            'factory.id' => [
                'required',
                'exists:factories,id',
            ],
        ];
    }
}
