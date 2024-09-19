<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\PHPUnitPlugins;

use PoP\PoP\Config\Rector\Downgrade\Configurators\AbstractExtensionDowngradeContainerConfigurationService;

class TestingContainerConfigurationService extends AbstractExtensionDowngradeContainerConfigurationService
{
    use TestingContainerConfigurationServiceTrait;
}
