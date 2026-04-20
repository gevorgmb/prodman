<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendVerificationEmailRequest;
use App\Http\Requests\VerifyEmailCodeRequest;
use App\Services\ContactVerificationService;
use Illuminate\Http\JsonResponse;

class ContactVerificationController extends Controller
{
    public function __construct(
        private readonly ContactVerificationService $contactVerificationService,
    ) {
    }

    public function sendVerificationEmail(SendVerificationEmailRequest $request): JsonResponse
    {
        $user = $request->user();
        $this->contactVerificationService->sendEmailVerificationCode($user);

        return response()->json([
            'message' => 'Verification code sent to your email address.',
        ]);
    }

    public function verifyEmailCode(VerifyEmailCodeRequest $request): JsonResponse
    {
        $user = $request->user();
        $code = $request->validated('code');

        if (! $this->contactVerificationService->verifyEmailCode($user, $code)) {
            return response()->json([
                'message' => 'Invalid or expired verification code.',
            ], 422);
        }

        return response()->json([
            'message' => 'Email address verified successfully.',
            'user' => $user->fresh(),
        ]);
    }
}
