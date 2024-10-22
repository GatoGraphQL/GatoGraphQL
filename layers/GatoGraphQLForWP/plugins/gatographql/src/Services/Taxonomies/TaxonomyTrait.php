<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Taxonomies;

trait TaxonomyTrait
{
    protected function getTaxonomyNamespace(): string
    {
        return 'graphql';
    }
}