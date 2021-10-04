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
    protected AccessControlGraphQLQueryConfigurator $accessControlGraphQLQueryConfigurator;
    protected SchemaConfigAccessControlListBlock $schemaConfigAccessControlListBlock;

    #[Required]
    final public function autowireAccessControlSchemaConfigurationExecuter(
        AccessControlGraphQLQueryConfigurator $accessControlGraphQLQueryConfigurator,
        SchemaConfigAccessControlListBlock $schemaConfigAccessControlListBlock,
    ): void {
        $this->accessControlGraphQLQueryConfigurator = $accessControlGraphQLQueryConfigurator;
        $this->schemaConfigAccessControlListBlock = $schemaConfigAccessControlListBlock;
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

    protected function getBlock(): BlockInterface
    {
        return $this->schemaConfigAccessControlListBlock;
    }
}
