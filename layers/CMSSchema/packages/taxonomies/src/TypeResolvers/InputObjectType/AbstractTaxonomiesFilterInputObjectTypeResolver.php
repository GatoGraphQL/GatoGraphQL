<?php

declare(strict_types=1);

namespace PoPSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor as SchemaCommonsFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;

abstract class AbstractTaxonomiesFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    abstract protected function addParentIDInputField(): bool;

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'search' => $this->getStringScalarTypeResolver(),
                'slugs' => $this->getStringScalarTypeResolver(),
            ],
            $this->addParentIDInputField() ? [
                'parentID' => $this->getIDScalarTypeResolver(),
            ] : []
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'search' => $this->__('Search for taxonomies containing the given string', 'taxonomies'),
            'slugs' => $this->__('Search for taxonomies with the given slugs', 'taxonomies'),
            'parentID' => $this->__('Limit results to taxonomies with the given parent ID. \'0\' means \'no parent\'', 'taxonomies'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'slugs' => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?array
    {
        return match ($inputFieldName) {
            'search' => [SchemaCommonsFilterInputProcessor::class, SchemaCommonsFilterInputProcessor::FILTERINPUT_SEARCH],
            'slugs' => [SchemaCommonsFilterInputProcessor::class, SchemaCommonsFilterInputProcessor::FILTERINPUT_SLUGS],
            'parentID' => [SchemaCommonsFilterInputProcessor::class, SchemaCommonsFilterInputProcessor::FILTERINPUT_PARENT_ID],
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
