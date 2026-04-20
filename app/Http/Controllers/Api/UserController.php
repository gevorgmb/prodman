<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\CurrentUserResponse;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        /** @var User|null $authUser */
        $authUser = $request->user();
        if ($authUser === null) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $user = $this->userRepository->getUserById($authUser->id);

        if ($user === null) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }

        return (new CurrentUserResponse($user))->toResponse($request);
    }
}
