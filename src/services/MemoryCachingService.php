<?php
namespace cryptochu\services;

use Cache\Adapter\PHPArray\ArrayCachePool;
use cryptochu\services\contracts\CachingServiceContract;

/**
 * MemoryCachingService caches CacheItemInterface instances in an in memory store (array).
 *
 * @author Emile Pels
 * @package cryptochu\services
 */
class MemoryCachingService implements CachingServiceContract
{
    /**
     * @var ArrayCachePool
     */
    private $arrayCachePool;

    /**
     *
     */
    public function __construct()
    {
        $this->arrayCachePool = new ArrayCachePool();
    }


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
        $has = $this->has($key);

        $this->arrayCachePool->delete($key);

        return $has;
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
        return $this->arrayCachePool->get($key, $default);
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
        return $this->arrayCachePool->has($key);
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
        $has = $this->has($key);

        $this->arrayCachePool->set($key, $value, $expiresAfterSeconds);

        return $has;
    }
}
