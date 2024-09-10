<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\CustomPostTypes;

use GatoGraphQL\GatoGraphQL\Registries\BlockRegistryInterface;
use GatoGraphQL\GatoGraphQL\Registries\EndpointAnnotatorRegistryInterface;
use GatoGraphQL\GatoGraphQL\Registries\PersistedQueryEndpointAnnotatorRegistryInterface;
use GatoGraphQL\GatoGraphQL\Registries\PersistedQueryEndpointBlockRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\PersistedQueryEndpointOptionsBlock;
use GatoGraphQL\GatoGraphQL\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy;
use GatoGraphQL\GatoGraphQL\Services\Taxonomies\TaxonomyInterface;

abstract class AbstractGraphQLPersistedQueryEndpointCustomPostType extends AbstractGraphQLEndpointCustomPostType
{
    use WithBlockRegistryCustomPostTypeTrait;

    private ?PersistedQueryEndpointBlockRegistryInterface $persistedQueryEndpointBlockRegistry = null;
    private ?PersistedQueryEndpointOptionsBlock $persistedQueryEndpointOptionsBlock = null;
    private ?GraphQLEndpointCategoryTaxonomy $graphQLEndpointCategoryTaxonomy = null;
    private ?PersistedQueryEndpointAnnotatorRegistryInterface $persistedQueryEndpointAnnotatorRegistry = null;

    final public function setPersistedQueryEndpointBlockRegistry(PersistedQueryEndpointBlockRegistryInterface $persistedQueryEndpointBlockRegistry): void
    {
        $this->persistedQueryEndpointBlockRegistry = $persistedQueryEndpointBlockRegistry;
    }
    final protected function getPersistedQueryEndpointBlockRegistry(): PersistedQueryEndpointBlockRegistryInterface
    {
        if ($this->persistedQueryEndpointBlockRegistry === null) {
            /** @var PersistedQueryEndpointBlockRegistryInterface */
            $persistedQueryEndpointBlockRegistry = $this->instanceManager->getInstance(PersistedQueryEndpointBlockRegistryInterface::class);
            $this->persistedQueryEndpointBlockRegistry = $persistedQueryEndpointBlockRegistry;
        }
        return $this->persistedQueryEndpointBlockRegistry;
    }
    final public function setPersistedQueryEndpointOptionsBlock(PersistedQueryEndpointOptionsBlock $persistedQueryEndpointOptionsBlock): void
    {
        $this->persistedQueryEndpointOptionsBlock = $persistedQueryEndpointOptionsBlock;
    }
    final protected function getPersistedQueryEndpointOptionsBlock(): PersistedQueryEndpointOptionsBlock
    {
        if ($this->persistedQueryEndpointOptionsBlock === null) {
            /** @var PersistedQueryEndpointOptionsBlock */
            $persistedQueryEndpointOptionsBlock = $this->instanceManager->getInstance(PersistedQueryEndpointOptionsBlock::class);
            $this->persistedQueryEndpointOptionsBlock = $persistedQueryEndpointOptionsBlock;
        }
        return $this->persistedQueryEndpointOptionsBlock;
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
    final public function setPersistedQueryEndpointAnnotatorRegistry(PersistedQueryEndpointAnnotatorRegistryInterface $persistedQueryEndpointAnnotatorRegistry): void
    {
        $this->persistedQueryEndpointAnnotatorRegistry = $persistedQueryEndpointAnnotatorRegistry;
    }
    final protected function getPersistedQueryEndpointAnnotatorRegistry(): PersistedQueryEndpointAnnotatorRegistryInterface
    {
        if ($this->persistedQueryEndpointAnnotatorRegistry === null) {
            /** @var PersistedQueryEndpointAnnotatorRegistryInterface */
            $persistedQueryEndpointAnnotatorRegistry = $this->instanceManager->getInstance(PersistedQueryEndpointAnnotatorRegistryInterface::class);
            $this->persistedQueryEndpointAnnotatorRegistry = $persistedQueryEndpointAnnotatorRegistry;
        }
        return $this->persistedQueryEndpointAnnotatorRegistry;
    }

    /**
     * Label to show on the "execute" action in the CPT table
     */
    protected function getExecuteActionLabel(): string
    {
        return __('Execute query', 'gatographql');
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

    protected function getEndpointAnnotatorRegistry(): EndpointAnnotatorRegistryInterface
    {
        return $this->getPersistedQueryEndpointAnnotatorRegistry();
    }

    public function isHierarchical(): bool
    {
        return true;
    }

    protected function getBlockRegistry(): BlockRegistryInterface
    {
        return $this->getPersistedQueryEndpointBlockRegistry();
    }

    /**
     * Indicates if to lock the Gutenberg templates
     */
    protected function lockGutenbergTemplate(): bool
    {
        return true;
    }

    public function getEndpointOptionsBlock(): BlockInterface
    {
        return $this->getPersistedQueryEndpointOptionsBlock();
    }
}
