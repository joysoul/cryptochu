<?php
namespace cryptochu\tests;

use cryptochu\http\contracts\HttpClient;

/**
 * @author Emile Pels
 * @package cryptochu\tests
 */
abstract class HttpTestCase extends TestCase
{
    /**
     * Mock constants.
     */
    const METHOD_GET_CONTENT = 'getContent';

    /**
     * @param string $content
     *
     * @return HttpClient
     */
    protected function getHttpClientThatReturnsContent(string $content): HttpClient
    {
        $httpClient = static::createMock(HttpClient::class);
        $httpClient->method(self::METHOD_GET_CONTENT)->willReturn($content);

        /** @var HttpClient $httpClient */
        return $httpClient;
    }
}
