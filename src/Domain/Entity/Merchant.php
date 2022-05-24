<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use DateTime;
use PayByBank\Domain\ValueObjects\MerchantState;

class Merchant
{
    private readonly int $id;

    private readonly string $username;

    private readonly string $password;

    private DateTime $dateCreated;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->dateCreated = new DateTime('now');
    }

    public static function fromState(MerchantState $merchantState): Merchant
    {
        $self = new self(
            $merchantState->username,
            $merchantState->password
        );
        $self->dateCreated = $merchantState->dateCreated;

        return $self;
    }

    public function getState(): MerchantState
    {
        return new MerchantState(
            $this->username,
            $this->password,
            $this->dateCreated
        );
    }
}
