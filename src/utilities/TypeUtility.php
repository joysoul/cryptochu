<?php
namespace cryptochu\utilities;

use cryptochu\exceptions\TypeException;

/**
 * @author Emile Pels
 * @package cryptochu\utilities\TypeUtility
 */
class TypeUtility
{
    /**
     * Error constants.
     */
    const ERROR_NOT_SUBCLASS_OF = 'Type "%s" is not a subclass of "%s".';
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
