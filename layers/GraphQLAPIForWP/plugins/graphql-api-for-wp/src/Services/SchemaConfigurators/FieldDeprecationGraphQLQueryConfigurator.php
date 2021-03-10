<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Schema\HookHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use PoP\ComponentModel\Schema\SchemaDefinition;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractControlBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\FieldDeprecationBlock;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\AbstractGraphQLQueryConfigurator;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\VersioningFunctionalityModuleResolver;

class FieldDeprecationGraphQLQueryConfigurator extends AbstractGraphQLQueryConfigurator
{
    /**
     * Extract the configuration items defined in the CPT,
     * and inject them into the service as to take effect in the current GraphQL query
     *
     * @return void
     */
    public function executeSchemaConfiguration(int $fdlPostID): void
    {
        // Only if the module is not disabled
        if (!$this->moduleRegistry->isModuleEnabled(VersioningFunctionalityModuleResolver::FIELD_DEPRECATION)) {
            return;
        }

        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var BlockHelpers */
        $blockHelpers = $instanceManager->getInstance(BlockHelpers::class);
        /**
         * @var FieldDeprecationBlock
         */
        $block = $instanceManager->getInstance(FieldDeprecationBlock::class);
        $fdlBlockItems = $blockHelpers->getBlocksOfTypeFromCustomPost(
            $fdlPostID,
            $block
        );
        $hooksAPI = HooksAPIFacade::getInstance();
        foreach ($fdlBlockItems as $fdlBlockItem) {
            if ($deprecationReason = $fdlBlockItem['attrs'][FieldDeprecationBlock::ATTRIBUTE_NAME_DEPRECATION_REASON] ?? null) {
                if ($typeFields = $fdlBlockItem['attrs'][AbstractControlBlock::ATTRIBUTE_NAME_TYPE_FIELDS] ?? null) {
                    // Add a hook to override the schema for the selected fields
                    foreach ($typeFields as $selectedField) {
                        $entriesFromField = $this->getEntriesFromField($selectedField, $deprecationReason);
                        foreach ($entriesFromField as $entry) {
                            // Once getting the entry, we an obtain the type and field,
                            // and we can modify the deprecated reason in the entry adding this information
                            $typeOrFieldInterfaceResolverClass = $entry[0];
                            // If we had a module (eg: "Users") and saved an entry with it,
                            // and then disable it, the typeResolveClass will be null
                            if (is_null($typeOrFieldInterfaceResolverClass)) {
                                continue;
                            }
                            $fieldName = $entry[1];
                            $hookName = HookHelpers::getSchemaDefinitionForFieldHookName(
                                $typeOrFieldInterfaceResolverClass,
                                $fieldName
                            );
                            $hooksAPI->addFilter(
                                $hookName,
                                function (array $schemaDefinition) use ($deprecationReason): array {
                                    $schemaDefinition[SchemaDefinition::ARGNAME_DEPRECATED] = true;
                                    $schemaDefinition[SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION] = $deprecationReason;
                                    return $schemaDefinition;
                                },
                                10,
                                1
                            );
                        }
                    }
                }
            }
        }
    }
}
