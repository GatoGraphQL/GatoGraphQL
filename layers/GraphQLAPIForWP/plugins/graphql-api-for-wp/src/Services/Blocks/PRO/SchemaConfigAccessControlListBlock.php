<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks\PRO;

use GraphQLAPI\GraphQLAPIPRO\ModuleResolvers\AccessControlFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPIPRO\Services\CustomPostTypes\GraphQLAccessControlListCustomPostType;

/**
 * Cache Control block
 */
class SchemaConfigAccessControlListBlock extends AbstractSchemaConfigPlaceholderPROBlock
{
    use PROPluginBlockTrait;

    // public final const ATTRIBUTE_NAME_ACCESS_CONTROL_LISTS = 'accessControlLists';

    // private ?GraphQLAccessControlListCustomPostType $graphQLAccessControlListCustomPostType = null;

    // final public function setGraphQLAccessControlListCustomPostType(GraphQLAccessControlListCustomPostType $graphQLAccessControlListCustomPostType): void
    // {
    //     $this->graphQLAccessControlListCustomPostType = $graphQLAccessControlListCustomPostType;
    // }
    // final protected function getGraphQLAccessControlListCustomPostType(): GraphQLAccessControlListCustomPostType
    // {
    //     /** @var GraphQLAccessControlListCustomPostType */
    //     return $this->graphQLAccessControlListCustomPostType ??= $this->instanceManager->getInstance(GraphQLAccessControlListCustomPostType::class);
    // }

    protected function getBlockName(): string
    {
        return 'schema-config-access-control-lists';
    }

    public function getBlockPriority(): int
    {
        return 2900;
    }

    public function getEnablingModule(): ?string
    {
        return AccessControlFunctionalityModuleResolver::ACCESS_CONTROL;
    }

    // protected function getAttributeName(): string
    // {
    //     return self::ATTRIBUTE_NAME_ACCESS_CONTROL_LISTS;
    // }

    // protected function getCustomPostType(): string
    // {
    //     return $this->getGraphQLAccessControlListCustomPostType()->getCustomPostType();
    // }

    // protected function getHeader(): string
    // {
    //     return \__('Access Control Lists:');
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
