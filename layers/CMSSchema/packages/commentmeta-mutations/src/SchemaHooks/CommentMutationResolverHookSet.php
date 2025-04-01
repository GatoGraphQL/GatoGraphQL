<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\SchemaHooks;

use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolverInterface;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\SchemaHooks\AbstractCommentMutationResolverHookSet;

class CommentMutationResolverHookSet extends AbstractCommentMutationResolverHookSet
{
    use CommentMutationResolverHookSetTrait;

    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;

    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        if ($this->commentObjectTypeResolver === null) {
            /** @var CommentObjectTypeResolver */
            $commentObjectTypeResolver = $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
            $this->commentObjectTypeResolver = $commentObjectTypeResolver;
        }
        return $this->commentObjectTypeResolver;
    }

    protected function getCommentTypeResolver(): CommentObjectTypeResolverInterface
    {
        return $this->getCommentObjectTypeResolver();
    }
}
