<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractTransientObjectObjectTypeResolver;

abstract class AbstractErrorPayloadObjectTypeResolver extends AbstractTransientObjectObjectTypeResolver
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload', 'schema-commons');
    }
}
