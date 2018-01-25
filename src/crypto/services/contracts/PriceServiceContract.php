<?php
namespace cryptochu\crypto\services\contracts;

use cryptochu\crypto\currencies\CryptoCurrency;
use cryptochu\money\MoneyAmount;

/**
 * PriceServiceContract implementations fetch ask and bid prices for a provided cryptocurrency.
 *
 * @author Emile Pels
 * @package cryptochu\crypto\services\contracts
 */
interface PriceServiceContract extends ExchangeServiceContract
{
    /**
     * Get the ask price for the given cryptocurrency.
     *
     * @param CryptoCurrency $cryptoCurrency
     *
     * @return MoneyAmount
     */
    public function getAsk(CryptoCurrency $cryptoCurrency): MoneyAmount;

    /**
     * Get the bid price for the given cryptocurrency.
     *
     * @param CryptoCurrency $cryptoCurrency
     *
     * @return MoneyAmount
     */
    public function getBid(CryptoCurrency $cryptoCurrency): MoneyAmount;
}
