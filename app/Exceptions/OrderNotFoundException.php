<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class OrderNotFoundException extends Exception
{
    public function __construct(
        public readonly int $id,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $message = sprintf(
            'Не существует заказа с id = %d',
            $id
        );

        parent::__construct($message, $code, $previous);
    }
}
