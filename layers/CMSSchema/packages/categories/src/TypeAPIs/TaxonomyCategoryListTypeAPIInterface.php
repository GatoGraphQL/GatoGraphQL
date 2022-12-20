<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeAPIs;

interface TaxonomyCategoryListTypeAPIInterface
{
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTaxonomyCategories(
        string $catTaxonomy,
        array $query,
        array $options = [],
    ): array;

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTaxonomyCategoryCount(
        string $catTaxonomy,
        array $query,
        array $options = [],
    ): int;
}
