<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\Overrides\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoPCMSSchema\Menus\FieldResolvers\ObjectType\RootObjectTypeFieldResolver as UpstreamRootObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootObjectTypeFieldResolver extends UpstreamRootObjectTypeFieldResolver
{
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        switch ($fieldDataAccessor->getFieldName()) {
            case 'menu':
                $menuParam = null;
                $by = $fieldDataAccessor->getValue('by');
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
                    return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
                }
                $menu = wp_get_nav_menu_object($menuParam);
                if ($menu === false) {
                    return null;
                }
                return $menu->term_id;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
