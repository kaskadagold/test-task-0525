<?php

namespace App\Http\Requests;

use App\Rules\UniqueOrderItem;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer' => ['sometimes', 'string'],
            'products' => ['sometimes', 'array', new UniqueOrderItem],
            'products.*' => ['array:id,count'],
            'products.*.id' => ['required', 'integer'],
            'products.*.count' => ['required', 'integer'],
        ];
    }
}
