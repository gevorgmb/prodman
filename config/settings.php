<?php

return [
    'trusted_client_ip_list' => explode(',', env('TRUSTED_CLIENT_IP_LIST', '')),
    'max_failed_verifications' => (int)env('MAX_FAILED_VERIFICATIONS', 1),
    'verification_code_life_hours' => (int)env('VERIFICATION_CODE_LIFE_HOURS', 1),
    'verification_lock_hours' => (int)env('VERIFICATION_LOCK_HOURS', 1),
];
