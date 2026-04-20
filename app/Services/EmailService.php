<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\VerifyEmailMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendVerificationEmail(User $user, string $verificationCode): void
    {
        Mail::to($user->email)->send(new VerifyEmailMail($verificationCode, $user->name));
    }
}
