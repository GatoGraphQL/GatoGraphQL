<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeHelpers;

use PoPSchema\CustomPosts\ComponentConfiguration;
use PoPSchema\CustomPosts\TypeResolvers\Union\CustomPostUnionTypeResolver;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoPSchema\CustomPosts\ObjectTypeResolverPickers\CustomPostTypeResolverPickerInterface;

/**
 * In the context of WordPress, "Custom Posts" are all posts (eg: posts, pages, attachments, events, etc)
 * Hence, this class can simply inherit from the Post dataloader, and add the post-types for all required types
 */
class CustomPostUnionTypeHelpers
{
    /**
     * Obtain the post types from all member typeResolvers
     */
    public static function getTargetObjectTypeResolverCustomPostTypes(string $unionTypeResolverClass): array
    {
        $customPostTypes = [];
        $instanceManager = InstanceManagerFacade::getInstance();
        $unionTypeResolver = $instanceManager->getInstance($unionTypeResolverClass);
        $typeResolverPickers = $unionTypeResolver->getObjectTypeResolverPickers();
        foreach ($typeResolverPickers as $typeResolverPicker) {
            // The picker should implement interface CustomPostTypeResolverPickerInterface
            if ($typeResolverPicker instanceof CustomPostTypeResolverPickerInterface) {
                $customPostTypes[] = $typeResolverPicker->getCustomPostType();
            }
        }
        return $customPostTypes;
    }

    /**
     * Based on `getUnionOrTargetObjectTypeResolverClass` from class
     * \PoP\ComponentModel\TypeResolvers\Union\UnionTypeHelpers, but applied
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
    public static function getCustomPostUnionOrTargetObjectTypeResolverClass(
        string $unionTypeResolverClass = CustomPostUnionTypeResolver::class
    ): ?string {
        $instanceManager = InstanceManagerFacade::getInstance();
        $unionTypeResolver = $instanceManager->getInstance($unionTypeResolverClass);
        $targetTypeResolverClasses = $unionTypeResolver->getTargetObjectTypeResolverClasses();
        if ($targetTypeResolverClasses) {
            // By configuration: If there is only 1 item, return only that one
            if (ComponentConfiguration::useSingleTypeInsteadOfCustomPostUnionType()) {
                return count($targetTypeResolverClasses) == 1 ?
                    $targetTypeResolverClasses[0] :
                    $unionTypeResolverClass;
            }
            return $unionTypeResolverClass;
        }
        return null;
    }
}
