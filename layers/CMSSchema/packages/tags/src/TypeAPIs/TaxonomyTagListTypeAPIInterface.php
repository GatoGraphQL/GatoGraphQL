<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeAPIs;

interface TaxonomyTagListTypeAPIInterface
{
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTaxonomyTags(
        string $catTaxonomy,
        array $query,
        array $options = [],
    ): array;

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTaxonomyTagCount(
        string $catTaxonomy,
        array $query,
        array $options = [],
    ): int;
}
