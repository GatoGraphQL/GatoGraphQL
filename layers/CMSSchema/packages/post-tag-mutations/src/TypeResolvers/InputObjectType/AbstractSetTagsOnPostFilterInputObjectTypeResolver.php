<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\TypeResolvers\InputObjectType;

use PoPSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
use PoPSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\AbstractSetTagsOnCustomPostFilterInputObjectTypeResolver;
use PoPSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;

abstract class AbstractSetTagsOnPostFilterInputObjectTypeResolver extends AbstractSetTagsOnCustomPostFilterInputObjectTypeResolver
{
    private ?PostTagObjectTypeResolver $postTagObjectTypeResolver = null;

    final public function setPostTagObjectTypeResolver(PostTagObjectTypeResolver $postTagObjectTypeResolver): void
    {
        $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
    }
    final protected function getPostTagObjectTypeResolver(): PostTagObjectTypeResolver
    {
        return $this->postTagObjectTypeResolver ??= $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
    }

    protected function getTagTypeResolver(): TagObjectTypeResolverInterface
    {
        return $this->getPostTagObjectTypeResolver();
    }

    protected function getEntityName(): string
    {
        return $this->__('post', 'postcategory-mutations');
    }
}
