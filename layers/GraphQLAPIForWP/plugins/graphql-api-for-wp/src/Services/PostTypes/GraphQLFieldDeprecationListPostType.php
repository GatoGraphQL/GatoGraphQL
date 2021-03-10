<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\PostTypes;

use GraphQLAPI\GraphQLAPI\Services\Blocks\FieldDeprecationBlock;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\VersioningFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\PostTypes\AbstractPostType;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

class GraphQLFieldDeprecationListPostType extends AbstractPostType
{
    /**
     * Custom Post Type name
     */
    public const POST_TYPE = 'graphql-deprec-list';

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
     * Module that enables this PostType
     */
    public function getEnablingModule(): ?string
    {
        return VersioningFunctionalityModuleResolver::FIELD_DEPRECATION;
    }

    /**
     * The position on which to add the CPT on the menu.
     */
    protected function getMenuPosition(): int
    {
        return 6;
    }

    /**
     * Custom post type name
     */
    public function getPostTypeName(): string
    {
        return \__('Field Deprecation List', 'graphql-api');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     * @return string
     */
    protected function getPostTypePluralNames(bool $uppercase): string
    {
        return \__('Field Deprecation Lists', 'graphql-api');
    }

    /**
     * Indicate if, whenever this CPT is saved/updated,
     * the timestamp must be regenerated
     *
     * @return boolean
     */
    protected function regenerateTimestampOnSave(): bool
    {
        return true;
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
     * Gutenberg templates to lock down the Custom Post Type to
     *
     * @return array<array> Every element is an array with template name in first pos, and attributes then
     */
    protected function getGutenbergTemplate(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var FieldDeprecationBlock
         */
        $fieldDeprecationBlock = $instanceManager->getInstance(FieldDeprecationBlock::class);
        return [
            [$fieldDeprecationBlock->getBlockFullName()],
        ];
    }
}
