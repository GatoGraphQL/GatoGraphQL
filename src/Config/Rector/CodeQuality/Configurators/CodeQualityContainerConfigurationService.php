<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\CodeQuality\Configurators;

class CodeQualityContainerConfigurationService extends AbstractCodeQualityContainerConfigurationService
{
    /**
     * @return string[]
     */
    protected function getPaths(): array
    {
        return [
            $this->rootDirectory . '/layers/API/packages/*/src/*',
            $this->rootDirectory . '/layers/Engine/packages/*/src/*',
            $this->rootDirectory . '/layers/GraphQLAPIForWP/packages/*/src/*',
            $this->rootDirectory . '/layers/GraphQLAPIForWP/plugins/*/src/*',
            $this->rootDirectory . '/layers/GraphQLByPoP/packages/*/src/*',
            $this->rootDirectory . '/layers/Legacy/Engine/packages/*/src/*',
            $this->rootDirectory . '/layers/Legacy/Schema/packages/*/src/*',
            $this->rootDirectory . '/layers/Legacy/Wassup/packages/*/src/*',
            $this->rootDirectory . '/layers/Schema/packages/*/src/*',
            $this->rootDirectory . '/layers/SiteBuilder/packages/*/src/*',
            $this->rootDirectory . '/layers/Wassup/packages/*/src/*',
            $this->rootDirectory . '/layers/WPSchema/packages/*/src/*',
        ];
    }
}
