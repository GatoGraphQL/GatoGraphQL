<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\AccessControlFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigAccessControlListBlock;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\AccessControlGraphQLQueryConfigurator;
use Symfony\Contracts\Service\Attribute\Required;

class AccessControlSchemaConfigurationExecuter extends AbstractSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?AccessControlGraphQLQueryConfigurator $accessControlGraphQLQueryConfigurator = null;
    private ?SchemaConfigAccessControlListBlock $schemaConfigAccessControlListBlock = null;

    final public function setAccessControlGraphQLQueryConfigurator(AccessControlGraphQLQueryConfigurator $accessControlGraphQLQueryConfigurator): void
    {
        $this->accessControlGraphQLQueryConfigurator = $accessControlGraphQLQueryConfigurator;
    }
    final protected function getAccessControlGraphQLQueryConfigurator(): AccessControlGraphQLQueryConfigurator
    {
        return $this->accessControlGraphQLQueryConfigurator ??= $this->instanceManager->getInstance(AccessControlGraphQLQueryConfigurator::class);
    }
    final public function setSchemaConfigAccessControlListBlock(SchemaConfigAccessControlListBlock $schemaConfigAccessControlListBlock): void
    {
        $this->schemaConfigAccessControlListBlock = $schemaConfigAccessControlListBlock;
    }
    final protected function getSchemaConfigAccessControlListBlock(): SchemaConfigAccessControlListBlock
    {
        return $this->schemaConfigAccessControlListBlock ??= $this->instanceManager->getInstance(SchemaConfigAccessControlListBlock::class);
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
                    $this->getAccessControlGraphQLQueryConfigurator()->executeSchemaConfiguration($accessControlListID);
                }
            }
        }
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigAccessControlListBlock();
    }
}
