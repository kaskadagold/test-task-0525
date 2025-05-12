<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\ProductFlowsRepositoryContract;
use App\DTO\FilterProductFlowsDTO;
use App\Http\Requests\GetProductFlowsRequest;
use App\Http\Resources\ProductFlowCollection;
use Illuminate\Http\JsonResponse;

class ProductFlowsController extends Controller
{
    public function __construct(
        private readonly ProductFlowsRepositoryContract $repo,
    ) {
    }


    public function index(GetProductFlowsRequest $request)
    {
        $fields = $request->validated();
        $fields['filters'] ??= [];
        $page = $request->get('page');

        $filterOrdersDTO = new FilterProductFlowsDTO($fields['filters']);

        $productFlows = $this->repo->paginate(
            filters: $filterOrdersDTO,
            perPage: $fields['perPage'] ?? 5,
            page: $page ?? 1,
            relations: ['warehouse', 'product']
        );
        $result = new ProductFlowCollection($productFlows);

        return new JsonResponse(['success' => true, 'data' => $result]);
    }
}
