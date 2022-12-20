<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeAPIs;

interface TaxonomyTypeAPIInterface
{
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string|int>|object[]|null
     */
    public function getCustomPostTaxonomyTerms(
        string $taxonomy,
        string|int|object $customPostObjectOrID,
        array $query = [],
        array $options = [],
    ): ?array;
}
