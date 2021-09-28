<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use Symfony\Contracts\Service\Attribute\Required;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AccessControlFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\AccessControlRuleBlockRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractControlBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AccessControlBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AccessControlRuleBlocks\AbstractAccessControlRuleBlock;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Registries\DirectiveRegistryInterface;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\Hooks\HooksAPIInterface;

class AccessControlGraphQLQueryConfigurator extends AbstractIndividualControlGraphQLQueryConfigurator
{
    protected AccessControlBlock $accessControlBlock;
    protected BlockHelpers $blockHelpers;
    protected AccessControlRuleBlockRegistryInterface $accessControlRuleBlockRegistry;
    protected AccessControlManagerInterface $accessControlManager;

    #[Required]
    public function autowireAccessControlGraphQLQueryConfigurator(
        AccessControlBlock $accessControlBlock,
        BlockHelpers $blockHelpers,
        AccessControlRuleBlockRegistryInterface $accessControlRuleBlockRegistry,
        AccessControlManagerInterface $accessControlManager,
    ) {
        $this->accessControlBlock = $accessControlBlock;
        $this->blockHelpers = $blockHelpers;
        $this->accessControlRuleBlockRegistry = $accessControlRuleBlockRegistry;
        $this->accessControlManager = $accessControlManager;
    }

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
            $aclRuleBlocks = $this->accessControlRuleBlockRegistry->getAccessControlRuleBlocks();
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

    public function getEnablingModule(): ?string
    {
        return AccessControlFunctionalityModuleResolver::ACCESS_CONTROL;
    }

    /**
     * Extract the access control items defined in the CPT,
     * and inject them into the service as to take effect in the current GraphQL query
     */
    protected function doExecuteSchemaConfiguration(int $aclPostID): void
    {
        $aclBlockItems = $this->blockHelpers->getBlocksOfTypeFromCustomPost(
            $aclPostID,
            $this->accessControlBlock
        );
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
                                $this->accessControlManager->addEntriesForFields(
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
                                $this->accessControlManager->addEntriesForDirectives(
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
