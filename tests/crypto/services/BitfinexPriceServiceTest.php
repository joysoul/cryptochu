<?php
namespace cryptochu\crypto\services\tests;

use cryptochu\crypto\currencies\CryptoCurrency;
use cryptochu\crypto\services\BitfinexPriceService;
use cryptochu\money\MoneyAmountBuilder;
use cryptochu\tests\TestCase;

/**
 * @author Emile Pels
 * @package cryptochu\crypto\services\tests
 */
class BitfinexPriceServiceTest extends TestCase
{
    /**
     * Fake response.
     */
    const FAKE_RESPONSE = '{"mid":"12707.5","bid":"12707.0","ask":"12708.0","last_price":"12708.0","low":"11134.0",'
        . '"high":"13017.0","volume":"47729.55925216","timestamp":"1516479579.1908865"}';

    /**
     * @var BitfinexPriceService
     */
    private $bitfinexPriceService;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();

        $this->bitfinexPriceService = new BitfinexPriceService(
            $this->getHttpClientThatReturnsContent(self::FAKE_RESPONSE),
            $this->getMockLoggerServiceThatIsCalled(static::once())
        );
    }

    /**
     *
     */
    public function testGetAsk()
    {
        static::assertEquals(
            MoneyAmountBuilder::fromUnitedStatesDollar()->whole(12708)->build(),
            $this->bitfinexPriceService->getAsk(CryptoCurrency::bitcoin())
        );
    }

    /**
     *
     */
    public function testGetBid()
    {
        static::assertEquals(
            MoneyAmountBuilder::fromUnitedStatesDollar()->whole(12707)->build(),
            $this->bitfinexPriceService->getBid(CryptoCurrency::bitcoin())
        );
    }
}
