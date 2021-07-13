<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\VersioningFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigFieldDeprecationListBlock;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\FieldDeprecationGraphQLQueryConfigurator;

class FieldDeprecationSchemaConfigurationExecuter extends AbstractSchemaConfigurationExecuter implements PersistedQuerySchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        protected FieldDeprecationGraphQLQueryConfigurator $fieldDeprecationGraphQLQueryConfigurator
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
        );
    }

    public function executeSchemaConfiguration(int $schemaConfigurationID): void
    {
        // Check it is enabled by module
        if (!$this->moduleRegistry->isModuleEnabled(VersioningFunctionalityModuleResolver::FIELD_DEPRECATION)) {
            return;
        }

        /** @var BlockHelpers */
        $blockHelpers = $this->instanceManager->getInstance(BlockHelpers::class);
        /**
         * @var SchemaConfigFieldDeprecationListBlock
         */
        $block = $this->instanceManager->getInstance(SchemaConfigFieldDeprecationListBlock::class);
        $schemaConfigFDLBlockDataItem = $blockHelpers->getSingleBlockOfTypeFromCustomPost(
            $schemaConfigurationID,
            $block
        );
        if (!is_null($schemaConfigFDLBlockDataItem)) {
            if ($fieldDeprecationLists = $schemaConfigFDLBlockDataItem['attrs'][SchemaConfigFieldDeprecationListBlock::ATTRIBUTE_NAME_FIELD_DEPRECATION_LISTS] ?? null) {
                foreach ($fieldDeprecationLists as $fieldDeprecationListID) {
                    $this->fieldDeprecationGraphQLQueryConfigurator->executeSchemaConfiguration($fieldDeprecationListID);
                }
            }
        }
    }
}
