<?php

namespace Database\Factories;

use App\Entities\StatusOrder;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer' => $this->faker->name(),
            'created_at' => now(),
            'completed_at' => null,
            'warehouse_id' => Warehouse::factory(),
            'status' => StatusOrder::ACTIVE,
        ];
    }
}
