<?php
namespace cryptochu\services\tests;

use cryptochu\common\EnumRegularCurrency;
use cryptochu\exceptions\ValueException;
use cryptochu\services\contracts\CachingServiceContract;
use cryptochu\services\MemoryCachingService;
use cryptochu\tests\TestCase;

/**
 * @author Emile Pels
 * @package cryptochu\services\tests
 */
abstract class CachingServiceTest extends TestCase
{
    /**
     * Cache expires after a minute.
     */
    const EXPIRES_AFTER_MINUTE = 60;
    const EXPIRES_NOW = 0;

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

        $this->cachingService = $this->getCachingService();
    }

    /**
     *
     */
    public function testDelete()
    {
        static::assertFalse($this->cachingService->delete('foo'));

        $this->cachingService->set('foo', 'bar', self::EXPIRES_AFTER_MINUTE);

        static::assertNotNull($this->cachingService->get('foo'));

        static::assertTrue($this->cachingService->delete('foo'));

        static::assertNull($this->cachingService->get('foo'));
    }

    /**
     *
     */
    public function testExpire()
    {
        static::assertFalse($this->cachingService->has('foo'));

        $this->cachingService->set('foo', 'bar', self::EXPIRES_NOW);

        static::assertFalse($this->cachingService->has('foo'));

        static::assertNull($this->cachingService->get('foo'));
    }

    /**
     * @param string $key
     * @param $value
     * @param int $expiresAfterSeconds
     * @dataProvider dataProviderGet
     */
    public function testGet(string $key, $value, int $expiresAfterSeconds)
    {
        static::assertFalse($this->cachingService->has($key));

        static::assertNull($this->cachingService->get($key));

        $this->cachingService->set($key, $value, $expiresAfterSeconds);

        static::assertEquals($value, $this->cachingService->get($key));
    }

    /**
     * @return array
     */
    public function dataProviderGet(): array
    {
        return [
            ['foo', 'bar', self::EXPIRES_AFTER_MINUTE],
            ['hello', EnumRegularCurrency::euro(), self::EXPIRES_AFTER_MINUTE],
        ];
    }

    /**
     * @param mixed $default
     * @dataProvider dataProviderGetDefault
     */
    public function testGetDefault($default)
    {
        static::assertFalse($this->cachingService->has('foo'));

        static::assertEquals($default, $this->cachingService->get('foo', $default));
    }

    /**
     * @return array
     */
    public function dataProviderGetDefault(): array
    {
        return [
            [null],
            [''],
            [new ValueException()],
        ];
    }

    /**
     *
     */
    public function testHas()
    {
        static::assertFalse($this->cachingService->has('foo'));

        $this->cachingService->set('foo', 'bar', self::EXPIRES_AFTER_MINUTE);

        static::assertTrue($this->cachingService->has('foo'));
    }

    /**
     *
     */
    public function testSet()
    {
        static::assertFalse($this->cachingService->has('foo'));

        static::assertFalse($this->cachingService->set('foo', 'bar', self::EXPIRES_AFTER_MINUTE));

        static::assertEquals('bar', $this->cachingService->get('foo'));

        static::assertTrue($this->cachingService->set('foo', 'not bar', self::EXPIRES_AFTER_MINUTE));

        static::assertEquals('not bar', $this->cachingService->get('foo'));
    }

    /**
     * @return CachingServiceContract
     */
    abstract protected function getCachingService(): CachingServiceContract;
}
