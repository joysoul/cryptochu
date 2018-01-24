<?php
namespace cryptochu\crypto\services;

use cryptochu\common\EnumRegularCurrency;
use cryptochu\crypto\currencies\CryptoCurrency;
use cryptochu\crypto\services\contracts\PriceServiceContract;
use cryptochu\http\contracts\HttpClient;
use cryptochu\money\MoneyAmount;
use cryptochu\money\MoneyAmountFormatter;
use cryptochu\money\MoneyAmountParser;
use cryptochu\services\contracts\LoggerServiceContract;
use cryptochu\utilities\JsonUtility;

/**
 * @author Emile Pels
 * @package cryptochu\crypto\services
 */
class BitstampPriceService implements PriceServiceContract
{
    /**
     * URL constants.
     */
    const BASE_URL = 'https://www.bitstamp.net/api/v2/ticker/%susd/';

    /**
     * Exchange constants.
     */
    const EXCHANGE_NAME = 'Bitstamp';

    /**
     * Key constants.
     */
    const KEY_ASK = 'ask';
    const KEY_BID = 'bid';

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var LoggerServiceContract
     */
    private $loggerService;

    /**
     * @var MoneyAmountParser
     */
    private $moneyAmountParser;

    /**
     * @param HttpClient $httpClient
     * @param LoggerServiceContract $loggerService
     */
    public function __construct(HttpClient $httpClient, LoggerServiceContract $loggerService)
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

        $this->loggerService->info('Price retrieved', [
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

        $this->loggerService->info('Price retrieved', [
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
                vsprintf(self::BASE_URL, [$cryptoCurrency->getSymbol()])
            )
        );
    }
}
