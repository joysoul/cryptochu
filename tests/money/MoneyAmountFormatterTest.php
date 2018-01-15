<?php
namespace cryptochu\money\tests;

use cryptochu\money\MoneyAmount;
use cryptochu\money\MoneyAmountFormatter;
use cryptochu\tests\TestCase;

class MoneyAmountFormatterTest extends TestCase
{
    /**
     * @param string $expected
     * @param MoneyAmount $moneyAmount
     * @dataProvider dataProviderFormatAsString
     */
    public function testFormatAsString(string $expected, MoneyAmount $moneyAmount)
    {
        static::assertEquals($expected, MoneyAmountFormatter::formatAsString($moneyAmount));
    }

    /**
     * @return array
     */
    public function dataProviderFormatAsString(): array
    {
        return [
            ['euro 123.45', MoneyAmount::fromEuro(12345)],
            ['euro 10.0', MoneyAmount::fromEuro(1000)],
            ['euro 0.10', MoneyAmount::fromEuro(10)],
        ];
    }
}
