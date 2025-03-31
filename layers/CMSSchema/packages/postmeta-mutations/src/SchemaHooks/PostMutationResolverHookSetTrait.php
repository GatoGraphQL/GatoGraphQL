<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\SchemaHooks;

use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\CreatePostTermInputObjectTypeResolverInterface;
use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\UpdatePostTermInputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;

trait PostMutationResolverHookSetTrait
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreatePostTermInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdatePostTermInputObjectTypeResolverInterface;
    }
}
