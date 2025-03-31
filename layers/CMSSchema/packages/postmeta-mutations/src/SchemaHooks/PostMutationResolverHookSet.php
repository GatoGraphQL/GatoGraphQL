<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\SchemaHooks;

use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\SchemaHooks\AbstractCustomPostMutationResolverHookSet;

class PostMutationResolverHookSet extends AbstractCustomPostMutationResolverHookSet
{
    use PostMutationResolverHookSetTrait;

    private ?PostObjectTypeResolver $postObjectTypeResolver = null;

    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        if ($this->postObjectTypeResolver === null) {
            /** @var PostObjectTypeResolver */
            $postObjectTypeResolver = $this->instanceManager->getInstance(PostObjectTypeResolver::class);
            $this->postObjectTypeResolver = $postObjectTypeResolver;
        }
        return $this->postObjectTypeResolver;
    }

    protected function getCustomPostTypeResolver(): CustomPostObjectTypeResolverInterface
    {
        return $this->getPostObjectTypeResolver();
    }
}
