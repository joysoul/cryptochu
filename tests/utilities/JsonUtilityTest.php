<?php
namespace cryptochu\utilities\tests;

use cryptochu\exceptions\ValueException;
use cryptochu\tests\TestCase;
use cryptochu\utilities\JsonUtility;

/**
 * @author Emile Pels
 * @package cryptochu\utilities\tests
 */
class JsonUtilityTest extends TestCase
{
    /**
     * @param array $expected
     * @param string $json
     * @dataProvider dataProviderDecodeJson
     */
    public function testDecodeJson(array $expected, string $json)
    {
        static::assertEquals($expected, JsonUtility::decodeJson($json));
    }

    /**
     * @return array
     */
    public function dataProviderDecodeJson(): array
    {
        return [
            [['foo' => 'bar'], '{"foo":"bar"}'],
            [['foo' => 'bar', 'hello' => 'world'], '{"hello":"world","foo":"bar"}'],
            [['foo' => [0 => ['bar' => true]]], '{"foo":[{"bar":true}]}'],
        ];
    }

    /**
     *
     */
    public function testDecodeJsonInvalid()
    {
        static::expectException(ValueException::class);

        JsonUtility::decodeJson('clearly not valid JSON');
    }
}
