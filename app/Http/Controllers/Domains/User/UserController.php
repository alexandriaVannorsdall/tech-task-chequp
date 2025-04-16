<?php

namespace App\Http\Controllers\Domains\User;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        // Retrieve all users; consider pagination for large datasets
        $users = User::all();

        // Return the list of users
        return response()->json(['users' => $users], 200);
    }
    
    public function store(Request $request): JsonResponse
    {
        // Validate the request
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric',
            'country' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female',
            'password' => 'required|string|min:8|confirmed', 
        ]);

        // Create the user
        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'country' => $validatedData['country'],
            'gender' => $validatedData['gender'],
            'password' => Hash::make($validatedData['password']), 
        ]);

        // Return a success response
        return response()->json(['message' => 'User created successfully.', 'user' => $user], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Validate the request
        $validatedData = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => "sometimes|required|email|unique:users,email,{$id}",
            'phone' => 'sometimes|required|numeric',
            'country' => 'sometimes|required|string|max:255',
            'gender' => 'sometimes|required|string|in:male,female',
            'password' => 'sometimes|required|string|min:8|confirmed',
        ]);

        // Update user details
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }
    
        $user->update($validatedData);

        // Return a success response
        return response()->json(['message' => 'User updated successfully.', 'user' => $user], 200);
    }
    
    public function show($id): JsonResponse
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Return the user data
        return response()->json(['user' => $user], 200);
    }

    public function destroy($id): JsonResponse
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Delete the user
        $user->delete();

        // Return a success response
        return response()->json(['message' => 'User deleted successfully.'], 200);
    }

}