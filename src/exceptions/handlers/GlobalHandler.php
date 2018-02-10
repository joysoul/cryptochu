<?php
namespace cryptochu\exceptions\handlers;

use cryptochu\exceptions\handlers\contracts\ErrorHandlerContract;
use cryptochu\exceptions\handlers\contracts\ExceptionHandlerContract;
use cryptochu\services\contracts\LoggingServiceContract;
use Throwable;

/**
 * ExceptionHandler is intended to catch all errors and exceptions.
 *
 * @author Emile Pels
 * @package cryptochu\exceptions
 */
class GlobalHandler implements ErrorHandlerContract, ExceptionHandlerContract
{
    /**
     * Signal the normal error handler should not continue after handleError.
     */
    const STOP_ERROR_HANDLER = true;

    /**
     * @var LoggingServiceContract
     */
    private $loggingService;

    /**
     * @param LoggingServiceContract $loggingService
     */
    public function __construct(LoggingServiceContract $loggingService)
    {
        $this->loggingService = $loggingService;
    }

    /**
     * Handles an error. Compliant with the callback expected by PHP's set_error_handler.
     * See also: http://php.net/manual/en/function.set-error-handler.php
     *
     * @param int $errorSeverity
     * @param string $errorString
     * @param string $errorFile
     * @param int $errorLine
     *
     * @return bool
     */
    public function handleError(int $errorSeverity, string $errorString, string $errorFile, int $errorLine): bool
    {
        $this->loggingService->critical('Uncaught error', [
            'method' => __METHOD__,
            'severity' => $errorSeverity,
            'message' => $errorString,
            'file' => $errorFile,
            'line' => $errorLine,
        ]);

        // Signals we have handled the error and it should not continue.
        // Omitting this will eventually create an ErrorException and end up at handleException.
        return true;
    }

    /**
     * Handles an exception. Compliant with the callback expected by PHP's set_exception_handler.
     * See also: http://php.net/manual/en/function.set-exception-handler.php
     *
     * @param Throwable $exception
     */
    public function handleException(Throwable $exception)
    {
        $this->loggingService->critical('Uncaught exception', [
            'method' => __METHOD__,
            'type' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}