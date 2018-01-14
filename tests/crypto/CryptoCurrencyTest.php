<?php
namespace cryptochu\crypto\currencies;

use cryptochu\tests\TestCase;

/**
 * @author Emile Pels
 * @package cryptochu\crypto\currencies
 */
class CryptoCurrencyTest extends TestCase
{
    /**
     * @param bool $expected
     * @param CryptoCurrency $left
     * @param $right
     * @dataProvider dataProviderEquals
     */
    public function testEquals(bool $expected, CryptoCurrency $left, $right)
    {
        static::assertEquals($expected, $left->equals($right));
    }

    /**
     * @return array
     */
    public function dataProviderEquals(): array
    {
        return [
            [true, CryptoCurrency::bitcoin(), CryptoCurrency::bitcoin()],
            [true, CryptoCurrency::ethereum(), CryptoCurrency::ethereum()],

            [false, CryptoCurrency::bitcoin(), CryptoCurrency::ethereum()],
            [false, CryptoCurrency::ethereum(), CryptoCurrency::bitcoin()],
            [false, CryptoCurrency::bitcoin(), 'bitcoin'],
            [false, CryptoCurrency::bitcoin(), 'btc'],
            [false, CryptoCurrency::bitcoin(), ''],
            [false, CryptoCurrency::bitcoin(), null],
        ];
    }

    /**
     * @param string $expected
     * @param CryptoCurrency $cryptoCurrency
     * @dataProvider dataProviderGetName
     */
    public function testGetName(string $expected, CryptoCurrency $cryptoCurrency)
    {
        static::assertEquals($expected, $cryptoCurrency->getName());
    }

    /**
     * @return array
     */
    public function dataProviderGetName(): array
    {
        return [
            ['bitcoin', CryptoCurrency::bitcoin()],
            ['ethereum', CryptoCurrency::ethereum()],
        ];
    }

    /**
     * @param string $expected
     * @param CryptoCurrency $cryptoCurrency
     * @dataProvider dataProviderGetSymbol
     */
    public function testGetSymbol(string $expected, CryptoCurrency $cryptoCurrency)
    {
        static::assertEquals($expected, $cryptoCurrency->getSymbol());
    }

    /**
     * @return array
     */
    public function dataProviderGetSymbol(): array
    {
        return [
            ['btc', CryptoCurrency::bitcoin()],
            ['eth', CryptoCurrency::ethereum()],
        ];
    }
}
