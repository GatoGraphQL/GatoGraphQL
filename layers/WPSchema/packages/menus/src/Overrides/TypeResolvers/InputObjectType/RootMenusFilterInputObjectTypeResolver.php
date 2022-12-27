<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\Overrides\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPCMSSchema\Menus\TypeResolvers\InputObjectType\RootMenusFilterInputObjectTypeResolver as UpstreamRootMenusFilterInputObjectTypeResolver;
use PoPWPSchema\Menus\FilterInputs\LocationsFilterInput;
use PoPWPSchema\Menus\TypeResolvers\ScalarType\MenuLocationEnumStringTypeResolver;

class RootMenusFilterInputObjectTypeResolver extends UpstreamRootMenusFilterInputObjectTypeResolver
{
    private ?MenuLocationEnumStringTypeResolver $menuLocationEnumTypeResolver = null;
    private ?LocationsFilterInput $locationsFilterInput = null;

    final public function setMenuLocationEnumStringTypeResolver(MenuLocationEnumStringTypeResolver $menuLocationEnumTypeResolver): void
    {
        $this->menuLocationEnumTypeResolver = $menuLocationEnumTypeResolver;
    }
    final protected function getMenuLocationEnumStringTypeResolver(): MenuLocationEnumStringTypeResolver
    {
        /** @var MenuLocationEnumStringTypeResolver */
        return $this->menuLocationEnumTypeResolver ??= $this->instanceManager->getInstance(MenuLocationEnumStringTypeResolver::class);
    }
    final public function setLocationsFilterInput(LocationsFilterInput $locationsFilterInput): void
    {
        $this->locationsFilterInput = $locationsFilterInput;
    }
    final protected function getLocationsFilterInput(): LocationsFilterInput
    {
        /** @var LocationsFilterInput */
        return $this->locationsFilterInput ??= $this->instanceManager->getInstance(LocationsFilterInput::class);
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'locations' => $this->getMenuLocationEnumStringTypeResolver(),
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

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return match ($inputFieldName) {
            'locations' => $this->getLocationsFilterInput(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
