<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AccessControlFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigAccessControlListBlock;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\AccessControlGraphQLQueryConfigurator;

class AccessControlSchemaConfigurationExecuter extends AbstractSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        protected AccessControlGraphQLQueryConfigurator $accessControlGraphQLQueryConfigurator,
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
        );
    }

    public function getEnablingModule(): ?string
    {
        return AccessControlFunctionalityModuleResolver::ACCESS_CONTROL;
    }

    public function executeSchemaConfiguration(int $schemaConfigurationID): void
    {
        $schemaConfigACLBlockDataItem = $this->getSchemaConfigBlockDataItem($schemaConfigurationID);
        if (!is_null($schemaConfigACLBlockDataItem)) {
            if ($accessControlLists = $schemaConfigACLBlockDataItem['attrs'][SchemaConfigAccessControlListBlock::ATTRIBUTE_NAME_ACCESS_CONTROL_LISTS] ?? null) {
                foreach ($accessControlLists as $accessControlListID) {
                    $this->accessControlGraphQLQueryConfigurator->executeSchemaConfiguration($accessControlListID);
                }
            }
        }
    }

    protected function getBlockClass(): string
    {
        return SchemaConfigAccessControlListBlock::class;
    }
}
