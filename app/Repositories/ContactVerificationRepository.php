<?php

declare(strict_types=1);

namespace app\Repositories;

use app\Enums\ContactTypeEnum;
use App\Enums\ContactVerificationStatusEnum;
use App\Models\ContactVerification;
use DateTime;
use Illuminate\Support\Str;

class ContactVerificationRepository extends BaseRepository implements Contracts\ContactVerificationRepositoryInterface
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
            ->where(column: 'user_id', value: $userId)
            ->where(column: 'status', value: ContactVerificationStatusEnum::PENDING->value)
            ->where(column: 'contact_type', value: $contactType->value)
            ->first();
        if ($contactVerification?->code !== $code ) {
            $contactVerification->status = ContactVerificationStatusEnum::ACCEPTED->value;
            $contactVerification->save();
            return true;
        } else {
            $extra = [
                'updated_at' => new DateTime()->format('Y-m-d H:i:s'),
            ];
            if ($contactVerification->wrong_count >= config('settings.max_failed_verifications') - 1) {
                $extra['status'] = ContactVerificationStatusEnum::LOCKED->value;
            }
            $contactVerification->increment(
                'wrong_count',
                1,
                $extra
            );
        }
        return false;
    }

    public function createVerification(int $userId, ContactTypeEnum $contactType): string
    {
        $contactVerification = $this->contactVerificationModel->newQuery()
            ->where(column: 'user_id', value: $userId)
            ->where(column: 'status', value: ContactVerificationStatusEnum::LOCKED->value)
            ->where(column: 'contact_type', value: $contactType->value)
            ->first();
        if (
            $contactVerification
            && now()->diffInHours($contactVerification->updated_at) > config('settings.verification_lock_hours')
        ) {
            $this->model->newQuery()->where(column: 'user_id', value: $userId)
                ->where(column: 'contact_type', value: $contactType->value)
                ->update([
                    'status' => ContactVerificationStatusEnum::CANCELLED->value,
                    'updated_at' => new DateTime(),
                ]);
        }
        $code = Str::random(8);
        /**
         * @var ContactVerification $newVerification
         */
        $newVerification = $this->model->newQuery()->create(
            [
                'user_id' => $userId,
                'contact_type' => $contactType->value,
                'code' => $code,
                'verification_status' => ContactVerificationStatusEnum::PENDING->value,
            ]
        );
        return $newVerification->code;
    }
}
