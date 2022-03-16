<?php

declare(strict_types=1);

namespace PayByBank\Domain\ValueObjects;

enum PaymentOrderStatus: int
{
  case PENDING_CONSENT = 1;
}
