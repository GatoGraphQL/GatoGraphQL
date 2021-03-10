<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\Facades\Registries\AccessControlRuleBlockRegistryFacade;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\AccessControlFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractControlBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AccessControlBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AccessControlRuleBlocks\AbstractAccessControlRuleBlock;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Misc\GeneralUtils;

class AccessControlGraphQLQueryConfigurator extends AbstractIndividualControlGraphQLQueryConfigurator
{
    /**
     * @var array<string, bool>|null
     */
    protected ?array $aclRuleBlockNameEnabled = null;

    // protected function doInit(): void
    // {
    //     $this->setAccessControlList();
    // }

    /**
     * Obtain the modules enabling/disabling each ACL rule block, through a hook
     *
     * @return array<string, bool>
     */
    protected function getACLRuleBlockNameEnabled(): array
    {
        // Lazy load
        if (is_null($this->aclRuleBlockNameEnabled)) {
            // Obtain the block names from the block classes
            $accessControlRuleBlockRegistry = AccessControlRuleBlockRegistryFacade::getInstance();
            $aclRuleBlocks = $accessControlRuleBlockRegistry->getAccessControlRuleBlocks();
            $this->aclRuleBlockNameEnabled = [];
            foreach ($aclRuleBlocks as $block) {
                $this->aclRuleBlockNameEnabled[$block->getBlockFullName()] = $block->isServiceEnabled();
            }
        }
        return $this->aclRuleBlockNameEnabled;
    }

    /**
     * Obtain the module enabling/disabling a certain ACL rule block
     */
    protected function isACLRuleBlockEnabled(string $blockName): bool
    {
        $aclRuleBlockNameEnabled = $this->getACLRuleBlockNameEnabled();
        return $aclRuleBlockNameEnabled[$blockName] ?? false;
    }

    /**
     * Extract the access control items defined in the CPT,
     * and inject them into the service as to take effect in the current GraphQL query
     *
     * @return void
     */
    public function executeSchemaConfiguration(int $aclPostID): void
    {
        // Only if the module is not disabled
        if (!$this->moduleRegistry->isModuleEnabled(AccessControlFunctionalityModuleResolver::ACCESS_CONTROL)) {
            return;
        }

        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var BlockHelpers */
        $blockHelpers = $instanceManager->getInstance(BlockHelpers::class);
        /**
         * @var AccessControlBlock
         */
        $block = $instanceManager->getInstance(AccessControlBlock::class);
        $aclBlockItems = $blockHelpers->getBlocksOfTypeFromCustomPost(
            $aclPostID,
            $block
        );
        $accessControlManager = AccessControlManagerFacade::getInstance();
        // The "Access Control" type contains the fields/directives
        foreach ($aclBlockItems as $aclBlockItem) {
            // The rule to apply is contained inside the nested blocks
            if ($aclBlockItemNestedBlocks = $aclBlockItem['innerBlocks']) {
                // Filter out the rules not enabled by module
                if (
                    $aclBlockItemNestedBlocks = array_filter(
                        $aclBlockItemNestedBlocks,
                        function ($block): bool {
                            return $this->isACLRuleBlockEnabled($block['blockName']);
                        }
                    )
                ) {
                    $aclBlockItemTypeFields = $aclBlockItem['attrs'][AbstractControlBlock::ATTRIBUTE_NAME_TYPE_FIELDS] ?? [];
                    $aclBlockItemDirectives = $aclBlockItem['attrs'][AbstractControlBlock::ATTRIBUTE_NAME_DIRECTIVES] ?? [];

                    // The value can be NULL, then it's the default mode
                    // In that case do nothing, since the default mode is already injected into GraphQL by PoP
                    $schemaMode = $aclBlockItem['attrs'][AccessControlBlock::ATTRIBUTE_NAME_SCHEMA_MODE] ?? null;

                    // Iterate all the nested blocks
                    foreach ($aclBlockItemNestedBlocks as $aclBlockItemNestedBlock) {
                        if ($accessControlGroup = $aclBlockItemNestedBlock['attrs'][AbstractAccessControlRuleBlock::ATTRIBUTE_NAME_ACCESS_CONTROL_GROUP] ?? null) {
                            // The value can be NULL, it depends on the actual nestedBlock
                            // (eg: Disable access doesn't have any, while Disable by role has the list of roles)
                            $value = $aclBlockItemNestedBlock['attrs'][AbstractAccessControlRuleBlock::ATTRIBUTE_NAME_VALUE] ?? null;

                            // Extract the saved fields
                            if (
                                $entriesForFields = GeneralUtils::arrayFlatten(
                                    array_map(
                                        function ($selectedField) use ($value, $schemaMode) {
                                            return $this->getIndividualControlEntriesFromField(
                                                $selectedField,
                                                $value,
                                                $schemaMode
                                            );
                                        },
                                        $aclBlockItemTypeFields
                                    )
                                )
                            ) {
                                $accessControlManager->addEntriesForFields(
                                    $accessControlGroup,
                                    $entriesForFields
                                );
                            }

                            // Extract the saved directives
                            if (
                                $entriesForDirectives = GeneralUtils::arrayFlatten(array_filter(
                                    array_map(
                                        function ($selectedDirective) use ($value, $schemaMode) {
                                            return $this->getIndividualControlEntriesFromDirective(
                                                $selectedDirective,
                                                $value,
                                                $schemaMode
                                            );
                                        },
                                        $aclBlockItemDirectives
                                    )
                                ))
                            ) {
                                $accessControlManager->addEntriesForDirectives(
                                    $accessControlGroup,
                                    $entriesForDirectives
                                );
                            }
                        }
                    }
                }
            }
        }
    }
}
