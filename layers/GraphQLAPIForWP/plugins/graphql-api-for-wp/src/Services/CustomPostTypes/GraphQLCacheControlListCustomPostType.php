<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

use GraphQLAPI\GraphQLAPI\Services\Blocks\CacheControlBlock;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractCustomPostType;

class GraphQLCacheControlListCustomPostType extends AbstractCustomPostType
{
    /**
     * Custom Post Type name
     */
    public function getCustomPostType(): string
    {
        return 'graphql-ccl';
    }

    /**
     * Module that enables this PostType
     */
    public function getEnablingModule(): ?string
    {
        return PerformanceFunctionalityModuleResolver::CACHE_CONTROL;
    }

    /**
     * The position on which to add the CPT on the menu.
     */
    protected function getMenuPosition(): int
    {
        return 5;
    }

    /**
     * Custom post type name
     */
    public function getCustomPostTypeName(): string
    {
        return \__('Cache Control List', 'graphql-api');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     */
    protected function getCustomPostTypePluralNames(bool $uppercase): string
    {
        return \__('Cache Control Lists', 'graphql-api');
    }

    /**
     * Indicate if the excerpt must be used as the CPT's description and rendered when rendering the post
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
        /**
         * @var CacheControlBlock
         */
        $cacheControlBlock = $this->instanceManager->getInstance(CacheControlBlock::class);
        return [
            [$cacheControlBlock->getBlockFullName()],
        ];
    }
}
