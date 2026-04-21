<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\ContactTypeEnum;
use App\Enums\ContactVerificationStatusEnum;
use App\Models\ContactVerification;
use App\Repositories\Contracts\ContactVerificationRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ContactVerificationRepository extends BaseRepository implements ContactVerificationRepositoryInterface
{
    public function __construct(
        private readonly ContactVerification $contactVerificationModel,
    )
    {
        parent::__construct($contactVerificationModel);
    }

    public function verifyCode(int $userId, string $code, ContactTypeEnum $contactType): bool
    {
        $contactVerification = $this->contactVerificationModel->newQuery()
            ->where('user_id', $userId)
            ->where('contact_type', $contactType->value)
            ->where('status', ContactVerificationStatusEnum::PENDING->value)
            ->orderByDesc('id')
            ->first();

        if ($contactVerification === null) {
            return false;
        }

        $expiresAt = $contactVerification->created_at->copy()->addHours(
            (int) config('settings.verification_code_life_hours')
        );
        if ($expiresAt->isPast()) {
            $contactVerification->status = ContactVerificationStatusEnum::CANCELLED->value;
            $contactVerification->save();

            return false;
        }

        if (! hash_equals($contactVerification->code, $code)) {
            $extra = [];
            if ($contactVerification->wrong_count >= (int) config('settings.max_failed_verifications') - 1) {
                $extra['status'] = ContactVerificationStatusEnum::LOCKED->value;
            }
            $contactVerification->increment('wrong_count', 1, $extra);

            return false;
        }

        $contactVerification->status = ContactVerificationStatusEnum::ACCEPTED->value;
        $contactVerification->save();

        return true;
    }

    public function createVerification(int $userId, ContactTypeEnum $contactType): string
    {
        $lockedVerification = $this->contactVerificationModel->newQuery()
            ->where('user_id', $userId)
            ->where('contact_type', $contactType->value)
            ->where('status', ContactVerificationStatusEnum::LOCKED->value)
            ->orderByDesc('id')
            ->first();

        if (
            $lockedVerification !== null
            && now()->diffInHours($lockedVerification->updated_at) < (int) config('settings.verification_lock_hours')
        ) {
            throw ValidationException::withMessages([
                'email' => ['Verification is temporarily locked after too many failed attempts. Please try again later.'],
            ]);
        }

        $this->model->newQuery()
            ->where('user_id', $userId)
            ->where('contact_type', $contactType->value)
            ->whereIn(
                'status',
                [
                    ContactVerificationStatusEnum::PENDING->value,
                    ContactVerificationStatusEnum::LOCKED->value,
                ],
            )
            ->update([
                'status' => ContactVerificationStatusEnum::CANCELLED->value,
                'updated_at' => now(),
            ]);

        $code = Str::random(8);

        /** @var ContactVerification $newVerification */
        $newVerification = $this->model->newQuery()->create([
            'user_id' => $userId,
            'contact_type' => $contactType->value,
            'code' => $code,
            'status' => ContactVerificationStatusEnum::PENDING->value,
        ]);

        return $newVerification->code;
    }
}
