<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

class MonorepoContainerConfigurationService extends AbstractContainerConfigurationService
{
    public function __construct(
        protected ContainerConfigurator $containerConfigurator,
        protected string $rootDirectory,
    ) {
    }
    
    public function applyCustomConfiguration(): void
    {
        $monorepoDir = $this->rootDirectory;

        // get parameters
        $parameters = $this->containerConfigurator->parameters();

        // // Rector relies on autoload setup of your project; Composer autoload is included by default; to add more:
        // $parameters->set(Option::BOOTSTRAP_FILES, [
        //     // full directory
        //     $monorepoDir . '/vendor/php-stubs/wordpress-stubs/wordpress-stubs.php',
        // ]);

        // files to skip downgrading
        $parameters->set(Option::SKIP, [
            // Skip tests
            '*/tests/*',
            '*/test/*',
            '*/Test/*',

            // Ignore downgrading the monorepo source
            $monorepoDir . '/src/*',

            // Even when downgrading all packages, skip Symfony's polyfills
            $monorepoDir . '/vendor/symfony/polyfill-*',

            // Skip since they are not needed and they fail
            $monorepoDir . '/vendor/composer/*',
            $monorepoDir . '/vendor/lkwdwrd/wp-muplugin-loader/*',

            // Ignore errors from classes we don't have in our environment,
            // or that come from referencing a class present in DEV, not PROD
            $monorepoDir . '/vendor/symfony/cache/Adapter/MemcachedAdapter.php',
            $monorepoDir . '/vendor/symfony/cache/DoctrineProvider.php',
            $monorepoDir . '/vendor/symfony/cache/Messenger/EarlyExpirationHandler.php',
            $monorepoDir . '/vendor/symfony/string/Slugger/AsciiSlugger.php',
        ]);
    }
}
