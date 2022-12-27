<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\Overrides\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\InputObjectType\MenuByInputObjectTypeResolver as UpstreamMenuByInputObjectTypeResolver;
use PoPWPSchema\Menus\TypeResolvers\ScalarType\MenuLocationEnumStringTypeResolver;

class MenuByInputObjectTypeResolver extends UpstreamMenuByInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?MenuLocationEnumStringTypeResolver $menuLocationEnumTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setMenuLocationEnumStringTypeResolver(MenuLocationEnumStringTypeResolver $menuLocationEnumTypeResolver): void
    {
        $this->menuLocationEnumTypeResolver = $menuLocationEnumTypeResolver;
    }
    final protected function getMenuLocationEnumStringTypeResolver(): MenuLocationEnumStringTypeResolver
    {
        /** @var MenuLocationEnumStringTypeResolver */
        return $this->menuLocationEnumTypeResolver ??= $this->instanceManager->getInstance(MenuLocationEnumStringTypeResolver::class);
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'slug' => $this->getStringScalarTypeResolver(),
                'location' => $this->getMenuLocationEnumStringTypeResolver(),
            ]
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'slug' => $this->__('Query by slug', 'menus'),
            'location' => $this->__('Query by location', 'menus'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
