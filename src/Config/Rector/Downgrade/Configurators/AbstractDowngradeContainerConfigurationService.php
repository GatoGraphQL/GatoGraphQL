<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

use DateTimeInterface;
use GraphQLAPI\GraphQLAPI\Polyfill\PHP72\DateTimeInterface as PolyfillDateTimeInterface;
use PoP\PoP\Config\Rector\Configurators\AbstractContainerConfigurationService;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Rector\Set\ValueObject\DowngradeLevelSetList;

abstract class AbstractDowngradeContainerConfigurationService extends AbstractContainerConfigurationService
{
    public function configureContainer(): void
    {
        $this->containerConfigurator->import(DowngradeLevelSetList::DOWN_TO_PHP_71);

        // Must also replace DateTimeInterface::ATOM for PHP 7.1
        $services = $this->containerConfigurator->services();
        $services->set(RenameClassConstFetchRector::class)
            ->configure([new RenameClassAndConstFetch(DateTimeInterface::class, 'ATOM', PolyfillDateTimeInterface::class, 'ATOM')]);

        $parameters = $this->containerConfigurator->parameters();

        // is your PHP version different from the one your refactor to? [default: your PHP version]
        $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_71);

        // Do not change the code, other than the required rules
        $parameters->set(Option::AUTO_IMPORT_NAMES, false);
        $parameters->set(Option::IMPORT_SHORT_CLASSES, false);

        // Rector relies on autoload setup of your project; Composer autoload is included by default; to add more:
        if ($bootstrapFiles = $this->getBootstrapFiles()) {
            $parameters->set(Option::BOOTSTRAP_FILES, $bootstrapFiles);
        }

        // files to skip downgrading
        if ($skip = $this->getSkip()) {
            $parameters->set(Option::SKIP, $skip);
        }
    }

    /**
     * @return string[]
     */
    protected function getBootstrapFiles(): array
    {
        return [
            /**
             * This file has been commented since it doesn't work with Rector v0.12,
             * due to having this code:
             *
             *   function readonly($readonly, $current = \true, $echo = \true)
             *   {
             *   }
             *
             * Instead use temporary custom stubs file, which has the required stubs only
             */
            // $this->rootDirectory . '/vendor/php-stubs/wordpress-stubs/wordpress-stubs.php',
            $this->rootDirectory . '/stubs/php-stubs/wordpress-stubs/wordpress-stubs.php',
        ];
    }

    /**
     * @return string[]
     */
    protected function getSkip(): array
    {
        return [
            // Skip tests
            '*/tests/*',
            '*/test/*',
            '*/Test/*',
        ];
    }
}
