<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Products", description="CRUD produktów demonstracyjnych")
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(path="/api/products", tags={"Products"}, summary="Lista produktów",
     *   @OA\Response(response=200, description="OK")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $products = Product::query()
            ->when($request->query('search'), fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate((int) $request->query('per_page', 15));

        return response()->json($products);
    }

    /**
     * @OA\Post(path="/api/products", tags={"Products"}, summary="Utwórz produkt",
     *   @OA\Response(response=201, description="Created")
     * )
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $product = Product::query()->create($request->validated());

        return response()->json($product, 201);
    }

    /**
     * @OA\Get(path="/api/products/{product}", tags={"Products"}, summary="Szczegóły produktu",
     *   @OA\Parameter(name="product", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK")
     * )
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json($product);
    }

    /**
     * @OA\Put(path="/api/products/{product}", tags={"Products"}, summary="Aktualizuj produkt",
     *   @OA\Parameter(name="product", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK")
     * )
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());

        return response()->json($product->fresh());
    }

    /**
     * @OA\Delete(path="/api/products/{product}", tags={"Products"}, summary="Usuń produkt",
     *   @OA\Parameter(name="product", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=204, description="No Content")
     * )
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json(null, 204);
    }
}
