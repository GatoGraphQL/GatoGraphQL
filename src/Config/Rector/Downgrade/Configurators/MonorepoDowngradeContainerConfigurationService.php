<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

class MonorepoDowngradeContainerConfigurationService extends AbstractDowngradeContainerConfigurationService
{
    /**
     * @return string[]
     */
    protected function getSkip(): array
    {
        return array_merge(
            parent::getSkip(),
            [
                // Ignore downgrading the monorepo source
                $this->rootDirectory . '/src/*',

                // Ignore downgrading the testing packages
                $this->rootDirectory . '/layers/GraphQLAPIForWP/phpunit-packages/*',

                // Even when downgrading all packages, skip Symfony's polyfills
                $this->rootDirectory . '/vendor/symfony/polyfill-*',

                // Skip since they are not needed and they fail
                $this->rootDirectory . '/vendor/composer/*',
                $this->rootDirectory . '/vendor/boxuk/wp-muplugin-loader/*',

                // Ignore errors from classes we don't have in our environment,
                // or that come from referencing a class present in DEV, not PROD
                // $this->rootDirectory . '/vendor/symfony/cache/Adapter/MemcachedAdapter.php',
                $this->rootDirectory . '/vendor/symfony/cache/DataCollector/CacheDataCollector.php',
                $this->rootDirectory . '/vendor/symfony/cache/DoctrineProvider.php',
                $this->rootDirectory . '/vendor/symfony/cache/Messenger/EarlyExpirationHandler.php',
                $this->rootDirectory . '/vendor/symfony/dotenv/Command/DebugCommand.php',
                $this->rootDirectory . '/vendor/symfony/dotenv/Command/DotenvDumpCommand.php',
                $this->rootDirectory . '/vendor/symfony/string/Slugger/AsciiSlugger.php',
            ]
        );
    }
}
