<?php
namespace cryptochu\http;

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
    const CURL_OPTION_USER_AGENT = 'cryptochu/0.1'; // @todo 20180120 emilepels Extract this to config file?

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
        curl_setopt($curlHandle, CURLOPT_USERAGENT, self::CURL_OPTION_USER_AGENT);

        $result = curl_exec($curlHandle);

        $this->assertCurlResultValid($result);

        return $result;
    }

    /**
     * @param mixed $result
     *
     * @throws HttpException
     */
    private function assertCurlResultValid($result)
    {
        if ($result === false) {
            throw new HttpException(vsprintf(self::ERROR_CURL_FAILED, [curl_error($curlHandle)]));
        }

        TypeUtility::assertIsType($result, TypeUtility::TYPE_STRING);
    }
}
