<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType\AbstractSetCategoriesOnCustomPostInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPCMSSchema\PostCategoryMutations\MutationResolvers\PayloadableSetCategoriesOnPostMutationResolver;
use PoPCMSSchema\PostCategoryMutations\MutationResolvers\SetCategoriesOnPostMutationResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType\PostSetCategoriesInputObjectTypeResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\ObjectType\PostSetCategoriesMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;

class PostObjectTypeFieldResolver extends AbstractCustomPostObjectTypeFieldResolver
{
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?SetCategoriesOnPostMutationResolver $setCategoriesOnPostMutationResolver = null;
    private ?PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver = null;
    private ?PostSetCategoriesInputObjectTypeResolver $postSetCategoriesInputObjectTypeResolver = null;
    private ?PayloadableSetCategoriesOnPostMutationResolver $payloadableSetCategoriesOnPostMutationResolver = null;
    private ?PostSetCategoriesMutationPayloadObjectTypeResolver $postSetCategoriesMutationPayloadObjectTypeResolver = null;

    final public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        /** @var PostObjectTypeResolver */
        return $this->postObjectTypeResolver ??= $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    final public function setSetCategoriesOnPostMutationResolver(SetCategoriesOnPostMutationResolver $setCategoriesOnPostMutationResolver): void
    {
        $this->setCategoriesOnPostMutationResolver = $setCategoriesOnPostMutationResolver;
    }
    final protected function getSetCategoriesOnPostMutationResolver(): SetCategoriesOnPostMutationResolver
    {
        /** @var SetCategoriesOnPostMutationResolver */
        return $this->setCategoriesOnPostMutationResolver ??= $this->instanceManager->getInstance(SetCategoriesOnPostMutationResolver::class);
    }
    final public function setPostCategoryObjectTypeResolver(PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver): void
    {
        $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
    }
    final protected function getPostCategoryObjectTypeResolver(): PostCategoryObjectTypeResolver
    {
        /** @var PostCategoryObjectTypeResolver */
        return $this->postCategoryObjectTypeResolver ??= $this->instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
    }
    final public function setPostSetCategoriesInputObjectTypeResolver(PostSetCategoriesInputObjectTypeResolver $postSetCategoriesInputObjectTypeResolver): void
    {
        $this->postSetCategoriesInputObjectTypeResolver = $postSetCategoriesInputObjectTypeResolver;
    }
    final protected function getPostSetCategoriesInputObjectTypeResolver(): AbstractSetCategoriesOnCustomPostInputObjectTypeResolver
    {
        /** @var PostSetCategoriesInputObjectTypeResolver */
        return $this->postSetCategoriesInputObjectTypeResolver ??= $this->instanceManager->getInstance(PostSetCategoriesInputObjectTypeResolver::class);
    }
    final public function setPayloadableSetCategoriesOnPostMutationResolver(PayloadableSetCategoriesOnPostMutationResolver $payloadableSetCategoriesOnPostMutationResolver): void
    {
        $this->payloadableSetCategoriesOnPostMutationResolver = $payloadableSetCategoriesOnPostMutationResolver;
    }
    final protected function getPayloadableSetCategoriesOnPostMutationResolver(): PayloadableSetCategoriesOnPostMutationResolver
    {
        /** @var PayloadableSetCategoriesOnPostMutationResolver */
        return $this->payloadableSetCategoriesOnPostMutationResolver ??= $this->instanceManager->getInstance(PayloadableSetCategoriesOnPostMutationResolver::class);
    }
    final public function setPostSetCategoriesMutationPayloadObjectTypeResolver(PostSetCategoriesMutationPayloadObjectTypeResolver $postSetCategoriesMutationPayloadObjectTypeResolver): void
    {
        $this->postSetCategoriesMutationPayloadObjectTypeResolver = $postSetCategoriesMutationPayloadObjectTypeResolver;
    }
    final protected function getPostSetCategoriesMutationPayloadObjectTypeResolver(): PostSetCategoriesMutationPayloadObjectTypeResolver
    {
        /** @var PostSetCategoriesMutationPayloadObjectTypeResolver */
        return $this->postSetCategoriesMutationPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(PostSetCategoriesMutationPayloadObjectTypeResolver::class);
    }

    public function getCustomPostObjectTypeResolver(): CustomPostObjectTypeResolverInterface
    {
        return $this->getPostObjectTypeResolver();
    }

    public function getSetCategoriesMutationResolver(): MutationResolverInterface
    {
        return $this->getSetCategoriesOnPostMutationResolver();
    }

    public function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface
    {
        return $this->getPostCategoryObjectTypeResolver();
    }

    public function getCustomPostSetCategoriesInputObjectTypeResolver(): AbstractSetCategoriesOnCustomPostInputObjectTypeResolver
    {
        return $this->getPostSetCategoriesInputObjectTypeResolver();
    }

    protected function getCustomPostSetCategoriesMutationPayloadObjectTypeResolver(): ConcreteTypeResolverInterface
    {
        return $this->getPostSetCategoriesMutationPayloadObjectTypeResolver();
    }

    public function getPayloadableSetCategoriesMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetCategoriesOnPostMutationResolver();
    }

    protected function getEntityName(): string
    {
        return $this->__('post', 'post-category-mutations');
    }
}
