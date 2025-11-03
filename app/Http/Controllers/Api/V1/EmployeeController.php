<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Http\Resources\V1\EmployeeResource;
use App\Http\Requests\Api\V1\StoreEmployeeRequest;
use App\Http\Requests\Api\V1\UpdateEmployeeRequest;

/**
 * @OA\Schema(
 *     schema="Employee",
 *     type="object",
 *     title="Employee",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="employeeId", type="string", example="EMP-001"),
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *         @OA\Property(property="gender", type="string", enum={"L", "P"}, example="L"),
 *         @OA\Property(property="address", type="string", nullable=true, example="123 Main St"),
 *         @OA\Property(property="phone", type="string", nullable=true, example="08123456789"),
 *         @OA\Property(property="email", type="string", format="email", nullable=true, example="john.doe@example.com"),
 *         @OA\Property(property="hireDate", type="string", format="date", example="2023-01-15"),
 *         @OA\Property(property="department", ref="#/components/schemas/Department"),
 *         @OA\Property(property="shift", ref="#/components/schemas/Shift")
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="StoreEmployeeRequest",
 *     type="object",
 *     title="Store Employee Request",
 *     required={"emp_id", "emp_nm", "dept_cd", "shift_cd", "gender", "hire_dt"},
 *     properties={
 *         @OA\Property(property="emp_id", type="string", maxLength=20, example="EMP-002"),
 *         @OA\Property(property="emp_nm", type="string", maxLength=100, example="Jane Smith"),
 *         @OA\Property(property="dept_cd", type="string", maxLength=20, example="PROD"),
 *         @OA\Property(property="shift_cd", type="string", maxLength=20, example="SHIFT-1"),
 *         @OA\Property(property="gender", type="string", enum={"L", "P"}, example="P"),
 *         @OA\Property(property="addr", type="string", nullable=true, example="456 Oak Ave"),
 *         @OA\Property(property="phone", type="string", maxLength=20, nullable=true, example="08987654321"),
 *         @OA\Property(property="email", type="string", format="email", maxLength=100, nullable=true, example="jane.smith@example.com"),
 *         @OA\Property(property="hire_dt", type="string", format="date", example="2024-02-20")
 *     }
 * )
 */
class EmployeeController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/employees",
     *      operationId="getEmployeesList",
     *      tags={"Master Data - Employees"},
     *      summary="Get list of employees",
     *      description="Returns a paginated list of employees.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Employee")),
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
        return EmployeeResource::collection(Employee::with(['department', 'shift'])->paginate());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/employees",
     *      operationId="storeEmployee",
     *      tags={"Master Data - Employees"},
     *      summary="Create a new employee",
     *      description="Stores a new employee and returns the created employee data.",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Employee data",
     *          @OA\JsonContent(ref="#/components/schemas/StoreEmployeeRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Employee created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Employee")
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
    public function store(StoreEmployeeRequest $request)
    {
        $employee = Employee::create($request->validated());
        return new EmployeeResource($employee->load(['department', 'shift']));
    }

    /**
          * @OA\Get(
     *      path="/api/v1/employees/{employee}",
     *      operationId="getEmployeeById",
     *      tags={"Master Data - Employees"},
     *      summary="Get employee information",
     *      description="Returns employee data by ID.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Employee ID (the auto-incrementing one, not emp_id string)",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Employee")
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
    public function show(Employee $employee)
    {
        return new EmployeeResource($employee->load(['department', 'shift']));
    }

    /**
          * @OA\Put(
     *      path="/api/v1/employees/{employee}",
     *      operationId="updateEmployee",
     *      tags={"Master Data - Employees"},
     *      summary="Update existing employee",
     *      description="Updates an existing employee and returns the updated employee data.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Employee ID (the auto-incrementing one, not emp_id string)",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Employee data",
     *          @OA\JsonContent(ref="#/components/schemas/StoreEmployeeRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Employee updated successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Employee")
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
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->validated());
        return new EmployeeResource($employee->load(['department', 'shift']));
    }

    /**
     * @OA\Delete(
     *      path="/employees/{id}",
     *      operationId="deleteEmployee",
     *      tags={"Master Data - Employees"},
     *      summary="Delete existing employee",
     *      description="Deletes an employee record and returns no content.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Employee ID (the auto-incrementing one, not emp_id string)",
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
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->noContent();
    }
}
