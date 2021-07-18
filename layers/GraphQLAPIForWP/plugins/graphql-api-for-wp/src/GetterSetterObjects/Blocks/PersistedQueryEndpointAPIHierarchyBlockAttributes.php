<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\GetterSetterObjects\Blocks;

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
