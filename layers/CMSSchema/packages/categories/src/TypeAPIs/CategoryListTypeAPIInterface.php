<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeAPIs;

interface CategoryListTypeAPIInterface
{
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCategories(array $query, array $options = []): array;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCategoryCount(array $query, array $options = []): int;
}
