<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\SchemaHooks;

use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\TagMetaMutations\SchemaHooks\AbstractTagMutationResolverHookSet;

class PostTagMutationResolverHookSet extends AbstractTagMutationResolverHookSet
{
    use PostTagMutationResolverHookSetTrait;

    private ?PostTagObjectTypeResolver $postTagObjectTypeResolver = null;

    final protected function getPostTagObjectTypeResolver(): PostTagObjectTypeResolver
    {
        if ($this->postTagObjectTypeResolver === null) {
            /** @var PostTagObjectTypeResolver */
            $postTagObjectTypeResolver = $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
            $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
        }
        return $this->postTagObjectTypeResolver;
    }

    protected function getTagTypeResolver(): TagObjectTypeResolverInterface
    {
        return $this->getPostTagObjectTypeResolver();
    }
}
