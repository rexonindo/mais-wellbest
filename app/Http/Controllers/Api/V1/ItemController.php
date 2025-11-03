<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Http\Resources\V1\ItemResource;
use App\Http\Requests\Api\V1\StoreItemRequest;
use App\Http\Requests\Api\V1\UpdateItemRequest;

/**
 * @OA\Schema(
 *     schema="Item",
 *     type="object",
 *     title="Item",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="itemCode", type="string", example="FG-001"),
 *         @OA\Property(property="name", type="string", example="Finished Good 1"),
 *         @OA\Property(property="description", type="string", nullable=true, example="Main finished good"),
 *         @OA\Property(property="itemType", type="string", example="FINISH GOOD"),
 *         @OA\Property(property="uom", type="string", example="PCS")
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="StoreItemRequest",
 *     type="object",
 *     title="Store Item Request",
 *     required={"itm_cd", "itm_nm", "itm_tp", "uom"},
 *     properties={
 *         @OA\Property(property="itm_cd", type="string", maxLength=20, example="RM-001"),
 *         @OA\Property(property="itm_nm", type="string", maxLength=100, example="Raw Material 1"),
 *         @OA\Property(property="descrp", type="string", nullable=true, example="Main raw material"),
 *         @OA\Property(property="itm_tp", type="string", maxLength=20, example="RAW MATERIAL"),
 *         @OA\Property(property="uom", type="string", maxLength=10, example="KG")
 *     }
 * )
 */
class ItemController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/items",
     *      operationId="getItemsList",
     *      tags={"Master Data - Items"},
     *      summary="Get list of items",
     *      description="Returns a paginated list of items.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Item")),
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
        return ItemResource::collection(Item::paginate());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/items",
     *      operationId="storeItem",
     *      tags={"Master Data - Items"},
     *      summary="Create a new item",
     *      description="Stores a new item and returns the created item data.",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Item data",
     *          @OA\JsonContent(ref="#/components/schemas/StoreItemRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Item created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Item")
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
    public function store(StoreItemRequest $request)
    {
        $item = Item::create($request->validated());
        return new ItemResource($item);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/items/{item}",
     *      operationId="getItemById",
     *      tags={"Master Data - Items"},
     *      summary="Get item information",
     *      description="Returns item data by ID.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Item ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Item")
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
    public function show(Item $item)
    {
        return new ItemResource($item);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/items/{item}",
     *      operationId="updateItem",
     *      tags={"Master Data - Items"},
     *      summary="Update existing item",
     *      description="Updates an existing item and returns the updated item data.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Item ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Item data",
     *          @OA\JsonContent(ref="#/components/schemas/StoreItemRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Item updated successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Item")
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
    public function update(UpdateItemRequest $request, Item $item)
    {
        $item->update($request->validated());
        return new ItemResource($item);
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/items/{item}",
     *      operationId="deleteItem",
     *      tags={"Master Data - Items"},
     *      summary="Delete existing item",
     *      description="Deletes an item record and returns no content.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Item ID",
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
    public function destroy(Item $item)
    {
        $item->delete();
        return response()->noContent();
    }
}
