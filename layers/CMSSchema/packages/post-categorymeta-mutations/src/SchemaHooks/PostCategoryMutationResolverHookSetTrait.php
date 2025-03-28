<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\SchemaHooks;

use PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType\CreatePostCategoryTermInputObjectTypeResolverInterface;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType\UpdatePostCategoryTermInputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;

trait PostCategoryMutationResolverHookSetTrait
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreatePostCategoryTermInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdatePostCategoryTermInputObjectTypeResolverInterface;
    }
}
