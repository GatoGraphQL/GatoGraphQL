<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\PostTagMutations\MutationResolvers\SetTagsOnPostMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

trait SetTagsOnPostObjectTypeFieldResolverTrait
{
    // use BasicServiceTrait;

    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?SetTagsOnPostMutationResolver $setTagsOnPostMutationResolver = null;

    public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        return $this->postObjectTypeResolver ??= $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    public function setSetTagsOnPostMutationResolver(SetTagsOnPostMutationResolver $setTagsOnPostMutationResolver): void
    {
        $this->setTagsOnPostMutationResolver = $setTagsOnPostMutationResolver;
    }
    protected function getSetTagsOnPostMutationResolver(): SetTagsOnPostMutationResolver
    {
        return $this->setTagsOnPostMutationResolver ??= $this->instanceManager->getInstance(SetTagsOnPostMutationResolver::class);
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
        return $this->getTranslationAPI()->__('post', 'post-tag-mutations');
    }
}
