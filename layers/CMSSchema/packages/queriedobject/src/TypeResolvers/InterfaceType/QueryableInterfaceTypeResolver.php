<?php

declare(strict_types=1);

namespace PoPCMSSchema\QueriedObject\TypeResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\AbstractInterfaceTypeResolver;

class QueryableInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'Queryable';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Entities that can be queried through an URL', 'queriedobject');
    }
}
