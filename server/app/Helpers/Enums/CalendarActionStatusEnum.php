<?php

namespace App\Helpers\Enums;

enum CalendarActionStatusEnum: int
{
    case CREATED = 1;

    case AWAITING = 2; // awaiting meeting

    case COMPLETED = 3;

    case CANCELLED = 4;
}
