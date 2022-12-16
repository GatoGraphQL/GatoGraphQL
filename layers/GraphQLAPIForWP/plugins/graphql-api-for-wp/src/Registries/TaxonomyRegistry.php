<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\Taxonomies\TaxonomyInterface;

class TaxonomyRegistry implements TaxonomyRegistryInterface
{
    /**
     * @var array<string,TaxonomyInterface>
     */
    protected array $taxonomies = [];

    public function addTaxonomy(
        TaxonomyInterface $taxonomy,
        string $serviceDefinitionID
    ): void {
        $this->taxonomies[$serviceDefinitionID] = $taxonomy;
    }

    /**
     * @param boolean|null $isHierarchical `true` => category, `false` => tag, `null` => categories + tags
     * @return array<string,TaxonomyInterface>
     */
    public function getTaxonomies(?bool $isHierarchical = null): array
    {
        if ($isHierarchical !== null) {
            return array_filter(
                $this->taxonomies,
                fn (TaxonomyInterface $taxonomy) => ($isHierarchical && $taxonomy->isHierarchical()) || (!$isHierarchical && !$taxonomy->isHierarchical())
            );
        }
        return $this->taxonomies;
    }
}
