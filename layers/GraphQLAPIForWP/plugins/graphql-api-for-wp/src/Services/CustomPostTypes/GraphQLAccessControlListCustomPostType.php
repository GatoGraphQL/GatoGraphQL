<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AccessControlBlock;
use GraphQLAPI\GraphQLAPI\Facades\Registries\AccessControlRuleBlockRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AccessControlFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractCustomPostType;

class GraphQLAccessControlListCustomPostType extends AbstractCustomPostType
{
    /**
     * Custom Post Type name
     */
    public function getCustomPostType(): string
    {
        return 'graphql-acl';
    }

    /**
     * Module that enables this PostType
     */
    public function getEnablingModule(): ?string
    {
        return AccessControlFunctionalityModuleResolver::ACCESS_CONTROL;
    }

    /**
     * The position on which to add the CPT on the menu.
     */
    protected function getMenuPosition(): int
    {
        return 4;
    }

    /**
     * Custom post type name
     */
    public function getCustomPostTypeName(): string
    {
        return \__('Access Control List', 'graphql-api');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     */
    protected function getCustomPostTypePluralNames(bool $uppercase): string
    {
        return \__('Access Control Lists', 'graphql-api');
    }

    /**
     * Indicate if the excerpt must be used as the CPT's description and rendered when rendering the post
     */
    public function usePostExcerptAsDescription(): bool
    {
        return true;
    }

    /**
     * Gutenberg templates for the Custom Post Type
     *
     * @return array<array> Every element is an array with template name in first pos, and attributes then
     */
    protected function getGutenbergTemplate(): array
    {
        /**
         * @var AccessControlBlock
         */
        $aclBlock = $this->instanceManager->getInstance(AccessControlBlock::class);
        return [
            [$aclBlock->getBlockFullName()],
        ];
    }

    /**
     * Use both the Access Control block and all of its nested blocks
     *
     * @return string[] The list of block names
     */
    protected function getGutenbergBlocksForCustomPostType(): array
    {
        /**
         * @var AccessControlBlock
         */
        $aclBlock = $this->instanceManager->getInstance(AccessControlBlock::class);
        $accessControlRuleBlockRegistry = AccessControlRuleBlockRegistryFacade::getInstance();
        $aclNestedBlocks = $accessControlRuleBlockRegistry->getAccessControlRuleBlocks();
        return array_merge(
            [
                $aclBlock->getBlockFullName(),
            ],
            array_map(
                fn ($aclNestedBlock) => $aclNestedBlock->getBlockFullName(),
                $aclNestedBlocks
            )
        );
    }
}
