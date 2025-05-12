<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueOrderItem implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $presences = [];
        foreach ($value as $item) {
            if (isset($presences[$item['id']])) {
                $fail('Заказ не может быть создан: среди списка товаров есть повторяющиеся записи.');
            }
            $presences[$item['id']] = 1;
        }
    }
}
