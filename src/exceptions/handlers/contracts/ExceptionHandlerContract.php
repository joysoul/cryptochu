<?php
namespace cryptochu\exceptions\handlers\contracts;

use Throwable;

/**
 * Handles an exception.
 *
 * @author Emile Pels
 * @package cryptochu\exceptions\contracts
 */
interface ExceptionHandlerContract
{
    /**
     * Handles an exception. Compliant with the callback expected by PHP's set_exception_handler.
     * See also: http://php.net/manual/en/function.set-exception-handler.php
     *
     * @param Throwable $exception
     */
    public function handleException(Throwable $exception);
}
