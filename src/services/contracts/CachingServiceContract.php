<?php
namespace cryptochu\services\contracts;

/**
 * CachingServiceContract should be implemented by services that provide caching features.
 * Because this fulfills current use cases, it is kept very concise.
 *
 * PSR-6 is ignored for reasons and problems outlined in this post:
 * http://andrew.carterlunn.co.uk/programming/2015/12/07/psr-6-has-serious-problems.html
 *
 * This does however not mean we can not use PSR-6 compliant caching libraries/backends:
 * writing a simple adapter suffices in those cases.
 *
 * @author Emile Pels
 * @package cryptochu\services\contracts
 */
interface CachingServiceContract
{
    /**
     * Deletes the value for the provided key if it is present in the cache.
     * Return value indicates whether a delete actually happened.
     *
     * @param string $key
     *
     * @return bool
     */
    public function delete(string $key): bool;

    /**
     * If has() is true, this returns the corresponding value. Otherwise, $default is returned.
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Returns true only if we have a value for the provided key that has not expired.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;

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
    public function set(string $key, $value, int $expiresAfterSeconds): bool;
}
