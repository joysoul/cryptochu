<?php
namespace cryptochu\config\contracts;

/**
 * @author Emile Pels
 * @package cryptochu\config\contracts
 */
interface ConfigContract
{
    /**
     * Name of the app.
     *
     * @return string
     */
    public function appName(): string;

    /**
     * Directory to use for caching on the file system.
     *
     * @return string
     */
    public function fileSystemCacheDirectory(): string;

    /**
     * Number of seconds the HttpClient's cache is valid for.
     *
     * @return int
     */
    public function httpClientCacheExpiresAfterSeconds(): int;

    /**
     * User agent that MAY be used during HTTP requests.
     *
     * @return string
     */
    public function httpClientUserAgent(): string;
}
