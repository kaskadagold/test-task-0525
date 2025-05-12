<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetProductFlowsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'perPage' => ['sometimes', 'integer'],
            'filters' => ['sometimes', 'array'],

            'filters.warehouse' => ['sometimes'],
            'filters.warehouse.operator' => ['sometimes', Rule::in(['=', 'like'])],
            'filters.warehouse.value' => ['sometimes'],

            'filters.product' => ['sometimes'],
            'filters.product.operator' => ['sometimes', Rule::in(['=', 'like'])],
            'filters.product.value' => ['sometimes'],

            'filters.created_at' => ['sometimes'],
            'filters.created_at.operator' => ['sometimes', Rule::in(['=', 'after', 'before', 'between'])],
            'filters.created_at.value' => ['sometimes', 'date'],
            'filters.created_at.value_start' => ['sometimes', 'date'],
            'filters.created_at.value_end' => ['sometimes', 'date'],

            'filters.*.order' => ['sometimes', Rule::in(['order_asc', 'order_desc'])],
        ];
    }
}
