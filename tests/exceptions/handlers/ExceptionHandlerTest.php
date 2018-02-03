<?php
namespace cryptochu\exceptions\handlers\tests;

use cryptochu\exceptions\handlers\GlobalHandler;
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
     * @var GlobalHandler
     */
    private $exceptionHandler;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();

        $this->exceptionHandler = new GlobalHandler(
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
