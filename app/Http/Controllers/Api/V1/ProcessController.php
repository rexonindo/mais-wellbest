<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Process;
use App\Http\Resources\V1\ProcessResource;
use App\Http\Requests\Api\V1\StoreProcessRequest;
use App\Http\Requests\Api\V1\UpdateProcessRequest;

/**
 * @OA\Schema(
 *     schema="Process",
 *     type="object",
 *     title="Process",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="processCode", type="string", example="PROC-01"),
 *         @OA\Property(property="name", type="string", example="Cutting"),
 *         @OA\Property(property="description", type="string", nullable=true, example="Process of cutting raw materials"),
 *         @OA\Property(property="department", ref="#/components/schemas/Department")
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="StoreProcessRequest",
 *     type="object",
 *     title="Store Process Request",
 *     required={"proc_cd", "proc_nm", "dept_cd"},
 *     properties={
 *         @OA\Property(property="proc_cd", type="string", maxLength=20, example="PROC-02"),
 *         @OA\Property(property="proc_nm", type="string", maxLength=100, example="Assembling"),
 *         @OA\Property(property="descrp", type="string", nullable=true, example="Process of assembling parts"),
 *         @OA\Property(property="dept_cd", type="string", maxLength=20, example="PROD")
 *     }
 * )
 */
class ProcessController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/processes",
     *      operationId="getProcessesList",
     *      tags={"Master Data - Processes"},
     *      summary="Get list of processes",
     *      description="Returns a paginated list of processes.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Process")),
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
        return ProcessResource::collection(Process::with('department')->paginate());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/processes",
     *      operationId="storeProcess",
     *      tags={"Master Data - Processes"},
     *      summary="Create a new process",
     *      description="Stores a new process and returns the created process data.",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Process data",
     *          @OA\JsonContent(ref="#/components/schemas/StoreProcessRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Process created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Process")
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
    public function store(StoreProcessRequest $request)
    {
        $process = Process::create($request->validated());
        return new ProcessResource($process->load('department'));
    }

    /**
          * @OA\Get(
     *      path="/api/v1/processes/{process}",
     *      operationId="getProcessById",
     *      tags={"Master Data - Processes"},
     *      summary="Get process information",
     *      description="Returns process data by ID.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Process ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Process")
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
    public function show(Process $process)
    {
        return new ProcessResource($process->load('department'));
    }

    /**
          * @OA\Put(
     *      path="/api/v1/processes/{process}",
     *      operationId="updateProcess",
     *      tags={"Master Data - Processes"},
     *      summary="Update existing process",
     *      description="Updates an existing process and returns the updated process data.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Process ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Process data",
     *          @OA\JsonContent(ref="#/components/schemas/StoreProcessRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Process updated successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Process")
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
    public function update(UpdateProcessRequest $request, Process $process)
    {
        $process->update($request->validated());
        return new ProcessResource($process->load('department'));
    }

    /**
     * @OA\Delete(
     *      path="/processes/{id}",
     *      operationId="deleteProcess",
     *      tags={"Master Data - Processes"},
     *      summary="Delete existing process",
     *      description="Deletes a process record and returns no content.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Process ID",
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
    public function destroy(Process $process)
    {
        $process->delete();
        return response()->noContent();
    }
}
