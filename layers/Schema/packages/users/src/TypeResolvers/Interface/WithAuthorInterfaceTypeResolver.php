<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeResolvers\Interface;

use PoP\ComponentModel\TypeResolvers\Interface\AbstractInterfaceTypeResolver;

class WithAuthorInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'WithAuthor';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Entities that have an author', 'queriedobject');
    }
}
