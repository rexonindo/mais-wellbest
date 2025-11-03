<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Http\Resources\V1\MachineResource;
use App\Http\Requests\Api\V1\StoreMachineRequest;
use App\Http\Requests\Api\V1\UpdateMachineRequest;

/**
 * @OA\Schema(
 *     schema="Machine",
 *     type="object",
 *     title="Machine",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="machineCode", type="string", example="MC-001"),
 *         @OA\Property(property="name", type="string", example="Mesin Bubut A"),
 *         @OA\Property(property="description", type="string", nullable=true, example="Mesin bubut otomatis"),
 *         @OA\Property(property="department", ref="#/components/schemas/Department")
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="StoreMachineRequest",
 *     type="object",
 *     title="Store Machine Request",
 *     required={"mchn_cd", "mchn_nm", "dept_cd"},
 *     properties={
 *         @OA\Property(property="mchn_cd", type="string", maxLength=20, example="MC-002"),
 *         @OA\Property(property="mchn_nm", type="string", maxLength=100, example="Mesin CNC B"),
 *         @OA\Property(property="descrp", type="string", nullable=true, example="Mesin CNC 5-axis"),
 *         @OA\Property(property="dept_cd", type="string", maxLength=20, example="PROD")
 *     }
 * )
 */
class MachineController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/machines",
     *      operationId="getMachinesList",
     *      tags={"Master Data - Machines"},
     *      summary="Get list of machines",
     *      description="Returns a paginated list of machines.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Machine")),
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
        return MachineResource::collection(Machine::with('department')->paginate());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/machines",
     *      operationId="storeMachine",
     *      tags={"Master Data - Machines"},
     *      summary="Create a new machine",
     *      description="Stores a new machine and returns the created machine data.",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Machine data",
     *          @OA\JsonContent(ref="#/components/schemas/StoreMachineRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Machine created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Machine")
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
    public function store(StoreMachineRequest $request)
    {
        $machine = Machine::create($request->validated());
        return new MachineResource($machine->load('department'));
    }

    /**
          * @OA\Get(
     *      path="/api/v1/machines/{machine}",
     *      operationId="getMachineById",
     *      tags={"Master Data - Machines"},
     *      summary="Get machine information",
     *      description="Returns machine data by ID.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Machine ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Machine")
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
    public function show(Machine $machine)
    {
        return new MachineResource($machine->load('department'));
    }

    /**
          * @OA\Put(
     *      path="/api/v1/machines/{machine}",
     *      operationId="updateMachine",
     *      tags={"Master Data - Machines"},
     *      summary="Update existing machine",
     *      description="Updates an existing machine and returns the updated machine data.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Machine ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Machine data",
     *          @OA\JsonContent(ref="#/components/schemas/StoreMachineRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Machine updated successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Machine")
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
    public function update(UpdateMachineRequest $request, Machine $machine)
    {
        $machine->update($request->validated());
        return new MachineResource($machine->load('department'));
    }

    /**
          * @OA\Delete(
     *      path="/api/v1/machines/{machine}",
     *      operationId="deleteMachine",
     *      tags={"Master Data - Machines"},
     *      summary="Delete existing machine",
     *      description="Deletes a machine record and returns no content.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Machine ID",
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
    public function destroy(Machine $machine)
    {
        $machine->delete();
        return response()->noContent();
    }
}
