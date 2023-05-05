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
        /** @var PersistedQueryEndpointBlockRegistryInterface */
        return $this->persistedQueryEndpointBlockRegistry ??= $this->instanceManager->getInstance(PersistedQueryEndpointBlockRegistryInterface::class);
    }
    final public function setPersistedQueryEndpointOptionsBlock(PersistedQueryEndpointOptionsBlock $persistedQueryEndpointOptionsBlock): void
    {
        $this->persistedQueryEndpointOptionsBlock = $persistedQueryEndpointOptionsBlock;
    }
    final protected function getPersistedQueryEndpointOptionsBlock(): PersistedQueryEndpointOptionsBlock
    {
        /** @var PersistedQueryEndpointOptionsBlock */
        return $this->persistedQueryEndpointOptionsBlock ??= $this->instanceManager->getInstance(PersistedQueryEndpointOptionsBlock::class);
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
    final public function setPersistedQueryEndpointAnnotatorRegistry(PersistedQueryEndpointAnnotatorRegistryInterface $persistedQueryEndpointAnnotatorRegistry): void
    {
        $this->persistedQueryEndpointAnnotatorRegistry = $persistedQueryEndpointAnnotatorRegistry;
    }
    final protected function getPersistedQueryEndpointAnnotatorRegistry(): PersistedQueryEndpointAnnotatorRegistryInterface
    {
        /** @var PersistedQueryEndpointAnnotatorRegistryInterface */
        return $this->persistedQueryEndpointAnnotatorRegistry ??= $this->instanceManager->getInstance(PersistedQueryEndpointAnnotatorRegistryInterface::class);
    }

    /**
     * Label to show on the "execute" action in the CPT table
     */
    protected function getExecuteActionLabel(): string
    {
        return __('Execute query', 'graphql-api');
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

    /**
     * Hierarchical
     */
    protected function isHierarchical(): bool
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
