<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType\AbstractSetCategoriesOnCustomPostInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\PayloadableSetCategoriesOnCustomPostBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\PayloadableSetCategoriesOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\SetCategoriesOnCustomPostBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\SetCategoriesOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType\GenericCustomPostSetCategoriesInputObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\ObjectType\GenericCustomPostSetCategoriesMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;

class GenericCustomPostObjectTypeFieldResolver extends AbstractCustomPostObjectTypeFieldResolver
{
    private ?GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver = null;
    private ?SetCategoriesOnCustomPostMutationResolver $setCategoriesOnCustomPostMutationResolver = null;
    private ?SetCategoriesOnCustomPostBulkOperationMutationResolver $setCategoriesOnCustomPostBulkOperationMutationResolver = null;
    private ?GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver = null;
    private ?GenericCustomPostSetCategoriesInputObjectTypeResolver $genericCustomPostSetCategoriesInputObjectTypeResolver = null;
    private ?PayloadableSetCategoriesOnCustomPostMutationResolver $payloadableSetCategoriesOnCustomPostMutationResolver = null;
    private ?PayloadableSetCategoriesOnCustomPostBulkOperationMutationResolver $payloadableSetCategoriesOnCustomPostBulkOperationMutationResolver = null;
    private ?GenericCustomPostSetCategoriesMutationPayloadObjectTypeResolver $genericCustomPostSetCategoriesMutationPayloadObjectTypeResolver = null;

    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        if ($this->genericCustomPostObjectTypeResolver === null) {
            /** @var GenericCustomPostObjectTypeResolver */
            $genericCustomPostObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
            $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
        }
        return $this->genericCustomPostObjectTypeResolver;
    }
    final protected function getSetCategoriesOnCustomPostMutationResolver(): SetCategoriesOnCustomPostMutationResolver
    {
        if ($this->setCategoriesOnCustomPostMutationResolver === null) {
            /** @var SetCategoriesOnCustomPostMutationResolver */
            $setCategoriesOnCustomPostMutationResolver = $this->instanceManager->getInstance(SetCategoriesOnCustomPostMutationResolver::class);
            $this->setCategoriesOnCustomPostMutationResolver = $setCategoriesOnCustomPostMutationResolver;
        }
        return $this->setCategoriesOnCustomPostMutationResolver;
    }
    final protected function getSetCategoriesOnCustomPostBulkOperationMutationResolver(): SetCategoriesOnCustomPostBulkOperationMutationResolver
    {
        if ($this->setCategoriesOnCustomPostBulkOperationMutationResolver === null) {
            /** @var SetCategoriesOnCustomPostBulkOperationMutationResolver */
            $setCategoriesOnCustomPostBulkOperationMutationResolver = $this->instanceManager->getInstance(SetCategoriesOnCustomPostBulkOperationMutationResolver::class);
            $this->setCategoriesOnCustomPostBulkOperationMutationResolver = $setCategoriesOnCustomPostBulkOperationMutationResolver;
        }
        return $this->setCategoriesOnCustomPostBulkOperationMutationResolver;
    }
    final protected function getGenericCategoryObjectTypeResolver(): GenericCategoryObjectTypeResolver
    {
        if ($this->genericCategoryObjectTypeResolver === null) {
            /** @var GenericCategoryObjectTypeResolver */
            $genericCategoryObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryObjectTypeResolver::class);
            $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
        }
        return $this->genericCategoryObjectTypeResolver;
    }
    final protected function getGenericCustomPostSetCategoriesInputObjectTypeResolver(): AbstractSetCategoriesOnCustomPostInputObjectTypeResolver
    {
        if ($this->genericCustomPostSetCategoriesInputObjectTypeResolver === null) {
            /** @var GenericCustomPostSetCategoriesInputObjectTypeResolver */
            $genericCustomPostSetCategoriesInputObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostSetCategoriesInputObjectTypeResolver::class);
            $this->genericCustomPostSetCategoriesInputObjectTypeResolver = $genericCustomPostSetCategoriesInputObjectTypeResolver;
        }
        return $this->genericCustomPostSetCategoriesInputObjectTypeResolver;
    }
    final protected function getPayloadableSetCategoriesOnCustomPostMutationResolver(): PayloadableSetCategoriesOnCustomPostMutationResolver
    {
        if ($this->payloadableSetCategoriesOnCustomPostMutationResolver === null) {
            /** @var PayloadableSetCategoriesOnCustomPostMutationResolver */
            $payloadableSetCategoriesOnCustomPostMutationResolver = $this->instanceManager->getInstance(PayloadableSetCategoriesOnCustomPostMutationResolver::class);
            $this->payloadableSetCategoriesOnCustomPostMutationResolver = $payloadableSetCategoriesOnCustomPostMutationResolver;
        }
        return $this->payloadableSetCategoriesOnCustomPostMutationResolver;
    }
    final protected function getPayloadableSetCategoriesOnCustomPostBulkOperationMutationResolver(): PayloadableSetCategoriesOnCustomPostBulkOperationMutationResolver
    {
        if ($this->payloadableSetCategoriesOnCustomPostBulkOperationMutationResolver === null) {
            /** @var PayloadableSetCategoriesOnCustomPostBulkOperationMutationResolver */
            $payloadableSetCategoriesOnCustomPostBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableSetCategoriesOnCustomPostBulkOperationMutationResolver::class);
            $this->payloadableSetCategoriesOnCustomPostBulkOperationMutationResolver = $payloadableSetCategoriesOnCustomPostBulkOperationMutationResolver;
        }
        return $this->payloadableSetCategoriesOnCustomPostBulkOperationMutationResolver;
    }
    final protected function getGenericCustomPostSetCategoriesMutationPayloadObjectTypeResolver(): GenericCustomPostSetCategoriesMutationPayloadObjectTypeResolver
    {
        if ($this->genericCustomPostSetCategoriesMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCustomPostSetCategoriesMutationPayloadObjectTypeResolver */
            $genericCustomPostSetCategoriesMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostSetCategoriesMutationPayloadObjectTypeResolver::class);
            $this->genericCustomPostSetCategoriesMutationPayloadObjectTypeResolver = $genericCustomPostSetCategoriesMutationPayloadObjectTypeResolver;
        }
        return $this->genericCustomPostSetCategoriesMutationPayloadObjectTypeResolver;
    }

    public function getCustomPostObjectTypeResolver(): CustomPostObjectTypeResolverInterface
    {
        return $this->getGenericCustomPostObjectTypeResolver();
    }

    public function getSetCategoriesMutationResolver(): MutationResolverInterface
    {
        return $this->getSetCategoriesOnCustomPostMutationResolver();
    }

    public function getSetCategoriesBulkOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getSetCategoriesOnCustomPostBulkOperationMutationResolver();
    }

    public function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface
    {
        return $this->getGenericCategoryObjectTypeResolver();
    }

    public function getCustomPostSetCategoriesInputObjectTypeResolver(): AbstractSetCategoriesOnCustomPostInputObjectTypeResolver
    {
        return $this->getGenericCustomPostSetCategoriesInputObjectTypeResolver();
    }

    protected function getCustomPostSetCategoriesMutationPayloadObjectTypeResolver(): ConcreteTypeResolverInterface
    {
        return $this->getGenericCustomPostSetCategoriesMutationPayloadObjectTypeResolver();
    }

    public function getPayloadableSetCategoriesMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetCategoriesOnCustomPostMutationResolver();
    }

    public function getPayloadableSetCategoriesBulkOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetCategoriesOnCustomPostBulkOperationMutationResolver();
    }

    protected function getEntityName(): string
    {
        return $this->__('custom post', 'category-mutations');
    }
}
