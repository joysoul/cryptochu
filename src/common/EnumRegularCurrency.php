<?php
namespace cryptochu\common;

/**
 * @author Emile Pels
 * @package cryptochu\common
 */
class EnumRegularCurrency extends Enum
{
    /**
     * Choice constants.
     */
    const CHOICE_EURO = 'euro';
    const CHOICE_UNITED_STATES_DOLLAR = 'unitedStatesDollar';

    /**
     * @return string[]
     */
    protected function getSupportedChoices(): array
    {
        return [
            self::CHOICE_EURO,
            self::CHOICE_UNITED_STATES_DOLLAR,
        ];
    }

    /**
     * @return EnumRegularCurrency
     */
    public static function euro(): EnumRegularCurrency
    {
        return new static(self::CHOICE_EURO);
    }

    /**
     * @return EnumRegularCurrency
     */
    public static function unitedStatesDollar(): EnumRegularCurrency
    {
        return new static(self::CHOICE_UNITED_STATES_DOLLAR);
    }
}