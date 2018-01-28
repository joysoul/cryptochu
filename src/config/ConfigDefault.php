<?php
namespace cryptochu\config;

use cryptochu\config\contracts\ConfigContract;

/**
 * ConfigDefault provides defaults for the configuration settings.
 * It is abstract because this is not intended to be used directly: instead, create environment specific subclasses
 * (e.g. ConfigProduction). The options below are just values that are in many cases likely to be suitable, but should
 * not be used without consideration.
 *
 * @author Emile Pels
 * @package cryptochu\config
 */
abstract class ConfigDefault implements ConfigContract
{
    /**
     * Name of the app.
     *
     * @return string
     */
    public function appName(): string
    {
        return 'cryptochu';
    }
}
