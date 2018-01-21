<?php
namespace cryptochu\crypto\services\tests;

use cryptochu\crypto\currencies\CryptoCurrency;
use cryptochu\crypto\services\BinancePriceService;
use cryptochu\money\MoneyAmountBuilder;
use cryptochu\tests\HttpTestCase;

/**
 * @author Emile Pels
 * @package cryptochu\crypto\services\tests
 */
class BinancePriceServiceTest extends HttpTestCase
{
    /**
     * Fake response.
     */
    const FAKE_RESPONSE = '{"symbol":"BTCUSDT","bidPrice":"12798.99000000",'
        . '"bidQty":"0.00020100","askPrice":"12799.00000000","askQty":"0.07053900"}';

    /**
     *
     */
    public function testGetAsk()
    {
        $httpClient = $this->getHttpClientThatReturnsContent(self::FAKE_RESPONSE);

        $bitfinexPriceService = new BinancePriceService($httpClient);
        $ask = $bitfinexPriceService->getAsk(CryptoCurrency::bitcoin());

        static::assertEquals(MoneyAmountBuilder::fromUnitedStatesDollar()->whole(12799)->build(), $ask);
    }

    /**
     *
     */
    public function testGetBid()
    {
        $httpClient = $this->getHttpClientThatReturnsContent(self::FAKE_RESPONSE);

        $bitfinexPriceService = new BinancePriceService($httpClient);
        $bid = $bitfinexPriceService->getBid(CryptoCurrency::bitcoin());

        static::assertEquals(MoneyAmountBuilder::fromUnitedStatesDollar()->whole(12798)->cents(99)->build(), $bid);
    }
}
