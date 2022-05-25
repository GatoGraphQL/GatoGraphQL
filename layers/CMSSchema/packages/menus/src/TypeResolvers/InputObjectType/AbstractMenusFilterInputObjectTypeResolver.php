<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\SearchFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\SlugsFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;

abstract class AbstractMenusFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?SearchFilterInputProcessor $seachFilterInputProcessor = null;
    private ?SlugsFilterInputProcessor $slugsFilterInputProcessor = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setSearchFilterInputProcessor(SearchFilterInputProcessor $seachFilterInputProcessor): void
    {
        $this->seachFilterInputProcessor = $seachFilterInputProcessor;
    }
    final protected function getSearchFilterInputProcessor(): SearchFilterInputProcessor
    {
        return $this->seachFilterInputProcessor ??= $this->instanceManager->getInstance(SearchFilterInputProcessor::class);
    }
    final public function setSlugsFilterInputProcessor(SlugsFilterInputProcessor $slugsFilterInputProcessor): void
    {
        $this->slugsFilterInputProcessor = $slugsFilterInputProcessor;
    }
    final protected function getSlugsFilterInputProcessor(): SlugsFilterInputProcessor
    {
        return $this->slugsFilterInputProcessor ??= $this->instanceManager->getInstance(SlugsFilterInputProcessor::class);
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'search' => $this->getStringScalarTypeResolver(),
                'slugs' => $this->getStringScalarTypeResolver(),
            ],
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'search' => $this->__('Filter menus that contain a string', 'menus'),
            'slugs' => $this->__('Filter menus based on slug', 'menus'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'slugs' => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getInputFieldTypeModifiers($inputFieldName)
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputProcessorInterface
    {
        return match ($inputFieldName) {
            'search' => $this->getSearchFilterInputProcessor(),
            'slugs' => $this->getSlugsFilterInputProcessor(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
