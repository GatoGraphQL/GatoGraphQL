<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

use Symfony\Contracts\Service\Attribute\Required;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AccessControlFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\AccessControlRuleBlockRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AccessControlBlock;

class GraphQLAccessControlListCustomPostType extends AbstractCustomPostType
{
    protected AccessControlBlock $accessControlBlock;
    protected AccessControlRuleBlockRegistryInterface $accessControlRuleBlockRegistry;

    #[Required]
    public function autowireGraphQLAccessControlListCustomPostType(
        AccessControlBlock $accessControlBlock,
        AccessControlRuleBlockRegistryInterface $accessControlRuleBlockRegistry,
    ): void {
        $this->accessControlBlock = $accessControlBlock;
        $this->accessControlRuleBlockRegistry = $accessControlRuleBlockRegistry;
    }

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
        return [
            [$this->accessControlBlock->getBlockFullName()],
        ];
    }

    /**
     * Use both the Access Control block and all of its nested blocks
     *
     * @return string[] The list of block names
     */
    protected function getGutenbergBlocksForCustomPostType(): array
    {
        $aclNestedBlocks = $this->accessControlRuleBlockRegistry->getAccessControlRuleBlocks();
        return array_merge(
            [
                $this->accessControlBlock->getBlockFullName(),
            ],
            array_map(
                fn ($aclNestedBlock) => $aclNestedBlock->getBlockFullName(),
                $aclNestedBlocks
            )
        );
    }
}
