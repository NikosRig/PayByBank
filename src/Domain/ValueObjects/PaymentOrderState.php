<?php

declare(strict_types=1);

namespace PayByBank\Domain\ValueObjects;

enum PaymentOrderState
{
  case PENDING_CONSENT;
}
