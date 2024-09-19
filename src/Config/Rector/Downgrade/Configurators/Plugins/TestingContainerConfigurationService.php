<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\Plugins;

use PoP\PoP\Config\Rector\Downgrade\Configurators\AbstractExtensionDowngradeContainerConfigurationService;

class TestingContainerConfigurationService extends AbstractExtensionDowngradeContainerConfigurationService
{
    use TestingContainerConfigurationServiceTrait;
}
