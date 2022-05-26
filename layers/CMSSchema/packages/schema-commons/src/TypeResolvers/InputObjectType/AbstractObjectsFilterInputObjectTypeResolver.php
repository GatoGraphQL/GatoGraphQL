<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\ExcludeIDsFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\IncludeFilterInputProcessor;

abstract class AbstractObjectsFilterInputObjectTypeResolver extends AbstractQueryableInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?ExcludeIDsFilterInputProcessor $excludeIDsFilterInputProcessor = null;
    private ?IncludeFilterInputProcessor $includeFilterInputProcessor = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setExcludeIDsFilterInputProcessor(ExcludeIDsFilterInputProcessor $excludeIDsFilterInputProcessor): void
    {
        $this->excludeIDsFilterInputProcessor = $excludeIDsFilterInputProcessor;
    }
    final protected function getExcludeIDsFilterInputProcessor(): ExcludeIDsFilterInputProcessor
    {
        return $this->excludeIDsFilterInputProcessor ??= $this->instanceManager->getInstance(ExcludeIDsFilterInputProcessor::class);
    }
    final public function setIncludeFilterInputProcessor(IncludeFilterInputProcessor $includeFilterInputProcessor): void
    {
        $this->includeFilterInputProcessor = $includeFilterInputProcessor;
    }
    final protected function getIncludeFilterInputProcessor(): IncludeFilterInputProcessor
    {
        return $this->includeFilterInputProcessor ??= $this->instanceManager->getInstance(IncludeFilterInputProcessor::class);
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

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputProcessorInterface
    {
        return match ($inputFieldName) {
            'ids' => $this->getIncludeFilterInputProcessor(),
            'excludeIDs' => $this->getExcludeIDsFilterInputProcessor(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
