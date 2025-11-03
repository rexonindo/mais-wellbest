<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Http\Resources\V1\WorkOrderResource;
use App\Http\Requests\Api\V1\StoreWorkOrderRequest;
use App\Http\Requests\Api\V1\UpdateWorkOrderRequest;
use Illuminate\Http\Request;
use App\Models\Process;
use App\Http\Resources\V1\ProcessResource;

/**
 * @OA\Schema(
 *     schema="WorkOrder",
 *     type="object",
 *     title="Work Order",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="workOrderNumber", type="string", example="WO-2025-001"),
 *         @OA\Property(property="orderDate", type="string", format="date", example="2025-10-26"),
 *         @OA\Property(property="planDate", type="string", format="date", example="2025-10-27"),
 *         @OA\Property(property="planQty", type="number", format="float", example=100.5),
 *         @OA\Property(property="status", type="string", enum={"Planned", "In Progress", "Completed", "Cancelled"}, example="Planned"),
 *         @OA\Property(property="item", ref="#/components/schemas/Item")
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="StoreWorkOrderRequest",
 *     type="object",
 *     title="Store Work Order Request",
 *     required={"wo_no", "itm_cd", "ord_dt", "plan_dt", "plan_qty"},
 *     properties={
 *         @OA\Property(property="wo_no", type="string", maxLength=20, example="WO-2025-002"),
 *         @OA\Property(property="itm_cd", type="string", maxLength=20, example="FG-001"),
 *         @OA\Property(property="ord_dt", type="string", format="date", example="2025-10-28"),
 *         @OA\Property(property="plan_dt", type="string", format="date", example="2025-10-29"),
 *         @OA\Property(property="plan_qty", type="number", format="float", example=250),
 *         @OA\Property(property="stats", type="string", enum={"Planned", "In Progress", "Completed", "Cancelled"}, example="Planned"),
 *         @OA\Property(property="rmks", type="string", nullable=true, example="Urgent order")
 *     }
 * )
 */
class WorkOrderController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/work-orders",
     *      operationId="getWorkOrdersList",
     *      tags={"Transactions - Work Orders"},
     *      summary="Get list of work orders",
     *      description="Returns a paginated list of work orders. Can be filtered by status.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="status",
     *          in="query",
     *          description="Filter by active status ('Planned', 'In Progress').",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              enum={"active"}
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/WorkOrder")),
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
    public function index(Request $request)
    {
        $query = WorkOrder::with('item');

        if ($request->query('status') === 'active') {
            $query->whereIn('stats', ['Planned', 'In Progress']);
        }

        return WorkOrderResource::collection($query->paginate());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/work-orders",
     *      operationId="storeWorkOrder",
     *      tags={"Transactions - Work Orders"},
     *      summary="Create a new work order",
     *      description="Stores a new work order and returns the created work order data.",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Work order data",
     *          @OA\JsonContent(ref="#/components/schemas/StoreWorkOrderRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Work order created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/WorkOrder")
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
    public function store(StoreWorkOrderRequest $request)
    {
        $workOrder = WorkOrder::create($request->validated());
        return new WorkOrderResource($workOrder->load('item'));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/work-orders/{id}",
     *      operationId="getWorkOrderById",
     *      tags={"Transactions - Work Orders"},
     *      summary="Get work order information",
     *      description="Returns work order data by ID.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Work Order ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/WorkOrder")
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
    public function show(WorkOrder $workOrder)
    {
        return new WorkOrderResource($workOrder->load('item'));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/work-orders/{id}",
     *      operationId="updateWorkOrder",
     *      tags={"Transactions - Work Orders"},
     *      summary="Update existing work order",
     *      description="Updates an existing work order and returns the updated work order data.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Work Order ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Work order data",
     *          @OA\JsonContent(ref="#/components/schemas/StoreWorkOrderRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Work order updated successfully",
     *          @OA\JsonContent(ref="#/components/schemas/WorkOrder")
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
    public function update(UpdateWorkOrderRequest $request, WorkOrder $workOrder)
    {
        $workOrder->update($request->validated());
        return new WorkOrderResource($workOrder->load('item'));
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/work-orders/{id}",
     *      operationId="deleteWorkOrder",
     *      tags={"Transactions - Work Orders"},
     *      summary="Delete existing work order",
     *      description="Deletes a work order record and returns no content.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Work Order ID",
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
    public function destroy(WorkOrder $workOrder)
    {
        $workOrder->delete();
        return response()->noContent();
    }

    /**
     * @OA\Get(
     *      path="/api/v1/work-orders/{work_order}/valid-processes",
     *      operationId="getValidProcessesForWo",
     *      tags={"Transactions - Work Orders"},
     *      summary="Get valid processes for a Work Order",
     *      description="Helper endpoint to get a list of valid processes for a given Work Order, based on its item type.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="work_order",
     *          in="path",
     *          required=true,
     *          description="The `wo_no` of the work order.",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="A list of valid processes.",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Process"))
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Work Order not found."
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      )
     * )
     */
    public function getValidProcesses(WorkOrder $work_order)
    {
        $processes = Process::getValidProcesses($work_order->itm_tp);
        return ProcessResource::collection($processes);
    }
}
