<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\EndpointConfiguration;

class AdminEndpointModuleConfiguratorService implements AdminEndpointModuleConfiguratorServiceInterface
{
    /**
     * @var array<string,array<string,array<string,mixed>>>
     */
    protected array $endpointGroupModuleConfigurations = [];

    /**
     * @param array<string,array<string,mixed>> $moduleConfiguration
     */
    public function addEndpointGroupModuleConfiguration(string $endpointGroup, array $moduleConfiguration): void
    {
        $this->endpointGroupModuleConfigurations[$endpointGroup] ??= [];
        foreach ($moduleConfiguration as $module => $moduleEnvVarConfiguration) {
            $this->endpointGroupModuleConfigurations[$endpointGroup] = array_merge(
                $this->endpointGroupModuleConfigurations[$endpointGroup][$module] ?? [],
                $moduleEnvVarConfiguration
            );
        };
    }

    /**
     * @return array<string,array<string,mixed>>|null
     */
    public function getModuleConfiguration(string $endpointGroup): ?array
    {
        return $this->endpointGroupModuleConfigurations[$endpointGroup] ?? null;
    }
}
