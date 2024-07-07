<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\CustomPostTypes;

use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Registries\BlockRegistryInterface;
use GatoGraphQL\GatoGraphQL\Registries\CustomEndpointAnnotatorRegistryInterface;
use GatoGraphQL\GatoGraphQL\Registries\EndpointAnnotatorRegistryInterface;
use GatoGraphQL\GatoGraphQL\Registries\EndpointBlockRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\CustomEndpointOptionsBlock;
use GatoGraphQL\GatoGraphQL\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy;
use GatoGraphQL\GatoGraphQL\Services\Taxonomies\TaxonomyInterface;
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
        if ($this->endpointBlockRegistry === null) {
            /** @var EndpointBlockRegistryInterface */
            $endpointBlockRegistry = $this->instanceManager->getInstance(EndpointBlockRegistryInterface::class);
            $this->endpointBlockRegistry = $endpointBlockRegistry;
        }
        return $this->endpointBlockRegistry;
    }
    final public function setCustomEndpointAnnotatorRegistry(CustomEndpointAnnotatorRegistryInterface $customEndpointAnnotatorRegistry): void
    {
        $this->customEndpointAnnotatorRegistry = $customEndpointAnnotatorRegistry;
    }
    final protected function getCustomEndpointAnnotatorRegistry(): CustomEndpointAnnotatorRegistryInterface
    {
        if ($this->customEndpointAnnotatorRegistry === null) {
            /** @var CustomEndpointAnnotatorRegistryInterface */
            $customEndpointAnnotatorRegistry = $this->instanceManager->getInstance(CustomEndpointAnnotatorRegistryInterface::class);
            $this->customEndpointAnnotatorRegistry = $customEndpointAnnotatorRegistry;
        }
        return $this->customEndpointAnnotatorRegistry;
    }
    final public function setCustomEndpointOptionsBlock(CustomEndpointOptionsBlock $customEndpointOptionsBlock): void
    {
        $this->customEndpointOptionsBlock = $customEndpointOptionsBlock;
    }
    final protected function getCustomEndpointOptionsBlock(): CustomEndpointOptionsBlock
    {
        if ($this->customEndpointOptionsBlock === null) {
            /** @var CustomEndpointOptionsBlock */
            $customEndpointOptionsBlock = $this->instanceManager->getInstance(CustomEndpointOptionsBlock::class);
            $this->customEndpointOptionsBlock = $customEndpointOptionsBlock;
        }
        return $this->customEndpointOptionsBlock;
    }
    final public function setGraphQLEndpointCategoryTaxonomy(GraphQLEndpointCategoryTaxonomy $graphQLEndpointCategoryTaxonomy): void
    {
        $this->graphQLEndpointCategoryTaxonomy = $graphQLEndpointCategoryTaxonomy;
    }
    final protected function getGraphQLEndpointCategoryTaxonomy(): GraphQLEndpointCategoryTaxonomy
    {
        if ($this->graphQLEndpointCategoryTaxonomy === null) {
            /** @var GraphQLEndpointCategoryTaxonomy */
            $graphQLEndpointCategoryTaxonomy = $this->instanceManager->getInstance(GraphQLEndpointCategoryTaxonomy::class);
            $this->graphQLEndpointCategoryTaxonomy = $graphQLEndpointCategoryTaxonomy;
        }
        return $this->graphQLEndpointCategoryTaxonomy;
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
        return \__('GraphQL custom endpoint', 'gatographql');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $titleCase Indicate if the name must be title case (for starting a sentence) or, otherwise, lowercase
     */
    protected function getCustomPostTypePluralNames(bool $titleCase): string
    {
        return \__('GraphQL Custom Endpoints', 'gatographql');
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
                'all_items' => \__('Custom Endpoints', 'gatographql'),
            )
        );
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
     * Label to show on the "execute" action in the CPT table
     */
    protected function getExecuteActionLabel(): string
    {
        return __('View endpoint', 'gatographql');
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
