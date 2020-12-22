<?php

declare(strict_types=1);

namespace PoP\GraphQLAPI\Config;

use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\DataStructure\DataStructureManagerInterface;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        ContainerBuilderUtils::injectServicesIntoService(
            DataStructureManagerInterface::class,
            'PoP\\GraphQLAPI\\DataStructureFormatters',
            'add'
        );
    }
}
