<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

use PoP\PoP\Config\Rector\Downgrade\Configurators\AbstractDowngradeContainerConfigurationService;

abstract class AbstractAdditionalDowngradeContainerConfigurationService extends AbstractDowngradeContainerConfigurationService
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
