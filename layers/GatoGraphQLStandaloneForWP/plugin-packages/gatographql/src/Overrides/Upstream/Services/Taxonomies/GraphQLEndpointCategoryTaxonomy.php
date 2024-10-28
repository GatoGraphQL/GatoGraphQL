<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\Services\Taxonomies;

use GatoGraphQL\GatoGraphQL\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy as UpstreamGraphQLEndpointCategoryTaxonomy;

class GraphQLEndpointCategoryTaxonomy extends UpstreamGraphQLEndpointCategoryTaxonomy
{
    public function showInMenu(): ?string
    {
        if ($this->isServiceEnabled()) {
            return parent::showInMenu();
        }
        return null;
    }

    protected function isPublic(): bool
    {
        if ($this->isServiceEnabled()) {
            return parent::isPublic();
        }
        return false;
    }

    protected function isPubliclyQueryable(): bool
    {
        if ($this->isServiceEnabled()) {
            return parent::isPubliclyQueryable();
        }
        return true;
    }
}
