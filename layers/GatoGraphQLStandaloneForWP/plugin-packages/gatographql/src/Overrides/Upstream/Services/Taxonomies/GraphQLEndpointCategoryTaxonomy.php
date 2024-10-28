<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\Services\Taxonomies;

use GatoGraphQL\GatoGraphQL\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy as UpstreamGraphQLEndpointCategoryTaxonomy;

class GraphQLEndpointCategoryTaxonomy extends UpstreamGraphQLEndpointCategoryTaxonomy
{
    public function showInMenu(): ?string
    {
        if (!$this->isServiceEnabled()) {
            return null;
        }
        return parent::showInMenu();
    }

    protected function isPublic(): bool
    {
        if (!$this->isServiceEnabled()) {
            return false;
        }
        return parent::isPublic();
    }

    protected function isPubliclyQueryable(): bool
    {
        if (!$this->isServiceEnabled()) {
            return true;
        }
        return parent::isPubliclyQueryable();
    }
}
