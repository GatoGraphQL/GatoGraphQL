<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Taxonomies;

interface TaxonomyInterface
{
    public function getTaxonomy(): string;

    public function getTaxonomyName(bool $titleCase = true): string;

    public function isHierarchical(): bool;
}
