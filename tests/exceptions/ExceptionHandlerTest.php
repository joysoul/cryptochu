<?php
namespace cryptochu\exceptions\tests;

use cryptochu\exceptions\ExceptionHandler;
use cryptochu\tests\TestCase;
use Exception;

/**
 * @author Emile Pels
 * @package cryptochu\exceptions\tests
 */
class ExceptionHandlerTest extends TestCase
{
    /**
     * Mock constants.
     */
    const METHOD_LOGGER_SERVICE_CRITICAL = 'critical';

    /**
     * @var ExceptionHandler
     */
    private $exceptionHandler;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();

        $this->exceptionHandler = new ExceptionHandler(
            static::getMockLoggerServiceThatIsCalled(static::once(), self::METHOD_LOGGER_SERVICE_CRITICAL)
        );
    }

    /**
     *
     */
    public function testError()
    {
        static::assertTrue($this->exceptionHandler->handleError(0, '', '', 0));
    }

    /**
     *
     */
    public function testException()
    {
        $this->exceptionHandler->handleException(new Exception());
    }
}
