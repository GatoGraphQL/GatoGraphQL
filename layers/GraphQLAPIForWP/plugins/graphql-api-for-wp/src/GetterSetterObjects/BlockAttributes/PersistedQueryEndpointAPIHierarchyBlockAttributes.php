<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\GetterSetterObjects\BlockAttributes;

class PersistedQueryEndpointAPIHierarchyBlockAttributes
{
    protected bool $inheritQuery;
    public function __construct(bool $inheritQuery)
    {
        $this->inheritQuery = $inheritQuery;
    }

    public function isInheritQuery(): bool
    {
        return $this->inheritQuery;
    }
}
