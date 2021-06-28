<?php

declare(strict_types=1);

use PoP\PoP\Extensions\Rector\DowngradePhp72\Rector\ClassMethod\LegacyDowngradeParameterTypeWideningRector;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DowngradePhp72\Rector\FuncCall\DowngradePregUnmatchedAsNullConstantRector;
use Rector\DowngradePhp72\Rector\FuncCall\DowngradeStreamIsattyRector;
use Rector\DowngradePhp72\Rector\FunctionLike\DowngradeObjectTypeDeclarationRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * Replace the current `DowngradeParameterTypeWideningRector` (because it takes too long)
 * with a "legacy" version (from up to v0.10.9), which is fast
 * but does not replace code within traits.
 * 
 * @see https://github.com/leoloso/PoP/issues/715
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_71);

    $services = $containerConfigurator->services();
    $services->set(DowngradeObjectTypeDeclarationRector::class);
    // Replace this rule
    // $services->set(DowngradeParameterTypeWideningRector::class);
    $services->set(LegacyDowngradeParameterTypeWideningRector::class);
    $services->set(DowngradePregUnmatchedAsNullConstantRector::class);
    $services->set(DowngradeStreamIsattyRector::class);
};
