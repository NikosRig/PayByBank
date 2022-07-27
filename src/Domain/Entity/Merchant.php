<?php

declare(strict_types=1);

namespace PayByBank\Domain\Entity;

use DateTime;
use PayByBank\Domain\ValueObjects\MerchantState;

class Merchant
{
    private ?string $id = null;

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
        $self->id = $merchantState->id;

        return $self;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getDateCreated(): DateTime
    {
        return $this->dateCreated;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getMid(): string
    {
        return $this->mid;
    }
}
