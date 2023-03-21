<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

use GraphQLAPI\GraphQLAPI\Module;
use GraphQLAPI\GraphQLAPI\ModuleConfiguration;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\BlockRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\CustomEndpointAnnotatorRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\EndpointAnnotatorRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\EndpointBlockRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\CustomEndpointOptionsBlock;
use GraphQLAPI\GraphQLAPI\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy;
use GraphQLAPI\GraphQLAPI\Services\Taxonomies\TaxonomyInterface;
use PoP\Root\App;

class GraphQLCustomEndpointCustomPostType extends AbstractGraphQLEndpointCustomPostType
{
    use WithBlockRegistryCustomPostTypeTrait;

    private ?EndpointBlockRegistryInterface $endpointBlockRegistry = null;
    private ?CustomEndpointAnnotatorRegistryInterface $customEndpointAnnotatorRegistry = null;
    private ?CustomEndpointOptionsBlock $customEndpointOptionsBlock = null;
    private ?GraphQLEndpointCategoryTaxonomy $graphQLEndpointCategoryTaxonomy = null;

    final public function setEndpointBlockRegistry(EndpointBlockRegistryInterface $endpointBlockRegistry): void
    {
        $this->endpointBlockRegistry = $endpointBlockRegistry;
    }
    final protected function getEndpointBlockRegistry(): EndpointBlockRegistryInterface
    {
        /** @var EndpointBlockRegistryInterface */
        return $this->endpointBlockRegistry ??= $this->instanceManager->getInstance(EndpointBlockRegistryInterface::class);
    }
    final public function setCustomEndpointAnnotatorRegistry(CustomEndpointAnnotatorRegistryInterface $customEndpointAnnotatorRegistry): void
    {
        $this->customEndpointAnnotatorRegistry = $customEndpointAnnotatorRegistry;
    }
    final protected function getCustomEndpointAnnotatorRegistry(): CustomEndpointAnnotatorRegistryInterface
    {
        /** @var CustomEndpointAnnotatorRegistryInterface */
        return $this->customEndpointAnnotatorRegistry ??= $this->instanceManager->getInstance(CustomEndpointAnnotatorRegistryInterface::class);
    }
    final public function setCustomEndpointOptionsBlock(CustomEndpointOptionsBlock $customEndpointOptionsBlock): void
    {
        $this->customEndpointOptionsBlock = $customEndpointOptionsBlock;
    }
    final protected function getCustomEndpointOptionsBlock(): CustomEndpointOptionsBlock
    {
        /** @var CustomEndpointOptionsBlock */
        return $this->customEndpointOptionsBlock ??= $this->instanceManager->getInstance(CustomEndpointOptionsBlock::class);
    }
    final public function setGraphQLEndpointCategoryTaxonomy(GraphQLEndpointCategoryTaxonomy $graphQLEndpointCategoryTaxonomy): void
    {
        $this->graphQLEndpointCategoryTaxonomy = $graphQLEndpointCategoryTaxonomy;
    }
    final protected function getGraphQLEndpointCategoryTaxonomy(): GraphQLEndpointCategoryTaxonomy
    {
        /** @var GraphQLEndpointCategoryTaxonomy */
        return $this->graphQLEndpointCategoryTaxonomy ??= $this->instanceManager->getInstance(GraphQLEndpointCategoryTaxonomy::class);
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
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getCustomEndpointSlugBase();
    }

    /**
     * Custom post type name
     */
    protected function getCustomPostTypeName(): string
    {
        return \__('GraphQL custom endpoint', 'graphql-api');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $titleCase Indicate if the name must be title case (for starting a sentence) or, otherwise, lowercase
     */
    protected function getCustomPostTypePluralNames(bool $titleCase): string
    {
        return \__('GraphQL Custom Endpoints', 'graphql-api');
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
     * @return TaxonomyInterface[]
     */
    protected function getTaxonomies(): array
    {
        return [
            $this->getGraphQLEndpointCategoryTaxonomy(),
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
        return $this->getEndpointBlockRegistry();
    }

    /**
     * Indicates if to lock the Gutenberg templates
     */
    protected function lockGutenbergTemplate(): bool
    {
        return true;
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

    public function getEndpointOptionsBlock(): BlockInterface
    {
        return $this->getCustomEndpointOptionsBlock();
    }

    protected function getEndpointAnnotatorRegistry(): EndpointAnnotatorRegistryInterface
    {
        return $this->getCustomEndpointAnnotatorRegistry();
    }
}
