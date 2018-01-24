<?php
namespace cryptochu\crypto\services\tests;

use cryptochu\crypto\currencies\CryptoCurrency;
use cryptochu\crypto\services\BitstampPriceService;
use cryptochu\money\MoneyAmountBuilder;
use cryptochu\tests\TestCase;

/**
 * @author Emile Pels
 * @package cryptochu\crypto\services\tests
 */
class BitstampPriceServiceTest extends TestCase
{
    /**
     * Fake response.
     */
    const FAKE_RESPONSE = '{"high": "13052.12", "last": "12828.03", "timestamp": "1516483497", "bid": "12800.33",'
        . '"vwap": "12375.06", "volume": "10203.66876269", "low": "11250.00", "ask": "12828.03", "open": "11560.82"}';

    /**
     * @var BitstampPriceService
     */
    private $bitstampPriceService;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();

        $this->bitstampPriceService = new BitstampPriceService(
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
            MoneyAmountBuilder::fromUnitedStatesDollar()->amount(12828, 3)->build(),
            $this->bitstampPriceService->getAsk(CryptoCurrency::bitcoin())
        );
    }

    /**
     *
     */
    public function testGetBid()
    {
        static::assertEquals(
            MoneyAmountBuilder::fromUnitedStatesDollar()->amount(12800, 33)->build(),
            $this->bitstampPriceService->getBid(CryptoCurrency::bitcoin())
        );
    }
}
