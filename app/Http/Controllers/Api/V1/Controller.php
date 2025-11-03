<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="MAIS Wellbest API Documentation",
 *      description="Standard OpenAPI documentation for the MAIS Wellbest application.",
 *      @OA\Contact(
 *          email="admin@rexonindo.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url="http://127.0.0.1:8000/api/v1",
 *      description="MAIS Wellbest API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter token in format (Bearer <token>)"
 * )
 *
 * @OA\Tag(
 *     name="Authentication",
 *     description="API Endpoints for user authentication"
 * )
 * @OA\Tag(
 *     name="Master Data - Departments",
 *     description="API Endpoints for Departments"
 * )
 * @OA\Tag(
 *     name="Master Data - Shifts",
 *     description="API Endpoints for Shifts"
 * )
 * @OA\Tag(
 *     name="Master Data - Machines",
 *     description="API Endpoints for Machines"
 * )
 * @OA\Tag(
 *     name="Master Data - Employees",
 *     description="API Endpoints for Employees"
 * )
 * @OA\Tag(
 *     name="Master Data - Items",
 *     description="API Endpoints for Items"
 * )
 * @OA\Tag(
 *     name="Master Data - Processes",
 *     description="API Endpoints for Processes"
 * )
 * @OA\Tag(
 *     name="Master Data - Product Routes",
 *     description="API Endpoints for Product Routes"
 * )
 * @OA\Tag(
 *     name="Transactions - Work Orders",
 *     description="API Endpoints for Work Orders"
 * )
 * @OA\Tag(
 *     name="Transactions - Production Logs",
 *     description="API Endpoints for Production Logs"
 * )
 *
 * @OA\Schema(
 *     schema="PaginationLinks",
 *     type="object",
 *     title="Pagination Links",
 *     properties={
 *         @OA\Property(property="first", type="string", example="http://localhost/api/v1/departments?page=1"),
 *         @OA\Property(property="last", type="string", example="http://localhost/api/v1/departments?page=5"),
 *         @OA\Property(property="prev", type="string", nullable=true, example=null),
 *         @OA\Property(property="next", type="string", nullable=true, example="http://localhost/api/v1/departments?page=2")
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="PaginationMeta",
 *     type="object",
 *     title="Pagination Meta",
 *     properties={
 *         @OA\Property(property="current_page", type="integer", example=1),
 *         @OA\Property(property="from", type="integer", example=1),
 *         @OA\Property(property="last_page", type="integer", example=5),
 *         @OA\Property(property="path", type="string", example="http://localhost/api/v1/departments"),
 *         @OA\Property(property="per_page", type="integer", example=15),
 *         @OA\Property(property="to", type="integer", example=15),
 *         @OA\Property(property="total", type="integer", example=75)
 *     }
 * )
 *
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
