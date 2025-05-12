<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductFlowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'source_type' => $this->source_type,
            'source_id' => $this->source_id,
            'source_action' => $this->source_action,
            'warehouse' => [
                'warehouse_id' => $this->warehouse_id,
                'warehouse_name' => $this->warehouse->name,
            ],
            'product' => [
                'product_id' => $this->product_id,
                'product_name' => $this->product->name,
            ],
            'old_value' => $this->old_value,
            'new_value' => $this->new_value,
            'diff' => $this->diff,
            'created_at' => $this->created_at,
        ];
    }
}
