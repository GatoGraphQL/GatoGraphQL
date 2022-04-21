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
            $this->rootDirectory . '/layers/API/packages/*/tests/*',
            $this->rootDirectory . '/layers/CMSSchema/packages/*/src/*',
            $this->rootDirectory . '/layers/CMSSchema/packages/*/tests/*',
            $this->rootDirectory . '/layers/Engine/packages/*/src/*',
            $this->rootDirectory . '/layers/Engine/packages/*/tests/*',
            $this->rootDirectory . '/layers/GraphQLAPIForWP/packages/*/src/*',
            $this->rootDirectory . '/layers/GraphQLAPIForWP/packages/*/tests/*',
            $this->rootDirectory . '/layers/GraphQLAPIForWP/phpunit-packages/*/src/*',
            $this->rootDirectory . '/layers/GraphQLAPIForWP/phpunit-packages/*/tests/*',
            $this->rootDirectory . '/layers/GraphQLAPIForWP/phpunit-plugins/*/src/*',
            $this->rootDirectory . '/layers/GraphQLAPIForWP/phpunit-plugins/*/tests/*',
            $this->rootDirectory . '/layers/GraphQLAPIForWP/plugins/*/src/*',
            $this->rootDirectory . '/layers/GraphQLAPIForWP/plugins/*/tests/*',
            $this->rootDirectory . '/layers/GraphQLByPoP/packages/*/src/*',
            $this->rootDirectory . '/layers/GraphQLByPoP/packages/*/tests/*',
            $this->rootDirectory . '/layers/Legacy/Engine/packages/*/src/*',
            $this->rootDirectory . '/layers/Legacy/Engine/packages/*/tests/*',
            $this->rootDirectory . '/layers/Legacy/Schema/packages/*/src/*',
            $this->rootDirectory . '/layers/Legacy/Schema/packages/*/tests/*',
            $this->rootDirectory . '/layers/Legacy/SiteBuilder/packages/*/src/*',
            $this->rootDirectory . '/layers/Legacy/SiteBuilder/packages/*/tests/*',
            $this->rootDirectory . '/layers/Legacy/Wassup/packages/*/src/*',
            $this->rootDirectory . '/layers/Legacy/Wassup/packages/*/tests/*',
            $this->rootDirectory . '/layers/Schema/packages/*/src/*',
            $this->rootDirectory . '/layers/Schema/packages/*/tests/*',
            $this->rootDirectory . '/layers/SiteBuilder/packages/*/src/*',
            $this->rootDirectory . '/layers/SiteBuilder/packages/*/tests/*',
            $this->rootDirectory . '/layers/Wassup/packages/*/src/*',
            $this->rootDirectory . '/layers/Wassup/packages/*/tests/*',
            $this->rootDirectory . '/layers/WPSchema/packages/*/src/*',
            $this->rootDirectory . '/layers/WPSchema/packages/*/tests/*',
        ];
    }
}
