<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use DateTime;
use PayByBank\Domain\ValueObjects\MerchantState;

class Merchant
{
    private readonly int $id;

    private readonly string $mid;

    private readonly string $firstName;

    private readonly string $lastName;

    private DateTime $dateCreated;

    public function __construct(string $mid, string $firstName, string $lastName)
    {
        $this->mid = $mid;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->dateCreated = new DateTime('now');
    }

    public static function fromState(MerchantState $merchantState): Merchant
    {
        $self = new self(
            $merchantState->mid,
            $merchantState->firstName,
            $merchantState->lastName
        );
        $self->dateCreated = $merchantState->dateCreated;

        return $self;
    }

    public function getState(): MerchantState
    {
        return new MerchantState(
            $this->mid,
            $this->firstName,
            $this->lastName,
            $this->dateCreated
        );
    }
}
