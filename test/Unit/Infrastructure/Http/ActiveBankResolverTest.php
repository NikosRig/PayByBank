<?php

declare(strict_types=1);

namespace Test\Unit\Infrastructure\Http;

use PayByBank\Domain\Http\Banks\Bank;
use PayByBank\Infrastructure\Http\ActiveBankResolver;
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

    public function testExpectInvalidArgumentWithUnknownBankName(): void
    {
        $NBG = $this->getMockBuilder(Bank::class)
            ->setMockClassName('NBG')->getMock();
        $banks = ['NBG' => $NBG];
        $activeBankResolver = new ActiveBankResolver($banks);
        $this->expectException(\InvalidArgumentException::class);

        $activeBankResolver->resolveWithName('Test');
    }
}
