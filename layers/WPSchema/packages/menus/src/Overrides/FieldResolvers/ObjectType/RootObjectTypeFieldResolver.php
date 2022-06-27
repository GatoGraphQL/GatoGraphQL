<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\Overrides\FieldResolvers\ObjectType;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoPCMSSchema\Menus\FieldResolvers\ObjectType\RootObjectTypeFieldResolver as UpstreamRootObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootObjectTypeFieldResolver extends UpstreamRootObjectTypeFieldResolver
{
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        switch ($field->getName()) {
            case 'menu':
                $menuParam = null;
                $by = $field->getArgument('by')?->getValue()->getValue();
                if (isset($by->slug)) {
                    $menuParam = $by->slug;
                } elseif (isset($by->location)) {
                    $locations = \get_nav_menu_locations();
                    $menuParam = $locations[$by->location] ?? null;
                    if ($menuParam === null) {
                        return null;
                    }
                }
                if ($menuParam === null) {
                    return parent::resolveValue($objectTypeResolver, $object, $field, $objectTypeFieldResolutionFeedbackStore);
                }
                $menu = wp_get_nav_menu_object($menuParam);
                if ($menu === false) {
                    return null;
                }
                return $menu->term_id;
        }

        return parent::resolveValue($objectTypeResolver, $object, $field, $objectTypeFieldResolutionFeedbackStore);
    }
}
