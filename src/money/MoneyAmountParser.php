<?php
namespace cryptochu\money;

use cryptochu\common\EnumRegularCurrency;
use cryptochu\exceptions\ValueException;
use cryptochu\utilities\TypeUtility;
use NumberFormatter;

/**
 * MoneyAmountParser parses a MoneyAmount from numeric strings.
 *
 * @author Emile Pels
 * @package cryptochu\money
 */
class MoneyAmountParser
{
    /**
     * Error constants.
     */
    const ERROR_NOT_VALID_MONEY_AMOUNT_STRING = 'String can not be parsed to a valid MoneyAmount.';

    /**
     * Used to assert the amount is a positive one.
     */
    const PARSING_MINIMUM_POSITIVE_FLOAT = 0.0;

    /**
     * Prefix to prepend before parsing the string. The actual currency is not relevant, as we handle currencies
     * ourselves: we only add this to the string so the formatter won't choke on it.
     */
    const PARSING_PREFIX = '$';

    /**
     * Indicates a successful regex match.
     */
    const PARSING_PREG_MATCH_MATCH = 1;

    /**
     * Locale to use while parsing.
     */
    const PARSING_LOCALE = 'en_US';

    /**
     * Precision to use for rounding the intermediate float.
     */
    const PARSING_ROUNDING_PRECISION = 2;

    /**
     * When asserting we're working with a valid money amount string,
     * we only want to replace this amount of occurrences of the separator.
     */
    const SEPARATOR_MONEY_AMOUNT_STRING_STRIP_LIMIT = '1';

    /**
     * Pattern to use for sanitizing/stripping the money amount string.
     */
    const SEPARATOR_MONEY_AMOUNT_STRING_STRIP_PATTERN = '/[.]/';

    /**
     * Replace the occurrence(s) of above pattern with this - an empty string, as we want to remove it.
     */
    const SEPARATOR_MONEY_AMOUNT_STRING_STRIP_REPLACEMENT = '';

    /**
     * @var EnumRegularCurrency
     */
    private $regularCurrency;

    /**
     * @param EnumRegularCurrency $regularCurrency
     */
    public function __construct(EnumRegularCurrency $regularCurrency)
    {
        $this->regularCurrency = $regularCurrency;
    }

    /**
     * @param string $moneyAmountString
     *
     * @return MoneyAmount
     */
    public function parse(string $moneyAmountString): MoneyAmount
    {
        $this->assertIsValidMoneyAmountString($moneyAmountString);

        $result = $this->getNumberFormatterResult($moneyAmountString);
        $moneyAmountFloat = round($result, self::PARSING_ROUNDING_PRECISION) * MoneyAmount::CENTS_IN_WHOLE;

        return new MoneyAmount(intval($moneyAmountFloat), $this->regularCurrency);
    }

    /**
     * @param string $moneyAmountString
     *
     * @throws ValueException
     */
    private function assertIsValidMoneyAmountString(string $moneyAmountString)
    {
        $moneyAmountStringWithoutFirstSeparatorOccurrence
            = $this->getStringWithoutFirstSeparatorOccurrence($moneyAmountString);

        if ($this->stringContainsSeparator($moneyAmountStringWithoutFirstSeparatorOccurrence)
            || $this->isPositiveFloatString($moneyAmountStringWithoutFirstSeparatorOccurrence) !== true) {
            throw new ValueException(self::ERROR_NOT_VALID_MONEY_AMOUNT_STRING);
        }
    }

    /**
     * @param string $moneyAmountString
     *
     * @return string
     */
    private function getStringWithoutFirstSeparatorOccurrence(string $moneyAmountString): string
    {
        $stringWithoutFirstSeparatorOccurrence = preg_replace(
            self::SEPARATOR_MONEY_AMOUNT_STRING_STRIP_PATTERN,
            self::SEPARATOR_MONEY_AMOUNT_STRING_STRIP_REPLACEMENT,
            $moneyAmountString,
            self::SEPARATOR_MONEY_AMOUNT_STRING_STRIP_LIMIT
        );

        TypeUtility::assertIsType($stringWithoutFirstSeparatorOccurrence, TypeUtility::TYPE_STRING);

        return $stringWithoutFirstSeparatorOccurrence;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    private function stringContainsSeparator(string $value): bool
    {
        return preg_match(self::SEPARATOR_MONEY_AMOUNT_STRING_STRIP_PATTERN, $value) === self::PARSING_PREG_MATCH_MATCH;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    private function isPositiveFloatString(string $value): bool
    {
        return is_numeric($value) === true && floatval($value) >= self::PARSING_MINIMUM_POSITIVE_FLOAT;
    }

    /**
     * @param string $moneyAmountString
     *
     * @return float
     */
    private function getNumberFormatterResult(string $moneyAmountString)
    {
        $moneyAmountStringPrepared = $this->prepareMoneyAmountStringForParsing($moneyAmountString);

        $fmt = new NumberFormatter(self::PARSING_LOCALE, NumberFormatter::CURRENCY);
        $parseResult = $fmt->parseCurrency($moneyAmountStringPrepared, $unusedReferenceOutputParameter);

        $this->assertParseResultSuccessful($parseResult);

        return $parseResult;
    }

    /**
     * @param string $moneyAmountString
     *
     * @return string
     */
    private function prepareMoneyAmountStringForParsing(string $moneyAmountString): string
    {
        return self::PARSING_PREFIX . $moneyAmountString;
    }

    /**
     * @param float|bool $result
     *
     * @throws ValueException
     */
    private function assertParseResultSuccessful($result)
    {
        if ($result === false) {
            throw new ValueException(self::ERROR_NOT_VALID_MONEY_AMOUNT_STRING);
        }
    }
}
