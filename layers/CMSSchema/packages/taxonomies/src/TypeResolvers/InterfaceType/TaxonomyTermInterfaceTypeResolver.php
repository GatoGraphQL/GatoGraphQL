<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\AbstractInterfaceTypeResolver;

class TaxonomyTermInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'TaxonomyTerm';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Entities representing a taxonomy term', 'taxonomies');
    }
}
