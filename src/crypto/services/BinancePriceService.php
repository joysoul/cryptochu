<?php
namespace cryptochu\crypto\services;

use cryptochu\common\EnumRegularCurrency;
use cryptochu\crypto\currencies\CryptoCurrency;
use cryptochu\crypto\services\contracts\PriceServiceContract;
use cryptochu\http\contracts\HttpClient;
use cryptochu\money\MoneyAmount;
use cryptochu\money\MoneyAmountFormatter;
use cryptochu\money\MoneyAmountParser;
use cryptochu\services\contracts\LoggingServiceContract;
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
     * @var LoggingServiceContract
     */
    private $loggerService;


    /**
     * @var MoneyAmountParser
     */
    private $moneyAmountParser;

    /**
     * @param HttpClient $httpClient
     * @param LoggingServiceContract $loggerService
     */
    public function __construct(HttpClient $httpClient, LoggingServiceContract $loggerService)
    {
        $this->httpClient = $httpClient;
        $this->loggerService = $loggerService;

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
        $ask = $this->moneyAmountParser->parse(
            $this->getJsonByCryptoCurrency($cryptoCurrency)[self::KEY_ASK]
        );

        $this->loggerService->info('binancePriceService.askRetrieved', [
            'amount' => MoneyAmountFormatter::formatAsString($ask),
            'exchange' => $this->getExchangeName(),
            'type' => 'ask',
        ]);

        return $ask;
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
        $bid = $this->moneyAmountParser->parse(
            $this->getJsonByCryptoCurrency($cryptoCurrency)[self::KEY_BID]
        );

        $this->loggerService->info('binancePriceService.bidRetrieved', [
            'amount' => MoneyAmountFormatter::formatAsString($bid),
            'exchange' => $this->getExchangeName(),
            'type' => 'bid',
        ]);

        return $bid;
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
