<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\Menus\Constants\MenuOrderBy;
use PoPCMSSchema\Menus\TypeResolvers\EnumType\MenuOrderByEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\SortInputObjectTypeResolver;

class MenuSortInputObjectTypeResolver extends SortInputObjectTypeResolver
{
    private ?MenuOrderByEnumTypeResolver $menuSortByEnumTypeResolver = null;

    final protected function getMenuOrderByEnumTypeResolver(): MenuOrderByEnumTypeResolver
    {
        if ($this->menuSortByEnumTypeResolver === null) {
            /** @var MenuOrderByEnumTypeResolver */
            $menuSortByEnumTypeResolver = $this->instanceManager->getInstance(MenuOrderByEnumTypeResolver::class);
            $this->menuSortByEnumTypeResolver = $menuSortByEnumTypeResolver;
        }
        return $this->menuSortByEnumTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'MenuSortInput';
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'by' => $this->getMenuOrderByEnumTypeResolver(),
            ]
        );
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'by' => MenuOrderBy::DATE,
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }
}
