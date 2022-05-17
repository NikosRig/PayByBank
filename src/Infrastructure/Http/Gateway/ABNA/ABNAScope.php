<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\ABNA;

enum ABNAScope: string
{
    case SEPA_PAYMENT = 'psd2:payment:sepa:write';
}
