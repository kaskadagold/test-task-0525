<?php

namespace App\Services;

use App\Contracts\Repositories\OrderItemsRepositoryContract;
use App\Contracts\Repositories\OrdersRepositoryContract;
use App\Contracts\Repositories\ProductsRepositoryContract;
use App\Contracts\Repositories\StocksRepositoryContract;
use App\Contracts\Services\ChangeStatusOrderServiceContract;
use App\Contracts\Services\StoreOrderServiceContract;
use App\Contracts\Services\UpdateOrderServiceContract;
use App\Entities\StatusOrder;
use App\Exceptions\NotEnoughStockException;
use App\Exceptions\StockNotFoundException;
use App\Models\Order;
use App\Models\Stock;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Override;

class OrdersService implements StoreOrderServiceContract, UpdateOrderServiceContract, ChangeStatusOrderServiceContract
{
    public function __construct(
        private readonly OrdersRepositoryContract $repo,
        private readonly StocksRepositoryContract $stockRepo,
        private readonly ProductsRepositoryContract $prodRepo,
        private readonly OrderItemsRepositoryContract $oiRepo,
    ) {
    }

    #[Override]
    public function create(array $fields): ?Order
    {
        $order = null;

        DB::transaction( function () use ($fields, &$order) {
            /* Get the list of required products' id */
            $products = [];
            foreach ($fields['products'] as $item) {
                $products[$item['id']] = $item['count'];
            }
            unset($fields['products']);

            $stocks = $this->stockRepo->getItems($fields['warehouse_id']);

            $order = $this->repo->create($fields);

            /* Update stocks and fill order items */
            $this->addOrderItems($products, $stocks, $order);
        });

        return $order;
    }

    #[Override]
    public function update(Order $order, array $fields): Order
    {
        DB::transaction(function () use ($order, $fields) {
            if (isset($fields['products'])) {
                $orderItems = $this->oiRepo->getByOrder($order->id);

                $oldItemsArr = $newItemsArr = [];
                $common = $add = $remove = [];

                /* Fill the arrays of differences between new and old orders */
                $this->fillDiffArrays(
                    $fields['products'],
                    $orderItems,
                    $oldItemsArr,
                    $newItemsArr,
                    $common,
                    $add,
                    $remove
                );

                $stocks = $this->stockRepo->getItems($order->warehouse_id);

                /* Update the existing items and the corresponding stocks */
                $this->updateOrderItems($common, $stocks, $order);
                /* Fill new order items and update the corresponding stocks */
                $this->addOrderItems($add, $stocks, $order);
                /* Remove the required items and update the corresponding stocks */
                $this->removeOrderItems($remove, $stocks, $order);

                unset($fields['products']);
            }

            if (!empty($fields)) {
                $order = $this->repo->update($order, $fields);
            }
        });

        $order = $this->repo->getById($order->id);

        return $order;
    }

    #[Override]
    public function cancelOrder(Order $order): Order
    {
        DB::transaction(function () use (&$order) {
            $products = $order->orderItems->pluck('count', 'product_id')->toArray();
            $stocks = $this->stockRepo->getItems($order->warehouse_id);

            foreach ($products as $prodId => $count) {
                $stock = $stocks->where('product_id', '=', $prodId)->first();

                $product = $this->prodRepo->getById($prodId);

                /* Update or add the corresponding stock */
                if ($stock) {
                    $product->stocks()->updateExistingPivot($order->warehouse_id, ['stock' => $stock->stock + $count]);
                } else {
                    $product->stocks()->attach($order->warehouse_id, ['stock' => $count]);
                }
            }

            $order = $this->repo->update($order, ['status' => StatusOrder::CANCELED]);
        });

        return $order;
    }

    #[Override]
    public function renewOrder(Order $order): Order
    {
        DB::transaction(function () use (&$order) {
            $products = $order->orderItems->pluck('count', 'product_id')->toArray();
            $stocks = $this->stockRepo->getItems($order->warehouse_id);

            foreach ($products as $prodId => $count) {
                $stock = $stocks->where('product_id', '=', $prodId)->first();

                /* Check if there is a required stock and it is enough for the order */
                $this->checkStock($stock, $prodId, $count, $order->warehouse_id);

                $product = $this->prodRepo->getById($prodId);
                /* Update the corresponding stock */
                $product->stocks()->updateExistingPivot($order->warehouse_id, ['stock' => $stock->stock - $count]);
            }

            $order = $this->repo->update($order, ['status' => StatusOrder::ACTIVE]);
        });

        return $order;
    }

    /**
     * Fill the arrays of differences between new and old orders (common items, items to add, and items to remove)
     *
     * @param array $products
     * @param \Illuminate\Support\Collection $orderItems
     * @param array $oldItemsArr
     * @param array $newItemsArr
     * @param array $common
     * @param array $add
     * @param array $remove
     *
     * @return void
     */
    private function fillDiffArrays(
        array $products,
        Collection $orderItems,
        array &$oldItemsArr,
        array &$newItemsArr,
        array &$common,
        array &$add,
        array &$remove,
    ): void {
        foreach ($orderItems as $item) {
            $oldItemsArr[$item->product_id] = $item->count;
        }

        foreach ($products as $item) {
            $newItemsArr[$item['id']] = $item['count'];
        }

        foreach ($newItemsArr as $prodId => $count) {
            /* If the product from a new order exists in an old order, then add to the common-array and calculate the count changes */
            if (isset($oldItemsArr[$prodId])) {
                $common[$prodId] = $count - $oldItemsArr[$prodId];
            } else {
            /* If there is no such a product in an old order, then add it to the add-array */
                $add[$prodId] = $count;
            }
        }

        foreach ($oldItemsArr as $prodId => $count) {
            /* If the product from an old order doesn't exist in a new order, then add it to the remove-array */
            if (!isset($common[$prodId])) {
                $remove[$prodId] = $count;
            }
        }
    }

    /**
     * Fill order items for the specified order and update the corresponding stocks.
     *
     * @param array $products
     * @param \Illuminate\Support\Collection $stocks
     * @param \App\Models\Order $order
     *
     * @throws \App\Exceptions\StockNotFoundException
     * @throws \App\Exceptions\NotEnoughStockException
     *
     * @return void
     */
    private function addOrderItems(array $products, Collection $stocks, Order $order): void
    {
        foreach ($products as $prodId => $count) {
            $stock = $stocks->where('product_id', '=', $prodId)->first();

            /* Check if there is a required stock and it is enough for the order */
            $this->checkStock($stock, $prodId, $count, $order->warehouse_id);

            /* Update the corresponding stock */
            $product = $this->prodRepo->getById($prodId);
            $product->stocks()->updateExistingPivot($order->warehouse_id, ['stock' => $stock->stock - $count]);

            $this->oiRepo->create([
                'order_id' => $order->id,
                'product_id' => $prodId,
                'count' => $count,
            ]);
        }
    }

    /**
     * Update the existing items of the order and the corresponding stocks.
     *
     * @param array $common
     * @param \Illuminate\Support\Collection $stocks
     * @param \App\Models\Order $order
     *
     * @throws \App\Exceptions\StockNotFoundException
     * @throws \App\Exceptions\NotEnoughStockException
     *
     * @return void
     */
    private function updateOrderItems(array $common, Collection $stocks, Order $order): void
    {
        foreach ($common as $prodId => $diffCount) {
            $stock = $stocks->where('product_id', '=', $prodId)->first();

            /* Check if it is necessary to TAKE products from the warehouse */
            if ($diffCount > 0) {
                /* Check if there is a required stock and it is enough for the order */
                $this->checkStock($stock, $prodId, $diffCount, $order->warehouse_id);
            }

            $product = $this->prodRepo->getById($prodId);

            /* Update or add the corresponding stock */
            if ($diffCount < 0 && !$stock) {
                $product->stocks()->attach($order->warehouse_id, ['stock' => -$diffCount]);
            } else {
                $product->stocks()->updateExistingPivot($order->warehouse_id, ['stock' => $stock->stock - $diffCount]);
            }

            $orderItem = $this->oiRepo->getByKey($order->id, $prodId);
            $orderItem = $this->oiRepo->update($orderItem, ['count' => $orderItem->count + $diffCount]);
        }
    }

    /**
     * Remove the required items of the order and update the corresponding stocks.
     *
     * @param array $remove
     * @param \Illuminate\Support\Collection $stocks
     * @param \App\Models\Order $order
     *
     * @return void
     */
    private function removeOrderItems(array $remove, Collection $stocks, Order $order): void
    {
        foreach ($remove as $prodId => $count) {
            $stock = $stocks->where('product_id', '=', $prodId)->first();

            $product = $this->prodRepo->getById($prodId);

            /* Update or add the corresponding stock */
            if ($stock) {
                $product->stocks()->updateExistingPivot($order->warehouse_id, ['stock' => $stock->stock + $count]);
            } else {
                $product->stocks()->attach($order->warehouse_id, ['stock' => $count]);
            }

            $orderItem = $this->oiRepo->getByKey($order->id, $prodId);
            $this->oiRepo->delete($orderItem);
        }
    }

    /**
     * Check if there is a required stock and it is enough for the order.
     *
     * @param mixed $stock
     * @param int $prodId
     * @param int $count
     * @param int $warehouseId
     *
     * @throws \App\Exceptions\StockNotFoundException
     * @throws \App\Exceptions\NotEnoughStockException
     *
     * @return void
     */
    private function checkStock(?Stock $stock, int $prodId, int $count, int $warehouseId): void
    {
        /* Check if there is a required product in the required warehouse */
        if (!$stock) {
            throw new StockNotFoundException($prodId, $warehouseId);
        }

        /* Check if the stock of the product is enough to make/update the order */
        if ($stock->stock < $count) {
            throw new NotEnoughStockException($prodId, $warehouseId);
        }
    }
}
