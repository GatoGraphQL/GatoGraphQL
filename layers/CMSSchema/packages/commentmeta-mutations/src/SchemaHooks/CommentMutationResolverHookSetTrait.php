<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\SchemaHooks;

use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\CreateCommentInputObjectTypeResolverInterface;
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\UpdateCommentInputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;

trait CommentMutationResolverHookSetTrait
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreateCommentInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdateCommentInputObjectTypeResolverInterface;
    }
}
