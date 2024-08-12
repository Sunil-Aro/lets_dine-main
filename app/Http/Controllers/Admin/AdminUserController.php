<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\Middleware;
class AdminUserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:user_show',only: ['index']),
            new Middleware('permission:user_edit',only: ['edit']),
            new Middleware('permission:user_create',only: ['create']),
            new Middleware('permission:user_delete',only: ['destroy']),
        ];
    }

    public function test(){
        dd("Hii");
    }


    public function index()
    {
        $users = User::all();
        $admins = Admin::all();
        return view('user.list',compact('users', 'admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('user.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email|unique:admins,email',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        if ($request->role === 'Super Admin') {
            $user = new Admin();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
            $user->syncRoles($request->role);
        } else {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
            $user->syncRoles($request->role);
        }

        return response()->json(['message' => 'User Created successfully!'], 201);
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
        $roles = Role::all();
        $user = User::find($id);
        return view('user.edit',compact('roles','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $request->id . '|unique:admins,email,' . $request->id,
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        // Check if the user is currently in the users table or admins table
        $user = User::find($request->email);
        if ($user) {
            $currentTable = 'users';
        } else {
            $user = Admin::find($request->email);
            $currentTable = 'admins';
        }

        if (!$user) {
            return response()->json(['errors' => ['User not found']], 404);
        }

        if ($request->role === 'Super Admin' && $currentTable === 'users') {
            $admin = new Admin();
            $admin->name = $user->name;
            $admin->email = $user->email;
            $admin->password = $user->password;
            $admin->save();
            $admin->syncRoles($request->role);
            $user->delete();
        } elseif ($request->role !== 'Super Admin' && $currentTable === 'admins') {
            $newUser = new User();
            $newUser->name = $user->name;
            $newUser->email = $user->email;
            $newUser->password = $user->password; // Assuming password is already hashed
            $newUser->save();
            $newUser->syncRoles($request->role);

            $user->delete();
        } else {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->update();
            $user->syncRoles($request->role);
        }

        return response()->json(['message' => 'User Updated successfully!'], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
