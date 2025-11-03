<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Http\Resources\V1\ShiftResource;
use App\Http\Requests\Api\V1\StoreShiftRequest;
use App\Http\Requests\Api\V1\UpdateShiftRequest;

/**
 * @OA\Schema(
 *     schema="Shift",
 *     type="object",
 *     title="Shift",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="shiftCode", type="string", example="SHIFT-1"),
 *         @OA\Property(property="name", type="string", example="Shift 1"),
 *         @OA\Property(property="startTime", type="string", format="time", example="07:00:00"),
 *         @OA\Property(property="endTime", type="string", format="time", example="15:00:00")
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="StoreShiftRequest",
 *     type="object",
 *     title="Store Shift Request",
 *     required={"shift_cd", "shift_nm", "start_time", "end_time"},
 *     properties={
 *         @OA\Property(property="shift_cd", type="string", maxLength=20, example="SHIFT-3"),
 *         @OA\Property(property="shift_nm", type="string", maxLength=100, example="Shift 3"),
 *         @OA\Property(property="start_time", type="string", format="time", example="23:00:00"),
 *         @OA\Property(property="end_time", type="string", format="time", example="07:00:00")
 *     }
 * )
 */
class ShiftController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/shifts",
     *      operationId="getShiftsList",
     *      tags={"Master Data - Shifts"},
     *      summary="Get list of shifts",
     *      description="Returns a paginated list of shifts.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Shift")),
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
        return ShiftResource::collection(Shift::paginate());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/shifts",
     *      operationId="storeShift",
     *      tags={"Master Data - Shifts"},
     *      summary="Create a new shift",
     *      description="Stores a new shift and returns the created shift data.",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Shift data",
     *          @OA\JsonContent(ref="#/components/schemas/StoreShiftRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Shift created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Shift")
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
    public function store(StoreShiftRequest $request)
    {
        $shift = Shift::create($request->validated());
        return new ShiftResource($shift);
    }

    /**
          * @OA\Get(
     *      path="/api/v1/shifts/{shift}",
     *      operationId="getShiftById",
     *      tags={"Master Data - Shifts"},
     *      summary="Get shift information",
     *      description="Returns shift data by ID.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Shift ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Shift")
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
    public function show(Shift $shift)
    {
        return new ShiftResource($shift);
    }

    /**
          * @OA\Put(
     *      path="/api/v1/shifts/{shift}",
     *      operationId="updateShift",
     *      tags={"Master Data - Shifts"},
     *      summary="Update existing shift",
     *      description="Updates an existing shift and returns the updated shift data.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Shift ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Shift data",
     *          @OA\JsonContent(ref="#/components/schemas/StoreShiftRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Shift updated successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Shift")
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
    public function update(UpdateShiftRequest $request, Shift $shift)
    {
        $shift->update($request->validated());
        return new ShiftResource($shift);
    }

    /**
     * @OA\Delete(
     *      path="/shifts/{id}",
     *      operationId="deleteShift",
     *      tags={"Master Data - Shifts"},
     *      summary="Delete existing shift",
     *      description="Deletes a shift record and returns no content.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Shift ID",
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
    public function destroy(Shift $shift)
    {
        $shift->delete();
        return response()->noContent();
    }
}
