<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\EndpointConfiguration;

use PoP\Root\Module\ModuleInterface;

class AdminEndpointModuleConfigurationStore implements AdminEndpointModuleConfigurationStoreInterface
{
    /**
     * @var array<string,array<class-string<ModuleInterface>,array<string,mixed>>>
     */
    protected array $endpointGroupModuleClassConfigurations = [];

    /**
     * @param array<class-string<ModuleInterface>,array<string,mixed>> $moduleClassConfiguration
     */
    public function addEndpointGroupModuleConfiguration(string $endpointGroup, array $moduleClassConfiguration): void
    {
        $this->endpointGroupModuleClassConfigurations[$endpointGroup] ??= [];
        foreach ($moduleClassConfiguration as $moduleClass => $moduleEnvVarConfiguration) {
            $this->endpointGroupModuleClassConfigurations[$endpointGroup] = array_merge(
                $this->endpointGroupModuleClassConfigurations[$endpointGroup][$moduleClass] ?? [],
                $moduleEnvVarConfiguration
            );
        };
    }

    /**
     * @return array<class-string<ModuleInterface>,array<string,mixed>>|null
     */
    public function getModuleClassConfiguration(string $endpointGroup): ?array
    {
        return $this->endpointGroupModuleClassConfigurations[$endpointGroup] ?? null;
    }
}
