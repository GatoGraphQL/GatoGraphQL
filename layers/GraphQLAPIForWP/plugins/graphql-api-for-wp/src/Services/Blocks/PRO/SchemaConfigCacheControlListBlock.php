<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks\PRO;

use GraphQLAPI\GraphQLAPIPRO\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPIPRO\Services\CustomPostTypes\GraphQLCacheControlListCustomPostType;

/**
 * Cache Control block
 */
class SchemaConfigCacheControlListBlock extends AbstractSchemaConfigPlaceholderPROBlock
{
    use PROPluginBlockTrait;

    // public final const ATTRIBUTE_NAME_CACHE_CONTROL_LISTS = 'cacheControlLists';

    // private ?GraphQLCacheControlListCustomPostType $graphQLCacheControlListCustomPostType = null;

    // final public function setGraphQLCacheControlListCustomPostType(GraphQLCacheControlListCustomPostType $graphQLCacheControlListCustomPostType): void
    // {
    //     $this->graphQLCacheControlListCustomPostType = $graphQLCacheControlListCustomPostType;
    // }
    // final protected function getGraphQLCacheControlListCustomPostType(): GraphQLCacheControlListCustomPostType
    // {
    //     /** @var GraphQLCacheControlListCustomPostType */
    //     return $this->graphQLCacheControlListCustomPostType ??= $this->instanceManager->getInstance(GraphQLCacheControlListCustomPostType::class);
    // }

    protected function getBlockName(): string
    {
        return 'schema-config-cache-control-lists';
    }

    public function getBlockPriority(): int
    {
        return 2700;
    }

    public function getEnablingModule(): ?string
    {
        return PerformanceFunctionalityModuleResolver::CACHE_CONTROL;
    }

    // protected function getAttributeName(): string
    // {
    //     return self::ATTRIBUTE_NAME_CACHE_CONTROL_LISTS;
    // }

    // protected function getCustomPostType(): string
    // {
    //     return $this->getGraphQLCacheControlListCustomPostType()->getCustomPostType();
    // }

    // protected function getHeader(): string
    // {
    //     return \__('Cache Control Lists:');
    // }

    /**
     * Add the locale language to the localized data?
     */
    protected function addLocalLanguage(): bool
    {
        return true;
    }

    /**
     * Default language for the script/component's documentation
     */
    protected function getDefaultLanguage(): ?string
    {
        // English
        return 'en';
    }

    /**
     * Register style-index.css
     */
    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }
}
