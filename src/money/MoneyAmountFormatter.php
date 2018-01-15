<?php
namespace cryptochu\money;

/**
 * MoneyAmountFormatter formats a MoneyAmount as a string.
 *
 * @author Emile Pels
 * @package cryptochu\common
 */
class MoneyAmountFormatter
{
    /**
     * Formatting constants.
     */
    const FORMAT_MONEY_AMOUNT = '%s %s.%s';

    /**
     * @param MoneyAmount $moneyAmount
     *
     * @return string
     */
    public static function formatAsString(MoneyAmount $moneyAmount): string
    {
        return vsprintf(
            self::FORMAT_MONEY_AMOUNT,
            [
                $moneyAmount->getRegularCurrency()->getChoice(),
                $moneyAmount->getWhole(),
                $moneyAmount->getCents()
            ]
        );
    }
}