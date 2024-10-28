<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Configurators;

trait ContainerConfigurationServiceTrait
{
    /**
     * @return string[]
     */
    protected function getProjectPaths(): array
    {
        return [
            $this->rootDirectory . '/layers/API/packages/*/src/*',
            $this->rootDirectory . '/layers/API/packages/*/tests/*',
            $this->rootDirectory . '/layers/CMSSchema/packages/*/src/*',
            $this->rootDirectory . '/layers/CMSSchema/packages/*/tests/*',
            $this->rootDirectory . '/layers/Engine/packages/*/src/*',
            $this->rootDirectory . '/layers/Engine/packages/*/tests/*',
            $this->rootDirectory . '/layers/GatoGraphQLForWP/packages/*/src/*',
            $this->rootDirectory . '/layers/GatoGraphQLForWP/packages/*/tests/*',
            $this->rootDirectory . '/layers/GatoGraphQLForWP/phpunit-packages/*/src/*',
            $this->rootDirectory . '/layers/GatoGraphQLForWP/phpunit-packages/*/tests/*',
            $this->rootDirectory . '/layers/GatoGraphQLForWP/phpunit-plugins/*/src/*',
            $this->rootDirectory . '/layers/GatoGraphQLForWP/phpunit-plugins/*/tests/*',
            $this->rootDirectory . '/layers/GatoGraphQLForWP/plugins/*/src/*',
            $this->rootDirectory . '/layers/GatoGraphQLForWP/plugins/*/tests/*',
            $this->rootDirectory . '/layers/GatoGraphQLForWP/standalone-plugins/*/src/*',
            $this->rootDirectory . '/layers/GatoGraphQLForWP/standalone-plugins/*/tests/*',
            $this->rootDirectory . '/layers/GraphQLByPoP/packages/*/src/*',
            $this->rootDirectory . '/layers/GraphQLByPoP/packages/*/tests/*',
            $this->rootDirectory . '/layers/Schema/packages/*/src/*',
            $this->rootDirectory . '/layers/Schema/packages/*/tests/*',
            $this->rootDirectory . '/layers/WPSchema/packages/*/src/*',
            $this->rootDirectory . '/layers/WPSchema/packages/*/tests/*',
        ];
    }
}
