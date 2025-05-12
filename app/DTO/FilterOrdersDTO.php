<?php

namespace App\DTO;

use App\Entities\FilterEntity;

class FilterOrdersDTO
{
    private ?FilterEntity $customer = null;
    private ?FilterEntity $warehouse = null;
    private ?FilterEntity $created_at = null;
    private ?FilterEntity $completed_at = null;
    private ?FilterEntity $status = null;

    public function __construct(array $filters = [])
    {
        foreach ($filters as $filter => $properties) {
            $this->$filter = new FilterEntity($properties);
        }
    }

    public function getCustomer(): ?FilterEntity
    {
        return $this->customer;
    }

    public function getWarehouse(): ?FilterEntity
    {
        return $this->warehouse;
    }

    public function getCreatedAt(): ?FilterEntity
    {
        return $this->created_at;
    }

    public function getCompletedAt(): ?FilterEntity
    {
        return $this->completed_at;
    }

    public function getStatus(): ?FilterEntity
    {
        return $this->status;
    }
}
