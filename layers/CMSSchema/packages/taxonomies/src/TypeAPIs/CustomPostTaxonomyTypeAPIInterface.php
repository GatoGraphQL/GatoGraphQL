<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeAPIs;

interface CustomPostTaxonomyTypeAPIInterface
{
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCustomPostTaxonomyTerms(
        string $taxonomy,
        string|int|object $customPostObjectOrID,
        array $query = [],
        array $options = [],
    ): array;
}
