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
        $response = ['status' => 'error', 'message' => ''];
        $statusCode = 500;

        try {
            $employee = Employee::where('id', $uuid)->firstOrFail();

            $data = $request->validated();

            $imagePath = $employee->image;
            if ($request->hasFile('image')) {
                if ($employee->image) {
                    Storage::disk('public')->delete($employee->image);
                }
                $imagePath = $request->file('image')->store('employees', 'public');
            } elseif ($request->has('image') && $request->input('image') === 'null') {
                if ($employee->image) {
                    Storage::disk('public')->delete($employee->image);
                }
                $imagePath = null;
            }

            $employee->update([
                'image' => $imagePath,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'division_id' => $data['division'],
                'position' => $data['position'],
            ]);

            $response = ['status' => 'success', 'message' => 'Employee updated successfully'];
            $statusCode = 200;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response['message'] = 'Failed to update employee: Employee not found.';
            $statusCode = 404;
        } catch (QueryException $e) {
            $response['message'] = 'Failed to update employee: Database error occurred.';
        } catch (\Exception $e) {
            $response['message'] = 'Failed to update employee: An unexpected error occurred.';
        }

        return response()->json($response, $statusCode);
    }

    public function destroy(string $uuid): JsonResponse
    {
        $response = ['status' => 'error', 'message' => ''];
        $statusCode = 500;

        try {
            $employee = Employee::where('id', $uuid)->firstOrFail();

            if ($employee->image) {
                Storage::disk('public')->delete($employee->image);
            }

            $employee->delete();

            $response = ['status' => 'success', 'message' => 'Employee deleted successfully'];
            $statusCode = 200;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response['message'] = 'Failed to delete employee: Employee not found.';
            $statusCode = 404;
        } catch (QueryException $e) {
            $response['message'] = 'Failed to delete employee: Database error occurred.';
        } catch (\Exception $e) {
            $response['message'] = 'Failed to delete employee: An unexpected error occurred.';
        }

        return response()->json($response, $statusCode);
    }
}
