<?php
namespace cryptochu\common;

use cryptochu\exceptions\ValueException;
use cryptochu\utilities\TypeUtility;
use PHPUnit\Framework\Constraint\RegularExpression;

/**
 * MoneyAmount represents a monetary value.
 * These are not properly handled as floating point numbers, so we store the number of cents as integers.
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

    /**
     * Minimum value for amount.
     */
    const AMOUNT_MINIMUM = 1;

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
    private function __construct($amount, EnumRegularCurrency $regularCurrency)
    {
        $this->assertAmountIsValid($amount);

        $this->amount = $amount;
        $this->regularCurrency = $regularCurrency;
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
        if ($this->sameCurrency($other)) {
            // Ok
        } else {
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
        return $this->getAmount() % self::CENTS_IN_WHOLE;
    }

    /**
     * Gets the whole "unit". E.g. if the amount is 120, this returns 1.
     *
     * @return int
     */
    public function getWhole(): int
    {
        return ($this->getAmount() - $this->getCents()) / self::CENTS_IN_WHOLE;
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
}
