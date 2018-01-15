<?php
namespace cryptochu\money;

use cryptochu\common\EnumRegularCurrency;
use cryptochu\exceptions\ValueException;
use cryptochu\utilities\TypeUtility;

/**
 * MoneyAmount represents a monetary value.
 * These are not properly handled as floating point numbers, so we store the number of cents as integers.
 * 12345 is 123.45 of the given currency, whereas 1000 refers to 10.00.
 *
 * @author Emile Pels
 * @package cryptochu\common
 */
class MoneyAmount
{
    /**
     * Error constants.
     */
    const ERROR_AMOUNT_IS_NOT_INTEGER = 'Amount "%s" is not a valid integer.';
    const ERROR_AMOUNT_TOO_LOW = 'Amount "%s" is lower than minimum "%s"';
    const ERROR_CURRENCY_NOT_EQUALS = 'Can not compare currency "%s" to "%s".';
    const ERROR_VALUE_OUT_OF_BOUNDS = 'Value for "%s" ("%s") is out of bounds.';

    /**
     * Value constants.
     */
    const AMOUNT_MINIMUM = 0;
    const CENTS_MAXIMUM = self::CENTS_IN_WHOLE - 1;
    const CENTS_MINIMUM = 0;
    const WHOLE_MINIMUM = 0;

    /**
     * Number of cents in a "whole" currency unit.
     */
    const CENTS_IN_WHOLE = 100;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var EnumRegularCurrency
     */
    private $regularCurrency;

    /**
     * We do not scalar type hint the amount so no implicit casts occur and we can assert an integer is passed.
     * This decreases the risk of, accidentally, providing a float.
     *
     * @param int $amount
     * @param EnumRegularCurrency $regularCurrency
     */
    public function __construct($amount, EnumRegularCurrency $regularCurrency)
    {
        $this->assertAmountIsValid($amount);

        $this->amount = $amount;
        $this->regularCurrency = $regularCurrency;
    }

    /**
     * @param int $cents
     *
     * @throws ValueException
     */
    public static function assertCentsWithinBounds(int $cents)
    {
        if ($cents < self::CENTS_MINIMUM || $cents > self::CENTS_MAXIMUM) {
            throw new ValueException(vsprintf(self::ERROR_VALUE_OUT_OF_BOUNDS, ['cents', $cents]));
        }
    }

    /**
     * @param int $whole
     * 
     * @throws ValueException
     */
    public static function assertWholeWithinBounds(int $whole)
    {
        if ($whole < self::WHOLE_MINIMUM) {
            throw new ValueException(vsprintf(self::ERROR_VALUE_OUT_OF_BOUNDS, ['whole', $whole]));
        }
    }

    /**
     * @param int $amount
     *
     * @return MoneyAmount
     */
    public static function fromEuro($amount): MoneyAmount
    {
        return new static($amount, EnumRegularCurrency::euro());
    }

    /**
     * @param int $amount
     *
     * @return MoneyAmount
     */
    public static function fromUnitedStatesDollar($amount): MoneyAmount
    {
        return new static($amount, EnumRegularCurrency::unitedStatesDollar());
    }

    /**
     * @param int $amount
     *
     * @throws ValueException
     */
    private function assertAmountIsValid($amount)
    {
        TypeUtility::assertIsType($amount, TypeUtility::TYPE_INTEGER);

        if ($amount < self::AMOUNT_MINIMUM) {
            throw new ValueException(vsprintf(self::ERROR_AMOUNT_TOO_LOW, [$amount, self::AMOUNT_MINIMUM]));
        }
    }

    /**
     * @param MoneyAmount $other
     *
     * @return bool
     * @throws ValueException
     */
    private function assertSameCurrency(MoneyAmount $other)
    {
        if ($this->sameCurrency($other) === false) {
            throw new ValueException(
                vsprintf(
                    self::ERROR_CURRENCY_NOT_EQUALS,
                    [
                        $this->getRegularCurrency()->getChoice(),
                        $other->getRegularCurrency()->getChoice()
                    ]
                )
            );
        }
    }

    /**
     * @param MoneyAmount $other
     * @return bool
     */
    private function sameCurrency(MoneyAmount $other): bool
    {
        return $this->getRegularCurrency()->equals($other->getRegularCurrency());
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return EnumRegularCurrency
     */
    public function getRegularCurrency(): EnumRegularCurrency
    {
        return $this->regularCurrency;
    }

    /**
     * Gets the cents. E.g. if the amount is 120, this returns 20.
     *
     * @return int
     */
    public function getCents(): int
    {
        $cents = $this->getAmount() % self::CENTS_IN_WHOLE;

        $this->assertCentsWithinBounds($cents);

        return $cents;
    }

    /**
     * Gets the whole "unit". E.g. if the amount is 120, this returns 1.
     *
     * @return int
     */
    public function getWhole(): int
    {
        $whole = ($this->getAmount() - $this->getCents()) / self::CENTS_IN_WHOLE;

        $this->assertWholeWithinBounds($whole);

        return $whole;
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
        } elseif (TypeUtility::isType($other, TypeUtility::getType($this)) && $this->sameCurrency($other)) {
            return $this->getAmount() === $other->getAmount();
        } else {
            return false;
        }
    }

    /**
     * @param MoneyAmount $other
     *
     * @return bool
     */
    public function greaterThan(MoneyAmount $other): bool
    {
        $this->assertSameCurrency($other);

        return $this->getAmount() > $other->getAmount();
    }

    /**
     * @param MoneyAmount $other
     *
     * @return bool
     */
    public function greaterThanOrEquals(MoneyAmount $other): bool
    {
        return $this->getAmount() >= $other->getAmount();
    }

    /**
     * @param MoneyAmount $other
     *
     * @return bool
     */
    public function lessThan(MoneyAmount $other): bool
    {
        return $this->getAmount() < $other->getAmount();
    }

    /**
     * @param MoneyAmount $other
     *
     * @return bool
     */
    public function lessThanOrEquals(MoneyAmount $other): bool
    {
        return $this->getAmount() <= $other->getAmount();
    }
}
