<?php

namespace App\Entities;

use JsonSerializable;

enum StatusOrder: string implements JsonSerializable
{
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';

    /** @return string  */
    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
