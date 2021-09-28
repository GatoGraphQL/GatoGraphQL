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
            $this->rootDirectory . '/layers/Wassup/packages/*/src/*',
            $this->rootDirectory . '/layers/GraphQLByPoP/packages/*/src/*',
            $this->rootDirectory . '/layers/APIDemoSite/packages/*/src/*',
            $this->rootDirectory . '/layers/GraphQLAPIForWP/plugins/*/src/*',
            $this->rootDirectory . '/layers/Engine/packages/*/src/*',
        ];
    }
}
