<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\AppObjects\BlockAttributes;

class PersistedQueryEndpointAPIHierarchyBlockAttributes
{
    public function __construct(
        protected bool $inheritQuery,
    ) {
    }

    public function isInheritQuery(): bool
    {
        return $this->inheritQuery;
    }
}
