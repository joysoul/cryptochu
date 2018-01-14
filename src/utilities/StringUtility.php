<?php
namespace cryptochu\utilities;

use cryptochu\exceptions\ValueException;

/**
 * @author Emile Pels
 * @package cryptochu\utilities
 */
class StringUtility
{
    /**
     * Error constants.
     */
    const ERROR_STRING_NULL_OR_WHITESPACE = 'Failed asserting that string is not null or whitespace.';

    /**
     * @param string $value
     *
     * @throws ValueException
     */
    public static function assertNotNullOrWhitespace($value)
    {
        if (static::isNullOrWhitespace($value)) {
            throw new ValueException(self::ERROR_STRING_NULL_OR_WHITESPACE);
        }
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isNullOrWhitespace($value): bool
    {
        if (is_null($value)) {
            return true;
        }

        TypeUtility::assertIsType($value, TypeUtility::TYPE_STRING);

        return $value === '' || ctype_space($value);
    }
}
