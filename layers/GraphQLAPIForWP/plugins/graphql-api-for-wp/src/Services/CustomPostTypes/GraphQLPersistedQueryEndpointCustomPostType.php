<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\Module;
use GraphQLAPI\GraphQLAPI\ModuleConfiguration;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\BlockRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\EndpointAnnotatorRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointAnnotatorRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointBlockRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointOptionsBlock;
use GraphQLAPI\GraphQLAPI\Services\Taxonomies\GraphQLQueryTaxonomy;

class GraphQLPersistedQueryEndpointCustomPostType extends AbstractGraphQLEndpointCustomPostType
{
    use WithBlockRegistryCustomPostTypeTrait;

    private ?PersistedQueryEndpointBlockRegistryInterface $persistedQueryEndpointBlockRegistry = null;
    private ?PersistedQueryEndpointAnnotatorRegistryInterface $persistedQueryEndpointAnnotatorRegistry = null;
    private ?PersistedQueryEndpointOptionsBlock $persistedQueryEndpointOptionsBlock = null;

    final public function setPersistedQueryEndpointBlockRegistry(PersistedQueryEndpointBlockRegistryInterface $persistedQueryEndpointBlockRegistry): void
    {
        $this->persistedQueryEndpointBlockRegistry = $persistedQueryEndpointBlockRegistry;
    }
    final protected function getPersistedQueryEndpointBlockRegistry(): PersistedQueryEndpointBlockRegistryInterface
    {
        return $this->persistedQueryEndpointBlockRegistry ??= $this->instanceManager->getInstance(PersistedQueryEndpointBlockRegistryInterface::class);
    }
    final public function setPersistedQueryEndpointAnnotatorRegistry(PersistedQueryEndpointAnnotatorRegistryInterface $persistedQueryEndpointAnnotatorRegistry): void
    {
        $this->persistedQueryEndpointAnnotatorRegistry = $persistedQueryEndpointAnnotatorRegistry;
    }
    final protected function getPersistedQueryEndpointAnnotatorRegistry(): PersistedQueryEndpointAnnotatorRegistryInterface
    {
        return $this->persistedQueryEndpointAnnotatorRegistry ??= $this->instanceManager->getInstance(PersistedQueryEndpointAnnotatorRegistryInterface::class);
    }
    final public function setPersistedQueryEndpointOptionsBlock(PersistedQueryEndpointOptionsBlock $persistedQueryEndpointOptionsBlock): void
    {
        $this->persistedQueryEndpointOptionsBlock = $persistedQueryEndpointOptionsBlock;
    }
    final protected function getPersistedQueryEndpointOptionsBlock(): PersistedQueryEndpointOptionsBlock
    {
        return $this->persistedQueryEndpointOptionsBlock ??= $this->instanceManager->getInstance(PersistedQueryEndpointOptionsBlock::class);
    }

    /**
     * Custom Post Type name
     */
    public function getCustomPostType(): string
    {
        return 'graphql-query';
    }

    /**
     * Module that enables this PostType
     */
    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::PERSISTED_QUERIES;
    }

    protected function getEndpointAnnotatorRegistry(): EndpointAnnotatorRegistryInterface
    {
        return $this->getPersistedQueryEndpointAnnotatorRegistry();
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
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        return $componentConfiguration->getPersistedQuerySlugBase();
    }

    /**
     * Custom post type name
     */
    public function getCustomPostTypeName(): string
    {
        return \__('GraphQL persisted query endpoint', 'graphql-api');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     */
    protected function getCustomPostTypePluralNames(bool $uppercase): string
    {
        return \__('GraphQL persisted queries', 'graphql-api');
    }

    /**
     * Label to show on the "execute" action in the CPT table
     */
    protected function getExecuteActionLabel(): string
    {
        return __('Execute query', 'graphql-api');
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
                'all_items' => \__('Persisted Queries', 'graphql-api'),
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

    // /**
    //  * Show in admin bar
    //  */
    // protected function showInAdminBar(): bool
    // {
    //     return true;
    // }

    protected function getBlockRegistry(): BlockRegistryInterface
    {
        return $this->getPersistedQueryEndpointBlockRegistry();
    }

    /**
     * Indicate if the excerpt must be used as the CPT's description and rendered when rendering the post
     */
    public function usePostExcerptAsDescription(): bool
    {
        return true;
    }

    public function getEndpointOptionsBlock(): BlockInterface
    {
        return $this->getPersistedQueryEndpointOptionsBlock();
    }
}
