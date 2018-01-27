<?php
namespace cryptochu\services\tests;

use cryptochu\services\contracts\CachingServiceContract;
use cryptochu\services\MemoryCachingService;

/**
 * @author Emile Pels
 * @package cryptochu\services\tests
 */
class MemoryCachingServiceTest extends CachingServiceTest
{
    /**
     * @return CachingServiceContract
     */
    protected function getCachingService(): CachingServiceContract
    {
        return new MemoryCachingService();
    }
}
