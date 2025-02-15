<?php

namespace App\Http\Requests;

use App\Models\Mhall;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateMhallRequest extends FormRequest
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
                Rule::unique('mhalls', 'name')->ignore($this->mhall),
            ],
            'type' => [
                'required',
                Rule::in(Mhall::TYPE_OPTIONS),
            ],
            'factory' => [
                'required',
                'array',
            ],
            'silo' => [
                'required',
                'array',
            ],
        ];
    }
}
