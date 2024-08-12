<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class PermissionController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */

    public static function middleware(): array
    {
        return [
            new Middleware('permission:permission_show',only: ['index']),
            new Middleware('permission:permission_edit',only: ['edit']),
            new Middleware('permission:permission_create',only: ['create']),
            new Middleware('permission:permission_delete',only: ['destroy']),
        ];
    }

    public function index()
    {

        $permissions = Permission::orderBy('name', 'ASC')->get();
        return view('permission.list',compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'permission' => 'required|string',
                'guard_name' => [
                    'required',
                    'string',
                    Rule::in(['web', 'admin']),
                    Rule::unique('permissions')->where(function ($query) use ($request) {
                        return $query->where('name', $request->permission);
                    }),
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->messages()], 422);
            }

            $permission = new Permission();
            $permission->name = $request->permission;
            $permission->guard_name = $request->guard_name;
            $permission->save();

            return response()->json(['message' => 'Permission added successfully!'], 201);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permission = Permission::findById($id);
        return view('permission.edit',compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:permissions,id',
                'permission' => 'required|unique:permissions,name,' . $request->id,
                'guard_name' => 'required|string|in:web,admin', // Add validation for guard_name
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->messages()], 422);
            }

            $permission = Permission::findById($request->id);
            if (!$permission) {
                return response()->json(['error' => 'Permission not found.'], 404);
            }

            $permission->name = $request->permission;
            $permission->guard_name = $request->guard_name;
            $permission->update();

            return response()->json(['message' => 'Permission updated successfully!'], 200);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::findById($id);
        $permission->delete();
        return response()->json(['message' => 'Permission Deleted successfully!'], 201);
    }
}
