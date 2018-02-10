<?php
namespace cryptochu\http;

use cryptochu\config\contracts\ConfigContract;
use cryptochu\http\contracts\HttpClient;
use cryptochu\services\contracts\CachingServiceContract;
use cryptochu\services\contracts\LoggingServiceContract;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * @todo Document
 *
 * @author Emile Pels
 * @package cryptochu\http
 */
class GuzzleHttpClient implements HttpClient
{
    /**
     * Option key constants.
     */
    const OPTION_KEY_HEADERS = 'headers';
    const OPTION_KEY_USER_AGENT = 'User-Agent';

    /**
     * HTTP constants.
     */
    const HTTP_METHOD_GET = 'GET';

    /**
     * @var CachingServiceContract
     */
    private $cachingService;

    /**
     * @var ConfigContract
     */
    private $config;

    /**
     * @var LoggingServiceContract
     */
    private $loggingService;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param CachingServiceContract $cachingService
     * @param ConfigContract $config
     * @param LoggingServiceContract $loggingService
     */
    public function __construct(
        CachingServiceContract $cachingService,
        ConfigContract $config,
        LoggingServiceContract $loggingService
    ) {
        $this->cachingService = $cachingService;
        $this->config = $config;
        $this->loggingService = $loggingService;

        $this->client = $this->getClientWithUserAgent($config->httpClientUserAgent());
    }

    /**
     * @param string $httpClientUserAgent
     *
     * @return Client
     */
    protected function getClientWithUserAgent(string $httpClientUserAgent): Client
    {
        return new Client([
            self::OPTION_KEY_HEADERS => [
                self::OPTION_KEY_USER_AGENT => $httpClientUserAgent,
            ]
        ]);
    }

    /**
     * Gets remote content over HTTP.
     *
     * @param string $url
     *
     * @return string
     */
    public function getContent(string $url): string
    {
        if ($this->cachingService->has($url)) {
            $this->logCacheHit($url);

            return $this->cachingService->get($url);
        }

        $response = $this->client->get($url);
        $bodyContents = $response->getBody()->getContents();

        $this->cachingService->set($url, $bodyContents, $this->getCacheExpiresAfterSeconds());

        $this->logRequest(self::HTTP_METHOD_GET, $url, $response);

        return $bodyContents;
    }

    /**
     * @return int
     */
    protected function getCacheExpiresAfterSeconds(): int
    {
        return $this->config->httpClientCacheExpiresAfterSeconds();
    }

    /**
     * @param string $url
     */
    private function logCacheHit(string $url)
    {
        $this->loggingService->info('GuzzleHttpClient ' .  __FUNCTION__, [
            'url' => $url,
        ]);
    }

    /**
     * @param string $method
     * @param string $url
     * @param ResponseInterface $response
     */
    private function logRequest(string $method, string $url, ResponseInterface $response)
    {
        $this->loggingService->info('GuzzleHttpClient ' .  __FUNCTION__, [
            'method' => $method,
            'url' => $url,
            'status' => $response->getStatusCode(),
        ]);
    }
}
