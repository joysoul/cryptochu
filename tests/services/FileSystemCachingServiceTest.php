<?php
namespace cryptochu\services\tests;

use cryptochu\config\ConfigDevelopment;
use cryptochu\config\contracts\ConfigContract;
use cryptochu\services\contracts\CachingServiceContract;
use cryptochu\services\FileSystemCachingService;

/**
 * @author Emile Pels
 * @package cryptochu\services\tests
 */
class FileSystemCachingServiceTest extends CachingServiceTest
{
    /**
     * Cache expires after one second.
     */
    const EXPIRES_AFTER_SECOND = 1;

    /**
     * Mock constants.
     */
    const METHOD_FILE_SYSTEM_CACHE_DIRECTORY = 'fileSystemCacheDirectory';

    /**
     *
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->cachingService->clear();
    }

    /**
     *
     */
    public function testDirectorySuffix()
    {
        $config = $this->getConfigForFileSystemCachingService();

        $left = new FileSystemCachingService($config, '/left');
        $right = new FileSystemCachingService($config, '/right');

        $left->set('foo', 'bar', self::EXPIRES_AFTER_MINUTE);

        static::assertFalse($right->has('foo'));
        static::assertTrue($left->has('foo'));
    }

    /**
     * FileSystemCache only considers expiresAfter values greater than 0.
     */
    public function testExpire()
    {
        static::assertFalse($this->cachingService->has('foo'));

        $this->cachingService->set('foo', 'bar', self::EXPIRES_AFTER_SECOND);

        sleep(self::EXPIRES_AFTER_SECOND + 1);

        static::assertFalse($this->cachingService->has('foo'));

        static::assertNull($this->cachingService->get('foo'));
    }

    /**
     * @return CachingServiceContract
     */
    protected function getCachingService(): CachingServiceContract
    {
        return new FileSystemCachingService($this->getConfigForFileSystemCachingService());
    }

    /**
     * @return ConfigContract
     */
    protected function getConfigForFileSystemCachingService(): ConfigContract
    {
        return new ConfigDevelopment();
    }
}
