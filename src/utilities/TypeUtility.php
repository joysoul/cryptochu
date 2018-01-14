<?php
namespace cryptochu\utilities;

use cryptochu\exceptions\TypeException;

/**
 * TypeUtility contains utility methods related to types.
 *
 * @author Emile Pels
 * @package cryptochu\utilities\TypeUtility
 */
class TypeUtility
{
    /**
     * Error constants.
     */
    const ERROR_UNEXPECTED_TYPE = 'Expected type "%s", got "%s".';

    /**
     * Type objects.
     */
    const TYPE_ARRAY = 'array';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_INTEGER = 'integer';
    const TYPE_OBJECT = 'object';
    const TYPE_STRING = 'string';

    /**
     * getType gets the type for the provided value.
     *
     * @param mixed $value
     *
     * @return string
     */
    public static function getType($value): string
    {
        $type = gettype($value);

        if ($type === self::TYPE_OBJECT) {
            return get_class($value);
        } else {
            return $type;
        }
    }

    /**
     * isType indicates whether the provided value has the provided type.
     *
     * @param mixed $value
     * @param string $type
     *
     * @return bool
     */
    public static function isType($value, string $type): bool
    {
        return static::getType($value) === $type;
    }

    /**
     * assertIsType throws a TypeException if the provided value does not have the provided type.
     *
     * @param mixed $value
     * @param string $type
     *
     * @throws TypeException
     */
    public static function assertIsType($value, string $type)
    {
        if (static::isType($value, $type)) {
            // Ok
        } else {
            throw new TypeException(vsprintf(self::ERROR_UNEXPECTED_TYPE, [$type, static::getType($value)]));
        }
    }
}
