<?php

declare(strict_types=1);

namespace PoP\APIMirrorQuery\Config;

use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\Root\Container\ContainerBuilderUtils;
use PoP\ComponentModel\DataStructure\DataStructureManagerInterface;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        ContainerBuilderUtils::injectServicesIntoService(
            DataStructureManagerInterface::class,
            'PoP\\APIMirrorQuery\\DataStructureFormatters',
            'add'
        );
    }
}
