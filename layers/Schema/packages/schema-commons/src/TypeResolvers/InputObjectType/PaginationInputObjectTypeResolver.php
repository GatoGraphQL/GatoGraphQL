<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;

class PaginationInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;

    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'PaginationInput';
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'limit' => $this->getIntScalarTypeResolver(),
            'offset' => $this->getIntScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'limit' => $this->getTranslationAPI()->__('Limit the results. \'-1\' brings all the results (or the maximum amount allowed)', 'schema-commons'),
            'offset' => $this->getTranslationAPI()->__('Offset the results by how many positions', 'schema-commons'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
