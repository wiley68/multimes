<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUproductionRequest extends FormRequest
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
            'status' => [
                'required',
                'integer',
                'in:0,1',
            ],
            'uhall' => [
                'required',
                'array',
            ],
            'finished_at' => [
                'nullable',
                'timestamp',
            ],
            'stock' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'price' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'group_number' => [
                'required',
                'integer',
                'min:0',
            ],
            'partida_number' => [
                'required',
                'integer',
                'min:0',
            ],
            'product' => [
                'nullable',
                'array',
            ],
            'product.id' => [
                'nullable',
                'exists:products,id',
            ],
        ];
    }
}
