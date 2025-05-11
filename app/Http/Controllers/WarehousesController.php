<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\WarehousesRepositoryContract;
use App\Http\Resources\WarehouseResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WarehousesController extends Controller
{
    public function __construct(
        private readonly WarehousesRepositoryContract $repo,
    ) {
    }

    /**
     * Display a listing of the warehouses.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $warehouses = $this->repo->getItems();
        $result = WarehouseResource::collection($warehouses);

        return new JsonResponse(['warehouses' => $result]);
    }
}
