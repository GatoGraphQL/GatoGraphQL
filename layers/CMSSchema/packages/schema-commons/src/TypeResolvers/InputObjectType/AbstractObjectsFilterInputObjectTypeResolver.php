<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputs\ExcludeIDsFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\IncludeFilterInput;

abstract class AbstractObjectsFilterInputObjectTypeResolver extends AbstractQueryableInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?ExcludeIDsFilterInput $excludeIDsFilterInput = null;
    private ?IncludeFilterInput $includeFilterInput = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        /** @var IDScalarTypeResolver */
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setExcludeIDsFilterInput(ExcludeIDsFilterInput $excludeIDsFilterInput): void
    {
        $this->excludeIDsFilterInput = $excludeIDsFilterInput;
    }
    final protected function getExcludeIDsFilterInput(): ExcludeIDsFilterInput
    {
        /** @var ExcludeIDsFilterInput */
        return $this->excludeIDsFilterInput ??= $this->instanceManager->getInstance(ExcludeIDsFilterInput::class);
    }
    final public function setIncludeFilterInput(IncludeFilterInput $includeFilterInput): void
    {
        $this->includeFilterInput = $includeFilterInput;
    }
    final protected function getIncludeFilterInput(): IncludeFilterInput
    {
        /** @var IncludeFilterInput */
        return $this->includeFilterInput ??= $this->instanceManager->getInstance(IncludeFilterInput::class);
    }

    /**
     * @return array<string, InputTypeResolverInterface>
     */
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

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return match ($inputFieldName) {
            'ids' => $this->getIncludeFilterInput(),
            'excludeIDs' => $this->getExcludeIDsFilterInput(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
