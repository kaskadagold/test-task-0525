<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\ProductsRepositoryContract;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductsController extends Controller
{
    public function __construct(
        private readonly ProductsRepositoryContract $repo,
    ) {
    }

    /**
     * Display a listing of the products.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = $this->repo->getItems(['stocks']);
        $result = ProductResource::collection($products);

        return new JsonResponse(['products' => $result]);
    }
}
