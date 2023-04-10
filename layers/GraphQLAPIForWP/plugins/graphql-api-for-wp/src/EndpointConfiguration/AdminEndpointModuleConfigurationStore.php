<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\EndpointConfiguration;

use PoP\Root\Module\ModuleInterface;

class AdminEndpointModuleConfigurationStore implements AdminEndpointModuleConfigurationStoreInterface
{
    /**
     * @var array<string,array<class-string<ModuleInterface>,array<string,mixed>>>
     */
    protected array $endpointGroupModuleConfigurations = [];

    /**
     * @param array<class-string<ModuleInterface>,array<string,mixed>> $moduleClassConfiguration
     */
    public function addEndpointGroupModuleConfiguration(string $endpointGroup, array $moduleClassConfiguration): void
    {
        $this->endpointGroupModuleConfigurations[$endpointGroup] ??= [];
        foreach ($moduleClassConfiguration as $moduleClass => $moduleEnvVarConfiguration) {
            $this->endpointGroupModuleConfigurations[$endpointGroup] = array_merge(
                $this->endpointGroupModuleConfigurations[$endpointGroup][$moduleClass] ?? [],
                $moduleEnvVarConfiguration
            );
        };
    }

    /**
     * @return array<class-string<ModuleInterface>,array<string,mixed>>|null
     */
    public function getModuleConfiguration(string $endpointGroup): ?array
    {
        return $this->endpointGroupModuleConfigurations[$endpointGroup] ?? null;
    }
}
