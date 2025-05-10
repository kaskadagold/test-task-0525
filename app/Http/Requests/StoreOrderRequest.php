<?php

namespace App\Http\Requests;

use App\Entities\StatusOrder;
use App\Rules\UniqueOrderItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer' => ['required', 'string'],
            'warehouse_id' => ['required', 'integer'],
            'products' => ['required', new UniqueOrderItem],
            'products.*' => ['required', 'array:id,count'],
            'products.*.id' => ['required', 'integer'],
            'products.*.count' => ['required', 'integer'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'products' => $this->products,
        ]);
    }
}
