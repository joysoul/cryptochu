<?php
namespace cryptochu\crypto\services\contracts;

use cryptochu\crypto\currencies\CryptoCurrency;
use cryptochu\money\MoneyAmount;

/**
 * @author Emile Pels
 * @package cryptochu\crypto\services\contracts
 */
interface PriceServiceContract extends ExchangeServiceContract
{
    public function getPrice(CryptoCurrency $cryptoCurrency): MoneyAmount;
}
