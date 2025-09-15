<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Throwable;

class UserController extends Controller
{
    /**
     *
     * @param UserRepository
     */
    public function __construct(protected UserRepository $userRepository) {}


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('perPage', 15);
        $users = $this->userRepository->paginate($perPage);

        return response()->json([
            'data' => $users,
            'message' => 'User Retrieved successfully'
        ]);
    }

    public function restore(int $id): JsonResponse
    {
        $user = User::withTrashed()->findOrFail($id);
        $this->userRepository->restore($user);

        return response()->json([
            'data' => $user->fresh(),
            'message' => 'User Restored Successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userRepository->create($request->validated());
        return response()->json([
            'data' => $user,
            'message' => 'User created Successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'data' => $user,
            'message' => 'User Retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $this->userRepository->update($user, $request->validate());

        return response()->json([
            'data' => $user->fresh(),
            'message' => 'User Updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function forceDelete(int $id): JsonResponse
    {
        $user = User::withTrashed()->findOrFail($id);
        $this->userRepository->forceDelete($user);

        return response()->json([
            'data' => $user->fresh(),
            'message' => 'User deleted Successfully'
        ]);
    }
}
