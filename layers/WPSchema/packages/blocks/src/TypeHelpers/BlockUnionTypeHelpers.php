<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\TypeHelpers;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoPWPSchema\Blocks\Module;
use PoPWPSchema\Blocks\ModuleConfiguration;
use PoPWPSchema\Blocks\TypeResolvers\UnionType\BlockUnionTypeResolver;

class BlockUnionTypeHelpers
{
    /**
     * Return a class or another depending on these possibilities:
     *
     *   - If there is more than 1 target type resolver for the Union, return the Union
     *   - (By configuration) If there is only one target, return that one directly
     *     and not the Union (since it's more efficient)
     */
    public static function getBlockUnionOrTargetObjectTypeResolver(
        ?UnionTypeResolverInterface $unionTypeResolver = null
    ): UnionTypeResolverInterface|ObjectTypeResolverInterface {
        if ($unionTypeResolver === null) {
            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var BlockUnionTypeResolver */
            $unionTypeResolver = $instanceManager->getInstance(BlockUnionTypeResolver::class);
        }

        $targetTypeResolvers = $unionTypeResolver->getTargetObjectTypeResolvers();
        if ($targetTypeResolvers === []) {
            return $unionTypeResolver;
        }

        /**
         * If there is only 1 item, check if the configuration if
         * to return only that one
         */
        if (count($targetTypeResolvers) === 1) {
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            return $moduleConfiguration->useSingleTypeInsteadOfBlockUnionType()
                ? $targetTypeResolvers[0]
                : $unionTypeResolver;
        }

        return $unionTypeResolver;
    }
}
