<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Upgrades\Configurators;

use PoP\PoP\Config\Rector\Configurators\ContainerConfigurationServiceTrait;

class PHPUnit10ContainerConfigurationService extends AbstractPHPUnit10ContainerConfigurationService
{
    use ContainerConfigurationServiceTrait;

    /**
     * @return string[]
     */
    protected function getPaths(): array
    {
        return $this->getProjectPaths();
    }
}
