<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

abstract class AbstractExtensionDowngradeContainerConfigurationService extends AbstractPluginDowngradeContainerConfigurationService
{
    /**
     * @return string[]
     */
    protected function getSkip(): array
    {
        return array_merge(
            parent::getSkip(),
            [
                // Skip since they are not needed and they fail
                $this->pluginDir . '/vendor/composer/*',
            ]
        );
    }
}
