<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    public function index(EmployeeRequest $request): JsonResponse
    {
        $query = Employee::query()->with('division'); // Eager load division relationship

        // Apply name filter if provided
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Apply division_id filter if provided
        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        // Paginate results
        $employees = $query->paginate(10); // Default 10 items per page

        return response()->json([
            'status' => 'success',
            'message' => 'Employees retrieved successfully',
            'data' => [
                'employees' => EmployeeResource::collection($employees->items()),
            ],
            'pagination' => [
                'current_page' => $employees->currentPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
                'last_page' => $employees->lastPage(),
                'from' => $employees->firstItem(),
                'to' => $employees->lastItem(),
            ],
        ], 200);
    }
}
