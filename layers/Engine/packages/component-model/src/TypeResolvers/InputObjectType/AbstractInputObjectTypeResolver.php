<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

abstract class AbstractInputObjectTypeResolver extends AbstractTypeResolver implements InputObjectTypeResolverInterface
{
    public function getInputObjectFieldDescription(string $inputObjectFieldName): ?string
    {
        return null;
    }
    public function getInputObjectFieldDeprecationMessage(string $inputObjectFieldName): ?string
    {
        return null;
    }
    public function getInputObjectFieldDefaultValue(string $inputObjectFieldName): mixed
    {
        return null;
    }
    public function getInputObjectFieldTypeModifiers(string $inputObjectFieldName): int
    {
        return SchemaTypeModifiers::NONE;
    }

    /**
     * This function simply returns the same value always.
     */
    public function coerceValue(mixed $inputValue): mixed
    {
        return $inputValue;
    }
}
