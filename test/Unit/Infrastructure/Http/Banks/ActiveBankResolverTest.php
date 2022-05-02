<?php

declare(strict_types=1);

namespace Test\Unit\Infrastructure\Http\Banks;

use PayByBank\Domain\Http\Banks\Bank;
use PayByBank\Infrastructure\Http\Banks\ActiveBankResolver;
use PHPUnit\Framework\TestCase;

class ActiveBankResolverTest extends TestCase
{
    public function testShouldResolveBankWithName(): void
    {
        $alphaBank = $this->getMockBuilder(Bank::class)
            ->setMockClassName('AlphaBank')->getMock();
        $banks = ['AlphaBank' => $alphaBank];
        $activeBankResolver = new ActiveBankResolver($banks);
        $resolvedBank = $activeBankResolver->resolveWithName('AlphaBank');

        $this->assertEquals($alphaBank, $resolvedBank);
    }
}
