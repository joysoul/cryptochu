<?php
namespace cryptochu\crypto\services;

use cryptochu\common\EnumRegularCurrency;
use cryptochu\crypto\currencies\CryptoCurrency;
use cryptochu\crypto\services\contracts\PriceServiceContract;
use cryptochu\http\contracts\HttpClient;
use cryptochu\money\MoneyAmount;
use cryptochu\money\MoneyAmountParser;
use cryptochu\utilities\JsonUtility;

/**
 * @author Emile Pels
 * @package cryptochu\crypto\services
 */
class BinancePriceService implements PriceServiceContract
{
    /**
     * URL constants.
     */
    const BASE_URL = 'https://api.binance.com/api/v3/ticker/bookTicker?symbol=%sUSDT';

    /**
     * Exchange constants.
     */
    const EXCHANGE_NAME = 'Binance';

    /**
     * Key constants.
     */
    const KEY_ASK = 'askPrice';
    const KEY_BID = 'bidPrice';

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var MoneyAmountParser
     */
    private $moneyAmountParser;

    /**
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;

        $this->moneyAmountParser = new MoneyAmountParser(EnumRegularCurrency::unitedStatesDollar());
    }

    /**
     * @return string
     */
    public function getExchangeName(): string
    {
        return self::EXCHANGE_NAME;
    }

    /**
     * Get the ask price for the given cryptocurrency.
     *
     * @param CryptoCurrency $cryptoCurrency
     *
     * @return MoneyAmount
     */
    public function getAsk(CryptoCurrency $cryptoCurrency): MoneyAmount
    {
        return $this->moneyAmountParser->parse(
            $this->getJsonByCryptoCurrency($cryptoCurrency)[self::KEY_ASK]
        );
    }

    /**
     * Get the bid price for the given cryptocurrency.
     *
     * @param CryptoCurrency $cryptoCurrency
     *
     * @return MoneyAmount
     */
    public function getBid(CryptoCurrency $cryptoCurrency): MoneyAmount
    {
        return $this->moneyAmountParser->parse(
            $this->getJsonByCryptoCurrency($cryptoCurrency)[self::KEY_BID]
        );
    }

    /**
     * @param CryptoCurrency $cryptoCurrency
     *
     * @return array
     */
    private function getJsonByCryptoCurrency(CryptoCurrency $cryptoCurrency): array
    {
        return JsonUtility::decodeJson(
            $this->httpClient->getContent(
                vsprintf(self::BASE_URL, [strtoupper($cryptoCurrency->getSymbol())])
            )
        );
    }
}
