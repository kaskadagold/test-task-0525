<?php

namespace App\Http\Requests;

use App\Entities\StatusOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetOrdersRequest extends FormRequest
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

            'filters.customer' => ['sometimes'],
            'filters.customer.operator' => ['sometimes', Rule::in(['=', 'like'])],
            'filters.customer.value' => ['sometimes', 'string'],

            'filters.warehouse' => ['sometimes'],
            'filters.warehouse.operator' => ['sometimes', Rule::in(['=', 'like'])],
            'filters.warehouse.value' => ['sometimes', 'string'],

            'filters.created_at' => ['sometimes'],
            'filters.created_at.operator' => ['sometimes', Rule::in(['=', 'after', 'before', 'between'])],
            'filters.created_at.value' => ['sometimes', 'date'],
            'filters.created_at.value_start' => ['sometimes', 'date'],
            'filters.created_at.value_end' => ['sometimes', 'date'],

            'filters.completed_at' => ['sometimes'],
            'filters.completed_at.operator' => ['sometimes', Rule::in(['=', 'after', 'before', 'between', 'is_null', 'is_not_null'])],
            'filters.completed_at.value' => ['sometimes', 'date'],
            'filters.completed_at.value_start' => ['sometimes', 'date'],
            'filters.completed_at.value_end' => ['sometimes', 'date'],

            'filters.*.order' => ['sometimes', Rule::in(['order_asc', 'order_desc'])],

            'filters.status' => ['sometimes', 'array:operator,value'],
            'filters.status.operator' => ['sometimes', Rule::in(['='])],
            'filters.status.value' => ['sometimes', Rule::in(StatusOrder::getAllStatuses())],
        ];
    }
}
