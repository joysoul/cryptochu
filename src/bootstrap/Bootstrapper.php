<?php
namespace cryptochu\bootstrap;

use cryptochu\exceptions\ExceptionHandler;
use cryptochu\services\LoggingService;

/**
 * Bootstraps the application to get it into an executable state.
 *
 * @author Emile Pels
 * @package cryptochu\bootstrap
 */
class Bootstrapper
{
    /**
     * Bootstrap the application to get it into an executable state.
     */
    public function bootstrap()
    {
        $this->enableAutoloader();

        $this->enableGlobalExceptionHandler();
    }

    /**
     *
     */
    protected function enableAutoloader()
    {
        require_once(__DIR__ . '/../../cryptochu_autoload.php');
    }

    /**
     *
     */
    protected function enableGlobalExceptionHandler()
    {
        // @todo 20180203 emilepels See if we can inject the LoggingService somehow.
        // when we introduce a DIC, it is likely we'll bootstrap it in this class too.
        // This means this class will already be coupled to it, so we may as well get a logger from it then...
        $handler = new ExceptionHandler(new LoggingService());

        set_error_handler([$handler, 'handleError']);
        set_exception_handler([$handler, 'handleException']);
    }
}

(new Bootstrapper())->bootstrap();
