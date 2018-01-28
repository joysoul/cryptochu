<?php
namespace cryptochu\services;

use cryptochu\config\contracts\ConfigContract;
use cryptochu\services\contracts\CachingServiceContract;
use Doctrine\Common\Cache\FilesystemCache;

/**
 * FileSystemCachingService caches items on the local file system. By default, all instances of this service share the
 * same directory and could thus be considered identical. This behaviour can be overridden by providing a directory
 * suffix: this makes it possible to have dedicated directories for each instance.
 *
 * @author Emile Pels
 * @package cryptochu\services
 */
class FileSystemCachingService implements CachingServiceContract
{
    /**
     * By default, don't use a suffix - this results in the same directory being used for every cache.
     */
    const DEFAULT_DIRECTORY_SUFFIX = '';

    /**
     * @var FilesystemCache
     */
    private $fileSystemCache;

    /**
     * Directory suffix can be set to create a dedicated directory for this specific caching service.
     *
     * @param ConfigContract $config
     * @param string $directorySuffix
     */
    public function __construct(ConfigContract $config, string $directorySuffix = self::DEFAULT_DIRECTORY_SUFFIX)
    {
        $this->fileSystemCache = new FilesystemCache($config->fileSystemCacheDirectory() . $directorySuffix);
    }

    /**
     * Clears the cache by deleting all items.
     */
    public function clear()
    {
        $this->fileSystemCache->deleteAll();
    }

    /**
     * Deletes the value for the provided key if it is present in the cache.
     *
     * @param string $key
     */
    public function delete(string $key)
    {
        $this->fileSystemCache->delete($key);
    }

    /**
     * If set and not expired, this returns the corresponding value. Otherwise, $default is returned.
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $result = $this->fileSystemCache->fetch($key);

        if ($result === false) {
            return $default;
        } else {
            return $result;
        }
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
        return $this->fileSystemCache->contains($key);
    }

    /**
     * Adds a value to the cache that expires after the provided number of seconds.
     *
     * @param string $key
     * @param $value
     * @param int $expiresAfterSeconds
     */
    public function set(string $key, $value, int $expiresAfterSeconds)
    {
        $this->fileSystemCache->save($key, $value, $expiresAfterSeconds);
    }
}
