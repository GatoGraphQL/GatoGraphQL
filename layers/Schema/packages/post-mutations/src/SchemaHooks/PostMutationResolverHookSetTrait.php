<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPSchema\PostMutations\TypeResolvers\InputObjectType\CreatePostFilterInputObjectTypeResolverInterface;
use PoPSchema\PostMutations\TypeResolvers\InputObjectType\UpdatePostFilterInputObjectTypeResolverInterface;

trait PostMutationResolverHookSetTrait
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreatePostFilterInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdatePostFilterInputObjectTypeResolverInterface;
    }
}
