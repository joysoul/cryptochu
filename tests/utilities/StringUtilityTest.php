<?php
namespace cryptochu\utilities\tests;

use cryptochu\exceptions\TypeException;
use cryptochu\exceptions\ValueException;
use cryptochu\tests\TestCase;
use cryptochu\utilities\StringUtility;

/**
 * @author Emile Pels
 * @package cryptochu\utilities\tests
 */
class StringUtilityTest extends TestCase
{
    /**
     * @param string $value
     * @dataProvider dataProviderAssertStringNotNullOrWhitespace
     */
    public function testAssertStringNotNullOrWhitespace($value)
    {
        static::expectException(ValueException::class);

        StringUtility::assertNotNullOrWhitespace($value);
    }

    /**
     * @return array
     */
    public function dataProviderAssertStringNotNullOrWhitespace(): array
    {
        return [
            [null],
            [''],
            ['    '],
            ["\t"],
            ["\r\n"],
            [PHP_EOL],
        ];
    }

    /**
     * @param bool $expected
     * @param string $value
     * @dataProvider dataProviderIsStringNullOrWhitespace
     */
    public function testIsStringNullOrWhitespace(bool $expected, $value)
    {
        static::assertEquals($expected, StringUtility::isNullOrWhitespace($value));
    }

    /**
     * @return array
     */
    public function dataProviderIsStringNullOrWhitespace(): array
    {
        return [
            [true, null],
            [true, ''],
            [true, '    '],
            [true, "\t"],
            [true, "\r\n"],
            [true, PHP_EOL],

            [false, 'hello'],
            [false, '    hello'],
            [false, 'hello   '],
            [false, '   hello   '],
        ];
    }

    /**
     *
     */
    public function testIsStringNullOrWhitespaceInvalidType()
    {
        static::expectException(TypeException::class);

        StringUtility::isNullOrWhitespace(1000);
    }
}
