<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\OrdersRepositoryContract;
use App\Contracts\Services\ChangeStatusOrderServiceContract;
use App\Contracts\Services\StoreOrderServiceContract;
use App\Contracts\Services\UpdateOrderServiceContract;
use App\DTO\FilterOrdersDTO;
use App\Entities\StatusOrder;
use App\Exceptions\WrongOrderStatus;
use App\Http\Requests\GetOrdersRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use Illuminate\Http\JsonResponse;

class OrdersController extends Controller
{
    public function __construct(
        private readonly OrdersRepositoryContract $repo,
    ) {
    }

    /**
     * Display a list of orders with applying filters and pagination.
     *
     * @param \App\Http\Requests\GetOrdersRequest $request
     * @return JsonResponse
     */
    public function index(GetOrdersRequest $request): JsonResponse
    {
        $fields = $request->validated();
        $fields['filters'] ??= [];
        $page = $request->get('page');

        $filterOrdersDTO = new FilterOrdersDTO($fields['filters']);

        $orders = $this->repo->paginate(
            filters: $filterOrdersDTO,
            perPage: $fields['perPage'] ?? 5,
            page: $page ?? 1,
            relations: ['warehouse', 'orderItems']
        );
        $result = new OrderCollection($orders);

        return new JsonResponse(['success' => true, 'data' => $result]);
    }

    /**
     * Store a newly created order in storage.
     *
     * @param \App\Http\Requests\StoreOrderRequest $request
     * @param \App\Contracts\Services\StoreOrderServiceContract $ordersService
     *
     * @return JsonResponse
     */
    public function store(StoreOrderRequest $request, StoreOrderServiceContract $ordersService): JsonResponse
    {
        $fields = $request->validated();
        $fields['created_at'] = now()->toDateTimeString();
        $fields['status'] = StatusOrder::ACTIVE;

        $order = $ordersService->create($fields);
        $order = new OrderResource($order);

        return new JsonResponse(['success' => true, 'order' => $order]);
    }

    /**
     * Update the specified order in storage.
     *
     * @param \App\Http\Requests\UpdateOrderRequest $request
     * @param int $orderId
     * @param \App\Contracts\Services\UpdateOrderServiceContract $ordersService
     *
     * @throws \App\Exceptions\WrongOrderStatus
     * @return JsonResponse
     */
    public function update(
        UpdateOrderRequest $request,
        int $orderId,
        UpdateOrderServiceContract $ordersService,
    ): JsonResponse {
        $order = $this->repo->getById($orderId, ['orderItems']);

        if ($order->status !== StatusOrder::ACTIVE) {
            throw new WrongOrderStatus($order->status, 'update');
        }

        $order = $ordersService->update($order, $request->validated());
        $order = new OrderResource($order);

        return new JsonResponse(['success' => true, 'order' => $order]);
    }

    /**
     * Complete the order.
     *
     * @param int $orderId
     * @throws \App\Exceptions\WrongOrderStatus
     * @return JsonResponse
     */
    public function complete(int $orderId): JsonResponse
    {
        $order = $this->repo->getById($orderId);

        if ($order->status !== StatusOrder::ACTIVE) {
            throw new WrongOrderStatus($order->status, 'complete');
        }

        $order = $this->repo->update($order, [
            'status' => StatusOrder::COMPLETED,
            'completed_at' => now(),
        ]);
        $order = new OrderResource($order);

        return new JsonResponse(['success' => true, 'order' => $order]);
    }

    /**
     * Cancel the order.
     *
     * @param int $orderId
     * @param \App\Contracts\Services\ChangeStatusOrderServiceContract $ordersService
     * @throws \App\Exceptions\WrongOrderStatus
     * @return JsonResponse
     */
    public function cancel(int $orderId, ChangeStatusOrderServiceContract $ordersService): JsonResponse
    {
        $order = $this->repo->getById($orderId, ['orderItems']);

        if ($order->status !== StatusOrder::ACTIVE) {
            throw new WrongOrderStatus($order->status, 'cancel');
        }

        $order = $ordersService->cancelOrder($order);
        $order = new OrderResource($order);

        return new JsonResponse(['success' => true, 'order' => $order]);
    }

    /**
     * Renew the order.
     *
     * @param int $orderId
     * @param \App\Contracts\Services\ChangeStatusOrderServiceContract $ordersService
     * @throws \App\Exceptions\WrongOrderStatus
     * @return JsonResponse
     */
    public function renew(int $orderId, ChangeStatusOrderServiceContract $ordersService): JsonResponse
    {
        $order = $this->repo->getById($orderId, ['orderItems']);

        if ($order->status !== StatusOrder::CANCELED) {
            throw new WrongOrderStatus($order->status, 'renew');
        }

        $order = $ordersService->renewOrder($order);
        $order = new OrderResource($order);

        return new JsonResponse(['success' => true, 'order' => $order]);
    }
}
