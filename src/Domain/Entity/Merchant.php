<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use DateTime;
use PayByBank\Domain\ValueObjects\MerchantState;

class Merchant
{
    private readonly int $id;

    private readonly string $mid;

    private DateTime $dateCreated;

    private readonly string $merchantName;

    public function __construct(string $mid, string $merchantName)
    {
        $this->mid = $mid;
        $this->merchantName = $merchantName;
        $this->dateCreated = new DateTime('now');
    }

    public static function fromState(MerchantState $merchantState): Merchant
    {
        $self = new self($merchantState->mid, $merchantState->merchantName);
        $self->dateCreated = $merchantState->dateCreated;

        return $self;
    }

    public function getState(): MerchantState
    {
        return new MerchantState(
            $this->mid,
            $this->merchantName,
            $this->dateCreated
        );
    }
}
