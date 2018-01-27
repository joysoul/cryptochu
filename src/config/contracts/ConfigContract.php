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
     * User agent that MAY be used during HTTP requests.
     *
     * @return string
     */
    public function httpClientUserAgent(): string;
}
