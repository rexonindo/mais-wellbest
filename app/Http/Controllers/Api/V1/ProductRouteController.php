<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ProductRoute;
use App\Http\Resources\V1\ProductRouteResource;
use App\Http\Requests\Api\V1\StoreProductRouteRequest;
use App\Http\Requests\Api\V1\UpdateProductRouteRequest;

/**
 * @OA\Schema(
 *     schema="ProductRoute",
 *     type="object",
 *     title="Product Route",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="itemType", type="string", example="FINISH GOOD"),
 *         @OA\Property(property="sequence", type="integer", example=10),
 *         @OA\Property(property="process", ref="#/components/schemas/Process")
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="StoreProductRouteRequest",
 *     type="object",
 *     title="Store Product Route Request",
 *     required={"itm_tp", "proc_cd", "seq"},
 *     properties={
 *         @OA\Property(property="itm_tp", type="string", maxLength=20, example="RAW MATERIAL"),
 *         @OA\Property(property="proc_cd", type="string", maxLength=20, example="PROC-01"),
 *         @OA\Property(property="seq", type="integer", example=20)
 *     }
 * )
 */
class ProductRouteController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/product-routes",
     *      operationId="getProductRoutesList",
     *      tags={"Master Data - Product Routes"},
     *      summary="Get list of product routes",
     *      description="Returns a paginated list of product routes.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ProductRoute")),
     *              @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
     *              @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      )
     * )
     */
    public function index()
    {
        return ProductRouteResource::collection(ProductRoute::with('process')->paginate());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/product-routes",
     *      operationId="storeProductRoute",
     *      tags={"Master Data - Product Routes"},
     *      summary="Create a new product route",
     *      description="Stores a new product route and returns the created product route data.",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Product route data",
     *          @OA\JsonContent(ref="#/components/schemas/StoreProductRouteRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Product route created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/ProductRoute")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      )
     * )
     */
    public function store(StoreProductRouteRequest $request)
    {
        $productRoute = ProductRoute::create($request->validated());
        return new ProductRouteResource($productRoute->load('process'));
    }

    /**
          * @OA\Get(
     *      path="/api/v1/product-routes/{product_route}",
     *      operationId="getProductRouteById",
     *      tags={"Master Data - Product Routes"},
     *      summary="Get product route information",
     *      description="Returns product route data by ID.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Product Route ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ProductRoute")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      )
     * )
     */
    public function show(ProductRoute $productRoute)
    {
        return new ProductRouteResource($productRoute->load('process'));
    }

    /**
          * @OA\Put(
     *      path="/api/v1/product-routes/{product_route}",
     *      operationId="updateProductRoute",
     *      tags={"Master Data - Product Routes"},
     *      summary="Update existing product route",
     *      description="Updates an existing product route and returns the updated product route data.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Product Route ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Product route data",
     *          @OA\JsonContent(ref="#/components/schemas/StoreProductRouteRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Product route updated successfully",
     *          @OA\JsonContent(ref="#/components/schemas/ProductRoute")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      )
     * )
     */
    public function update(UpdateProductRouteRequest $request, ProductRoute $productRoute)
    {
        $productRoute->update($request->validated());
        return new ProductRouteResource($productRoute->load('process'));
    }

    /**
     * @OA\Delete(
     *      path="/product-routes/{id}",
     *      operationId="deleteProductRoute",
     *      tags={"Master Data - Product Routes"},
     *      summary="Delete existing product route",
     *      description="Deletes a product route record and returns no content.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Product Route ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="No Content"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      )
     * )
     */
    public function destroy(ProductRoute $productRoute)
    {
        $productRoute->delete();
        return response()->noContent();
    }
}
