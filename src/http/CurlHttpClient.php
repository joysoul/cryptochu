<?php
namespace cryptochu\http;

use cryptochu\config\contracts\ConfigContract;
use cryptochu\exceptions\HttpException;
use cryptochu\http\contracts\HttpClient;
use cryptochu\services\contracts\LoggingServiceContract;
use cryptochu\utilities\TypeUtility;

/**
 * CurlHttpClient is a simple wrapper around cURL that fetches remote HTTP content.
 *
 * @author Emile Pels
 * @package cryptochu\http
 */
class CurlHttpClient implements HttpClient
{
    /**
     * Error constants.
     */
    const ERROR_CURL_FAILED = 'cURL request failed with error: "%s"';

    /**
     * cURL option constants.
     */
    const CURL_OPTION_RETURN_TRANSFER = true;

    /**
     * HTTP method  constants.
     */
    const HTTP_METHOD_GET = 'GET';

    /**
     * @var ConfigContract
     */
    private $config;

    /**
     * @var LoggingServiceContract
     */
    private $loggingService;

    /**
     * @param ConfigContract $config
     * @param LoggingServiceContract $loggingService
     */
    public function __construct(ConfigContract $config, LoggingServiceContract $loggingService)
    {
        $this->config = $config;
        $this->loggingService = $loggingService;
    }

    /**
     * Gets remote content over HTTP.
     *
     * @param string $url
     *
     * @return string
     * @throws HttpException
     */
    public function getContent(string $url): string
    {
        $curlHandle = curl_init($url);

        // Return the response rather than outputting to STDOUT.
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, self::CURL_OPTION_RETURN_TRANSFER);
        curl_setopt($curlHandle, CURLOPT_USERAGENT, $this->getUserAgent());

        $result = curl_exec($curlHandle);

        $this->assertCurlResultValid($result, $curlHandle);

        $this->logRequest(self::HTTP_METHOD_GET, $url, curl_getinfo($curlHandle, CURLINFO_HTTP_CODE));

        return $result;
    }

    /**
     * Gets the user agent to use for HTTP requests.
     *
     * @return string
     */
    protected function getUserAgent(): string
    {
        return $this->config->httpClientUserAgent();
    }

    /**
     * @param mixed $result
     * @param resource $curlHandle
     *
     * @throws HttpException
     */
    protected function assertCurlResultValid($result, $curlHandle)
    {
        if ($result === false) {
            throw new HttpException(vsprintf(self::ERROR_CURL_FAILED, [curl_error($curlHandle)]));
        }

        TypeUtility::assertIsType($result, TypeUtility::TYPE_STRING);
    }

    /**
     * @param string $method
     * @param string $url
     * @param int $statusCode
     */
    protected function logRequest(string $method, string $url, int $statusCode)
    {
        $this->loggingService->info('HTTP request', [
            'method' => $method,
            'url' => $url,
            'statusCode' => $statusCode,
        ]);
    }
}
