<?php
namespace cryptochu\exceptions\handlers\contracts;

/**
 * Handles an error.
 *
 * @author Emile Pels
 * @package cryptochu\exceptions\contracts
 */
interface ErrorHandlerContract
{
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
    public function handleError(int $errorSeverity, string $errorString, string $errorFile, int $errorLine): bool;
}
