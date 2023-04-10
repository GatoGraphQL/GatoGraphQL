<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\EndpointConfiguration;

interface AdminEndpointModuleConfiguratorServiceInterface
{
    /**
     * @param array<string,array<string,mixed>> $moduleConfiguration
     */
    public function addEndpointGroupModuleConfiguration(string $endpointGroup, array $moduleConfiguration): void;
    /**
     * @return array<string,array<string,mixed>>|null
     */
    public function getModuleConfiguration(string $endpointGroup): ?array;
}
