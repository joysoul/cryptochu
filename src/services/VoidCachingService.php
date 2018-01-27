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
     * Deletes the value for the provided key if it is present in the cache.
     * Return value indicates whether a delete actually happened.
     *
     * @param string $key
     *
     * @return bool
     */
    public function delete(string $key): bool
    {
        return false;
    }

    /**
     * If has() is true, this returns the corresponding value. Otherwise, $default is returned.
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return null;
    }

    /**
     * Returns true only if we have a value for the provided key that has not expired.
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
     * Adds a value to the cache that expires after the provided number of seconds.
     * Return value indicates whether the cache already had a value for this key, in which case an override happens.
     *
     * @param string $key
     * @param $value
     * @param int $expiresAfterSeconds
     *
     * @return bool
     */
    public function set(string $key, $value, int $expiresAfterSeconds): bool
    {
        return false;
    }
}
