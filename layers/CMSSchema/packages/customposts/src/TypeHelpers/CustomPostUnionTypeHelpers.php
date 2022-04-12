<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeHelpers;

use PoP\Root\App;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\ObjectTypeResolverPickers\ObjectTypeResolverPickerInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\CustomPosts\Component;
use PoPCMSSchema\CustomPosts\ComponentConfiguration;
use PoPCMSSchema\CustomPosts\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;

/**
 * In the context of WordPress, "Custom Posts" are all posts (eg: posts, pages, attachments, events, etc)
 * Hence, this class can simply inherit from the Post dataloader, and add the post-types for all required types
 */
class CustomPostUnionTypeHelpers
{
    /**
     * Obtain the custom post types from all member typeResolvers
     */
    public static function getTargetObjectTypeResolverCustomPostTypes(?UnionTypeResolverInterface $unionTypeResolver = null): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        $unionTypeResolver ??= $instanceManager->getInstance(CustomPostUnionTypeResolver::class);
        $customPostObjectTypeResolverPickers = array_values(array_filter(
            $unionTypeResolver->getObjectTypeResolverPickers(),
            fn (ObjectTypeResolverPickerInterface $objectTypeResolverPicker) => $objectTypeResolverPicker instanceof CustomPostObjectTypeResolverPickerInterface
        ));
        return array_map(
            fn (CustomPostObjectTypeResolverPickerInterface $customPostObjectTypeResolverPicker) => $customPostObjectTypeResolverPicker->getCustomPostType(),
            $customPostObjectTypeResolverPickers
        );
    }

    /**
     * Based on `getUnionOrTargetObjectTypeResolver` from class
     * \PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeHelpers, but applied
     * to the CustomPostUnion type, to add its own configuration.
     *
     * Return a class or another depending on these possibilities:
     *
     * - If there is more than 1 target type resolver for the Union, return the Union
     * - (By configuration) If there is only one target, return that one directly
     *   and not the Union (since it's more efficient)
     * - If there are none types, return `null`. As a consequence,
     *   the ID is returned as a field, not as a connection
     */
    public static function getCustomPostUnionOrTargetObjectTypeResolver(
        ?UnionTypeResolverInterface $unionTypeResolver = null
    ): ?UnionTypeResolverInterface {
        $instanceManager = InstanceManagerFacade::getInstance();
        $unionTypeResolver ??= $instanceManager->getInstance(CustomPostUnionTypeResolver::class);
        $targetTypeResolvers = $unionTypeResolver->getTargetObjectTypeResolvers();
        if ($targetTypeResolvers) {
            // By configuration: If there is only 1 item, return only that one
            /** @var ComponentConfiguration */
            $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
            if ($componentConfiguration->useSingleTypeInsteadOfCustomPostUnionType()) {
                return count($targetTypeResolvers) === 1 ?
                    $targetTypeResolvers[0] :
                    $unionTypeResolver;
            }
            return $unionTypeResolver;
        }
        return null;
    }
}
