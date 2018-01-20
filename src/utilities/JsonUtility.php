<?php
namespace cryptochu\utilities;

use cryptochu\exceptions\ValueException;

/**
 * @author Emile Pels
 * @package cryptochu\utilities
 */
class JsonUtility
{
    /**
     * Error constants.
     */
    const ERROR_CAN_NOT_DECODE_JSON = 'Can not decode JSON.';

    /**
     * Decoding constants.
     */
    const DECODE_TO_ASSOCIATIVE_ARRAY = true;

    /**
     * @param string $json
     *
     * @return array
     * @throws ValueException
     */
    public static function decodeJson(string $json): array
    {
        $jsonDecoded = json_decode($json, self::DECODE_TO_ASSOCIATIVE_ARRAY);

        if ($jsonDecoded === null) {
            throw new ValueException(self::ERROR_CAN_NOT_DECODE_JSON);
        }

        TypeUtility::assertIsType($jsonDecoded, TypeUtility::TYPE_ARRAY);

        return $jsonDecoded;
    }
}
