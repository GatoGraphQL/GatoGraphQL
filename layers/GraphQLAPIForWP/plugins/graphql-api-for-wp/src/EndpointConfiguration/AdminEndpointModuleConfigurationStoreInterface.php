<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\EndpointConfiguration;

use PoP\Root\Module\ModuleInterface;

interface AdminEndpointModuleConfigurationStoreInterface
{
    /**
     * @param array<class-string<ModuleInterface>,array<string,mixed>> $moduleConfiguration
     */
    public function addEndpointGroupModuleConfiguration(string $endpointGroup, array $moduleConfiguration): void;
    /**
     * @return array<class-string<ModuleInterface>,array<string,mixed>>|null
     */
    public function getModuleConfiguration(string $endpointGroup): ?array;
}
