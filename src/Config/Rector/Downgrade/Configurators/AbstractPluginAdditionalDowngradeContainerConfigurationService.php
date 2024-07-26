<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

abstract class AbstractPluginAdditionalDowngradeContainerConfigurationService extends AbstractPluginDowngradeContainerConfigurationService
{
    public function configureContainer(): void
    {
        parent::configureContainer();

        $this->rectorConfig->paths($this->getPaths());
    }

    /**
     * @return string[]
     */
    abstract protected function getPaths(): array;
}
