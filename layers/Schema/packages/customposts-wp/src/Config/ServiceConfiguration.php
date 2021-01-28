<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostsWP\Config;

use PoPSchema\CustomPosts\TypeDataLoaders\CustomPostUnionTypeDataLoader;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        ContainerBuilderUtils::injectValuesIntoService(
            InstanceManagerInterface::class,
            'overrideClass',
            CustomPostUnionTypeDataLoader::class,
            \PoPSchema\CustomPostsWP\TypeDataLoaders\Overrides\CustomPostUnionTypeDataLoader::class
        );

        ContainerBuilderUtils::injectValuesIntoService(
            InstanceManagerInterface::class,
            'overrideClass',
            CustomPostUnionTypeResolver::class,
            \PoPSchema\CustomPostsWP\TypeResolvers\Overrides\CustomPostUnionTypeResolver::class
        );
    }
}
