<?php

declare(strict_types=1);

use PHPStan\Type\NullType;
use PHPStan\Type\StringType;
use PoP\PoP\Extensions\Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationInTraitRector;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\DowngradeSetList;
use Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration;
use Symfony\Component\Cache\Traits\AbstractAdapterTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Contracts\Cache\CacheTrait;
use Symfony\Contracts\Service\ServiceLocatorTrait;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

function doCommonContainerConfiguration(ContainerConfigurator $containerConfigurator): void
{
    $containerConfigurator->import(DowngradeSetList::PHP_80);
    $containerConfigurator->import(DowngradeSetList::PHP_74);
    $containerConfigurator->import(DowngradeSetList::PHP_73);
    /**
     * We can't run `DowngradeParameterTypeWideningRector` because it takes too long.
     * So execute all rules from the downgrade set, skipping this rule.
     * 
     * @see https://github.com/leoloso/PoP/issues/715
     */
    // $containerConfigurator->import(DowngradeSetList::PHP_72);
    $services = $containerConfigurator->services();
    $services->set(DowngradeObjectTypeDeclarationRector::class);
    // $services->set(DowngradeParameterTypeWideningRector::class);
    $services->set(DowngradePregUnmatchedAsNullConstantRector::class);
    $services->set(DowngradeStreamIsattyRector::class);
    
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
    $services->set(AddParamTypeDeclarationInTraitRector::class)
        ->call('configure', [[
            AddParamTypeDeclarationInTraitRector::PARAMETER_TYPEHINTS => ValueObjectInliner::inline([
                new AddParamTypeDeclaration(AbstractAdapterTrait::class, 'clear', 0, new NullType()),
                new AddParamTypeDeclaration(ServiceLocatorTrait::class, 'has', 0, new NullType()),
                new AddParamTypeDeclaration(ServiceLocatorTrait::class, 'get', 0, new NullType()),
                // The type for this param is being removed, add it again
                new AddParamTypeDeclaration(CacheTrait::class, 'get', 0, new StringType()),
                new AddParamTypeDeclaration(CacheTrait::class, 'get', 2, new NullType()),
                new AddParamTypeDeclaration(CacheTrait::class, 'get', 3, new NullType()),
            ]),
        ]]);

    $parameters = $containerConfigurator->parameters();
    
    // is your PHP version different from the one your refactor to? [default: your PHP version]
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_71);

    // Do not change the code, other than the required rules
    $parameters->set(Option::AUTO_IMPORT_NAMES, false);
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);

    $monorepoDir = dirname(__DIR__, 2);

    // Rector relies on autoload setup of your project; Composer autoload is included by default; to add more:
    $parameters->set(Option::BOOTSTRAP_FILES, [
        $monorepoDir . '/vendor/php-stubs/wordpress-stubs/wordpress-stubs.php',
    ]);
};
