<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\AbstractSetTagsOnCustomPostInputObjectTypeResolver;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;

abstract class AbstractSetTagsOnPostInputObjectTypeResolver extends AbstractSetTagsOnCustomPostInputObjectTypeResolver
{
    private ?PostTagObjectTypeResolver $postTagObjectTypeResolver = null;

    final public function setPostTagObjectTypeResolver(PostTagObjectTypeResolver $postTagObjectTypeResolver): void
    {
        $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
    }
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

    protected function getEntityName(): string
    {
        return $this->__('post', 'posttag-mutations');
    }

    protected function addTaxonomyInputField(): bool
    {
        return false;
    }
}
