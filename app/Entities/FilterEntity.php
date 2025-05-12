<?php

namespace App\Entities;

class FilterEntity
{
    private ?string $order = null;
    private ?string $operator = null;
    private ?string $value = null;
    private ?string $value_start = null;
    private ?string $value_end = null;

    public function __construct(array $properties = [])
    {
        foreach ($properties as $property => $value) {
            $this->$property = $value;
        }

        if ($this->operator === 'like') {
            $this->value = "%$this->value%";
        }

        if ($this->operator === 'after') {
            $this->operator = '>=';
        }

        if ($this->operator === 'before') {
            $this->operator = '<=';
        }
    }

    public function getOrder(): ?string
    {
        return $this->order;
    }

    public function getOperator(): ?string
    {
        return $this->operator;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getValueStart(): ?string
    {
        return $this->value_start;
    }

    public function getValueEnd(): ?string
    {
        return $this->value_end;
    }
}
