<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\SlugsFilterInput;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;
use PoPCMSSchema\Taxonomies\FilterInputs\HideEmptyFilterInput;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

abstract class AbstractTaxonomiesFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?ParentIDFilterInput $parentIDFilterInput = null;
    private ?SearchFilterInput $searchFilterInput = null;
    private ?SlugsFilterInput $slugsFilterInput = null;
    private ?HideEmptyFilterInput $hideEmptyFilterInput = null;

    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }
    final protected function getParentIDFilterInput(): ParentIDFilterInput
    {
        if ($this->parentIDFilterInput === null) {
            /** @var ParentIDFilterInput */
            $parentIDFilterInput = $this->instanceManager->getInstance(ParentIDFilterInput::class);
            $this->parentIDFilterInput = $parentIDFilterInput;
        }
        return $this->parentIDFilterInput;
    }
    final protected function getSearchFilterInput(): SearchFilterInput
    {
        if ($this->searchFilterInput === null) {
            /** @var SearchFilterInput */
            $searchFilterInput = $this->instanceManager->getInstance(SearchFilterInput::class);
            $this->searchFilterInput = $searchFilterInput;
        }
        return $this->searchFilterInput;
    }
    final protected function getSlugsFilterInput(): SlugsFilterInput
    {
        if ($this->slugsFilterInput === null) {
            /** @var SlugsFilterInput */
            $slugsFilterInput = $this->instanceManager->getInstance(SlugsFilterInput::class);
            $this->slugsFilterInput = $slugsFilterInput;
        }
        return $this->slugsFilterInput;
    }
    final protected function getHideEmptyFilterInput(): HideEmptyFilterInput
    {
        if ($this->hideEmptyFilterInput === null) {
            /** @var HideEmptyFilterInput */
            $hideEmptyFilterInput = $this->instanceManager->getInstance(HideEmptyFilterInput::class);
            $this->hideEmptyFilterInput = $hideEmptyFilterInput;
        }
        return $this->hideEmptyFilterInput;
    }

    abstract protected function addParentIDInputField(): bool;

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'search' => $this->getStringScalarTypeResolver(),
                'slugs' => $this->getStringScalarTypeResolver(),
                'hideEmpty' => $this->getBooleanScalarTypeResolver(),
            ],
            $this->addParentIDInputField() ? [
                'parentID' => $this->getIDScalarTypeResolver(),
            ] : [],
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'search' => $this->__('Search for taxonomies containing the given string', 'taxonomies'),
            'slugs' => $this->__('Search for taxonomies with the given slugs', 'taxonomies'),
            'hideEmpty' => $this->__('Hide empty taxonomies terms?', 'taxonomies'),
            'parentID' => $this->__('Limit results to taxonomies with the given parent ID. \'0\' means \'no parent\'', 'taxonomies'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'slugs' => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            'hideEmpty' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'hideEmpty' => false,
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return match ($inputFieldName) {
            'search' => $this->getSearchFilterInput(),
            'slugs' => $this->getSlugsFilterInput(),
            'hideEmpty' => $this->getHideEmptyFilterInput(),
            'parentID' => $this->getParentIDFilterInput(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
