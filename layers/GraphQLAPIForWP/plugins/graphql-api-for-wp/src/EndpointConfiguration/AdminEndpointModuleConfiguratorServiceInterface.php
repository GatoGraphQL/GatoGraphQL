<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\EndpointConfiguration;

interface AdminEndpointModuleConfiguratorServiceInterface
{
    public function addModuleConfigurationForAdminEndpointGroup(string $group, array $configuration): void;
    public function getModuleConfiguration(string $group): array;
}
