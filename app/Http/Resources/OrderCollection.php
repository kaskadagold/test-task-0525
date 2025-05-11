<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'page' => $this->resource->currentPage(),
            'per_page' => $this->resource->perPage(),
            'total_count_orders' => $this->resource->total(),
            'last_page' => $this->resource->lastPage(),
            'orders' => $this->collection,
        ];
    }
}
