<?php
namespace cryptochu\exceptions;

use cryptochu\services\contracts\LoggingServiceContract;
use ErrorException;
use Throwable;

/**
 * ExceptionHandler is intended to catch all errors and exceptions.
 *
 * @author Emile Pels
 * @package cryptochu\exceptions
 */
class ExceptionHandler
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
     * Simply throws an exception based on the error provided. Will be handled by handleException below.
     *
     * @param int $errorSeverity
     * @param string $errorString
     * @param string $errorFile
     * @param int $errorLine
     *
     * @return bool
     * @throws ErrorException
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
     * @param $exception
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