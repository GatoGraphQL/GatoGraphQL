<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

// use DateTimeInterface;
// use PoPSchema\SchemaCommons\Polyfill\PHP72\DateTimeInterface as PolyfillDateTimeInterface;
// use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
// use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use PoP\PoP\Config\Rector\Configurators\AbstractContainerConfigurationService;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\DowngradeLevelSetList;

abstract class AbstractDowngradeContainerConfigurationService extends AbstractContainerConfigurationService
{
    public function configureContainer(): void
    {
        $this->containerConfigurator->import(DowngradeLevelSetList::DOWN_TO_PHP_71);

        /**
         * @todo Uncomment this code
         * Currently it doesn't work, maybe because `RenameClassConstFetchRector`
         * doesn't handle interfaces, so it doesn't replace `DateTimeInterface`
         * Solution: Create a similar rule
         */
        // // Must also replace DateTimeInterface::ATOM for PHP 7.1
        // $services = $this->containerConfigurator->services();
        // $services->set(RenameClassConstFetchRector::class)
        //     ->configure([new RenameClassAndConstFetch(DateTimeInterface::class, 'ATOM', PolyfillDateTimeInterface::class, 'ATOM')]);

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
            $this->rootDirectory . '/vendor/php-stubs/wordpress-stubs/wordpress-stubs.php',
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
