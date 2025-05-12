<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $productsCount = 10;
        $warehousesCount = 5;
        $ordersCount = 5;

        /* Fill the table `products` with data */
        $products = Product::factory()->count($productsCount)->create();

        /* Fill the table `warehouses` with data */
        $warehouses = Warehouse::factory()->count($warehousesCount)->create();

        /* Fill the table `order` with data */
        $orders = collect();
        for ($i = 0; $i < $ordersCount; $i++) {
            $orders->push(
                Order::factory()->create(['warehouse_id' => $warehouses->random()])
            );
        }

        /* Fill the table `stocks` with data */
        $this->fillStocks($products, $warehouses);

        /* Fill the table `order_items` with data */
        $this->fillOrderItems($orders);
    }

    /**
     * Fill the pivot table `stocks` using the existing data of products and warehouses
     *
     * @param \Illuminate\Support\Collection $products
     * @param Collection $warehouses
     * @return void
     */
    private function fillStocks(Collection $products, Collection $warehouses): void
    {
        foreach($products as $product) {
            /* The list of already used warehouses' id */
            $addedWarehouses = [];

            for ($i = 0; $i <= rand(-1, 4); $i++) {
                $addWarehouse = $warehouses->random();

                /* Check if the randomly chosen warehouse hasn't already been used for this product */
                while (in_array($addWarehouse->id, $addedWarehouses)) {
                    $addWarehouse = $warehouses->random();
                }

                $product->stocks()->attach($addWarehouse, ['stock' => rand(0, 9)]);

                $addedWarehouses[] = $addWarehouse->id;
            }
        }
    }

    /**
     * Fill the `order_items` table using the existing data of orders, products, and warehouses
     *
     * @param \Illuminate\Support\Collection $orders
     * @return void
     */
    private function fillOrderItems(Collection $orders): void
    {
        foreach ($orders as $order) {
            $availableProducts = $order->warehouse()
                ->first()
                ->stocks()
                ->get();

            /* The list of already used products' id */
            $addedProducts = [];
            for ($i = 0; $i < rand(1, count($availableProducts)); $i++) {
                $addProduct = $availableProducts->random();

                /* Check if the randomly chosen product hasn't already been used for this order */
                while (in_array($addProduct->id, $addedProducts)) {
                    $addProduct = $availableProducts->random();
                }

                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $addProduct,
                ]);

                $addedProducts[] = $addProduct->id;
            }
        }
    }
}
