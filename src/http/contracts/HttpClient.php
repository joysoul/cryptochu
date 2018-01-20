<?php
namespace cryptochu\http\contracts;

/**
 * HttpClient is a simple abstraction for fetching remote content over HTTP.
 *
 * @author Emile Pels
 * @package cryptochu\http\contracts
 */
interface HttpClient
{
    /**
     * Gets remote content over HTTP.
     *
     * @param string $url
     *
     * @return string
     */
    public function getContent(string $url): string;
}