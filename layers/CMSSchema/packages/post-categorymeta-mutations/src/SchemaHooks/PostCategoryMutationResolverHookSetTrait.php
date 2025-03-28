<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\SchemaHooks;

use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType\CreatePostCategoryTermInputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType\UpdatePostCategoryTermInputObjectTypeResolverInterface;
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
