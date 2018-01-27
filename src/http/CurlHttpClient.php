<?php
namespace cryptochu\http;

use cryptochu\config\contracts\ConfigContract;
use cryptochu\exceptions\HttpException;
use cryptochu\http\contracts\HttpClient;
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
     * @var ConfigContract
     */
    private $config;

    /**
     * @param ConfigContract $config
     */
    public function __construct(ConfigContract $config)
    {
        $this->config = $config;
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
    private function assertCurlResultValid($result, $curlHandle)
    {
        if ($result === false) {
            throw new HttpException(vsprintf(self::ERROR_CURL_FAILED, [curl_error($curlHandle)]));
        }

        TypeUtility::assertIsType($result, TypeUtility::TYPE_STRING);
    }
}
