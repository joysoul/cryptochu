<?php
namespace cryptochu\crypto\currencies;

use cryptochu\utilities\StringUtility;
use cryptochu\utilities\TypeUtility;

/**
 * @author Emile Pels
 * @package cryptochu\crypto\currencies
 */
class CryptoCurrency
{
    /**
     * CryptoCurrency constants.
     */
    const BITCOIN_NAME = 'bitcoin';
    const BITCOIN_SYMBOL = 'btc';
    const ETHEREUM_NAME = 'ethereum';
    const ETHEREUM_SYMBOL = 'eth';

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $symbol;

    /**
     * @param string $name
     * @param string $symbol
     */
    private function __construct(string $name, string $symbol)
    {
        $this->assertNameAndSymbolValid($name, $symbol);

        $this->name = $name;
        $this->symbol = $symbol;
    }

    /**
     * @param string $name
     * @param string $symbol
     */
    private function assertNameAndSymbolValid(string $name, string $symbol)
    {
        StringUtility::assertNotNullOrWhitespace($name);
        StringUtility::assertNotNullOrWhitespace($symbol);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param $other
     *
     * @return bool
     */
    public function equals($other): bool
    {
        if (is_null($other)) {
            return false;
        }

        if (TypeUtility::isType($this, TypeUtility::getType($other))) {
            return $this->getName() === $other->getName() && $this->getSymbol() === $other->getSymbol();
        }

        return false;
    }

    /**
     * @return CryptoCurrency
     */
    public static function bitcoin(): CryptoCurrency
    {
        return new static(self::BITCOIN_NAME, self::BITCOIN_SYMBOL);
    }

    /**
     * @return CryptoCurrency
     */
    public static function ethereum(): CryptoCurrency
    {
        return new static(self::ETHEREUM_NAME, self::ETHEREUM_SYMBOL);
    }
}
