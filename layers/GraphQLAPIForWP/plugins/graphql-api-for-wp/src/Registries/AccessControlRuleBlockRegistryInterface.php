<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

interface AccessControlRuleBlockRegistryInterface
{
    public function addServiceDefinitionID(string $serviceClass): void;
    /**
     * @return string[]
     */
    public function getServiceDefinitionIDs(): array;
}
