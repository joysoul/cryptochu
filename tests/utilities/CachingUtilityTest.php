<?php
namespace cryptochu\utilities\tests;

use cryptochu\config\ConfigDevelopment;
use cryptochu\config\contracts\ConfigContract;
use cryptochu\services\contracts\CachingServiceContract;
use cryptochu\services\FileSystemCachingService;
use cryptochu\tests\TestCase;
use cryptochu\utilities\CachingUtility;

/**
 * @author Emile Pels
 * @package cryptochu\utilities\tests
 */
class CachingUtilityTest extends TestCase
{
    /**
     * Cache expires after a minute.
     */
    const EXPIRES_AFTER_MINUTE = 60;

    /**
     * @var CachingServiceContract
     */
    private $cachingService;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();

        $this->cachingService = new FileSystemCachingService($this->getConfigForFileSystemCachingService());
    }

    /**
     *
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->cachingService->clear();
    }

    /**
     * @return ConfigContract
     */
    protected function getConfigForFileSystemCachingService(): ConfigContract
    {
        return new ConfigDevelopment();
    }

    /**
     *
     */
    public function testGetOrDefaultFunctionWithoutArguments()
    {
        static::assertFalse($this->cachingService->has('foo'));

        CachingUtility::getOrDefaultFunction(
            $this->cachingService,
            'foo',
            self::EXPIRES_AFTER_MINUTE,
            function () { return 'bar'; }
        );

        static::assertEquals('bar', $this->cachingService->get('foo'));
    }

    /**
     *
     */
    public function testGetOrDefaultFunctionWithArguments()
    {
        static::assertFalse($this->cachingService->has('foo'));

        CachingUtility::getOrDefaultFunction(
            $this->cachingService,
            'foo',
            self::EXPIRES_AFTER_MINUTE,
            function ($value) { return $value; },
            ['baz']
        );

        static::assertEquals('baz', $this->cachingService->get('foo'));
    }
}
