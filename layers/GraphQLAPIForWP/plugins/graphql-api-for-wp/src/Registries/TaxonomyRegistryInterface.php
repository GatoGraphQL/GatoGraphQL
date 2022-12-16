<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\Taxonomies\TaxonomyInterface;

interface TaxonomyRegistryInterface
{
    public function addTaxonomy(TaxonomyInterface $taxonomy, string $serviceDefinitionID): void;

    /**
     * @param boolean|null $isHierarchical `true` => category, `false` => tag, `null` => categories + tags
     * @return array<string,TaxonomyInterface>
     */
    public function getTaxonomies(?bool $isHierarchical = null): array;
}
