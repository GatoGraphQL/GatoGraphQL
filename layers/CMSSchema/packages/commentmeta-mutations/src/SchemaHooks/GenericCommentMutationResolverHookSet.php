<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\SchemaHooks;

use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolverInterface;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\GenericCommentObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\SchemaHooks\AbstractCommentMutationResolverHookSet;

class GenericCommentMutationResolverHookSet extends AbstractCommentMutationResolverHookSet
{
    use GenericCommentMutationResolverHookSetTrait;

    private ?GenericCommentObjectTypeResolver $genericCommentObjectTypeResolver = null;

    final protected function getGenericCommentObjectTypeResolver(): GenericCommentObjectTypeResolver
    {
        if ($this->genericCommentObjectTypeResolver === null) {
            /** @var GenericCommentObjectTypeResolver */
            $genericCommentObjectTypeResolver = $this->instanceManager->getInstance(GenericCommentObjectTypeResolver::class);
            $this->genericCommentObjectTypeResolver = $genericCommentObjectTypeResolver;
        }
        return $this->genericCommentObjectTypeResolver;
    }

    protected function getCommentTypeResolver(): CommentObjectTypeResolverInterface
    {
        return $this->getGenericCommentObjectTypeResolver();
    }
}
