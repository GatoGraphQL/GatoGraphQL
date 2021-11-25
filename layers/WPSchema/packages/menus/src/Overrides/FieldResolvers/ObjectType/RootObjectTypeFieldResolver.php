<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\Overrides\FieldResolvers\ObjectType;

use PoPSchema\Menus\FieldResolvers\ObjectType\RootObjectTypeFieldResolver as UpstreamRootObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootObjectTypeFieldResolver extends UpstreamRootObjectTypeFieldResolver
{
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
        switch ($fieldName) {
            case 'menu':
                $menuParam = null;
                $by = $fieldArgs['by'];
                if (isset($by->slug)) {
                    $menuParam = $by->slug;
                } elseif (isset($by->location)) {
                    $locations = \get_nav_menu_locations();
                    $menuParam = $locations[$by->location] ?? null;
                }
                if ($menuParam === null) {
                    return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
                }
                $menu = wp_get_nav_menu_object($menuParam);
                if ($menu === false) {
                    return null;
                }
                return $menu->term_id;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
