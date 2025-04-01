<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\SchemaHooks;

use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\CreateGenericCommentInputObjectTypeResolverInterface;
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\UpdateGenericCommentInputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;

trait GenericCommentMutationResolverHookSetTrait
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreateGenericCommentInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdateGenericCommentInputObjectTypeResolverInterface;
    }
}
