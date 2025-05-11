<?php

namespace App\Entities;

class StatusOrder
{
    public const ACTIVE = 'active';
    public const COMPLETED = 'completed';
    public const CANCELED = 'canceled';

    public static function getAllStatuses(): array
    {
        $statuses['ACTIVE'] = self::ACTIVE;
        $statuses['COMPLETED'] = self::COMPLETED;
        $statuses['CANCELED'] = self::CANCELED;

        return $statuses;
    }
}
