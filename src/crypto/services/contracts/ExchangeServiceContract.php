<?php
namespace cryptochu\crypto\services\contracts;

/**
 * ExchangeServiceContract helps identifying the exchanges used by implementing services.
 *
 * @author Emile Pels
 * @package cryptochu\crypto\services\contracts
 */
interface ExchangeServiceContract
{
    /**
     * @return string
     */
    public function getExchangeName(): string;
}
