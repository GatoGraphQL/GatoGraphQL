<?php

declare(strict_types=1);

use PHPStan\Type\NullType;
use PoP\PoP\Extensions\Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationInTraitRector;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration;
use Symfony\Component\Cache\Traits\AbstractAdapterTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Contracts\Service\ServiceLocatorTrait;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

/**
 * Hack to fix bug.
 *
 * DowngradeParameterTypeWideningRector is modifying function `clear` from vendor/symfony/cache/Adapter/AdapterInterface.php:
 * from:
 *     public function clear(string $prefix = '');
 * to:
 *     public function clear($prefix = '');
 * But the same modification is not being done in vendor/symfony/cache/Traits/AbstractAdapterTrait.php
 * So apply this change manually
 *
 * @see https://github.com/leoloso/PoP/issues/597#issue-855005786
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();

    $services = $containerConfigurator->services();
    $services->set(AddParamTypeDeclarationInTraitRector::class)
        ->call('configure', [[
            AddParamTypeDeclarationInTraitRector::PARAMETER_TYPEHINTS => ValueObjectInliner::inline([
                new AddParamTypeDeclaration(AbstractAdapterTrait::class, 'clear', 0, new NullType()),
                new AddParamTypeDeclaration(ServiceLocatorTrait::class, 'has', 0, new NullType()),
                new AddParamTypeDeclaration(ServiceLocatorTrait::class, 'get', 0, new NullType()),
            ]),
        ]]);

    $parameters->set(Option::PATHS, [
        __DIR__ . '/vendor/symfony/cache/Traits/AbstractAdapterTrait.php',
        __DIR__ . '/vendor/symfony/service-contracts/ServiceLocatorTrait.php',
    ]);

    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_71);
    $parameters->set(Option::AUTO_IMPORT_NAMES, false);
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);
};
