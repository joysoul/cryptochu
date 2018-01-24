<?php
namespace cryptochu\tests;

use cryptochu\http\contracts\HttpClient;
use cryptochu\services\contracts\LoggerServiceContract;
use PHPUnit\Framework\MockObject\Matcher\Invocation;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * @author Emile Pels
 * @package cryptochu
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * Mock constants.
     */
    const METHOD_HTTP_CLIENT_GET_CONTENT = 'getContent';
    const METHOD_LOGGER_SERVICE_INFO = 'info';

    /**
     * Gets a HttpClient mock that returns the content provided.
     *
     * @param string $content
     *
     * @return HttpClient
     */
    protected function getHttpClientThatReturnsContent(string $content): HttpClient
    {
        $httpClient = static::createMock(HttpClient::class);
        $httpClient->method(self::METHOD_HTTP_CLIENT_GET_CONTENT)->willReturn($content);

        /** @var HttpClient $httpClient */
        return $httpClient;
    }

    /**
     * Gets a LoggerService mock that registers the provided invocation expectation.
     *
     * @param Invocation $matcher
     * @param string $method
     *
     * @return LoggerServiceContract
     */
    protected function getMockLoggerServiceThatIsCalled(
        Invocation $matcher,
        string $method = self::METHOD_LOGGER_SERVICE_INFO
    ): LoggerServiceContract {
        $mockLoggerService = static::createMock(LoggerServiceContract::class);
        $mockLoggerService->expects($matcher)->method($method);

        /** @var LoggerServiceContract $mockLoggerService */
        return $mockLoggerService;
    }
}
