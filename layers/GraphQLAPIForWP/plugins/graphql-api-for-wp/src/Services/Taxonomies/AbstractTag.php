<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Taxonomies;

abstract class AbstractTag extends AbstractTaxonomy
{
    public function isHierarchical(): bool
    {
        return false;
    }
}
