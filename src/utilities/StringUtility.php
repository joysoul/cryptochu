<?php
namespace cryptochu\utilities;

use cryptochu\exceptions\ValueException;

/**
 * StringUtility contains utility methods related to strings.
 *
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
     * isNullOrWhitespace indicates whether the provided value is null, empty
     * or only contains whitespace characters like spaces or tabs.
     *
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

    /**
     * assertNotNullOrWhitespace throws a ValueException if the provided value is null, empty
     * or only contains whitespace characters like spaces or tabs.
     *
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
}
