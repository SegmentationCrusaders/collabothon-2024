<?php

namespace App\Helpers\Enums;

enum CalendarActionTagEnum: string
{
    case CASH_FLOW = 'Cash flow';

    case SHORT_TERM_LOAN = 'Short-term loan';

    case LONG_TERM_LOAN = 'Long-term loan';

    case PAYMENT = 'Payment';

    case TECHNICAL = 'Technical';

    case OTHER = 'Other';
}
