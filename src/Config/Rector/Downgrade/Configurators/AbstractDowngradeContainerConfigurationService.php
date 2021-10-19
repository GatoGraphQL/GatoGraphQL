<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

use PoP\PoP\Config\Rector\Configurators\AbstractContainerConfigurationService;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\Set\ValueObject\DowngradeSetList;

abstract class AbstractDowngradeContainerConfigurationService extends AbstractContainerConfigurationService
{
    public function configureContainer(): void
    {
        /**
         * This line produces a bug, executing `DowngradeParameterTypeWideningRector`
         * when it should not.
         *
         * @todo Check if it is fixed in Rector v0.11.60, then revert this code
         *
         * @see https://github.com/leoloso/PoP/pull/1181
         */
        // $this->containerConfigurator->import(DowngradeLevelSetList::DOWN_TO_PHP_71);
        $this->containerConfigurator->import(DowngradeSetList::PHP_80);
        $this->containerConfigurator->import(DowngradeSetList::PHP_74);
        $this->containerConfigurator->import(DowngradeSetList::PHP_73);
        $this->containerConfigurator->import(DowngradeSetList::PHP_72);

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
