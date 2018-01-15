<?php
namespace cryptochu\money;

use cryptochu\common\EnumRegularCurrency;
use cryptochu\exceptions\ValueException;

/**
 * MoneyAmountBuilder provides a light and convenient way to create MoneyAmount instances for humans.
 * Invoking the whole/cents etc methods multiple times overwrites its previously set value, if applicable.
 *
 * Invoking amount(a, b) is equal to whole(a)->cents(b)
 *
 * The examples below produce equal outputs - a MoneyAmount instance representing EUR 12.34.
 * MoneyAmountBuilder::fromEuro()->whole(12)->cents(34)->build();
 * MoneyAmountBuilder::fromEuro()->amount(12, 34)->build();
 *
 * @author Emile Pels
 * @package cryptochu\money
 */
class MoneyAmountBuilder
{
    /**
     * Error constants.
     */
    const ERROR_VALUE_OUT_OF_BOUNDS = 'Value for "%s" ("%s") is out of bounds.';

    /**
     * Value constants.
     */
    const WHOLE_DEFAULT = 0;
    const WHOLE_MINIMUM = 0;
    const CENTS_DEFAULT = 0;
    const CENTS_MINIMUM = 0;
    const CENTS_MAXIMUM = MoneyAmount::CENTS_IN_WHOLE - 1;

    /**
     * @var EnumRegularCurrency
     */
    private $regularCurrency;

    /**
     * @var int
     */
    private $whole;

    /**
     * @var int
     */
    private $cents;

    /**
     * MoneyAmountBuilder constructor.
     * @param EnumRegularCurrency $regularCurrency
     */
    private function __construct(EnumRegularCurrency $regularCurrency)
    {
        $this->regularCurrency = $regularCurrency;

        $this->whole = self::WHOLE_DEFAULT;
        $this->cents = self::WHOLE_MINIMUM;
    }

    /**
     * @return MoneyAmountBuilder
     */
    public static function fromEuro(): MoneyAmountBuilder
    {
        return new static(EnumRegularCurrency::euro());
    }

    /**
     * @return MoneyAmountBuilder
     */
    public static function fromUnitedStatesDollar(): MoneyAmountBuilder
    {
        return new static(EnumRegularCurrency::unitedStatesDollar());
    }

    /**
     * @param int $whole
     * @param int $cents
     *
     * @return MoneyAmountBuilder
     */
    public function amount(int $whole, int $cents): MoneyAmountBuilder
    {
        return $this->whole($whole)->cents($cents);
    }

    /**
     * @param int $whole
     *
     * @return MoneyAmountBuilder
     */
    public function whole(int $whole): MoneyAmountBuilder
    {
        $this->whole = $whole;

        return $this;
    }

    /**
     * @param int $cents
     *
     * @return MoneyAmountBuilder
     */
    public function cents(int $cents): MoneyAmountBuilder
    {
        $this->cents = $cents;

        return $this;
    }

    /**
     * @return MoneyAmount
     */
    public function build(): MoneyAmount
    {
        $this->assertValuesWithinBounds();

        return new MoneyAmount($this->getAmount(), $this->regularCurrency);
    }

    /**
     * @throws ValueException
     */
    private function assertValuesWithinBounds()
    {
        if ($this->whole < self::WHOLE_MINIMUM) {
            throw new ValueException(vsprintf(self::ERROR_VALUE_OUT_OF_BOUNDS, ['whole', $this->whole]));
        }

        if ($this->cents < self::CENTS_MINIMUM || $this->cents > self::CENTS_MAXIMUM) {
            throw new ValueException(vsprintf(self::ERROR_VALUE_OUT_OF_BOUNDS, ['cents', $this->cents]));
        }
    }

    /**
     * @return int
     */
    private function getAmount(): int
    {
        return $this->whole * $this->getCentsInWhole() + $this->cents;
    }

    /**
     * @return int
     */
    private function getCentsInWhole(): int
    {
        return MoneyAmount::CENTS_IN_WHOLE;
    }
}