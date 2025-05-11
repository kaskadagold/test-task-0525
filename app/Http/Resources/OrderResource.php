<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $orderItems = [];
        foreach ($this->orderItems as $item) {
            $orderItems[] = [
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'count' => $item->count,
            ];
        }

        return [
            'id' => $this->id,
            'customer' => $this->customer,
            'status' => $this->status,
            'warehouse' => [
                'warehouse_id' => $this->warehouse->id,
                'warehouse_name' => $this->warehouse->name,
            ],
            'created_at' => $this->created_at,
            'completed_at' => $this->completed_at,
            'order_items' => $orderItems,
        ];
    }
}
