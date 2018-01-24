<?php
namespace cryptochu\crypto\services\tests;

use cryptochu\crypto\currencies\CryptoCurrency;
use cryptochu\crypto\services\BinancePriceService;
use cryptochu\money\MoneyAmountBuilder;
use cryptochu\tests\HttpTestCase;
use cryptochu\tests\TestCase;

/**
 * @author Emile Pels
 * @package cryptochu\crypto\services\tests
 */
class BinancePriceServiceTest extends TestCase
{
    /**
     * Fake response.
     */
    const FAKE_RESPONSE = '{"symbol":"BTCUSDT","bidPrice":"12798.99000000",'
        . '"bidQty":"0.00020100","askPrice":"12799.00000000","askQty":"0.07053900"}';

    /**
     * Method that should be called on the LoggerServiceInterface.
     */
    const METHOD_LOGGER_SERVICE_INFO = 'info';

    /**
     * @var BinancePriceService
     */
    private $binancePriceService;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();

        $this->binancePriceService = new BinancePriceService(
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
            MoneyAmountBuilder::fromUnitedStatesDollar()->whole(12799)->build(),
            $this->binancePriceService->getAsk(CryptoCurrency::bitcoin())
        );
    }

    /**
     *
     */
    public function testGetBid()
    {
        static::assertEquals(
            MoneyAmountBuilder::fromUnitedStatesDollar()->whole(12798)->cents(99)->build(),
            $this->binancePriceService->getBid(CryptoCurrency::bitcoin())
        );
    }
}
