<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class OrderItemNotFoundException extends Exception
{
    public function __construct(
        public readonly int $orderId,
        public readonly int $productId,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $message = sprintf(
            'Среди списка позиций заказа (id = %d) не существует товара с id = %d',
            $orderId, $productId
        );

        parent::__construct($message, $code, $previous);
    }
}
