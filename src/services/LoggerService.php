<?php
namespace cryptochu\services;

use cryptochu\services\contracts\LoggerServiceContract;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;

/**
 * @author Emile Pels
 * @package cryptochu\services
 */
class LoggerService extends Logger implements LoggerServiceContract
{
    /**
     * Logger constants.
     */
    const DEFAULT_LOGGER_NAME = 'cryptochu';

    /**
     * @param string $name
     */
    public function __construct(string $name = self::DEFAULT_LOGGER_NAME)
    {
        parent::__construct($name);

        $this->addDefaultHandlers();
    }

    /**
     *
     */
    private function addDefaultHandlers()
    {
        foreach ($this->getDefaultHandlers() as $defaultHandler) {
            $this->pushHandler($defaultHandler);
        }
    }

    /**
     * @return HandlerInterface[]
     */
    protected function getDefaultHandlers(): array
    {
        return [
            // No handlers. Logs will be written to stdout.
        ];
    }
}
