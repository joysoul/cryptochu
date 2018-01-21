<?php
namespace cryptochu\crypto\services\tests;

use cryptochu\crypto\currencies\CryptoCurrency;
use cryptochu\crypto\services\BitfinexPriceService;
use cryptochu\money\MoneyAmountBuilder;
use cryptochu\tests\HttpTestCase;

/**
 * @author Emile Pels
 * @package cryptochu\crypto\services\tests
 */
class BitfinexPriceServiceTest extends HttpTestCase
{
    /**
     * Fake response.
     */
    const FAKE_RESPONSE = '{"mid":"12707.5","bid":"12707.0","ask":"12708.0","last_price":"12708.0","low":"11134.0",'
        . '"high":"13017.0","volume":"47729.55925216","timestamp":"1516479579.1908865"}';

    /**
     *
     */
    public function testGetAsk()
    {
        $httpClient = $this->getHttpClientThatReturnsContent(self::FAKE_RESPONSE);

        $bitfinexPriceService = new BitfinexPriceService($httpClient);
        $ask = $bitfinexPriceService->getAsk(CryptoCurrency::bitcoin());

        static::assertEquals(MoneyAmountBuilder::fromUnitedStatesDollar()->whole(12708)->build(), $ask);
    }

    /**
     *
     */
    public function testGetBid()
    {
        $httpClient = $this->getHttpClientThatReturnsContent(self::FAKE_RESPONSE);

        $bitfinexPriceService = new BitfinexPriceService($httpClient);
        $bid = $bitfinexPriceService->getBid(CryptoCurrency::bitcoin());

        static::assertEquals(MoneyAmountBuilder::fromUnitedStatesDollar()->whole(12707)->build(), $bid);
    }
}
