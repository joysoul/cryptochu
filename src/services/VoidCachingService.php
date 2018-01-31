<?php
namespace cryptochu\services;

use cryptochu\services\contracts\CachingServiceContract;

/**
 * VoidCachingService is a caching service that does not do any caching. Returns false for all booleans, otherwise null.
 *
 * What? Why? When we don't want to do any caching, we can simply inject this implementation without bothering
 * the consumer with these details. They don't need to know.
 *
 * @author Emile Pels
 * @package cryptochu\services
 */
class VoidCachingService implements CachingServiceContract
{
    /**
     * Does nothing.
     */
    public function clear()
    {
        // Do nothing
    }

    /**
     * Does nothing.
     *
     * @param string $key
     */
    public function delete(string $key)
    {
        // Do nothing
    }

    /**
     * Returns null.
     *
     * @param string $key
     * @param mixed $default
     *
     * @return null
     */
    public function get(string $key, $default = null)
    {
        return null;
    }

    /**
     * Returns false.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return false;
    }

    /**
     * Does nothing.
     *
     * @param string $key
     * @param $value
     * @param int $expiresAfterSeconds
     */
    public function set(string $key, $value, int $expiresAfterSeconds)
    {
        // Do nothing
    }
}
