<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class NotEnoughStockException extends Exception
{
    public function __construct(
        public readonly int $productId,
        public readonly int $warehouseId,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $message = sprintf(
            'Невозможно выполнить: на складе (id = %d) недостаточно товара (id = %d)',
            $warehouseId, $productId
        );

        parent::__construct($message, $code, $previous);
    }
}
