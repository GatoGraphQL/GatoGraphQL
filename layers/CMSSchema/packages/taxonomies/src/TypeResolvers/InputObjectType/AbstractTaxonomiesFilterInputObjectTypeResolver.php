<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\SlugsFilterInput;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;

abstract class AbstractTaxonomiesFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?ParentIDFilterInput $parentIDFilterInput = null;
    private ?SearchFilterInput $searchFilterInput = null;
    private ?SlugsFilterInput $slugsFilterInput = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setParentIDFilterInput(ParentIDFilterInput $parentIDFilterInput): void
    {
        $this->parentIDFilterInput = $parentIDFilterInput;
    }
    final protected function getParentIDFilterInput(): ParentIDFilterInput
    {
        return $this->parentIDFilterInput ??= $this->instanceManager->getInstance(ParentIDFilterInput::class);
    }
    final public function setSearchFilterInput(SearchFilterInput $searchFilterInput): void
    {
        $this->searchFilterInput = $searchFilterInput;
    }
    final protected function getSearchFilterInput(): SearchFilterInput
    {
        return $this->searchFilterInput ??= $this->instanceManager->getInstance(SearchFilterInput::class);
    }
    final public function setSlugsFilterInput(SlugsFilterInput $slugsFilterInput): void
    {
        $this->slugsFilterInput = $slugsFilterInput;
    }
    final protected function getSlugsFilterInput(): SlugsFilterInput
    {
        return $this->slugsFilterInput ??= $this->instanceManager->getInstance(SlugsFilterInput::class);
    }

    abstract protected function addParentIDInputField(): bool;

    /**
     * @return array<string,\PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface>
     */
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

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return match ($inputFieldName) {
            'search' => $this->getSearchFilterInput(),
            'slugs' => $this->getSlugsFilterInput(),
            'parentID' => $this->getParentIDFilterInput(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
