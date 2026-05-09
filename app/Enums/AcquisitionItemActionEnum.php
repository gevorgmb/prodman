<?php

namespace App\Enums;

enum AcquisitionItemActionEnum: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
}
