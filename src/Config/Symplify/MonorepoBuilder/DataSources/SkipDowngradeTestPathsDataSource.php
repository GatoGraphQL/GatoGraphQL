<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class SkipDowngradeTestPathsDataSource
{
    public function __construct(protected string $rootDir)
    {
    }

    /**
     * @return string[]
     */
    public function getSkipDowngradeTestPaths(): array
    {
        return array_merge(
            $this->getSkipDowngradeTestProjectPaths(),
            $this->getSkipDowngradeTestVendorPaths()
        );
    }

    /**
     * @return string[]
     */
    protected function getSkipDowngradeTestProjectPaths(): array
    {
        return [
            'layers/GatoGraphQLForWP/phpunit-packages/',
        ];
    }

    /**
     * @return string[]
     */
    protected function getSkipDowngradeTestVendorPaths(): array
    {
        return [
            // This library is used for testing the source; it is added under "require" so it must be excluded
            'vendor/fakerphp/faker/',
            'vendor/michelf/php-markdown/test/',
            /**
             * Gato GraphQL plugin: This code is commented out as it is
             * not needed anymore, because package `brain/cortex`
             * is not loaded
             *
             * @see layers/Engine/packages/root-wp/src/Module.php
             */            
            // 'vendor/nikic/fast-route/test/',
            'vendor/psr/log/Psr/Log/Test/',
            'vendor/symfony/cache/DataCollector/CacheDataCollector.php',
            'vendor/symfony/cache/DoctrineProvider.php',
            'vendor/symfony/cache/Messenger/EarlyExpirationHandler.php',
            'vendor/symfony/cache/Traits/Redis6Proxy.php',
            'vendor/symfony/cache/Traits/RedisCluster6Proxy.php',
            'vendor/symfony/dom-crawler/Test/',
            'vendor/symfony/dotenv/Command/DebugCommand.php',
            'vendor/symfony/dotenv/Command/DotenvDumpCommand.php',
            'vendor/symfony/http-foundation/Test/',
            'vendor/symfony/polyfill-ctype/bootstrap80.php',
            'vendor/symfony/polyfill-intl-grapheme/bootstrap80.php',
            'vendor/symfony/polyfill-intl-idn/bootstrap80.php',
            'vendor/symfony/polyfill-intl-normalizer/bootstrap80.php',
            'vendor/symfony/polyfill-mbstring/bootstrap80.php',
            'vendor/symfony/polyfill-php83/bootstrap81.php',
            'vendor/symfony/service-contracts/Test/',
            'vendor/symfony/string/Slugger/AsciiSlugger.php',
        ];
    }
}
