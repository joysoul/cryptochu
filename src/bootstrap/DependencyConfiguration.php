<?php
namespace cryptochu\bootstrap;

use cryptochu\config\ConfigDevelopment;
use cryptochu\config\contracts\ConfigContract;
use cryptochu\crypto\services\BinancePriceService;
use cryptochu\crypto\services\BitfinexPriceService;
use cryptochu\crypto\services\BitstampPriceService;
use cryptochu\http\contracts\HttpClient;
use cryptochu\http\GuzzleHttpClient;
use cryptochu\services\contracts\CachingServiceContract;
use cryptochu\services\contracts\LoggingServiceContract;
use cryptochu\services\FileSystemCachingService;
use cryptochu\services\MonologLoggingService;
use DI\Container;
use DI\ContainerBuilder;
use DI\Definition\Helper\ObjectDefinitionHelper;
use function DI\object;

/**
 * @author Emile Pels
 * @package cryptochu\bootstrap
 */
class DependencyConfiguration
{
    /**
     * @return Container
     */
    public static function getContainer(): Container
    {
        static $container = null;

        if (is_null($container)) {
            $builder = new ContainerBuilder();
            $builder->addDefinitions(static::getDefinitions());

            $container = $builder->build();
        }

        return $container;
    }

    /**
     * @return ObjectDefinitionHelper[]
     */
    public static function getDefinitions(): array
    {
        return [
            // Binance
            BinancePriceService::class => object(BinancePriceService::class)->lazy(),

            // Bitfinex
            BitfinexPriceService::class => object(BitfinexPriceService::class)->lazy(),

            // Bitstamp
            BitstampPriceService::class => object(BitstampPriceService::class)->lazy(),

            // Caching service
            CachingServiceContract::class => object(FileSystemCachingService::class)->lazy(),

            // Config
            ConfigContract::class => object(ConfigDevelopment::class)->lazy(),

            // HTTP client
            HttpClient::class => object(GuzzleHttpClient::class)->lazy(),

            // Logging service
            LoggingServiceContract::class => object(MonologLoggingService::class)->lazy(),
        ];
    }
}
