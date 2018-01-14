<?php
namespace cryptochu\utilities\tests;

use cryptochu\exceptions\TypeException;
use cryptochu\tests\TestCase;
use cryptochu\utilities\TypeUtility;
use Exception;

class TypeUtilityTest extends TestCase
{
    /**
     * @param string $expected
     * @param $value
     * @dataProvider dataProviderGetType
     */
    public function testGetType(string $expected, $value)
    {
        static::assertEquals($expected, TypeUtility::getType($value));
    }

    /**
     * @return array
     */
    public function dataProviderGetType(): array
    {
        return [
            ['array', []],
            ['array', [1, 2, 3]],
            ['boolean', true],
            ['integer', 1000],
            ['string', 'hello'],
            ['NULL', null],
            [TypeException::class, new TypeException()],
            [TypeUtility::class, new TypeUtility()],
        ];
    }

    /**
     * @param bool $expected
     * @param mixed $value
     * @param string $type
     * @dataProvider dataProviderIsType
     */
    public function testIsType(bool $expected, $value, string $type)
    {
        static::assertEquals($expected, TypeUtility::isType($value, $type));
    }

    /**
     * @return array
     */
    public function dataProviderIsType(): array
    {
        return [
            [true, 1000, TypeUtility::TYPE_INTEGER],
            [false, 1, TypeUtility::TYPE_BOOLEAN],
            [true, new TypeException(), TypeException::class],
            [false, new TypeException(), Exception::class],
        ];
    }

    /**
     * @param mixed $value
     * @param string $type
     * @dataProvider dataProviderAssertIsType
     */
    public function testAssertIsType($value, string $type)
    {
        static::expectException(TypeException::class);

        TypeUtility::assertIsType($value, $type);
    }

    /**
     * @return array
     */
    public function dataProviderAssertIsType(): array
    {
        return [
            [1000, 'float'],
            [1, 'boolean'],
            [new Exception(), TypeException::class],
        ];
    }
}
