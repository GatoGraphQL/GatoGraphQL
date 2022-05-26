<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\ParentIDFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\SearchFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\SlugsFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;

abstract class AbstractTaxonomiesFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?ParentIDFilterInputProcessor $parentIDFilterInputProcessor = null;
    private ?SearchFilterInputProcessor $searchFilterInputProcessor = null;
    private ?SlugsFilterInputProcessor $slugsFilterInputProcessor = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setParentIDFilterInputProcessor(ParentIDFilterInputProcessor $parentIDFilterInputProcessor): void
    {
        $this->parentIDFilterInputProcessor = $parentIDFilterInputProcessor;
    }
    final protected function getParentIDFilterInputProcessor(): ParentIDFilterInputProcessor
    {
        return $this->parentIDFilterInputProcessor ??= $this->instanceManager->getInstance(ParentIDFilterInputProcessor::class);
    }
    final public function setSearchFilterInputProcessor(SearchFilterInputProcessor $searchFilterInputProcessor): void
    {
        $this->searchFilterInputProcessor = $searchFilterInputProcessor;
    }
    final protected function getSearchFilterInputProcessor(): SearchFilterInputProcessor
    {
        return $this->searchFilterInputProcessor ??= $this->instanceManager->getInstance(SearchFilterInputProcessor::class);
    }
    final public function setSlugsFilterInputProcessor(SlugsFilterInputProcessor $slugsFilterInputProcessor): void
    {
        $this->slugsFilterInputProcessor = $slugsFilterInputProcessor;
    }
    final protected function getSlugsFilterInputProcessor(): SlugsFilterInputProcessor
    {
        return $this->slugsFilterInputProcessor ??= $this->instanceManager->getInstance(SlugsFilterInputProcessor::class);
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

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputProcessorInterface
    {
        return match ($inputFieldName) {
            'search' => $this->getSearchFilterInputProcessor(),
            'slugs' => $this->getSlugsFilterInputProcessor(),
            'parentID' => $this->getParentIDFilterInputProcessor(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
