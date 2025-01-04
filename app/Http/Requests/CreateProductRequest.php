<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateProductRequest extends FormRequest
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
            ],
            'nomenklature' => [
                'required',
                'string',
                'max:32',
                Rule::unique('products', 'nomenklature')->ignore($this->product)
            ],
            'price' => [
                'required',
                'numeric',
            ],
            'stock' => [
                'required',
                'numeric',
            ],
            'me' => [
                'required',
                'in:бр,кг,л,м',
            ],
            'type' => [
                'required',
                'in:Обща употреба,Процес угояване,Силоз угояване',
            ],
        ];
    }
}
