<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PostTypes;

use GraphQLAPI\GraphQLAPI\Blocks\AccessControlBlock;
use GraphQLAPI\GraphQLAPI\PostTypes\AbstractPostType;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\Facades\Registries\AccessControlRuleBlockRegistryFacade;

class GraphQLAccessControlListPostType extends AbstractPostType
{
    /**
     * Custom Post Type name
     */
    public const POST_TYPE = 'graphql-acl';

    /**
     * Custom Post Type name
     *
     * @return string
     */
    protected function getPostType(): string
    {
        return self::POST_TYPE;
    }

    /**
     * Custom post type name
     */
    public function getPostTypeName(): string
    {
        return \__('Access Control List', 'graphql-api');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     * @return string
     */
    protected function getPostTypePluralNames(bool $uppercase): string
    {
        return \__('Access Control Lists', 'graphql-api');
    }

    /**
     * Indicate if the excerpt must be used as the CPT's description and rendered when rendering the post
     *
     * @return boolean
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
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var AccessControlBlock
         */
        $aclBlock = $instanceManager->getInstance(AccessControlBlock::class);
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
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var AccessControlBlock
         */
        $aclBlock = $instanceManager->getInstance(AccessControlBlock::class);
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
