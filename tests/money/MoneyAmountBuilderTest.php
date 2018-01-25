<?php
namespace cryptochu\money\tests;

use cryptochu\exceptions\ValueException;
use cryptochu\money\MoneyAmount;
use cryptochu\money\MoneyAmountBuilder;
use cryptochu\tests\TestCase;

/**
 * @author Emile Pels
 * @package cryptochu\money\tests
 */
class MoneyAmountBuilderTest extends TestCase
{
    /**
     * @param MoneyAmount $expected
     * @param MoneyAmountBuilder $moneyAmountBuilder
     * @dataProvider dataProviderBuild
     */
    public function testBuild(MoneyAmount $expected, MoneyAmountBuilder $moneyAmountBuilder)
    {
        static::assertEquals($expected, $moneyAmountBuilder->build());
    }

    /**
     * @return array
     */
    public function dataProviderBuild(): array
    {
        return [
            [MoneyAmount::fromEuro(1234), MoneyAmountBuilder::fromEuro()->whole(12)->cents(34)],
            [MoneyAmount::fromEuro(1234), MoneyAmountBuilder::fromEuro()->amount(12, 34)],
            [MoneyAmount::fromEuro(133799), MoneyAmountBuilder::fromEuro()->cents(99)->whole(1337)],
            [MoneyAmount::fromEuro(0), MoneyAmountBuilder::fromEuro()],
            [MoneyAmount::fromUnitedStatesDollar(1000), MoneyAmountBuilder::fromUnitedStatesDollar()->whole(10)],
            [MoneyAmount::fromUnitedStatesDollar(10), MoneyAmountBuilder::fromUnitedStatesDollar()->cents(10)],
        ];
    }


    /**
     * @param MoneyAmount $expected
     * @param MoneyAmountBuilder $moneyAmountBuilder
     * @dataProvider dataProviderBuildOverwrite
     */
    public function testBuildOverwrite(MoneyAmount $expected, MoneyAmountBuilder $moneyAmountBuilder)
    {
        static::assertEquals($expected, $moneyAmountBuilder->build());
    }

    /**
     * @return array
     */
    public function dataProviderBuildOverwrite(): array
    {
        return [
            [
                MoneyAmount::fromEuro(4321),
                MoneyAmountBuilder::fromEuro()
                    ->amount(1, 21)
                    ->whole(43)
            ],
            [
                MoneyAmount::fromUnitedStatesDollar(6789),
                MoneyAmountBuilder::fromUnitedStatesDollar()
                    ->whole(100)
                    ->amount(67, 89)
            ]
        ];
    }

    /**
     * @param MoneyAmountBuilder $moneyAmountBuilder
     * @dataProvider dataProviderBuildInvalid
     */
    public function testBuildInvalid(MoneyAmountBuilder $moneyAmountBuilder)
    {
        static::expectException(ValueException::class);

        $moneyAmountBuilder->build();
    }

    /**
     * @return array
     */
    public function dataProviderBuildInvalid(): array
    {
        return [
            [MoneyAmountBuilder::fromEuro()->whole(100)->cents(100)],
            [MoneyAmountBuilder::fromEuro()->whole(1)->cents(200)],
            [MoneyAmountBuilder::fromEuro()->whole(-1)],
            [MoneyAmountBuilder::fromEuro()->cents(-1)],
            [MoneyAmountBuilder::fromEuro()->whole(-1)->cents(-1)],
            [MoneyAmountBuilder::fromEuro()->amount(-1, -1)],
            [MoneyAmountBuilder::fromEuro()->amount(-1, 1)],
            [MoneyAmountBuilder::fromEuro()->amount(1, -1)],
        ];
    }
}
