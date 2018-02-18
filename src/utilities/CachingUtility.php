<?php
namespace cryptochu\utilities;

use cryptochu\services\contracts\CachingServiceContract;

/**
 * @author Emile Pels
 * @package cryptochu\utilities
 */
class CachingUtility
{
    /**
     * @param CachingServiceContract $cachingService
     * @param string $key
     * @param int $expiresAfterSeconds
     * @param callable $defaultFunction
     * @param array $arguments
     *
     * @return mixed
     */
    public static function getOrDefaultFunction(
        CachingServiceContract $cachingService,
        string $key,
        int $expiresAfterSeconds,
        callable $defaultFunction,
        array $arguments = []
    ) {
        if ($cachingService->has($key)) {
            return $cachingService->get($key);
        } else {
            $result = call_user_func_array($defaultFunction, $arguments);

            $cachingService->set($key, $result, $expiresAfterSeconds);

            return $result;
        }
    }
}
