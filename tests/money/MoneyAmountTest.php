<?php
namespace cryptochu\money\tests;

use cryptochu\exceptions\ValueException;
use cryptochu\money\MoneyAmount;
use cryptochu\money\MoneyAmountBuilder;
use cryptochu\tests\TestCase;

/**
 * @author Emile Pels
 * @package cryptochu\common\tests
 */
class MoneyAmountTest extends TestCase
{

    /**
     * @param int $expected
     * @param MoneyAmount $moneyAmount
     * @dataProvider dataProviderGetAmount
     */
    public function testGetAmount(int $expected, MoneyAmount $moneyAmount)
    {
        static::assertEquals($expected, $moneyAmount->getAmount());
    }

    /**
     * @return array
     */
    public function dataProviderGetAmount(): array
    {
        return [
            [1000, MoneyAmount::fromEuro(1000)],
            [2000, MoneyAmount::fromUnitedStatesDollar(2000)],
        ];
    }

    /**
     * @param int $expected
     * @param MoneyAmount $moneyAmount
     * @dataProvider dataProviderGetWhole
     */
    public function testGetWhole(int $expected, MoneyAmount $moneyAmount)
    {
        static::assertEquals($expected, $moneyAmount->getWhole());
    }

    /**
     * @return array
     */
    public function dataProviderGetWhole(): array
    {
        return [
            [1, MoneyAmount::fromEuro(110)],
            [999, MoneyAmount::fromUnitedStatesDollar(99999)],
        ];
    }

    /**
     * @param int $expected
     * @param MoneyAmount $moneyAmount
     * @dataProvider dataProviderGetCents
     */
    public function testGetCents(int $expected, MoneyAmount $moneyAmount)
    {
        static::assertEquals($expected, $moneyAmount->getCents());
    }

    /**
     * @return array
     */
    public function dataProviderGetCents(): array
    {
        return [
            [10, MoneyAmount::fromEuro(110)],
            [99, MoneyAmount::fromUnitedStatesDollar(99999)],
        ];
    }

    /**
     * @param bool $expected
     * @param MoneyAmount $left
     * @param $right
     * @dataProvider dataProviderEquals
     */
    public function testEquals(bool $expected, MoneyAmount $left, $right)
    {
        static::assertEquals($expected, $left->equals($right));
    }

    /**
     * @return array
     */
    public function dataProviderEquals(): array
    {
        return [
            [true, MoneyAmount::fromEuro(1000), MoneyAmount::fromEuro(1000)],
            [true, MoneyAmount::fromUnitedStatesDollar(2000), MoneyAmount::fromUnitedStatesDollar(2000)],

            [false, MoneyAmount::fromEuro(1000), MoneyAmount::fromEuro(2000)],
            [false, MoneyAmount::fromUnitedStatesDollar(2000), MoneyAmount::fromEuro(1000)],
            [false, MoneyAmount::fromEuro(1000), MoneyAmount::fromUnitedStatesDollar(1001)],
            [false, MoneyAmount::fromUnitedStatesDollar(1), MoneyAmount::fromEuro(2)],
            [false, MoneyAmount::fromUnitedStatesDollar(1000), null],
        ];
    }

    /**
     * @param bool $expected
     * @param MoneyAmount $left
     * @param MoneyAmount $right
     * @dataProvider dataProviderGreaterThan
     */
    public function testGreaterThan(bool $expected, MoneyAmount $left, MoneyAmount $right)
    {
        static::assertEquals($expected, $left->greaterThan($right));
    }

    /**
     * @return array
     */
    public function dataProviderGreaterThan(): array
    {
        return [
            [true, MoneyAmount::fromEuro(1000), MoneyAmount::fromEuro(100)],
            [true, MoneyAmount::fromUnitedStatesDollar(2), MoneyAmount::fromUnitedStatesDollar(1)],

            [false, MoneyAmount::fromEuro(20), MoneyAmount::fromEuro(20)],
            [false, MoneyAmount::fromEuro(1000), MoneyAmount::fromEuro(10000)],
            [false, MoneyAmount::fromUnitedStatesDollar(1), MoneyAmount::fromUnitedStatesDollar(2)],
        ];
    }

    /**
     * @param bool $expected
     * @param MoneyAmount $left
     * @param MoneyAmount $right
     * @dataProvider dataProviderGreaterThanOrEquals
     */
    public function testGreaterThanOrEquals(bool $expected, MoneyAmount $left, MoneyAmount $right)
    {
        static::assertEquals($expected, $left->greaterThanOrEquals($right));
    }

    /**
     * @return array
     */
    public function dataProviderGreaterThanOrEquals(): array
    {
        return [
            [true, MoneyAmount::fromEuro(1000), MoneyAmount::fromEuro(100)],
            [true, MoneyAmount::fromUnitedStatesDollar(2), MoneyAmount::fromUnitedStatesDollar(1)],
            [true, MoneyAmount::fromEuro(20), MoneyAmount::fromEuro(20)],

            [false, MoneyAmount::fromEuro(1000), MoneyAmount::fromEuro(10000)],
            [false, MoneyAmount::fromUnitedStatesDollar(1), MoneyAmount::fromUnitedStatesDollar(2)],
        ];
    }

    /**
     * @param bool $expected
     * @param MoneyAmount $left
     * @param MoneyAmount $right
     * @dataProvider dataProviderLessThan
     */
    public function testLessThan(bool $expected, MoneyAmount $left, MoneyAmount $right)
    {
        static::assertEquals($expected, $left->lessThan($right));
    }

    /**
     * @return array
     */
    public function dataProviderLessThan(): array
    {
        return [
            [true, MoneyAmount::fromEuro(1000), MoneyAmount::fromEuro(10000)],
            [true, MoneyAmount::fromUnitedStatesDollar(1), MoneyAmount::fromUnitedStatesDollar(2)],

            [false, MoneyAmount::fromEuro(20), MoneyAmount::fromEuro(20)],
            [false, MoneyAmount::fromEuro(1000), MoneyAmount::fromEuro(100)],
            [false, MoneyAmount::fromUnitedStatesDollar(2), MoneyAmount::fromUnitedStatesDollar(1)],
        ];
    }

    /**
     * @param bool $expected
     * @param MoneyAmount $left
     * @param MoneyAmount $right
     * @dataProvider dataProviderLessThanOrEquals
     */
    public function testLessThanOrEquals(bool $expected, MoneyAmount $left, MoneyAmount $right)
    {
        static::assertEquals($expected, $left->lessThanOrEquals($right));
    }

    /**
     * @return array
     */
    public function dataProviderLessThanOrEquals(): array
    {
        return [
            [true, MoneyAmount::fromEuro(1000), MoneyAmount::fromEuro(10000)],
            [true, MoneyAmount::fromUnitedStatesDollar(1), MoneyAmount::fromUnitedStatesDollar(2)],
            [true, MoneyAmount::fromUnitedStatesDollar(20), MoneyAmount::fromUnitedStatesDollar(20)],

            [false, MoneyAmount::fromEuro(1000), MoneyAmount::fromEuro(100)],
            [false, MoneyAmount::fromUnitedStatesDollar(2), MoneyAmount::fromUnitedStatesDollar(1)],
        ];
    }

    /**
     * @param MoneyAmount $expected
     * @param MoneyAmount $left
     * @param MoneyAmount $right
     * @dataProvider dataProviderAdd
     */
    public function testAdd(MoneyAmount $expected, MoneyAmount $left, MoneyAmount $right)
    {
        static::assertEquals($expected, $left->add($right));
    }

    /**
     * @return array
     */
    public function dataProviderAdd(): array
    {
        return [
            [
                MoneyAmount::fromEuro(123456),
                MoneyAmount::fromEuro(123400),
                MoneyAmount::fromEuro(56)
            ],
            [
                MoneyAmount::fromUnitedStatesDollar(15),
                MoneyAmount::fromUnitedStatesDollar(10),
                MoneyAmount::fromUnitedStatesDollar(5)
            ],
            [
                MoneyAmount::fromUnitedStatesDollar(137),
                MoneyAmount::fromUnitedStatesDollar(99),
                MoneyAmount::fromUnitedStatesDollar(38)
            ],
        ];
    }

    /**
     * @param MoneyAmount $expected
     * @param MoneyAmount $left
     * @param MoneyAmount $right
     * @dataProvider dataProviderSubtract
     */
    public function testSubtract(MoneyAmount $expected, MoneyAmount $left, MoneyAmount $right)
    {
        static::assertEquals($expected, $left->subtract($right));
    }

    /**
     * @return array
     */
    public function dataProviderSubtract(): array
    {
        return [
            [
                MoneyAmount::fromEuro(99),
                MoneyAmount::fromEuro(123),
                MoneyAmount::fromEuro(24)
            ],
            [
                MoneyAmount::fromEuro(5000),
                MoneyAmount::fromEuro(9999),
                MoneyAmount::fromEuro(10000)->subtract(MoneyAmountBuilder::fromEuro()->whole(50)->cents(1)->build())
            ]
        ];
    }

    /**
     *
     */
    public function testAssertSameCurrency()
    {
        static::expectException(ValueException::class);

        MoneyAmount::fromEuro(100)->lessThan(MoneyAmount::fromUnitedStatesDollar(1));
    }

    /**
     *
     */
    public function testAssertAmountIsValid()
    {
        static::expectException(ValueException::class);

        MoneyAmount::fromEuro(-100);
    }
}
