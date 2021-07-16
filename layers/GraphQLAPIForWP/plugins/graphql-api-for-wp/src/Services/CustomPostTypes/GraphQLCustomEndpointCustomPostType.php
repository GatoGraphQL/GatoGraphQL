<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\BlockRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\CustomEndpointAnnotatorRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\CustomEndpointExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\EndpointAnnotatorRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\EndpointBlockRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\EndpointExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractEndpointOptionsBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\EndpointOptionsBlock;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractGraphQLEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\Taxonomies\GraphQLQueryTaxonomy;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Hooks\HooksAPIInterface;
use WP_Post;

class GraphQLCustomEndpointCustomPostType extends AbstractGraphQLEndpointCustomPostType
{
    use WithBlockRegistryCustomPostTypeTrait;

    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        UserAuthorizationInterface $userAuthorization,
        HooksAPIInterface $hooksAPI,
        protected EndpointBlockRegistryInterface $endpointBlockRegistry,
        protected CustomEndpointExecuterRegistryInterface $customEndpointExecuterRegistryInterface,
        protected CustomEndpointAnnotatorRegistryInterface $customEndpointAnnotatorRegistryInterface,
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
            $userAuthorization,
            $hooksAPI,
        );
    }

    /**
     * Custom Post Type name
     */
    public function getCustomPostType(): string
    {
        return 'graphql-endpoint';
    }

    /**
     * Module that enables this PostType
     */
    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS;
    }

    /**
     * The position on which to add the CPT on the menu.
     */
    protected function getMenuPosition(): int
    {
        return 1;
    }

    /**
     * Access endpoints under /graphql, or wherever it is configured to
     */
    protected function getSlugBase(): ?string
    {
        return ComponentConfiguration::getCustomEndpointSlugBase();
    }

    /**
     * Custom post type name
     */
    public function getCustomPostTypeName(): string
    {
        return \__('GraphQL endpoint', 'graphql-api');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     */
    protected function getCustomPostTypePluralNames(bool $uppercase): string
    {
        return \__('GraphQL endpoints', 'graphql-api');
    }

    /**
     * Labels for registering the post type
     *
     * @param string $name_uc Singular name uppercase
     * @param string $names_uc Plural name uppercase
     * @param string $names_lc Plural name lowercase
     * @return array<string, string>
     */
    protected function getCustomPostTypeLabels(string $name_uc, string $names_uc, string $names_lc): array
    {
        /**
         * Because the name is too long, shorten it for the admin menu only
         */
        return array_merge(
            parent::getCustomPostTypeLabels($name_uc, $names_uc, $names_lc),
            array(
                'all_items' => \__('Custom Endpoints', 'graphql-api'),
            )
        );
    }

    /**
     * The Query is publicly accessible, and the permalink must be configurable
     */
    protected function isPublic(): bool
    {
        return true;
    }

    /**
     * Taxonomies
     *
     * @return string[]
     */
    protected function getTaxonomies(): array
    {
        return [
            GraphQLQueryTaxonomy::TAXONOMY_CATEGORY,
        ];
    }

    /**
     * Hierarchical
     */
    protected function isHierarchical(): bool
    {
        return true;
    }

    protected function getBlockRegistry(): BlockRegistryInterface
    {
        return $this->endpointBlockRegistry;
    }

    /**
     * Indicate if the excerpt must be used as the CPT's description and rendered when rendering the post
     */
    public function usePostExcerptAsDescription(): bool
    {
        return true;
    }

    /**
     * Label to show on the "execute" action in the CPT table
     */
    protected function getExecuteActionLabel(): string
    {
        return __('View endpoint', 'graphql-api');
    }

    protected function getEndpointOptionsBlock(): AbstractEndpointOptionsBlock
    {
        /**
         * @var EndpointOptionsBlock
         */
        $block = $this->instanceManager->getInstance(EndpointOptionsBlock::class);
        return $block;
    }

    protected function getEndpointExecuterRegistry(): EndpointExecuterRegistryInterface
    {
        return $this->customEndpointExecuterRegistryInterface;
    }

    protected function getEndpointAnnotatorRegistry(): EndpointAnnotatorRegistryInterface
    {
        return $this->customEndpointAnnotatorRegistryInterface;
    }
}
