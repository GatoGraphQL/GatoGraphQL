<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostsWP\Config;

use PoPSchema\CustomPosts\TypeDataLoaders\CustomPostUnionTypeDataLoader;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\Root\Container\ContainerBuilderUtils;
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
            \PoPSchema\CustomPostsWP\Overrides\TypeDataLoaders\CustomPostUnionTypeDataLoader::class
        );
    }
}
