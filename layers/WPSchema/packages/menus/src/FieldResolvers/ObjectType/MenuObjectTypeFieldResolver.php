<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;
use PoPWPSchema\Menus\TypeResolvers\ScalarType\MenuLocationSelectableStringTypeResolver;
use WP_Term;

class MenuObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?MenuLocationSelectableStringTypeResolver $menuLocationEnumTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setMenuLocationSelectableStringTypeResolver(MenuLocationSelectableStringTypeResolver $menuLocationEnumTypeResolver): void
    {
        $this->menuLocationEnumTypeResolver = $menuLocationEnumTypeResolver;
    }
    final protected function getMenuLocationSelectableStringTypeResolver(): MenuLocationSelectableStringTypeResolver
    {
        return $this->menuLocationEnumTypeResolver ??= $this->instanceManager->getInstance(MenuLocationSelectableStringTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            MenuObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'name',
            'slug',
            'count',
            'locations',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'name',
            'slug'
                => $this->getStringScalarTypeResolver(),
            'locations'
                => $this->getMenuLocationSelectableStringTypeResolver(),
            'count'
                => $this->getIntScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'count'
                => SchemaTypeModifiers::NON_NULLABLE,
            'locations'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'name' => $this->__('Menu\'s name', 'pop-menus'),
            'slug' => $this->__('Menu\'s slug', 'pop-menus'),
            'count' => $this->__('Number of items contained in the menu', 'pop-menus'),
            'locations' => $this->__('To which locations has the menu been assigned to', 'pop-menus'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        /** @var WP_Term */
        $menu = $object;
        $menuID = $objectTypeResolver->getID($menu);
        switch ($fieldName) {
            case 'name':
                return $menu->name;
            case 'slug':
                return $menu->slug;
            case 'count':
                return $menu->count;
            case 'locations':
                $locationMenuIDs = \get_nav_menu_locations();
                return array_keys(
                    array_filter(
                        $locationMenuIDs,
                        fn (string | int $locationMenuID) => $locationMenuID === $menuID
                    )
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
