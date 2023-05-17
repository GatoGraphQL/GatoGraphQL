<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType\AbstractRootObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType\AbstractSetCategoriesOnCustomPostInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPCMSSchema\PostCategoryMutations\MutationResolvers\PayloadableSetCategoriesOnPostMutationResolver;
use PoPCMSSchema\PostCategoryMutations\MutationResolvers\SetCategoriesOnPostMutationResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType\RootSetCategoriesOnCustomPostInputObjectTypeResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\ObjectType\RootSetCategoriesOnPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;

class RootObjectTypeFieldResolver extends AbstractRootObjectTypeFieldResolver
{
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?SetCategoriesOnPostMutationResolver $setCategoriesOnPostMutationResolver = null;
    private ?PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver = null;
    private ?RootSetCategoriesOnCustomPostInputObjectTypeResolver $rootSetCategoriesOnCustomPostInputObjectTypeResolver = null;
    private ?PayloadableSetCategoriesOnPostMutationResolver $payloadableSetCategoriesOnPostMutationResolver = null;
    private ?RootSetCategoriesOnPostMutationPayloadObjectTypeResolver $rootSetCategoriesOnPostMutationPayloadObjectTypeResolver = null;

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
    final public function setRootSetCategoriesOnCustomPostInputObjectTypeResolver(RootSetCategoriesOnCustomPostInputObjectTypeResolver $rootSetCategoriesOnCustomPostInputObjectTypeResolver): void
    {
        $this->rootSetCategoriesOnCustomPostInputObjectTypeResolver = $rootSetCategoriesOnCustomPostInputObjectTypeResolver;
    }
    final protected function getRootSetCategoriesOnCustomPostInputObjectTypeResolver(): AbstractSetCategoriesOnCustomPostInputObjectTypeResolver
    {
        /** @var RootSetCategoriesOnCustomPostInputObjectTypeResolver */
        return $this->rootSetCategoriesOnCustomPostInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootSetCategoriesOnCustomPostInputObjectTypeResolver::class);
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
    final public function setRootSetCategoriesOnPostMutationPayloadObjectTypeResolver(RootSetCategoriesOnPostMutationPayloadObjectTypeResolver $rootSetCategoriesOnPostMutationPayloadObjectTypeResolver): void
    {
        $this->rootSetCategoriesOnPostMutationPayloadObjectTypeResolver = $rootSetCategoriesOnPostMutationPayloadObjectTypeResolver;
    }
    final protected function getRootSetCategoriesOnPostMutationPayloadObjectTypeResolver(): RootSetCategoriesOnPostMutationPayloadObjectTypeResolver
    {
        /** @var RootSetCategoriesOnPostMutationPayloadObjectTypeResolver */
        return $this->rootSetCategoriesOnPostMutationPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(RootSetCategoriesOnPostMutationPayloadObjectTypeResolver::class);
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
        return $this->getRootSetCategoriesOnCustomPostInputObjectTypeResolver();
    }

    public function getPayloadableSetCategoriesMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetCategoriesOnPostMutationResolver();
    }

    protected function getRootSetCategoriesMutationPayloadObjectTypeResolver(): ConcreteTypeResolverInterface
    {
        return $this->getRootSetCategoriesOnPostMutationPayloadObjectTypeResolver();
    }

    protected function getEntityName(): string
    {
        return $this->__('post', 'post-category-mutations');
    }

    protected function getSetCategoriesFieldName(): string
    {
        return 'setCategoriesOnPost';
    }
}
