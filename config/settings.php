<?php

return [
    'trusted_client_ip_list' => explode(',', env('TRUSTED_CLIENT_IP_LIST', '')),
];
