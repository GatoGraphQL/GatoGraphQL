<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor;

abstract class AbstractObjectsFilterInputObjectTypeResolver extends AbstractQueryableInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'ids' => $this->getIDScalarTypeResolver(),
            'excludeIDs' => $this->getIDScalarTypeResolver(),
        ];
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'ids',
            'excludeIDs'
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'ids' => $this->__('Limit results to elements with the given IDs', 'schema-commons'),
            'excludeIDs' => $this->__('Exclude elements with the given IDs', 'schema-commons'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?array
    {
        return match ($inputFieldName) {
            'ids' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_INCLUDE],
            'excludeIDs' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EXCLUDE_IDS],
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
