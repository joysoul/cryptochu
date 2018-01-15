<?php
namespace cryptochu\common\tests;

use cryptochu\common\Enum;
use cryptochu\common\EnumRegularCurrency;
use cryptochu\tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @author Emile Pels
 * @package cryptochu\common\tests
 */
class EnumTest extends TestCase
{
    /**
     * @param bool $expected
     * @param Enum $left
     * @param Enum $right
     * @dataProvider dataProviderEquals
     */
    public function testEquals(bool $expected, Enum $left, $right)
    {
        static::assertNotNull($left);

        static::assertEquals($expected, $left->equals($right));
    }

    /**
     * @return array
     */
    public function dataProviderEquals(): array
    {
        return [
            [true, EnumRegularCurrency::euro(), EnumRegularCurrency::euro()],
            [true, EnumRegularCurrency::unitedStatesDollar(), EnumRegularCurrency::unitedStatesDollar()],

            [false, EnumRegularCurrency::euro(), EnumRegularCurrency::unitedStatesDollar()],
            [false, EnumRegularCurrency::unitedStatesDollar(), EnumRegularCurrency::euro()],
            [false, EnumRegularCurrency::unitedStatesDollar(), null],
            [
                false,
                EnumRegularCurrency::unitedStatesDollar(),
                $this->getMockForAbstractClassWithoutOriginalConstructor(Enum::class),
            ],
        ];
    }

    /**
     * @param string $className
     *
     * @return MockObject
     */
    private function getMockForAbstractClassWithoutOriginalConstructor(string $className): MockObject
    {
        return static::getMockForAbstractClass($className, [], '', false);
    }

    /**
     *
     */
    public function testGetChoice()
    {
        static::assertEquals(
            EnumRegularCurrency::euro()->getChoice(),
            EnumRegularCurrency::CHOICE_EURO
        );
    }
}
