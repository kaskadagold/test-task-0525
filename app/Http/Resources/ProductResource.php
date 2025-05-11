<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $warehouses = [];
        foreach ($this->stocks as $stock) {
            $warehouses[] = [
                'warehouse_id' => $stock->id,
                'warehouse_name' => $stock->name,
                'stock' => $stock->pivot->stock,
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->id,
            'stocks' => $warehouses,
        ];
    }
}
