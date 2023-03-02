<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks\PRO;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\PRO\VersioningFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPIPRO\Services\CustomPostTypes\GraphQLFieldDeprecationListCustomPostType;

/**
 * Cache Control block
 */
class SchemaConfigFieldDeprecationListBlock extends AbstractSchemaConfigPlaceholderPROBlock
{
    use PROPluginBlockTrait;

    // public final const ATTRIBUTE_NAME_FIELD_DEPRECATION_LISTS = 'fieldDeprecationLists';

    // private ?GraphQLFieldDeprecationListCustomPostType $graphQLFieldDeprecationListCustomPostType = null;

    // final public function setGraphQLFieldDeprecationListCustomPostType(GraphQLFieldDeprecationListCustomPostType $graphQLFieldDeprecationListCustomPostType): void
    // {
    //     $this->graphQLFieldDeprecationListCustomPostType = $graphQLFieldDeprecationListCustomPostType;
    // }
    // final protected function getGraphQLFieldDeprecationListCustomPostType(): GraphQLFieldDeprecationListCustomPostType
    // {
    //     /** @var GraphQLFieldDeprecationListCustomPostType */
    //     return $this->graphQLFieldDeprecationListCustomPostType ??= $this->instanceManager->getInstance(GraphQLFieldDeprecationListCustomPostType::class);
    // }

    protected function getBlockName(): string
    {
        return 'schema-config-field-deprecation-lists';
    }

    public function getBlockPriority(): int
    {
        return 2600;
    }

    public function getEnablingModule(): ?string
    {
        return VersioningFunctionalityModuleResolver::FIELD_DEPRECATION;
    }

    // protected function getAttributeName(): string
    // {
    //     return self::ATTRIBUTE_NAME_FIELD_DEPRECATION_LISTS;
    // }

    // protected function getCustomPostType(): string
    // {
    //     return $this->getGraphQLFieldDeprecationListCustomPostType()->getCustomPostType();
    // }

    // protected function getHeader(): string
    // {
    //     return \__('Field Deprecation Lists:');
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

    // /**
    //  * Register style-index.css
    //  */
    // protected function registerCommonStyleCSS(): bool
    // {
    //     return true;
    // }
}
