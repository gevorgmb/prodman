<?php

declare(strict_types=1);

namespace app\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ContactVerificationController extends Controller
{
    public function __construct(

    )
    {
    }

    public function sendVerificationEmail() {
        $user = auth()->user();

    }
}
