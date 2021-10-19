<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

use PoP\PoP\Config\Rector\Configurators\AbstractContainerConfigurationService;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\DowngradeSetList;

abstract class AbstractDowngradeContainerConfigurationService extends AbstractContainerConfigurationService
{
    public function configureContainer(): void
    {
        $this->containerConfigurator->import(DowngradeSetList::PHP_80);
        $this->containerConfigurator->import(DowngradeSetList::PHP_74);
        $this->containerConfigurator->import(DowngradeSetList::PHP_73);
        /**
         * Replace the current `DowngradeParameterTypeWideningRector` (because it takes too long)
         * with a "legacy" version (from up to v0.10.9), which is fast
         * but does not replace code within traits.
         *
         * To make up, the hack below manually fixes the code within traits.
         *
         * @see https://github.com/leoloso/PoP/issues/715
         */
        $this->containerConfigurator->import(DowngradeSetList::PHP_72);
        // $this->containerConfigurator->import(CustomDowngradeSetList::PHP_72);

        /**
         * Hack to fix bug.
         *
         * DowngradeParameterTypeWideningRector is modifying function `clear` from vendor/symfony/cache/Adapter/AdapterInterface.php:
         *
         * from:
         *     public function clear(string $prefix = '');
         * to:
         *     public function clear($prefix = '');
         *
         * But the same modification is not being done in vendor/symfony/cache/Traits/AbstractAdapterTrait.php
         * So apply this change (and several similar others) manually
         *
         * @see https://github.com/leoloso/PoP/issues/597#issue-855005786
         */
        // $services = $this->containerConfigurator->services();
        // $services->set(AddParamTypeDeclarationInTraitRector::class)
        //     ->call('configure', [[
        //         AddParamTypeDeclarationInTraitRector::PARAMETER_TYPEHINTS => ValueObjectInliner::inline([
        //             new AddParamTypeDeclaration(AbstractAdapterTrait::class, 'clear', 0, new NullType()),
        //             new AddParamTypeDeclaration(ServiceLocatorTrait::class, 'has', 0, new NullType()),
        //             new AddParamTypeDeclaration(ServiceLocatorTrait::class, 'get', 0, new NullType()),
        //             // The type for this param is being removed, add it again
        //             new AddParamTypeDeclaration(CacheTrait::class, 'get', 0, new StringType()),
        //             new AddParamTypeDeclaration(CacheTrait::class, 'get', 2, new NullType()),
        //             new AddParamTypeDeclaration(CacheTrait::class, 'get', 3, new NullType()),
        //         ]),
        //     ]]);

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
