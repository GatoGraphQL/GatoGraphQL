<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\SchemaHooks;

use PoPCMSSchema\CustomPostTagMutations\SchemaHooks\AbstractCustomPostMutationResolverHookSet;
use PoPCMSSchema\PostMutations\SchemaHooks\PostMutationResolverHookSetTrait;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;

class PostMutationResolverHookSet extends AbstractCustomPostMutationResolverHookSet
{
    use PostMutationResolverHookSetTrait;

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
