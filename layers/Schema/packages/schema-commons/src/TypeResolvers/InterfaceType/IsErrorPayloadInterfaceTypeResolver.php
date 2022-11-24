<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\AbstractInterfaceTypeResolver;

class IsErrorPayloadInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'IsErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Entities representing an Error payload', 'schema-commons');
    }
}
