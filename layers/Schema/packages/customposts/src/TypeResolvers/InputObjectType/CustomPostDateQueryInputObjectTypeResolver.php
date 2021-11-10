<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;

class CustomPostDateQueryInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?DateScalarTypeResolver $dateScalarTypeResolver = null;

    final public function setDateScalarTypeResolver(DateScalarTypeResolver $dateScalarTypeResolver): void
    {
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
    }
    final protected function getDateScalarTypeResolver(): DateScalarTypeResolver
    {
        return $this->dateScalarTypeResolver ??= $this->instanceManager->getInstance(DateScalarTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'CustomPostDateQueryInput';
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'after' => $this->getDateScalarTypeResolver(),
            'before' => $this->getDateScalarTypeResolver(),
        ];
    }
}
