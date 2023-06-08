<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\CodeQuality\Configurators;

use PoP\PoP\Config\Rector\Configurators\ContainerConfigurationServiceTrait;

class CodeQualityContainerConfigurationService extends AbstractCodeQualityContainerConfigurationService
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
