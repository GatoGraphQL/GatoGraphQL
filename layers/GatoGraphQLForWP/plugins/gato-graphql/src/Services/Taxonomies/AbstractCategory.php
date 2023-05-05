<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Taxonomies;

abstract class AbstractCategory extends AbstractTaxonomy
{
    public function isHierarchical(): bool
    {
        return true;
    }
}
