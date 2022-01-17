<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPSchema\CustomPostTagMutations\FieldResolvers\ObjectType\AbstractRootObjectTypeFieldResolver;
use PoPSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\AbstractSetTagsOnCustomPostFilterInputObjectTypeResolver;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\PostTagMutations\MutationResolvers\SetTagsOnPostMutationResolver;
use PoPSchema\PostTagMutations\TypeResolvers\InputObjectType\RootSetTagsOnCustomPostFilterInputObjectTypeResolver;

class RootObjectTypeFieldResolver extends AbstractRootObjectTypeFieldResolver
{
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?SetTagsOnPostMutationResolver $setTagsOnPostMutationResolver = null;
    private ?RootSetTagsOnCustomPostFilterInputObjectTypeResolver $rootSetTagsOnCustomPostFilterInputObjectTypeResolver = null;

    final public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        return $this->postObjectTypeResolver ??= $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    final public function setSetTagsOnPostMutationResolver(SetTagsOnPostMutationResolver $setTagsOnPostMutationResolver): void
    {
        $this->setTagsOnPostMutationResolver = $setTagsOnPostMutationResolver;
    }
    final protected function getSetTagsOnPostMutationResolver(): SetTagsOnPostMutationResolver
    {
        return $this->setTagsOnPostMutationResolver ??= $this->instanceManager->getInstance(SetTagsOnPostMutationResolver::class);
    }
    final public function setRootSetTagsOnCustomPostFilterInputObjectTypeResolver(RootSetTagsOnCustomPostFilterInputObjectTypeResolver $rootSetTagsOnCustomPostFilterInputObjectTypeResolver): void
    {
        $this->rootSetTagsOnCustomPostFilterInputObjectTypeResolver = $rootSetTagsOnCustomPostFilterInputObjectTypeResolver;
    }
    final protected function getRootSetTagsOnCustomPostFilterInputObjectTypeResolver(): AbstractSetTagsOnCustomPostFilterInputObjectTypeResolver
    {
        return $this->rootSetTagsOnCustomPostFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootSetTagsOnCustomPostFilterInputObjectTypeResolver::class);
    }

    public function getCustomPostObjectTypeResolver(): CustomPostObjectTypeResolverInterface
    {
        return $this->getPostObjectTypeResolver();
    }

    public function getSetTagsMutationResolver(): MutationResolverInterface
    {
        return $this->getSetTagsOnPostMutationResolver();
    }

    protected function getEntityName(): string
    {
        return $this->__('post', 'post-tag-mutations');
    }

    public function getCustomPostSetTagsFilterInputObjectTypeResolver(): AbstractSetTagsOnCustomPostFilterInputObjectTypeResolver
    {
        return $this->getRootSetTagsOnCustomPostFilterInputObjectTypeResolver();
    }

    protected function getSetTagsFieldName(): string
    {
        return 'setTagsOnPost';
    }
}
