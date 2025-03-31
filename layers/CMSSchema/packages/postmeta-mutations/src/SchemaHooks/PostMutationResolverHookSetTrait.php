<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\SchemaHooks;

use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\CreatePostInputObjectTypeResolverInterface;
use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\UpdatePostInputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;

trait PostMutationResolverHookSetTrait
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreatePostInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdatePostInputObjectTypeResolverInterface;
    }
}
