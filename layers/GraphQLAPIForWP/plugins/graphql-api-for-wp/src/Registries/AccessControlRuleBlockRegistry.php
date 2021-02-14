<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

class AccessControlRuleBlockRegistry implements AccessControlRuleBlockRegistryInterface
{
    /**
     * @var string[]
     */
    protected array $serviceDefinitionIDs = [];

    public function addServiceDefinitionID(string $serviceDefinitionID): void
    {
        $this->serviceDefinitionIDs[] = $serviceDefinitionID;
    }
    /**
     * @return string[]
     */
    public function getServiceDefinitionIDs(): array
    {
        return $this->serviceDefinitionIDs;
    }
}
