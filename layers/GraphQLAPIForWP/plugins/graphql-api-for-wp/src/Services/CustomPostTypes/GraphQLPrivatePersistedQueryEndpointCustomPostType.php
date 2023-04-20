<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

use GraphQLAPI\GraphQLAPI\Module;
use GraphQLAPI\GraphQLAPI\ModuleConfiguration;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use PoP\Root\App;

class GraphQLPrivatePersistedQueryEndpointCustomPostType extends AbstractGraphQLPersistedQueryEndpointCustomPostType
{
    /**
     * Custom Post Type name
     */
    public function getCustomPostType(): string
    {
        return 'private-query';
    }

    /**
     * Module that enables this PostType
     */
    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::PRIVATE_PERSISTED_QUERIES;
    }

    /**
     * The position on which to add the CPT on the menu.
     */
    protected function getMenuPosition(): int
    {
        return 2;
    }

    /**
     * Access endpoints under /graphql-query, or wherever it is configured to
     */
    protected function getSlugBase(): ?string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getPrivatePersistedQuerySlugBase();
    }

    /**
     * Custom post type name
     */
    protected function getCustomPostTypeName(): string
    {
        return \__('GraphQL private persisted query endpoint', 'graphql-api');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $titleCase Indicate if the name must be title case (for starting a sentence) or, otherwise, lowercase
     */
    protected function getCustomPostTypePluralNames(bool $titleCase): string
    {
        return \__('GraphQL Private Persisted Queries', 'graphql-api');
    }

    /**
     * Labels for registering the post type
     *
     * @param string $name_uc Singular name uppercase
     * @param string $names_uc Plural name uppercase
     * @param string $names_lc Plural name lowercase
     * @return array<string,string>
     */
    protected function getCustomPostTypeLabels(string $name_uc, string $names_uc, string $names_lc): array
    {
        /**
         * Because the name is too long, shorten it for the admin menu only
         */
        return array_merge(
            parent::getCustomPostTypeLabels($name_uc, $names_uc, $names_lc),
            array(
                'all_items' => \__('Persisted Queries (Private)', 'graphql-api'),
            )
        );
    }
}
