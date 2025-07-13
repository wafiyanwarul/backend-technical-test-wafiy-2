<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmployeeRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;

class EmployeeController extends Controller
{
    public function index(EmployeeRequest $request): JsonResponse
    {
        try {
            $query = Employee::query()->with('division');

            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->filled('division_id')) {
                $query->where('division_id', $request->division_id);
            }

            $employees = $query->paginate(10);

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
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve employees: Database error occurred.',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve employees: An unexpected error occurred.',
            ], 500);
        }
    }

    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('employees', 'public');
            }

            $employee = Employee::create([
                'id' => \Illuminate\Support\Str::uuid(),
                'image' => $imagePath,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'division_id' => $data['division'],
                'position' => $data['position'],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Employee created successfully',
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create employee: Database error occurred.',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create employee: An unexpected error occurred.',
            ], 500);
        }
    }

    public function update(UpdateEmployeeRequest $request, string $uuid): JsonResponse
    {
        try {
            $employee = Employee::where('id', $uuid)->firstOrFail();

            $data = $request->validated();

            // Handle image update or removal
            $imagePath = $employee->image;
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($employee->image) {
                    Storage::disk('public')->delete($employee->image);
                }
                $imagePath = $request->file('image')->store('employees', 'public');
            } elseif ($request->has('image') && is_null($request->image)) {
                // Explicitly remove image if image field is set to null
                if ($employee->image) {
                    Storage::disk('public')->delete($employee->image);
                }
                $imagePath = null;
            }

            // Update employee
            $employee->update([
                'image' => $imagePath,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'division_id' => $data['division'],
                'position' => $data['position'],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Employee updated successfully',
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update employee: Database error occurred.',
            ], 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update employee: Employee not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update employee: An unexpected error occurred.',
            ], 500);
        }
    }
}
