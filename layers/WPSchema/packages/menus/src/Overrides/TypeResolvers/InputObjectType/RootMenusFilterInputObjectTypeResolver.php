<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\Overrides\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPCMSSchema\Menus\TypeResolvers\InputObjectType\RootMenusFilterInputObjectTypeResolver as UpstreamRootMenusFilterInputObjectTypeResolver;
use PoPWPSchema\Menus\FilterInputProcessors\LocationsFilterInputProcessor;
use PoPWPSchema\Menus\TypeResolvers\ScalarType\MenuLocationSelectableStringTypeResolver;

class RootMenusFilterInputObjectTypeResolver extends UpstreamRootMenusFilterInputObjectTypeResolver
{
    private ?MenuLocationSelectableStringTypeResolver $menuLocationEnumTypeResolver = null;
    private ?LocationsFilterInputProcessor $locationsFilterInputProcessor = null;

    final public function setMenuLocationSelectableStringTypeResolver(MenuLocationSelectableStringTypeResolver $menuLocationEnumTypeResolver): void
    {
        $this->menuLocationEnumTypeResolver = $menuLocationEnumTypeResolver;
    }
    final protected function getMenuLocationSelectableStringTypeResolver(): MenuLocationSelectableStringTypeResolver
    {
        return $this->menuLocationEnumTypeResolver ??= $this->instanceManager->getInstance(MenuLocationSelectableStringTypeResolver::class);
    }
    final public function setLocationsFilterInputProcessor(LocationsFilterInputProcessor $locationsFilterInputProcessor): void
    {
        $this->locationsFilterInputProcessor = $locationsFilterInputProcessor;
    }
    final protected function getLocationsFilterInputProcessor(): LocationsFilterInputProcessor
    {
        return $this->locationsFilterInputProcessor ??= $this->instanceManager->getInstance(LocationsFilterInputProcessor::class);
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'locations' => $this->getMenuLocationSelectableStringTypeResolver(),
            ],
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'locations' => $this->__('Filter menus based on locations', 'menus'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'locations' => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getInputFieldTypeModifiers($inputFieldName)
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputProcessorInterface
    {
        return match ($inputFieldName) {
            'locations' => $this->getLocationsFilterInputProcessor(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
