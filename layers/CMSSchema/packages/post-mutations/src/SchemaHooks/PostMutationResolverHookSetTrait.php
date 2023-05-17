<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\CreatePostInputObjectTypeResolverInterface;
use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\UpdatePostInputObjectTypeResolverInterface;

trait PostMutationResolverHookSetTrait
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreatePostInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdatePostInputObjectTypeResolverInterface;
    }
}
