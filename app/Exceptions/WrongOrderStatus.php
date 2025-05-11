<?php

namespace App\Exceptions;

use App\Entities\StatusOrder;
use Exception;
use Throwable;

class WrongOrderStatus extends Exception
{
    public function __construct(
        public readonly string $status,
        public readonly string $action,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        if ($status === StatusOrder::COMPLETED) {
            $message = match ($action) {
                'renew' => 'Нельзя возобновить завершённый заказ',
                'complete' => 'Заказ уже был завершён',
                'cancel' => 'Нельзя отменить завершённый заказ',
                'update' => 'Нельзя обновить завершённый заказ',
            };
        } elseif ($status === StatusOrder::CANCELED) {
            $message = match ($action) {
                'complete' => 'Нельзя отменить отменённый заказ',
                'cancel' => 'Заказ уже был отменён',
                'update' => 'Нельзя обновить отменённый заказ',
            };
        } elseif ($status === StatusOrder::ACTIVE) {
            if ($action === 'renew') {
                $message = 'Заказ уже находится в работе';
            }
        } else {
            $message = 'Неподходящий статус заказа';
        }

        parent::__construct($message, $code, $previous);
    }
}
