<?php

namespace App\Enums;

enum ActionStatusEnum: string
{
    case SUCCESS = 'success';
    case CREATED = 'created';
    case FAILED = 'failed';
}
