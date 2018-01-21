<?php
namespace cryptochu\money\tests;

use cryptochu\common\EnumRegularCurrency;
use cryptochu\exceptions\ValueException;
use cryptochu\money\MoneyAmount;
use cryptochu\money\MoneyAmountParser;
use cryptochu\tests\TestCase;

/**
 * @author Emile Pels
 * @package cryptochu\money\tests
 */
class MoneyAmountParserTest extends TestCase
{
    /**
     * @param MoneyAmount $expected
     * @param MoneyAmountParser $moneyAmountParser
     * @param string $moneyAmountString
     * @dataProvider dataProviderParse
     */
    public function testParse(MoneyAmount $expected, MoneyAmountParser $moneyAmountParser, string $moneyAmountString)
    {
        static::assertEquals($expected, $moneyAmountParser->parse($moneyAmountString));
    }

    /**
     * @return array
     */
    public function dataProviderParse(): array
    {
        return [
            [
                MoneyAmount::fromEuro(12345),
                new MoneyAmountParser(EnumRegularCurrency::euro()),
                '123.45',
            ],
            [
                MoneyAmount::fromEuro(12345),
                new MoneyAmountParser(EnumRegularCurrency::euro()),
                '123.451',
            ],
            [
                MoneyAmount::fromEuro(12346),
                new MoneyAmountParser(EnumRegularCurrency::euro()),
                '123.459',
            ],
            [
                MoneyAmount::fromEuro(1000),
                new MoneyAmountParser(EnumRegularCurrency::euro()),
                '10.',
            ],
            [
                MoneyAmount::fromEuro(1000),
                new MoneyAmountParser(EnumRegularCurrency::euro()),
                '10.0',
            ],
            [
                MoneyAmount::fromEuro(0),
                new MoneyAmountParser(EnumRegularCurrency::euro()),
                '0.0',
            ],
            [
                MoneyAmount::fromUnitedStatesDollar(54321),
                new MoneyAmountParser(EnumRegularCurrency::unitedStatesDollar()),
                '543.21',
            ],
            [
                MoneyAmount::fromUnitedStatesDollar(5432100),
                new MoneyAmountParser(EnumRegularCurrency::unitedStatesDollar()),
                '54321.00',
            ],
            [
                MoneyAmount::fromUnitedStatesDollar(77760),
                new MoneyAmountParser(EnumRegularCurrency::unitedStatesDollar()),
                '777.6',
            ],
            [
                MoneyAmount::fromUnitedStatesDollar(11100),
                new MoneyAmountParser(EnumRegularCurrency::unitedStatesDollar()),
                '111',
            ],
            [
                MoneyAmount::fromUnitedStatesDollar(1),
                new MoneyAmountParser(EnumRegularCurrency::unitedStatesDollar()),
                '0.01',
            ],
        ];
    }

    /**
     * @param MoneyAmountParser $moneyAmountParser
     * @param string $moneyAmountString
     * @dataProvider dataProviderParseInvalid
     */
    public function testParseInvalid(MoneyAmountParser $moneyAmountParser, string $moneyAmountString)
    {
        static::expectException(ValueException::class);

        $moneyAmountParser->parse($moneyAmountString);
    }

    /**
     * @return array
     */
    public function dataProviderParseInvalid(): array
    {
        return [
            [new MoneyAmountParser(EnumRegularCurrency::euro()), 'not a number'],
            [new MoneyAmountParser(EnumRegularCurrency::euro()), '123.4.5'],
            [new MoneyAmountParser(EnumRegularCurrency::euro()), '123,45'],
            [new MoneyAmountParser(EnumRegularCurrency::unitedStatesDollar()), '-543.21'],
            [new MoneyAmountParser(EnumRegularCurrency::unitedStatesDollar()), '-0.01'],
        ];
    }
}
