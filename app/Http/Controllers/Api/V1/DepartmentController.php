<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Http\Resources\V1\DepartmentResource;
use App\Http\Requests\Api\V1\StoreDepartmentRequest;
use App\Http\Requests\Api\V1\UpdateDepartmentRequest;

/**
 * @OA\Schema(
 *     schema="Department",
 *     type="object",
 *     title="Department",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="departmentCode", type="string", example="PROD"),
 *         @OA\Property(property="name", type="string", example="Production"),
 *         @OA\Property(property="description", type="string", nullable=true, example="Main production department")
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="DepartmentStoreRequest",
 *     type="object",
 *     title="Department Store Request",
 *     required={"dept_cd", "dept_nm"},
 *     properties={
 *         @OA\Property(property="dept_cd", type="string", maxLength=20, example="QC"),
 *         @OA\Property(property="dept_nm", type="string", maxLength=100, example="Quality Control"),
 *         @OA\Property(property="descrp", type="string", nullable=true, example="Department for quality checks")
 *     }
 * )
 */
class DepartmentController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/departments",
     *      operationId="getDepartmentsList",
     *      tags={"Master Data - Departments"},
     *      summary="Get list of departments",
     *      description="Returns a paginated list of departments.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Department")),
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
        return DepartmentResource::collection(Department::paginate());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/departments",
     *      operationId="storeDepartment",
     *      tags={"Master Data - Departments"},
     *      summary="Create a new department",
     *      description="Stores a new department and returns the created department data.",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Department data",
     *          @OA\JsonContent(ref="#/components/schemas/DepartmentStoreRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Department created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Department")
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
    public function store(StoreDepartmentRequest $request)
    {
        $department = Department::create($request->validated());
        return new DepartmentResource($department);
    }

    /**
          * @OA\Get(
     *      path="/api/v1/departments/{department}",
     *      operationId="getDepartmentById",
     *      tags={"Master Data - Departments"},
     *      summary="Get department information",
     *      description="Returns department data by ID.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Department ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Department")
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
    public function show(Department $department)
    {
        return new DepartmentResource($department);
    }

    /**
          * @OA\Put(
     *      path="/api/v1/departments/{department}",
     *      operationId="updateDepartment",
     *      tags={"Master Data - Departments"},
     *      summary="Update existing department",
     *      description="Updates an existing department and returns the updated department data.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Department ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Department data",
     *          @OA\JsonContent(ref="#/components/schemas/DepartmentStoreRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Department updated successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Department")
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
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $department->update($request->validated());
        return new DepartmentResource($department);
    }

    /**
     * @OA\Delete(
     *      path="/departments/{id}",
     *      operationId="deleteDepartment",
     *      tags={"Master Data - Departments"},
     *      summary="Delete existing department",
     *      description="Deletes a department record and returns no content.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Department ID",
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
    public function destroy(Department $department)
    {
        $department->delete();
        return response()->noContent();
    }
}
