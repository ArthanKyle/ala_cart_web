<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Storage;
use App\Http\Requests\UpdateProfilePictureRequest;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
        
    }

    /**
     * Display a listing of the users.
     */
    public function index(): JsonResponse
    {
        $users = $this->userService->getAllUsers();
        return response()->json($users);
    }

    /**
     * Store a newly created user.
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated());
        return response()->json($user, 201);
    }

    /**
     * Display the specified user.
     */
    public function show(Request $request)
    {
        return response()->json(Auth::user());
    }
    
    /**
     * Update the specified user.
     */

     public function update(UserUpdateRequest $request, string $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Only update fields that are provided
            $user->update($request->only([
                'first_name', 'middle_name', 'last_name',
                'email', 'phone_number', 'address'
            ]));

            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => $user
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update user',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Remove the specified user.
     */
    public function destroy(string $id): JsonResponse
    {
        $this->userService->deleteUser($id);
        return response()->json(['message' => 'User deleted successfully']);
    }
}
